#!/usr/bin/env python3
"""Crawl HTML pages and collect every anchor href plus validation status."""

from __future__ import annotations

import argparse
import json
import logging
from collections import deque
from concurrent.futures import ThreadPoolExecutor, as_completed
from dataclasses import asdict, dataclass
from html.parser import HTMLParser
from pathlib import Path
from typing import Dict, Iterable, List, Optional, Sequence, Tuple
from urllib.parse import urlparse

try:
    import requests
    from requests.exceptions import RequestException
except ImportError:  # pragma: no cover - optional dependency
    requests = None
    RequestException = Exception  # type: ignore

STATE_VERSION = 1
DEFAULT_ROOT = Path("library/type2/library.htm")
DEFAULT_BASE = Path("library/type2")
DEFAULT_REPO_ROOT = Path(".")
DEFAULT_JSON_OUTPUT = Path("reports/library_type2_links.json")
DEFAULT_STATE_FILE = Path("reports/library_type2_state.json")
DEFAULT_HTTP_TIMEOUT = 5.0
DEFAULT_LOG_FILE = Path("reports/library_type2_scan.log")
DEFAULT_HTTP_WORKERS = 8


class LinkParser(HTMLParser):
    """Lightweight parser that just collects href values."""

    def __init__(self) -> None:
        super().__init__()
        self.links: List[str] = []

    def handle_starttag(self, tag: str, attrs: Sequence[Tuple[str, Optional[str]]]) -> None:
        if tag.lower() != "a":
            return
        for attr, value in attrs:
            if attr.lower() == "href" and value:
                self.links.append(value.strip())


def parse_links(path: Path) -> Iterable[str]:
    parser = LinkParser()
    try:
        parser.feed(path.read_text(encoding="utf-8", errors="ignore"))
    except Exception as exc:  # pragma: no cover - defensive, reports later
        raise RuntimeError(f"Failed to parse {path}: {exc}") from exc
    return parser.links


def relpath(path: Optional[Path], base: Path) -> Optional[str]:
    if path is None:
        return None
    try:
        return str(path.relative_to(base))
    except ValueError:
        return str(path)


def is_within(path: Path, directory: Path) -> bool:
    try:
        path.relative_to(directory)
        return True
    except ValueError:
        return False


def find_default_document(directory: Path) -> Optional[Path]:
    for name in ("index.html", "index.htm", "default.html", "default.htm"):
        candidate = directory / name
        if candidate.exists():
            return candidate
    return None


@dataclass
class LinkTarget:
    category: str
    path: Optional[Path] = None
    url: Optional[str] = None


def resolve_target(current_file: Path, href: str, repo_root: Path) -> LinkTarget:
    text = href.strip()
    if not text:
        return LinkTarget("empty")
    if text.startswith("#"):
        return LinkTarget("fragment", path=current_file)
    if text.startswith("//"):
        return LinkTarget("external-http", url=f"https:{text}")

    parsed = urlparse(text)
    if parsed.scheme in {"http", "https"}:
        return LinkTarget("external-http", url=text)
    if parsed.scheme in {"mailto", "tel"}:
        return LinkTarget(f"external-{parsed.scheme}", url=text)
    if parsed.scheme:
        return LinkTarget("external-other", url=text)

    target_text = parsed.path
    if not target_text:
        return LinkTarget("fragment", path=current_file)

    if target_text.startswith("/"):
        candidate = (repo_root / target_text.lstrip("/")).resolve(strict=False)
    else:
        candidate = (current_file.parent / target_text).resolve(strict=False)

    if candidate.is_dir():
        default_doc = find_default_document(candidate)
        if default_doc:
            return LinkTarget("local-file", path=default_doc)
        return LinkTarget("local-directory", path=candidate)
    return LinkTarget("local-file", path=candidate)


def check_http_url(url: str, timeout: float) -> Tuple[str, bool]:
    try:
        response = requests.head(url, allow_redirects=True, timeout=timeout)
        if response.status_code in {405, 501}:
            response = requests.get(url, allow_redirects=True, timeout=timeout, stream=True)
            response.close()
        ok = 200 <= response.status_code < 400
        return f"HTTP {response.status_code}", ok
    except RequestException as exc:  # pragma: no cover - depends on network
        return f"error: {exc.__class__.__name__}: {exc}", False


@dataclass
class LinkResult:
    source: str
    href: str
    category: str
    target: Optional[str]
    status: str
    ok: Optional[bool]


def iter_html_targets(
    results: Iterable[LinkResult],
    repo_root: Path,
    base_dir: Path,
) -> Iterable[Path]:
    for result in results:
        if result.category != "local-file" or not result.ok or not result.target:
            continue
        target_path = Path(result.target)
        if not target_path.is_absolute():
            target_path = (repo_root / target_path).resolve(strict=False)
        if (
            target_path.exists()
            and target_path.suffix.lower() in {".htm", ".html"}
            and is_within(target_path, base_dir)
        ):
            yield target_path


