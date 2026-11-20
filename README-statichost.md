# statichost.eu migration (Vercel replacement)

Status: **testing** on Hobby free plan.

- Site: https://type2.statichost.page/
- Dashboard: https://builder.statichost.eu/type2/
- Branch deployed: `statichost` (repo root, no build step, `statichost.yml` on branch)
- Vercel still serving type2.com until cutover.

## Config (generated, do not hand-edit)

- `_redirects` — 62 rules ported from vercel.json. All forced (`301!`) to keep
  Vercel's redirect-before-static precedence. `(.*)` sources → `/*` + `:splat`.
- `_headers` — 458 Content-Type blocks (values vary: text/plain, text/html,
  photoshop, etc.) for extensionless files.
- `404.html` — plain minimal page (statichost has none by default).
- Regenerate: `python3 scripts/vercel-to-statichost.py` (reads vercel.json).

## What works natively (verified on live deploy 2026-11-20)

- Symlinks (`bartnik`, `m-codes`, `rescue`, `~`-dirs): serve fine, no rewrites
- Trailing slash: native 308 `/locate/` → `/locate` (canonical flips vs
  Vercel — old slash links redirect, acceptable)
- `.htm` files: correct text/html
- Extensionless files: served, but need `_headers` for Content-Type

## Deploy flow

GitHub webhook (wired 2026-11-20):

- github.com/type2/website → Settings → Webhooks → `builder.statichost.eu/type2`
- Payload URL: `https://builder.statichost.eu/type2`
- Content type: `application/json`, trigger: push
- Push to `statichost` branch = auto build. No webhook = manual rebuild button
- Hardening (not yet done): enable webhook auth in site settings (Source &
  build), create API key in My account, send `Authorization: Bearer <key>`

## Known gaps / TODO before cutover

- [ ] Verify `_redirects` + `_headers` honored on Hobby plan (docs say some
      path-matching features are plan-dependent)
- [ ] Junk exposure: repo root publishes everything tracked — `vercel.json`,
      `sitemaps-old-unmaintained/`, old scripts. Clean or accept.
- [ ] Confirm overage behavior with support: €1/10GB + spending limit vs
      Vercel-style lockout. Critical question before DNS cutover.
- [ ] Bandwidth: Hobby = 10GB/mo free; our traffic 6-9GB/mo = 90% hot-month.
      Starter (500GB) = safer long-term.
- [ ] sitemap.xml lists slash-form URLs → 308s now. Regenerate eventually.

## Cutover checklist

1. All config verified on statichost.page domain
2. Overage behavior confirmed with support (no surprise pause)
3. Add custom domain type2.com in dashboard, get DNS instructions
4. Lower DNS TTL to 300
5. Flip A/CNAME records
6. Soak, then kill Vercel project + delete `.vercel/`
