# chumworx.com Project Notes

## Purpose
chumworx.com is the landing page / gateway site for Wes Chumley projects.

Current linked projects:
- Wolf Disc Golf Scoring App: `/wolf/`
- DG Series app: `https://dgseries.com/`

This root site is intended to stay lightweight and easy to deploy on shared hosting.

---

## Hosting / Deployment Setup

### Hosting
- Host: Namecheap shared hosting
- cPanel user: `chumuvep`
- Web root: `/home/chumuvep/public_html`

### Git / Deployment Workflow
Primary workflow:
1. Work locally
2. Commit and push from GitHub Desktop
3. In cPanel Git Version Control:
   - **Update from Remote**
   - **Deploy HEAD Commit**

Repository paths:
- chumworx.com repo: `/home/chumuvep/repos/chumworx.com`
- wolf repo: `/home/chumuvep/repos/wolf`
- dgseries repo: `/home/chumuvep/repos/dgseries.com`

GitHub repo:
- `https://github.com/weschum/chumworx.com.git`

---

## Important Deployment Rule

The chumworx.com deployment must **never touch** these existing server folders:

- `public_html/wolf/`
- `public_html/uplay/`
- `public_html/dunhill/`
- `public_html/cgi-bin/`
- `public_html/.well-known/`

This protection is handled by `.cpanel.yml`.

Do not change deployment behavior without confirming these exclusions remain in place.

---

## Current `.cpanel.yml`

```yaml
---
deployment:
  tasks:
    - export DEPLOYPATH=/home/chumuvep/public_html
    - /usr/bin/rsync -av --delete --exclude='.git/' --exclude='.cpanel.yml' --exclude='wolf/' --exclude='uplay/' --exclude='dunhill/' --exclude='cgi-bin/' --exclude='.well-known/' ./ $DEPLOYPATH/
    - /bin/chmod 755 $DEPLOYPATH
    - /usr/bin/find $DEPLOYPATH -maxdepth 1 -type f -exec chmod 644 {} \;
    - /usr/bin/find $DEPLOYPATH/assets -type d -exec chmod 755 {} \;
    - /usr/bin/find $DEPLOYPATH/assets -type f -exec chmod 644 {} \;