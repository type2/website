#!/usr/bin/env python3
from pathlib import Path
from xml.etree import ElementTree as ET

SITEMAP = Path('sitemap.xml')
URLSET = '{http://www.sitemaps.org/schemas/sitemap/0.9}urlset'
URL = '{http://www.sitemaps.org/schemas/sitemap/0.9}url'
LOC = '{http://www.sitemaps.org/schemas/sitemap/0.9}loc'

REMOVE_PATHS = {
    'https://type2.com/lists/type2/listinfo',
    'https://type2.com/lists/type2/listinfo/index.html',
    'https://type2.com/lists/vintagebus/listinfo',
    'https://type2.com/lists/vintagebus/listinfo/index.html',
}

ADD_PATHS = [
    'https://type2.com/lists/type2/main',
    'https://type2.com/lists/vintagebus/main',
]

namespaces = {'ns': 'http://www.sitemaps.org/schemas/sitemap/0.9'}

root = ET.parse(SITEMAP).getroot()

existing = {url.find(LOC, namespaces).text for url in root.findall('ns:url', namespaces)}

for loc in REMOVE_PATHS:
    for url in root.findall('ns:url', namespaces):
        if url.find(LOC, namespaces).text == loc:
            root.remove(url)

for loc in ADD_PATHS:
    if loc not in existing:
        url = ET.SubElement(root, URL)
        ET.SubElement(url, LOC).text = loc

ET.ElementTree(root).write(SITEMAP, encoding='utf-8', xml_declaration=True)
