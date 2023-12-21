#!/usr/bin/env python3

"""
Custom Python HTTP Server with Vercel-like Configuration
--------------------------------------------------------

This HTTP server extends Python's SimpleHTTPRequestHandler to simulate Vercel's behavior using a vercel.json configuration file. It includes enhanced MIME type detection and custom header, redirect, and rewrite rules.

Enhancements:
- MIME Type Detection: Uses the Unix 'file' command for accurate MIME types.
- Custom Headers: Applies headers defined in vercel.json to responses.
- Redirects and Rewrites: Handles URL redirects and rewrites as specified in vercel.json.
- Fallback MIME Type: optionally defaults to 'application/octet-stream' if detection fails OR uses the mimetype magic detection

Usage:
- Listens on port 80 by default.
- Serve files from the script's directory.
- Requires 'file' command (Unix-like systems) for MIME detection.

Note:
- The server dynamically reloads vercel.json for each request.
- It handles requests with enhanced logic for redirects, rewrites, and custom headers.
"""


import http.server
import socketserver
import socket
import subprocess
import os
import json
import logging

# Configure logging
logging.basicConfig(level=logging.DEBUG)

# import inspect
# source_code = inspect.getsource(http.server.SimpleHTTPRequestHandler.translate_path)
# print(source_code)


PORT = 80
USE_MIME_TYPE_DETECTION = False  # Set to True to enable custom MIME type detection

class MyHTTPRequestHandler(http.server.SimpleHTTPRequestHandler):
    # Load and parse vercel.json
    with open('vercel.json', 'r') as file:
        vercel_config = json.load(file)


    def get_custom_headers(self, path):
        headers = {}
        for header_rule in self.vercel_config.get('headers', []):
            normalized_source = header_rule['source'].rstrip('/')
            if path.startswith(normalized_source):
                for header in header_rule['headers']:
                    headers[header['key']] = header['value']
        return headers


    def send_response(self, code, message=None):
        super().send_response(code, message=message)
        custom_headers = self.get_custom_headers(self.path)
        for key, value in custom_headers.items():
            if key.lower() == 'content-type':
                continue
            self.send_header(key, value)
            logging.debug(f"Applying header: {key} -> {value}")



    def guess_type(self, path):
        custom_headers = self.get_custom_headers(self.path)
        for key, value in custom_headers.items():
            if key.lower() == 'content-type':
                logging.debug(f"Applying header: {key} -> {value}")
                return value

        if not USE_MIME_TYPE_DETECTION:
            return super().guess_type(path)

        if path.endswith('/'):
            return 'text/html'
        else:
            file_path = os.path.join(os.getcwd(), path.lstrip('/'))
            try:
                mime = subprocess.check_output(['file', '--mime-type', '-b', file_path]).decode().strip()
                return mime
            except Exception as e:
                logging.error(f"Error determining MIME type: {e}")
                return 'application/octet-stream'


    def is_directory_request_without_trailing_slash(self):
        path = self.translate_path(self.path)
        return os.path.isdir(path) and not self.path.endswith('/')


    def do_GET(self):
        # Reload vercel.json for each request
        with open('vercel.json', 'r') as file:
            self.vercel_config = json.load(file)

        # # Check if the path doesn't end with a slash and doesn't contain a dot
        # if not self.path.endswith('/') and '.' not in os.path.basename(self.path):
              # logging.debug("Appending trailing slash and sending 308 redirect.")

        #     # Redirect to the same path with a trailing slash
        #     self.send_response(308)
        #     self.send_header('Location', self.path + '/')
        #     self.end_headers()
        #     return

        # Check if the path doesn't end with a slash and doesn't contain a dot
        if not self.path.endswith('/') and '.' not in self.path:
            # Redirect to the same path with a trailing slash
            logging.debug("Appending trailing slash and sending 308 redirect.")
            self.send_response(308)
            self.send_header('Location', self.path + '/')
            self.end_headers()
            return

      # Modify the path here if it points to a directory without a trailing slash
        original_path = self.path
        if self.is_directory_request_without_trailing_slash():
            self.path += '/'



        # Store the original path
        original_path = self.path

        # Remove trailing slash if it's a file
        if self.path.endswith('/'):
            path_without_slash = self.path.rstrip('/')
            file_system_path = self.translate_path(path_without_slash)
            if os.path.isfile(file_system_path):
                self.path = path_without_slash

        # Redirect and rewrite handling based on vercel.json
        path = self.path

        # Apply redirects
        for redirect in self.vercel_config.get('redirects', []):
            if path == redirect['source']:
                logging.debug(f"Redirect to: {redirect['destination']}")
                self.send_response(301)
                self.send_header('Location', redirect['destination'])
                self.end_headers()
                return

        # Apply rewrites
        for rewrite in self.vercel_config.get('rewrites', []):
            if path == rewrite['source']:
                self.path = rewrite['destination']
                break

        # Proceed with the regular GET handling
        super().do_GET()

        # Restore the original path
        self.path = original_path


import time

Handler = MyHTTPRequestHandler

for i in range(100):
    try:
        httpd = socketserver.TCPServer(("", PORT), Handler)

        logging.info("serving at port %d", PORT)
        httpd.serve_forever()
    except OSError as e:
        if e.errno != 48:
            raise
        logging.warning("Address already in use, retrying in 1 seconds...")
        time.sleep(1)
