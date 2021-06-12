<?php declare(strict_types=1);

namespace sqrd\ApisCP\Extensions;

use Cache_Super_Global;
use HTTP_Request2;
use HTTP_Request2_Adapter_Curl;
use function json_decode;

class UptimeRobot
{
    const STATUS_URL = MISC_SYS_STATUS;
    const STATUS_PAGE = EXTENSIONS_UPTIMEROBOT_PAGE;
    const NOT_DETERMINED = 'Not determined';
    const OPERATIONAL = 'Operational';
    const MAJOR_OUTAGE = 'Major outage';
    const PARTIAL_OUTAGE = 'Partial outage';

    public function getStatusPage(): ?string
    {
        return static::STATUS_URL ?? null;
    }

    public function getNetworkStatus(): ?string
    {
        // Fail early if no status page has been set
        if (!static::STATUS_PAGE)
        {
            return null;
        }

        $key = 'sys.status';
        $cache = Cache_Super_Global::spawn();

        if (false !== ($status = $cache->get($key)))
        {
            // Return cached status if found
            return $status;
        }

        try
        {
            // Try to retrieve API data
            $url = sprintf('https://stats.uptimerobot.com/api/getMonitorList/%s', static::STATUS_PAGE);
            $adapter = new HTTP_Request2_Adapter_Curl();
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_GET, [
                'adapter' => $adapter,
                'connect_timeout' => 5,
                'timeout' => 5,
            ]);

            $resp = $req->send();
            if ($resp->getStatus() !== 200)
            {
                // Return if status was not 200, which should result in static::NOT_DETERMINED
                return static::NOT_DETERMINED;
            }

            $body = json_decode($resp->getBody());

            // Search for stats data
            if (isset($body->statistics->counts))
            {
                // Operational if no monitors with down status
                if (0 === $body->statistics->counts->down)
                {
                    $status = static::OPERATIONAL;
                }
                else
                {
                    // Major outage if down monitors are greater than up monitors
                    $status = $body->statistics->counts->down > $body->statistics->counts->up ? static::MAJOR_OUTAGE : static::PARTIAL_OUTAGE;
                }
            }

            // Keep data cached for 15 mins
            $cache->set($key, $status, 300);

        } catch (\Exception $e)
        {
            // Fallback to static::NOT_DETERMINED if anything breaks
            $status = static::NOT_DETERMINED;
        }

        return $status;
    }

    public function textByStatus($status): string
    {
        // Fail early if not a string
        if (!is_string($status))
        {
            return 'text-muted';
        }

        $status = str_replace(' ', '-', strtolower($status));

        switch ($status)
        {
            case 'operational':
                return 'text-success';
            case 'major-outage':
                return 'text-danger';
            case 'partial-outage':
                return 'text-warning';
            default:
                return 'text-muted';
        }
    }
}
