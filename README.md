# UptimeRobot integration for ApisCP

This extensions provides an implementation for ApisCP to fetch network status from UptimeRobot.

## Install

1. Clone and install this extension.
   ```
   cd /usr/local/apnscp
   mkdir -p extensions
   git clone https://github.com/thundersquared/apiscp-uptimerobot.git extensions/apiscp-uptimerobot
   cd extensions/apiscp-uptimerobot
   composer install
   ```

2. Set your status page URL, key and page ID.
   ```
   cpcmd scope:set cp.config misc sys_status 'https://status.domain.com/'
   cpcmd scope:set cp.config extensions uptimerobot_page 9gZ1ltm55y
   ```
3. Wait for Apache to reload and check out your panel.
