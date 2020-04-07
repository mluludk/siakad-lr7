<style>
	.navbar-nav>.notifications-menu>.dropdown-menu>li .menu>li>a, .navbar-nav>.messages-menu>.dropdown-menu>li .menu>li>a, .navbar-nav>.tasks-menu>.dropdown-menu>li .menu>li>a {
	white-space: normal !important;
	}
</style>

<?php
	$notif_count = count($notifications);
?>
@if($notif_count > 0)
<li class="dropdown notifications-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<i class="fa fa-bell-o"></i>
		<span class="label label-warning">{{ $notif_count }}</span>
	</a>
	<ul class="dropdown-menu">
		<li class="header">Anda mempunyai {{ $notif_count }} pemberitahuan</li>
		<li>
			<!-- inner menu: contains the actual data -->
			<ul class="menu">
				@foreach($notifications as $notif)
				<li>
					<a>
						<i class="{{ $notif['icon'] ?? 'fa fa-bell-o' }} {{ $notif['status'] ?? '' }}"></i> {{ $notif['message'] ?? '' }}
					</a>
				</li>
				@endforeach
			</ul>
		</li>
		<li class="footer"><a href="#">View all</a></li>
	</ul>
</li>
@endif