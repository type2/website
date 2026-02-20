# Workflow for Converting Mike West Notes to HTML

1. Move the original plain-text file into `original/` using `git mv` from the repo root when the note lives under `~keen/west/` (e.g., run `git mv ~keen/west/foo ~keen/west/original/foo` from `/Users/keen/git/type2/github/website`). This preserves history and keeps the source untouched.
2. Create the new HTML file in the original location **with the same filename** (e.g., `welding` stays `welding`). Use `welding2-example-html` as the reference example: header + nav link at top, metadata card (Date/From/To/Cc/Subject), all message text wrapped in `<p>` paragraphs (no `<pre>` blocks), and a footer paragraph with a link to `/~keen/west/original/<file>` labeled “Original message”. Do not rewrite the content—only reformat it.
3. Link in the shared stylesheet by placing `<link rel="stylesheet" href="/~keen/west/west.css"/>` inside `<head>` (delete the old inline `<style>` block). All note pages must share the same look-and-feel defined in `west.css`.
4. Customize the “Posted by …” line in the header based on the actual message (e.g., “Posted by Mike West to the VintagVW list” or “Posted by Will Wood to the Type2 list”). Don’t leave a generic value.
5. Move all Date/From/To/Cc/Subject lines out of the body and into the metadata card. Skip any field that is blank in the original message so we don't render empty rows.
6. Preserve the original line breaks and punctuation; convert `\n\n` blocks into paragraph tags and keep quotes/ellipses verbatim. Present quoted original questions in a `<section class="question"><blockquote>…` wrapper when appropriate, and keep the answer in a separate `<section class="reply">`.
7. Include a `← Back to Pushing Back the Darkness` link at the top so users can return to `darkness.html`.
8. Repeat this process manually for each file in batches of up to 20; no automation/scripts, because the message structures vary too much.
