# Pushing Back the Darkness

Static HTML replacement for Mike West’s “Pushing Back the Darkness” archive.

## Regenerating `index.html`

All archive metadata (titles, descriptions, sections, etc.) lives in
`darkness_catalog.json`. Any time you edit that file you must rebuild
`index.html` so the static page picks up the new content.

Run the generator from this directory:

```sh
./generate_index.py
```

The script reads `darkness_catalog.json`, renders the Bootstrap-styled
page, and writes the result to both `index.html` and `darkness.html`.
No other build steps are required—serve the HTML as-is.
