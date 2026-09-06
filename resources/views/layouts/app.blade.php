<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>@yield('title','AFHM English Program')</title>
	@vite(['resources/css/app.css','resources/js/app.js'])
	@stack('head')
</head>
<body class="role-{{ auth()->user()->role }}">
	<div class="shell">
		<aside id="sidebar" aria-label="Main navigation">
			<a class="brand" href="{{ route(auth()->user()->role.'.dashboard') }}">
				<span>AF</span>
				<b>AFHM English Program</b>
			</a>

			<nav>
				<a href="{{ route(auth()->user()->role.'.dashboard') }}">Dashboard</a>

				@if(auth()->user()->role === 'admin')
					<a href="{{ route('admin.users.index') }}">Users</a>
					<a href="{{ route('admin.assignments.edit') }}">Assign Supervisor</a>
					<a href="{{ route('admin.timetables.index') }}">Timetables</a>
				@elseif(auth()->user()->role === 'teacher')
					<a href="{{ route('teacher.timetable') }}">My Timetable</a>
					<a href="{{ route('teacher.reports.create') }}">Submit Report</a>
				@elseif(auth()->user()->role === 'supervisor')
					<a href="{{ route('supervisor.teachers.index') }}">Assigned Teachers</a>
				@endif

				<a href="{{ route('reports.index') }}">Teaching Reports</a>
				<a href="{{ route('materials.index') }}">Materials</a>

				@if(auth()->user()->hasRole('admin','school_manager'))
					<a href="{{ route('performance.index') }}">Performance</a>
				@endif

				<a href="{{ route('notifications.index') }}">Notifications
					@if(auth()->user()->unreadNotifications()->count())
						<em>{{ auth()->user()->unreadNotifications()->count() }}</em>
					@endif
				</a>
				<a href="{{ route('profile.edit') }}">Profile</a>
			</nav>
		</aside>

		<main>
			<header>
				<div class="header-left">
					<button id="menu" aria-label="Toggle menu">☰</button>

					<div class="header-title">
						<small>AFHM English Department</small>
						<strong>{{ auth()->user()->name }}</strong>
					</div>
				</div>

				<div class="header-actions">
					<button id="theme-toggle" class="theme-toggle" type="button" aria-label="Toggle dark mode">
						<span class="theme-toggle__icon">☀️</span>
						<span class="theme-toggle__text">Light</span>
					</button>

					<form method="POST" action="{{ route('logout') }}">
						@csrf
						<button class="link" aria-label="Sign out">Sign out</button>
					</form>
				</div>
			</header>

			<script>
				(function () {
					const root = document.body;
					const toggle = document.getElementById('theme-toggle');
					const saved = localStorage.getItem('afhm-theme');

					const applyTheme = (mode) => {
						const isDark = mode === 'dark';
						root.classList.toggle('dark-mode', isDark);

						if (!toggle) return;
						toggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
						toggle.querySelector('.theme-toggle__icon').textContent = isDark ? '🌙' : '☀️';
						toggle.querySelector('.theme-toggle__text').textContent = isDark ? 'Dark' : 'Light';
					};

					applyTheme(saved || 'light');

					if (toggle) {
						toggle.addEventListener('click', function () {
							const nextMode = root.classList.contains('dark-mode') ? 'light' : 'dark';
							localStorage.setItem('afhm-theme', nextMode);
							applyTheme(nextMode);
						});
					}
				})();
			</script>

			<section class="content">
				@if(session('success'))
					<div class="alert success">{{ session('success') }}</div>
				@endif

				@if($errors->any())
					<div class="alert error">
						<b>Please correct the following:</b>
						<ul>
							@foreach($errors->all() as $e)
								<li>{{ $e }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				@yield('content')
			</section>
		</main>
	</div>
</body>
</html>
