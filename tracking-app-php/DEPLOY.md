# Deployment Checklist

## Pre-Deployment

- [ ] Edit `config.php` - Set `API_BASE_URL` to your main app
- [ ] Edit `config.php` - Set `APP_NAME` 
- [ ] Edit `sitemap.xml` - Replace domain with yours
- [ ] Edit `robots.txt` - Update sitemap URL
- [ ] Test API endpoint works: `curl {API_BASE_URL}/track/{TEST_ID}`

## Upload Files

Upload these files to your tracking domain:
```
├── index.php
├── result.php
├── 404.php
├── config.php
├── functions.php
├── .htaccess
├── robots.txt
├── sitemap.xml
├── cache/          (create directory)
└── logs/           (create directory)
```

## Set Permissions

```bash
chmod 755 cache logs
chmod 644 *.php *.txt *.xml
```

## Post-Deployment Tests

- [ ] Homepage loads: `https://yourdomain.com/`
- [ ] Tracking works: Search for a valid tracking ID
- [ ] Error handling: Search for invalid ID shows error
- [ ] Rate limiting: Rapid requests get blocked
- [ ] Caching: Same ID twice = fast second load
- [ ] Dark mode: Toggle works and persists
- [ ] Recent searches: Shows after first search
- [ ] Copy link: Button works
- [ ] Print: Page looks good when printed
- [ ] 404 page: Visit invalid URL shows custom 404
- [ ] Clean URL: `/ABC123` works (if using Apache)

## Security Checks

- [ ] Cache directory not accessible via browser
- [ ] Logs directory not accessible via browser
- [ ] PHP files don't show source code
- [ ] HTTPS enabled (SSL certificate)

## Optional: Clean URLs (Apache)

If using Apache with mod_rewrite:
- [ ] `.htaccess` file is uploaded
- [ ] `mod_rewrite` is enabled
- [ ] Test: `yourdomain.com/TRACK123` redirects to result

## Optional: Clean URLs (Nginx)

If using Nginx, add to server block:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ ^/(cache|logs)/ {
    deny all;
}
```

## Done! 🎉
