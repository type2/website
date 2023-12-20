#!/usr/bin/env python3


"""
Custom Python HTTP Server with Enhanced MIME Type Detection
-----------------------------------------------------------

This HTTP server extends the functionality of the SimpleHTTPRequestHandler class from the Python standard library's http.server module. It's designed to serve files over HTTP with the following custom enhancements:

Enhanced MIME Type Detection:
- Unlike the default Python HTTP server, this server uses the Unix 'file' command to determine the MIME type of served files.
- For files without an extension, or when the MIME type is not readily determinable through the extension, the 'file' command provides a more accurate MIME type identification.
- This is particularly useful for serving a variety of file types with correct MIME types, ensuring that browsers and clients handle the files appropriately.

Fallback Mechanism:
- If the MIME type cannot be determined using the 'file' command (e.g., command not available, file not found), the server falls back to a default MIME type of 'application/octet-stream'.
- This fallback ensures that the server remains functional even if MIME type detection fails for any reason.

Usage:
- The server is set to listen on port 80 by default.
- To start the server, simply run this script. The server will serve files from the directory where the script is located.

Note:
- This implementation relies on the external 'file' command, commonly available on Unix-like systems.
- Ensure that the 'file' command is installed and accessible in the environment where this server is run.
"""


import http.server
import socketserver
import subprocess
import os

PORT = 80

class MyHTTPRequestHandler(http.server.SimpleHTTPRequestHandler):
    def guess_type(self, path):
        if path.endswith('/'):
            return 'text/html'
        else:
            # Get the absolute path of the file
            file_path = os.path.join(os.getcwd(), path.lstrip('/'))
            # Use 'file' command to get the MIME type
            try:
                mime = subprocess.check_output(['file', '--mime-type', '-b', file_path])
                return mime.decode().strip()
            except Exception as e:
                print(f"Error determining MIME type: {e}")
                # Fallback to default behavior for files with no extension
                return 'application/octet-stream'

Handler = MyHTTPRequestHandler

with socketserver.TCPServer(("", PORT), Handler) as httpd:
    print("serving at port", PORT)
    httpd.serve_forever()
