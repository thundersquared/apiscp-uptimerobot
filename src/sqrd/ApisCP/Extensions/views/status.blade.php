<div class="col-12 mt-3 text-center status-indicator">
    @php
        $monitor = new \sqrd\ApisCP\Extensions\UptimeRobot();
        $status = $monitor->getNetworkStatus();
    @endphp
    <a class="card-link ui-expandable"
       data-toggle="tooltip"
       title="{{ $status }}"
       href="{{ $monitor->getStatusPage() }}">
		<span class="fa fa-circle mr-1 {{ $monitor->textByStatus($status) }}"></span>
        <span>Network Status</span>
    </a>
</div>