def evaluate_links(
    current: Path,
    links: Iterable[str],
    repo_root: Path,
    base_dir: Path,
    check_http: bool,
    timeout: float,
    http_workers: int,
) -> List[LinkResult]:
    results: List[LinkResult] = []
    http_jobs: List[Tuple[int, str]] = []
    for href in links:
        target_info = resolve_target(current, href, repo_root)
        source_rel = relpath(current, repo_root)

        if target_info.category == "local-file":
            target_path = target_info.path
            exists = target_path.exists() if target_path else False
            status = "ok" if exists else "missing"
            results.append(
                LinkResult(
                    source=source_rel,
                    href=href,
                    category="local-file",
                    target=relpath(target_path, repo_root),
                    status=status,
                    ok=True if exists else False,
                )
            )
            continue

        if target_info.category == "local-directory":
            path = target_info.path
            exists = path.exists() if path else False
            status = "missing (no default document)" if exists else "missing directory"
            results.append(
                LinkResult(
                    source=source_rel,
                    href=href,
                    category="local-directory",
                    target=relpath(path, repo_root),
                    status=status,
                    ok=False,
                )
            )
            continue

        if target_info.category == "fragment":
            # Skip fragment-only links; they reference the current document.
            continue

        if target_info.category.startswith("external"):
            if target_info.category == "external-http" and check_http and target_info.url:
                result = LinkResult(
                    source=source_rel,
                    href=href,
                    category=target_info.category,
                    target=target_info.url,
                    status="pending HTTP check",
                    ok=None,
                )
                results.append(result)
                http_jobs.append((len(results) - 1, target_info.url))
                continue
            if target_info.category == "external-http":
                status_text, ok = ("unchecked (use --offline to skip)", None)
            else:
                status_text, ok = (f"{target_info.category} (not checked)", None)
            results.append(
                LinkResult(
                    source=source_rel,
                    href=href,
                    category=target_info.category,
                    target=target_info.url,
                    status=status_text,
                    ok=ok,
                )
            )
            continue

        results.append(
            LinkResult(
                source=source_rel,
                href=href,
                category=target_info.category,
                target=None,
                status="ignored",
                ok=None,
            )
        )
    if http_jobs:
        logging.info("Checking %s HTTP links in parallel", len(http_jobs))
        with ThreadPoolExecutor(max_workers=max(1, http_workers)) as executor:
            future_map = {
                executor.submit(check_http_url, url, timeout): (index, url) for index, url in http_jobs
            }
            for future in as_completed(future_map):
                index, url = future_map[future]
                try:
                    status_text, ok = future.result()
                except Exception as exc:  # pragma: no cover - defensive
                    status_text, ok = (f"error: {exc}", False)
                results[index].status = status_text
                results[index].ok = ok
        logging.info("Completed HTTP checks for %s links", len(http_jobs))

    return results


def load_state(path: Optional[Path]) -> Dict[str, Dict[str, object]]:
    if not path or not path.exists():
        return {}
    try:
        data = json.loads(path.read_text(encoding="utf-8"))
    except (OSError, json.JSONDecodeError):
        return {}
    if data.get("version") != STATE_VERSION:
        return {}
    files = data.get("files")
    if not isinstance(files, dict):
        return {}
    return files  # type: ignore[return-value]


def save_state(path: Path, files: Dict[str, Dict[str, object]]) -> None:
    payload = {
        "version": STATE_VERSION,
        "files": files,
    }
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(json.dumps(payload, indent=2, sort_keys=True) + "\n", encoding="utf-8")


def crawl(
    root_file: Path,
    base_dir: Path,
    repo_root: Path,
    check_http: bool,
    timeout: float,
    http_workers: int,
    state_cache: Optional[Dict[str, Dict[str, object]]] = None,
    state_path: Optional[Path] = None,
    force_recheck: bool = False,
) -> Tuple[List[LinkResult], List[str], Dict[str, Dict[str, object]], int]:
    queue = deque([root_file])
    visited = set()
    results: List[LinkResult] = []
    failures: List[str] = []
    state_cache = state_cache or {}
    reused = 0

    while queue:
        current = queue.popleft()
        if current in visited:
            continue
        visited.add(current)
        current_mtime = current.stat().st_mtime
        source_rel = relpath(current, repo_root)
        logging.info("Scanning %s", source_rel)
        cached_results: Optional[List[LinkResult]] = None
        cache_entry = state_cache.get(source_rel)
        if cache_entry and not force_recheck:
            entry_http = cache_entry.get("http_checked")
            entry_mtime = cache_entry.get("mtime")
            if entry_http == check_http and entry_mtime == current_mtime:
                cached_data = cache_entry.get("results")
                if isinstance(cached_data, list):
                    cached_results = [LinkResult(**item) for item in cached_data]  # type: ignore[arg-type]
                    reused += 1
                    logging.info("Reusing cached results for %s", source_rel)

        if cached_results is None:
            try:
                links = list(parse_links(current))
            except RuntimeError as exc:
                failures.append(str(exc))
                continue
            link_results = evaluate_links(
                current,
                links,
                repo_root,
                base_dir,
                check_http,
                timeout,
                http_workers,
            )
        else:
            link_results = cached_results

        state_cache[source_rel] = {
            "mtime": current_mtime,
            "http_checked": check_http,
            "results": [asdict(result) for result in link_results],
        }
        if state_path:
            save_state(state_path, state_cache)
        results.extend(link_results)
        for target_path in iter_html_targets(link_results, repo_root, base_dir):
            queue.append(target_path)
    return results, failures, state_cache, reused


