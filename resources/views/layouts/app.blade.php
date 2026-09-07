<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>@yield('title',__('AFHM English Program'))</title>
	@vite(['resources/css/app.css','resources/js/app.js'])
	@stack('head')
</head>
<body class="role-{{ auth()->user()->role }}">
	<div class="shell">
		<aside id="sidebar" aria-label="{{ __('Main navigation') }}">
			<a class="brand" href="{{ route(auth()->user()->role.'.dashboard') }}">
				<span>AF</span>
				<b>{{ __('AFHM English Program') }}</b>
			</a>

			<nav>
				<a href="{{ route(auth()->user()->role.'.dashboard') }}">{{ __('Dashboard') }}</a>

				@if(auth()->user()->role === 'admin')
					<a href="{{ route('admin.users.index') }}">{{ __('Users') }}</a>
					<a href="{{ route('admin.assignments.edit') }}">{{ __('Assign Supervisor') }}</a>
					<a href="{{ route('admin.timetables.index') }}">{{ __('Timetables') }}</a>
				@elseif(auth()->user()->role === 'teacher')
					<a href="{{ route('teacher.timetable') }}">{{ __('My Timetable') }}</a>
					<a href="{{ route('teacher.reports.create') }}">{{ __('Submit Report') }}</a>
				@elseif(auth()->user()->role === 'supervisor')
					<a href="{{ route('supervisor.teachers.index') }}">{{ __('Assigned Teachers') }}</a>
				@endif

				<a href="{{ route('reports.index') }}">{{ __('Teaching Reports') }}</a>
				<a href="{{ route('materials.index') }}">{{ __('Materials') }}</a>

				@if(auth()->user()->hasRole('admin','school_manager'))
					<a href="{{ route('performance.index') }}">{{ __('Performance') }}</a>
				@endif

				<a href="{{ route('notifications.index') }}">{{ __('Notifications') }}
					@if(auth()->user()->unreadNotifications()->count())
						<em>{{ auth()->user()->unreadNotifications()->count() }}</em>
					@endif
				</a>
				<a href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
			</nav>
		</aside>

		<main>
			<header>
				<div class="header-left">
					<button id="menu" aria-label="{{ __('Toggle menu') }}">☰</button>

					<div class="header-title">
						<small>{{ __('AFHM English Department') }}</small>
						<strong>{{ auth()->user()->name }}</strong>
					</div>
				</div>

				<div class="header-actions">
					@include('partials.language-switch')
					<button id="theme-toggle" class="theme-toggle" type="button" aria-label="{{ __('Switch to dark mode') }}">
						<span class="theme-toggle__icon" aria-hidden="true">🌙</span>
						<span class="theme-toggle__text">{{ __('Dark') }}</span>
					</button>

					<form method="POST" action="{{ route('logout') }}">
						@csrf
						<button class="link" aria-label="{{ __('Sign out') }}">{{ __('Sign out') }}</button>
					</form>
				</div>
			</header>

			<script>
				(function () {
					const root = document.body;
					const toggle = document.getElementById('theme-toggle');
					let saved = 'light';
					try {
						saved = localStorage.getItem('afhm-theme') || 'light';
					} catch {
						// Switching still works when browser storage is unavailable.
					}

					const applyTheme = (mode) => {
						const isDark = mode === 'dark';
						root.classList.toggle('dark-mode', isDark);

						if (!toggle) return;
						toggle.setAttribute('aria-label', isDark ? @json(__('Switch to light mode')) : @json(__('Switch to dark mode')));
						toggle.querySelector('.theme-toggle__icon').textContent = isDark ? '☀️' : '🌙';
						toggle.querySelector('.theme-toggle__text').textContent = isDark ? @json(__('Light')) : @json(__('Dark'));
					};

					applyTheme(saved || 'light');

					if (toggle) {
						toggle.addEventListener('click', function () {
							const nextMode = root.classList.contains('dark-mode') ? 'light' : 'dark';
							applyTheme(nextMode);
							try {
								localStorage.setItem('afhm-theme', nextMode);
							} catch {
								// Keep the selected theme even if it cannot be saved.
							}
						});
					}
				})();
			</script>

			<section class="content">
				@if(session('success'))
					<div class="alert success">{{ __(session('success')) }}</div>
				@endif

				@if($errors->any())
					<div class="alert error">
						<b>{{ __('Please correct the following:') }}</b>
						<ul>
							@foreach($errors->all() as $e)
								<li>{{ __($e) }}</li>
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
