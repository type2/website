#!/usr/bin/env python3
"""Generate statichost.eu config (_redirects, _headers) from vercel.json.

_redirects:
  - static sources stay literal, sources with (.*) become /* + :splat
  - all redirects forced (!) so they match Vercel precedence (redirects
    before static files)
  - Vercel default 308 permanent -> 301

_headers:
  - every vercel header rule (all Content-Type, values vary: text/plain,
    text/html, image/vnd.adobe.photoshop, ...) -> exact-path blocks with
    value preserved
"""
import json
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
with open(os.path.join(ROOT, "vercel.json")) as f:
    vcfg = json.load(f)

# --- _redirects -----------------------------------------------------------
lines = ["# generated from vercel.json by scripts/vercel-to-statichost.py"]
for r in vcfg.get("redirects", []):
    src = r["source"]
    dst = r["destination"]
    if "(.*)" in src:
        # /library/body/(.*) -> /library/body/*
        # /library/type2/body/$1 -> /library/type2/body/:splat
        src = re.sub(r"\(\.\*\)\$$", "*", re.sub(r"\(\.\*\)", "*", src))
        src = src.rstrip("*") + "*" if not src.endswith("*") else src
        src = re.sub(r"\(\.\*\)", "*", src)
        dst = dst.replace("$1", ":splat")
    lines.append(f"{src}\t{dst}\t301!")

with open(os.path.join(ROOT, "_redirects"), "w") as f:
    f.write("\n".join(lines) + "\n")
print(f"_redirects: {len(vcfg.get('redirects', []))} rules")

# --- _headers -------------------------------------------------------------
hlines = ["# generated from vercel.json by scripts/vercel-to-statichost.py"]
n = 0
for h in vcfg.get("headers", []):
    path = h["source"].rstrip("/")  # statichost canonical = no trailing slash
    keys = {hh["key"] for hh in h["headers"]}
    if keys != {"Content-Type"}:
        print(f"SKIP non-Content-Type header: {path}")
        continue
    hlines.append(f"{path}")
    for hh in h["headers"]:
        hlines.append(f"  {hh['key']}: {hh['value']}")
    n += 1

with open(os.path.join(ROOT, "_headers"), "w") as f:
    f.write("\n".join(hlines) + "\n")
print(f"_headers: {n} blocks")
