#!/usr/bin/env python3
"""
Regenerate index.html from darkness_catalog.json.
"""
from __future__ import annotations

import html
import json
import re
from email.utils import parsedate_to_datetime
from pathlib import Path

NAME_IN_PARENS = re.compile(r"\(([^()@<>]+)\)\s*$")
EMAIL_TOKEN = re.compile(r"[\w.+-]+@[\w.+-]+")


def load_catalog(path: Path) -> dict:
    with path.open(encoding="utf-8") as fh:
        return json.load(fh)


def format_date(raw: str | None) -> str:
    if not raw:
        return ""
    try:
        dt = parsedate_to_datetime(raw)
    except Exception:
        return raw
    return dt.strftime("%b %d, %Y")


def author_name(raw: str | None) -> str:
    if not raw:
        return ""
    text = raw.strip()
    match = NAME_IN_PARENS.search(text)
    if match:
        name = match.group(1).strip()
        if name:
            return name
    if "<" in text and ">" in text:
        before = text.split("<", 1)[0].strip(' "\'\t-.,')
        if before:
            return before
    no_email = EMAIL_TOKEN.sub("", text).replace("_at_", " ")
    no_email = re.sub(r"\s+", " ", no_email).strip(' "\'\t-.,')
    if no_email:
        return no_email
    match = EMAIL_TOKEN.search(text)
    if match:
        local = match.group(0).split("@", 1)[0]
        pretty = re.sub(r"[._-]+", " ", local).strip()
        if pretty:
            return pretty.title()
    return text


def render(catalog: dict) -> str:
    sections = catalog["sections"]
    entries = catalog["entries"]
    parts: list[str] = []
    parts.append("<!DOCTYPE html>")
    parts.append('<html lang="en">')
    parts.append("<head>")
    parts.append('<meta charset="utf-8">')
    parts.append("<title>Pushing Back the Darkness - Mike West Notes</title>")
    parts.append('<meta name="viewport" content="width=device-width, initial-scale=1">')
    parts.append('<link rel="stylesheet" href="/css/bootstrap.min.css">')
    parts.append("<style>")
    parts.append(
        'body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; '
        "background: #f5f5f5; color: #1b1e23; margin: 0; font-size: 17px; line-height: 1.55; }"
    )
    parts.append("a { color: #0a62c2; }")
    parts.append("header { text-align: center; padding: 48px 20px 18px; }")
    parts.append("header h1 { margin: 0 0 12px; font-size: 3rem; font-weight: 700; }")
    parts.append("header p { margin: 3px 0; color: #333; }")
    parts.append("main { max-width: 1050px; margin: 0 auto; padding: 0 20px 70px; }")
    parts.append("section.collection { margin-top: 44px; }")
    parts.append("section.collection h2 { font-size: 1.8rem; margin: 0 0 6px; }")
    parts.append(
        "section.collection .section-note { margin: 0 0 14px; color: #555; white-space: pre-line; }"
    )
    parts.append(
        ".series-note { max-width: 760px; margin: 0 auto 24px; padding: 0 20px; "
        "color: #4f535c; text-align: left; }"
    )
    parts.append(
        ".table-wrap { background: #fff; border: 1px solid rgba(0,0,0,0.08); "
        "border-radius: 6px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.04); }"
    )
    parts.append("table.archive { width: 100%; margin-bottom: 0; }")
    parts.append("table.archive td { vertical-align: top; padding: 14px 18px; }")
    parts.append(
        'table.archive td.date { width: 15rem; font-family: "IBM Plex Mono", "Courier New", monospace; color: #333; }'
    )
    parts.append("table.archive td.title a { font-weight: 600; text-decoration: none; }")
    parts.append("table.archive td.title a:hover { text-decoration: underline; }")
    parts.append("table.archive td.title .desc { margin-top: 6px; color: #1f212b; }")
    parts.append("table.archive td.author { width: 13rem; font-weight: 600; color: #2b2d35; }")
    parts.append(
        "@media (max-width: 720px) { table.archive td { display: block; width: 100%; } "
        "table.archive td.date, table.archive td.author { width: auto; font-size: 0.95rem; } }"
    )
    parts.append("</style>")
    parts.append("</head>")
    parts.append("<body>")
    parts.append("<header>")
    parts.append("<h1>Pushing Back the Darkness</h1>")
    parts.append("<p>Written by the late great Mike West (who passed in 2001)</p>")
    parts.append('<p>Maintained by <a href="mailto:keen@type2.com">David Raistrick</a></p>')
    parts.append("<p>All messages posted with permission or by request.</p>")
    parts.append("</header>")
    parts.append(
        '<p class="series-note">Pushing Back the Darkness is Mike West\'s archive of '
        "late-'90s technical emails about air-cooled VW buses, Beetles, and Type 2 engines. "
        "It's his working notebook for welding magnesium VW cases, balancing VW crankshafts, "
        "plumbing full-flow oil systems, fixing valve-train geometry, and testing every idea "
        "before bolting it back into a bus.</p>"
    )

    parts.append("<main>")

    for index, section in enumerate(sections):
        heading = html.escape(section["heading"])
        note = section.get("note") or ""
        parts.append('<section class="collection">')
        if index != 0:
            parts.append(f"<h2>{heading}</h2>")
        if note.strip() and index != 0:
            parts.append(f'<p class="section-note">{html.escape(note)}</p>')
        parts.append('<div class="table-wrap">')
        parts.append('<table class="archive table table-striped">')
        parts.append("<tbody>")

        for slug in section["slugs"]:
            entry = entries.get(slug)
            if not entry:
                continue
            date_text = html.escape(format_date(entry.get("date", "")).strip())
            title_text = html.escape(entry.get("title") or slug)
            desc = entry.get("description") or ""
            desc_html = f'<div class="desc">{html.escape(desc)}</div>' if desc else ""
            author_text = html.escape(author_name(entry.get("author", "")))
            href = html.escape(slug)
            parts.append("<tr>")
            parts.append(f'<td class="date">{date_text}</td>')
            parts.append(f'<td class="title"><a href="{href}">{title_text}</a>{desc_html}</td>')
            parts.append(f'<td class="author">{author_text}</td>')
            parts.append("</tr>")

        parts.append("</tbody></table></div></section>")

    parts.append("</main>")
    parts.append("</body>")
    parts.append("</html>")
    return "\n".join(parts)


def main() -> None:
    catalog = load_catalog(Path("darkness_catalog.json"))
    html_text = render(catalog)
    Path("index.html").write_text(html_text, encoding="utf-8")
    Path("darkness.html").write_text(html_text, encoding="utf-8")


if __name__ == "__main__":
    main()
