const { readFileSync } = require('fs');
const { join } = require('path');

module.exports = (req, res) => {
  const { pathname } = new URL(req.url, `http://${req.headers.host}`);
  if (!/\./.test(pathname)) {
    // Trying to serve a file without an extension
    try {
      const content = readFileSync(join(__dirname, '..', pathname));
      res.setHeader('Content-Type', 'text/html');
      res.status(200).send(content);
    } catch (e) {
      res.status(404).send('Not found');
    }
  } else {
    // For other requests, serve as usual
    res.status(404).send('Not found');
  }
};
