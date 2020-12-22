# UptimeRobot integration for ApisCP

This extensions provides an implementation for ApisCP to fetch network status from UptimeRobot.

## Make it work

1. Create a public status page
2. Get your PSP ID (e.g. `https://stats.uptimerobot.com/abc123` &rarr; `abc123` is what you need)
3. Clone and install this extension.
   ```
   cd /usr/local/apnscp
   mkdir -p extensions
   git clone https://github.com/thundersquared/apiscp-uptimerobot.git extensions/apiscp-uptimerobot
   cd extensions/apiscp-uptimerobot
   composer install
   ```
4. Set your status page URL and ID.
   ```
   cpcmd scope:set cp.config misc sys_status 'https://status.domain.com/'
   cpcmd scope:set cp.config extensions uptimerobot_page abc123
   ```
5. Wait for Apache to reload and check out your panel.

## Updating

Just a copy-pasta:
```
cd /usr/local/apnscp/extensions/apiscp-uptimerobot
git pull
composer update
```