def main() -> None:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument(
        "--root",
        default=DEFAULT_ROOT,
        type=Path,
        help="Entry HTML file to start crawling from.",
    )
    parser.add_argument(
        "--base",
        default=DEFAULT_BASE,
        type=Path,
        help="Restrict crawling to HTML files under this directory.",
    )
    parser.add_argument(
        "--repo-root",
        default=DEFAULT_REPO_ROOT,
        type=Path,
        help="Repository root used to resolve absolute (/foo) hrefs.",
    )
    parser.add_argument(
        "--json-output",
        default=DEFAULT_JSON_OUTPUT,
        type=Path,
        help="If provided, write a JSON report to this path.",
    )
    parser.add_argument(
        "--state-file",
        default=DEFAULT_STATE_FILE,
        type=Path,
        help="Cache scan results per HTML file to avoid re-checking unchanged pages.",
    )
    parser.add_argument(
        "--force-recheck",
        action="store_true",
        help="Ignore cached results and rescan all files even if cached.",
    )
    parser.add_argument(
        "--offline",
        action="store_true",
        help="Skip HTTP requests for external links (only check local files).",
    )
    parser.add_argument(
        "--http-timeout",
        default=DEFAULT_HTTP_TIMEOUT,
        type=float,
        help="Timeout (seconds) for HTTP requests when --check-http is enabled.",
    )
    parser.add_argument(
        "--http-workers",
        default=DEFAULT_HTTP_WORKERS,
        type=int,
        help=f"Number of concurrent HTTP requests (default: {DEFAULT_HTTP_WORKERS}).",
    )
    parser.add_argument(
        "--log-file",
        default=DEFAULT_LOG_FILE,
        type=Path,
        help=f"Write progress logs to this file (default: {DEFAULT_LOG_FILE}).",
    )
    args = parser.parse_args()

    args.log_file.parent.mkdir(parents=True, exist_ok=True)
    logging.basicConfig(
        level=logging.INFO,
        format="%(asctime)s %(levelname)s %(message)s",
        handlers=[
            logging.StreamHandler(),
            logging.FileHandler(args.log_file, mode="a", encoding="utf-8"),
        ],
    )

    repo_root = args.repo_root.resolve()
    root_file = args.root.resolve()
    base_dir = args.base.resolve()

    if not root_file.exists():
        raise SystemExit(f"Root file {root_file} does not exist")
    if not base_dir.exists():
        raise SystemExit(f"Base directory {base_dir} does not exist")
    check_http = not args.offline

    logging.info("HTTP checking enabled: %s", check_http)

    if check_http and requests is None:
        raise SystemExit("The requests package is required for HTTP link checking.")

    cached_files = load_state(args.state_file) if args.state_file else None
    results, failures, updated_state, reused = crawl(
        root_file,
        base_dir,
        repo_root,
        check_http,
        args.http_timeout,
        args.http_workers,
        state_cache=cached_files,
        state_path=args.state_file,
        force_recheck=args.force_recheck,
    )

    if args.state_file:
        save_state(args.state_file, updated_state)

    if args.json_output:
        report_path = args.json_output
        report_path.parent.mkdir(parents=True, exist_ok=True)
        report_path.write_text(
            json.dumps(
                {
                    "root": str(root_file),
                    "base": str(base_dir),
                    "check_http": check_http,
                    "links": [asdict(result) for result in results],
                    "failures": failures,
                },
                indent=2,
                sort_keys=True,
            )
            + "\n",
            encoding="utf-8",
        )

    total = len(results)
    broken = [r for r in results if r.ok is False]
    unchecked = [r for r in results if r.ok is None and r.category == "external-http"]

    print(f"Total links scanned: {total}")
    if args.state_file:
        print(f"Reused cached pages: {reused}")
    print(f"Broken links: {len(broken)}")
    if broken:
        print("\nBroken references:")
        for item in broken:
            print(f"  {item.source} -> {item.href} ({item.status})")

    if failures:
        print("\nFailed to parse:")
        for entry in failures:
            print(f"  {entry}")

    if unchecked:
        print(f"\n{len(unchecked)} external links were not checked. Rerun with --check-http to validate them.")


if __name__ == "__main__":
    main()
