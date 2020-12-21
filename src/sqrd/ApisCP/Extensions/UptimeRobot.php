<?php declare(strict_types=1);

namespace sqrd\ApisCP\Extensions;

use Cache_Super_Global;
use HTTP_Request2;
use HTTP_Request2_Adapter_Curl;

class UptimeRobot
{
    const STATUS_URL = MISC_SYS_STATUS;
    const STATUS_PAGE = EXTENSIONS_UPTIMEROBOT_PAGE;

    public function getStatusPage(): string
    {
        return static::STATUS_URL;
    }

    public function getNetworkStatus()
    {
        $status = 'Not determined';

        if (!static::STATUS_PAGE)
        {
            return null;
        }

        $key = "sys.status";
        $cache = Cache_Super_Global::spawn();

        if (false !== ($status = $cache->get($key)))
        {
            return $status;
        }

        $url = sprintf('https://stats.uptimerobot.com/api/getMonitorList/%s', static::STATUS_PAGE);
        $adapter = new HTTP_Request2_Adapter_Curl();
        $req = new HTTP_Request2($url, HTTP_Request2::METHOD_GET, [
            'adapter' => $adapter,
        ]);

        $resp = $req->send();
        if ($resp->getStatus() !== 200)
        {
            return $status;
        }

        $body = json_decode($resp->getBody());

        if (isset($body->statistics->counts))
        {
            if (0 === $body->statistics->counts->down)
            {
                $status = 'Operational';
            }
            else
            {
                $status = $body->statistics->counts->down > $body->statistics->counts->up ? 'Major outage' : 'Partial outage';
            }
        }

        $cache->set($key, $status, 300);

        return $status;
    }

    public function textByStatus($status)
    {
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
