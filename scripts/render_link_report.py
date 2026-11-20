#!/usr/bin/env python3
"""Render a human-readable HTML report from the link checker JSON output."""

from __future__ import annotations

import argparse
import json
from collections import defaultdict
from html import escape
from pathlib import Path
from typing import Dict, List
from urllib.parse import urljoin


def load_links(path: Path) -> Dict[str, List[dict]]:
    data = json.loads(path.read_text(encoding="utf-8"))
    grouped: Dict[str, List[dict]] = defaultdict(list)
    for item in data.get("links", []):
        grouped[item["source"]].append(item)
    return data, grouped


def should_include(link: dict, include_ok: bool) -> bool:
    url_text = (link.get("target") or link.get("href") or "").lower()
    if "web.archive.org" in url_text:
        return False
    if link.get("category") == "fragment":
        return False
    if url_text.startswith("mailto:"):
        return False
    if not include_ok and link.get("ok") is True:
        return False
    return True


def render_report(data: dict, grouped: Dict[str, List[dict]], include_ok: bool) -> str:
    filtered_links = [
        link for link in data.get("links", []) if should_include(link, include_ok=True)
    ]
    total = len(filtered_links)
    broken = sum(1 for link in filtered_links if link.get("ok") is False)
    ok_count = sum(1 for link in filtered_links if link.get("ok"))
    unchecked = total - broken - ok_count

    parts = [
        "<!DOCTYPE html>",
        "<html><head><meta charset='utf-8'/>",
        "<title>Type2 Library Link Report</title>",
        "<style>",
        "body { font-family: Arial, sans-serif; margin: 20px; }",
        "summary { cursor: pointer; font-weight: bold; }",
        "table { border-collapse: collapse; width: 100%; margin: 8px 0 24px; }",
        "th, td { border: 1px solid #ccc; padding: 4px 6px; font-size: 13px; }",
        "th { background: #f0f0f0; }",
        ".ok { color: #0a7; }",
        ".broken { color: #c00; font-weight: bold; }",
        ".unknown { color: #a60; }",
        ".copy-btn { margin-left: 6px; padding: 2px 6px; font-size: 11px; cursor: pointer; }",
        ".archive-btn { margin-left: 4px; padding: 2px 6px; font-size: 11px; cursor: pointer; }",
        "</style></head><body>",
        "<script>",
        "function copyText(text) {",
        "  if (navigator.clipboard && navigator.clipboard.writeText) {",
        "    navigator.clipboard.writeText(text);",
        "  } else {",
        "    const textarea = document.createElement('textarea');",
        "    textarea.value = text;",
        "    document.body.appendChild(textarea);",
        "    textarea.select();",
        "    document.execCommand('copy');",
        "    document.body.removeChild(textarea);",
        "  }",
        "}",
        "</script>",
        "<h1>Type2 Library Link Report</h1>",
        "<p>Source JSON: <code>{}</code></p>".format(escape(str(data.get("root", "")))),
        "<ul>",
        f"<li>Total links: {total}</li>",
        f"<li>OK: {ok_count}</li>",
        f"<li>Broken: {broken}</li>",
        f"<li>Unchecked: {unchecked}</li>",
        "</ul>",
    ]

    for source in sorted(grouped.keys()):
        links = [link for link in grouped[source] if should_include(link, include_ok)]
        if not links:
            continue
        source_path = source.lstrip("/")
        source_url = f"http://localhost/{source_path}"
        parts.append(f"<h2><a href='{escape(source_url)}'>{escape(source)}</a></h2>")
        parts.append(f"<p>Total links in this file: {len(links)}</p>")
        parts.append("<table>")
        parts.append("<tr><th>#</th><th>Link</th><th>Category</th><th>Status</th></tr>")
        for idx, link in enumerate(links, 1):
            status = link.get("status", "n/a")
            css_class = "unknown"
            if link.get("ok") is True:
                css_class = "ok"
            elif link.get("ok") is False:
                css_class = "broken"
            href_text = link.get("href", "")
            if href_text.startswith(("http://", "https://", "mailto:", "tel:")):
                href_url = href_text
            else:
                href_url = urljoin(f"http://localhost/{source_path}", href_text)
            href_cell = f"<a href='{escape(href_url)}'>{escape(href_text)}</a>"

            target_text = link.get("target")
            if target_text:
                if target_text.startswith(("http://", "https://", "mailto:", "tel:")):
                    target_url = target_text
                else:
                    target_url = f"http://localhost/{target_text.lstrip('/')}"
                target_cell = f"<a href='{escape(target_url)}'>{escape(target_text)}</a>"
            else:
                target_cell = ""

            def copy_button(url: str) -> str:
                safe = url.replace("\\", "\\\\").replace("'", "\\'")
                return f"<button class='copy-btn' onclick=\"copyText('{escape(safe)}')\">Copy</button>"

            def archive_button(url: str) -> str:
                if url.startswith("http://") or url.startswith("https://"):
                    safe = url.replace("\\", "\\\\").replace("'", "\\'")
                    archive_url = "https://web.archive.org/" + safe
                    return (
                        f"<a class='archive-btn' href='{archive_url}' target='_blank'>Archive</a>"
                    )
                return ""

            parts.append(
                "<tr>"
                f"<td>{idx}</td>"
                f"<td>{href_cell} {copy_button(href_url)} {archive_button(href_url)}</td>"
                f"<td>{escape(link.get('category', ''))}</td>"
                f"<td class='{css_class}'>{escape(status)}</td>"
                "</tr>"
            )
        parts.append("</table>")

    parts.append("</body></html>")
    return "\n".join(parts)


def main() -> None:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument(
        "--input",
        default="reports/library_type2_links.json",
        type=Path,
        help="Path to the JSON output produced by check_library_links.py",
    )
    parser.add_argument(
        "--output",
        default="reports/library_type2_links.html",
        type=Path,
        help="Where to write the HTML report.",
    )
    parser.add_argument(
        "--include-ok",
        action="store_true",
        help="Include successfully validated links in the report. By default, only broken/unchecked links appear.",
    )
    args = parser.parse_args()

    data, grouped = load_links(args.input)
    html = render_report(data, grouped, include_ok=args.include_ok)
    args.output.write_text(html, encoding="utf-8")
    print(f"Wrote human-readable report to {args.output}")


if __name__ == "__main__":
    main()
