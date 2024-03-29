<div class="col-12 mt-3 text-center status-indicator">
    @php
        $monitor = new \sqrd\ApisCP\Extensions\UptimeRobot();
        $status = $monitor->getNetworkStatus();
    @endphp
    @if(!is_null($status) && $monitor->getStatusPage())
        <a data-toggle="tooltip"
           title="{{ $status }}"
           href="{{ $monitor->getStatusPage() }}">
            <span class="fa fa-circle mr-1 {{ $monitor->textByStatus($status) }}"></span><span>Network Status</span>
        </a>
    @endif
</div>
