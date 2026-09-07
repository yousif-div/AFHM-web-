@extends('layouts.app')

@section('title', __(':role Dashboard', ['role' => __(ucwords(str_replace('_',' ', $u->role)))]))

@section('content')

    @php
        $roleTheme = match ($u->role) {
            'admin' => 'admin',
            'school_manager' => 'school-manager',
            'supervisor' => 'supervisor',
            default => 'teacher',
        };
    @endphp

    <div class="dashboard-shell role-{{ $roleTheme }}">
        <div class="pagehead">
            <div>
                <p class="eyebrow">{{ __(':role workspace', ['role' => __(ucwords(str_replace('_',' ', $u->role)))]) }}</p>
                <h1>{{ __('Welcome back, :name', ['name' => $u->name]) }}</h1>
                <p>{{ __('Here is the latest activity in the English department.') }}</p>
            </div>

            @if($u->role === 'teacher')
                <div class="actions">
                    <a class="primary" href="{{ route('teacher.reports.create') }}">{{ __('Submit report') }}</a>
                </div>
            @endif
        </div>

        <div class="stats">
            <article>
                <span>{{ __('Teachers') }}</span>
                <b>{{ $data['activeTeachers'] }}</b>
                <small>{{ __('active faculty') }}</small>
            </article>

            <article>
                <span>{{ __('Teaching reports') }}</span>
                <b>{{ $data['reports'] }}</b>
                <small>{{ __('submitted') }}</small>
            </article>

            <article>
                <span>{{ __('Supervisors') }}</span>
                <b>{{ $data['supervisors'] }}</b>
                <small>{{ __('department leads') }}</small>
            </article>

            <article>
                <span>{{ __('Average score') }}</span>
                <b>{{ $data['averageScore'] ?: '—' }}</b>
                <small>{{ __('out of 5') }}</small>
            </article>
        </div>

        @if($u->role === 'teacher')
            <div class="grid2">
                <section class="panel">
                    <h2>{{ __('Weekly timetable') }}</h2>

                    @forelse($data['myTimetables'] as $x)
                        <div class="row">
                            <b>{{ __($x->day) }} {{ $x->start_time }}–{{ $x->end_time }}</b>
                            <span>{{ $x->subject }} · {{ $x->classroom }}</span>
                        </div>
                    @empty
                        <p class="empty">{{ __('No timetable entries assigned.') }}</p>
                    @endforelse
                </section>

                <section class="panel">
                    <h2>{{ __('Your support') }}</h2>
                    <div class="row"><b>{{ __('Supervisor') }}</b><span>{{ $u->supervisor?->name ?? __('Not assigned') }}</span></div>
                    <div class="row"><b>{{ __('Latest evaluation') }}</b><span>{{ $data['myEvaluation']?->score ?? __('No evaluation yet') }}</span></div>
                    <div class="row"><b>{{ __('Latest feedback') }}</b><span>{{ $data['myFeedback']?->content ?? __('No feedback yet') }}</span></div>
                </section>
            </div>

        @elseif($u->role === 'supervisor')
            <div class="grid2">
                <section class="panel">
                    <h2>{{ __('Assigned teachers') }}</h2>
                    @forelse($data['assigned'] as $t)
                        <a class="row" href="{{ route('supervisor.teachers.show', $t) }}">
                            <b>{{ $t->name }}</b>
                            <span>{{ $t->subject }} · {{ $t->grade }}</span>
                        </a>
                    @empty
                        <p class="empty">{{ __('No teachers assigned.') }}</p>
                    @endforelse
                </section>

                <section class="panel">
                    <h2>{{ __('Recent assigned reports') }}</h2>
                    @forelse($data['assignedReports'] as $r)
                        <a class="row" href="{{ route('reports.show', $r) }}">
                            <b>{{ $r->title }}</b>
                            <span>{{ $r->submitted_at->translatedFormat('M j') }}</span>
                        </a>
                    @empty
                        <p class="empty">{{ __('No reports submitted.') }}</p>
                    @endforelse
                </section>
            </div>

        @else
            <div class="grid2">
                <section class="panel">
                    <h2>{{ __('Recent teaching reports') }}</h2>
                    @forelse($data['recentReports'] as $r)
                        <a class="row" href="{{ route('reports.show', $r) }}">
                            <b>{{ $r->title }}</b>
                            <span>{{ $r->teacher->name }} · {{ $r->submitted_at->translatedFormat('M j') }}</span>
                        </a>
                    @empty
                        <p class="empty">{{ __('No recent reports.') }}</p>
                    @endforelse
                </section>

                <section class="panel">
                    <h2>{{ __('Recent evaluations') }}</h2>
                    @forelse($data['recentEvaluations'] as $e)
                        <div class="row">
                            <b>{{ $e->teacher->name }}</b>
                            <span>{{ $e->score }} / 5 · {{ $e->evaluated_at->translatedFormat('M j') }}</span>
                        </div>
                    @empty
                        <p class="empty">{{ __('No evaluations yet.') }}</p>
                    @endforelse
                </section>
            </div>
        @endif
    </div>

@endsection
