# Hosting: one branch, three hosts

Unified hosting config. Every host reads its own config files from the same
branch lineage (`master` = canonical, mirrored on host branches).

| Host | Dashboard | Branch | Status |
|------|-----------|--------|--------|
| deploybase (primary) | https://app.deploybase.eu/deployments | `deploybase` (primary), `master` previews | **live** — https://type2.on.deploybase.dev/ |
| statichost.eu | https://builder.statichost.eu/type2/ | `statichost` | **blocked** — abuse-scan false positive, support review pending (email sent 2026-11-20) |
| Vercel | (account locked ~22 days) | `master` | dead; `vercel.json` intact, revivable |

Site URL history: type2.com → Vercel (dead) → statichost.page (partial) →
**deploybase primary**.

## Who reads what

| File | Vercel | statichost | deploybase |
|------|--------|------------|------------|
| `vercel.json` | ✅ redirects + 458 Content-Type headers + trailingSlash | — | — |
| `_redirects` | — | ✅ 62 rules, Netlify syntax, `301!` forced | ✅ same file, same syntax |
| `_headers` | — | ✅ 445 Content-Type blocks | ignored (no feature) |
| `statichost.yml` | — | ✅ `public: .`, no build | ignored, stripped from output |
| `build-static.sh` + `rename-list.txt` | — | not run | ✅ build step (guarded command) |
| `404.html` | (vercel.json `404` handling) | ✅ | ✅ |

Repo content itself is never transformed in-repo: extensionless files stay
extensionless, symlinks stay symlinks. All transforms happen build-side.

## deploybase (primary)

- Dashboard: https://app.deploybase.eu/deployments — project `type2`
- Primary branch: `deploybase`. Push to any tracked branch = auto build
  (GitHub App webhook)
- Build settings (project-wide, **not** per-branch — applies to all branches):
  ```
  build command:  sh -c 'if [ -f build-static.sh ]; then sh build-static.sh; else mkdir -p out && tar -ch --exclude=./out -cf - . | tar -xf - -C out; fi'
  output:         out
  image:          alpine default
  ```
  Guard: branch has script (deploybase) → transform; branch lacks it
  (master) → plain copy. No build settings exist in-repo; deploybase does
  not read any project yaml/toml.
- Build transform (`build-static.sh`):
  1. `cp -RL`-style deref via `tar -ch` — symlink dirs (`bartnik`,
     `m-codes`, `rescue`, `molenari`, `rvanness` → `~`-dirs) become real dirs
  2. 458 extensionless html files renamed `*.html` (list =
     `rename-list.txt`, from vercel.json headers); deploybase clean URLs
     serve `/foo` → `foo.html` with `text/html` (fixes octet-stream download)
  3. Appends 458 `/foo/ → /foo 301` lines to `_redirects` (520 rules total,
     under the 1000 cap)
- Redirects: Netlify `_redirects` syntax, `*` + `:splat`, `!` forces rule
  before static-file match (Vercel precedence parity). Docs:
  https://docs.deploybase.eu/guides/redirects/
- Limits (Free): 1 site, 100 GB/mo bandwidth (traffic ≈ 9 GB/mo), 200 build
  min, 1000 `_redirects` rules, 15 min build wall clock. Overage: **site
  keeps serving**, notify at 80%/100% — no pause, no lockout (the reason
  Vercel is being replaced)
- Known: deploys slow (~6 min — clone 372 MB + upload 15k files); preview
  branch URLs auth-gated until made public

## statichost.eu (backup, blocked)

- Dashboard: https://builder.statichost.eu/type2/ — site `type2`
- Branch: `statichost`; dashboard settings: no build, public = repo root
- Serves extensionless + symlinks natively (verified 2026-11-20), needs
  `_headers` for Content-Type, `_redirects` for rules
- Hobby free: 1 site, 10 GB/mo bandwidth (traffic = 90% of cap hot month),
  no card required
- **Blocked**: abuse-scan false positive at "Scanning content for abuse"
  (2026-11-20, builds `3MNy061Co0q583aZ0yK6jlipv58` et al). Deterministic —
  identical content that passed at 19:55 blocked at 20:04. Bisect ruled out
  new config files; flag went sticky site-wide. Support email sent; awaiting
  review. Build IDs submitted with ticket.
- Note: exotic Content-Type blocks (photoshop/wine/executable, 13 files)
  were dropped from `_headers` during bisect; those paths now sniffed.

## Vercel (legacy, dead)

- Account locked ~30 days after quota overage (new Vercel policy). DNS was
  repointed off ~2026-11-20 with ~22 days left on lockout.
- `vercel.json` on master = complete config (62 redirects, 458 headers,
  trailingSlash) if account ever revived.

## DNS / cutover

- type2.com CNAME → `type2.statichost.page` (set 2026-11-20, provider has
  no TTL control — propagation on their schedule)
- **TODO deploybase cutover**: dashboard → domains → add `type2.com` +
  `www`, follow DNS instructions, then remove/replace the statichost CNAME
- Verify after cutover: same curl battery as
  2026-11-20 (redirects/symlinks/extensionless/404)

## TODO

- [ ] statichost abuse review reply → third host green
- [ ] deploybase custom domain for type2.com
- [ ] junk exposure cleanup: `vercel.json`, `sitemaps-old-unmaintained/`,
      old scripts are publicly fetchable on deploybase
- [ ] `sitemap.xml` lists slash-form URLs → 301/308s now; regenerate
- [ ] regenerate `rename-list.txt`/`_redirects` if vercel.json changes:
      `python3 scripts/vercel-to-statichost.py`
