#!/usr/bin/env python3
"""Verify that every link in library/index-orig.html is covered by vercel redirects."""

from __future__ import annotations

import json
import re
from html.parser import HTMLParser
from pathlib import Path
from typing import Iterable, List, Set
from urllib.parse import urljoin, urlsplit

HTML_FILE = Path("library/index-orig.html")
VERCEL_FILE = Path("vercel.json")
BASE_URL = "http://localhost/library/index-orig.html"


class LinkParser(HTMLParser):
    def __init__(self) -> None:
        super().__init__()
        self.links: Set[str] = set()

    def handle_starttag(self, tag: str, attrs: Iterable[tuple[str, str]]) -> None:
        if tag.lower() != "a":
            return
        for attr, value in attrs:
            if attr.lower() == "href" and value:
                self.links.add(value)


def load_links() -> Set[str]:
    parser = LinkParser()
    parser.feed(HTML_FILE.read_text(encoding="utf-8"))
    paths: Set[str] = set()
    for href in parser.links:
        if href.startswith("mailto:") or href.startswith("#"):
            continue
        absolute = urljoin(BASE_URL, href)
        parts = urlsplit(absolute)
        path = parts.path
        if not path.startswith("/library/"):
            continue
        paths.add(path)
    return paths


def compile_redirect_patterns(data: dict) -> List[re.Pattern[str]]:
    patterns: List[re.Pattern[str]] = []
    for entry in data.get("redirects", []):
        source = entry.get("source", "")
        regex = "^"
        i = 0
        while i < len(source):
            if source.startswith("(.*)", i):
                regex += "(.*)"
                i += 4
                continue
            regex += re.escape(source[i])
            i += 1
        regex += "$"
        patterns.append(re.compile(regex))
    return patterns


def main() -> None:
    if not HTML_FILE.exists():
        raise SystemExit(f"{HTML_FILE} does not exist")
    if not VERCEL_FILE.exists():
        raise SystemExit(f"{VERCEL_FILE} does not exist")

    paths = sorted(load_links())
    vercel_data = json.loads(VERCEL_FILE.read_text(encoding="utf-8"))
    patterns = compile_redirect_patterns(vercel_data)

    missing: List[str] = []
    for path in paths:
        if any(pattern.match(path) for pattern in patterns):
            continue
        missing.append(path)

    print(f"Checked {len(paths)} unique /library links.")
    if missing:
        print("Missing redirect coverage for:")
        for path in missing:
            print(f"  {path}")
        raise SystemExit(1)
    print("All links are covered by redirect rules.")


if __name__ == "__main__":
    main()
