import http.server
import socketserver

PORT = 80

class MyHTTPRequestHandler(http.server.SimpleHTTPRequestHandler):
    def guess_type(self, path):
        if path.endswith('/'):
            return 'text/html'
        else:
            # Default behavior for files with no extension
            return 'text/html'

Handler = MyHTTPRequestHandler

with socketserver.TCPServer(("", PORT), Handler) as httpd:
    print("serving at port", PORT)
    httpd.serve_forever()
