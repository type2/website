# Type2.com Repository Documentation

## Overview

This repository contains the code and configuration for Type2.com. It includes the integration of a new dynamic site with the older static content, ensuring backward compatibility and maintaining existing URLs.

### Key Features

Overlay of the new site on the old site - retaining all of the historical content and links.

Turned off the dynamic new website features since they wont work in a static hosted environment.   This means AIRS is dead, again.

All code is now manually maintained - there's no build path from the perl site to this currently.


## IMPORTANT

We can't just change url paths - we have 25 years of backlinking out on the internet to, now, TWO versions of the website - http for the old site and https for the new site.  We need rewrites and redirects to keep the old paths in place.

The homedir pages can't be ignored either.


## Deployment

### Vercel Deployment

- **Dashboard:** https://vercel.com/type2com/website
- **`dev` Branch:** https://website-tau-jet.vercel.app/
- **`master` Branch:** https://www.type2.com/
- **Dynamic Branch Environments:** Patterned URLs like `https://website-git-[branch-name]-type2com.vercel.app/`

### Render Deployment

- **Dashboard:** https://dashboard.render.com/static/srv-cebb9farrk0bbte9vqm0

unsupported, can't resolve the content-type and trailing slash issues

### Cloudflare Pages Deployment

https://dash.cloudflare.com/3d0181005ccae6e89efc11e0b042ebe5/pages/view/type2

unsupported, can't resolve the content-type and trailing slash issues

## Issues and Workarounds

### Trailing Slash Issue

Vercel by default doesnt add trailing slashes to directories - which breaks relative links.   `"trailingSlash": true,` works around this, but _that_ means that all URIs without dots in them get redirected to the trailing slash version - so our header rules have to reflect that.  its...ugly.

This DOES NOT fix it for URIs with dots in them (website mirrors, particularly).

### Content-Type Handling

Vercel uses file extensions to determine content-type - which breaks all of the files in older parts of the website that relied on apache/unix mimetype magic.  You can use `guess-mimetypes.sh` to recreate the headers section of vercel.json if these change.

### Symlinks

for the symlinks, we've added rewrite rules to simulate their behavior in vercel.json

### Old Archive Inclusion

- Including 'oldarchive' adds about 4GB, which is breaks vercel deployment even though it's under the 13g max.
- The oldarchive requires content-type handling for each file as well (or patterns)

## Development Notes

- The site is a combination of a Wallflower build of the perl site and a wget mirror of parts that can't be built through Wallflower, overlayed on top of the complete old site.
- The static copy of the old site was assembled around 12/12/2022 from the filesystem of `purple.type2.com`, the wallflower build was around 12/15/2022
- Contains symlinks for directories like `./bartnik`, `./rvanness`, etc., to maintain the structure of the original site

## Building and Running Locally

- Use the command `vercel dev` for local development.
- Note that running locally, headers will not work as expected.
- Use the hacky local python3 `./server.py` to get a similated behavior.   still imperfect, but it roughly follows the idea, and it follows symlinks


## TBD

remove/link new site after confirmation

/rvanness - to library

/rescue - to ...airs.  but also dead... ~airswork/rescue/

/reflect should probably go, but.....

molenaar/molenari - site is fairly broken and missing parts.  not sure if it's salvagable.  m-codes works fine.

/m-codes - rewrites to molenaar...

/library - the old index.  repl by library/type2/library.htm

/daklia - seems to work. may have library overlap?  bookmarks has some dead links

/~keen

/bartnik - redir to library?

/airs - ...dirs to rescue already.

