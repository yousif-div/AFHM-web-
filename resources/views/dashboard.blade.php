@extends('layouts.app')

@section('title', ucwords(str_replace('_',' ', $u->role)) . ' Dashboard')

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
                <p class="eyebrow">{{ ucwords(str_replace('_',' ', $u->role)) }} workspace</p>
                <h1>Welcome back, {{ $u->name }}</h1>
                <p>Here is the latest activity in the English department.</p>
            </div>

            @if($u->role === 'teacher')
                <div class="actions">
                    <a class="primary" href="{{ route('teacher.reports.create') }}">Submit report</a>
                </div>
            @endif
        </div>

        <div class="stats">
            <article>
                <span>Teachers</span>
                <b>{{ $data['activeTeachers'] }}</b>
                <small>active faculty</small>
            </article>

            <article>
                <span>Teaching reports</span>
                <b>{{ $data['reports'] }}</b>
                <small>submitted</small>
            </article>

            <article>
                <span>Supervisors</span>
                <b>{{ $data['supervisors'] }}</b>
                <small>department leads</small>
            </article>

            <article>
                <span>Average score</span>
                <b>{{ $data['averageScore'] ?: '—' }}</b>
                <small>out of 5</small>
            </article>
        </div>

        @if($u->role === 'teacher')
            <div class="grid2">
                <section class="panel">
                    <h2>Weekly timetable</h2>

                    @forelse($data['myTimetables'] as $x)
                        <div class="row">
                            <b>{{ $x->day }} {{ $x->start_time }}–{{ $x->end_time }}</b>
                            <span>{{ $x->subject }} · {{ $x->classroom }}</span>
                        </div>
                    @empty
                        <p class="empty">No timetable entries assigned.</p>
                    @endforelse
                </section>

                <section class="panel">
                    <h2>Your support</h2>
                    <div class="row"><b>Supervisor</b><span>{{ $u->supervisor?->name ?? 'Not assigned' }}</span></div>
                    <div class="row"><b>Latest evaluation</b><span>{{ $data['myEvaluation']?->score ?? 'No evaluation yet' }}</span></div>
                    <div class="row"><b>Latest feedback</b><span>{{ $data['myFeedback']?->content ?? 'No feedback yet' }}</span></div>
                </section>
            </div>

        @elseif($u->role === 'supervisor')
            <div class="grid2">
                <section class="panel">
                    <h2>Assigned teachers</h2>
                    @forelse($data['assigned'] as $t)
                        <a class="row" href="{{ route('supervisor.teachers.show', $t) }}">
                            <b>{{ $t->name }}</b>
                            <span>{{ $t->subject }} · {{ $t->grade }}</span>
                        </a>
                    @empty
                        <p class="empty">No teachers assigned.</p>
                    @endforelse
                </section>

                <section class="panel">
                    <h2>Recent assigned reports</h2>
                    @forelse($data['assignedReports'] as $r)
                        <a class="row" href="{{ route('reports.show', $r) }}">
                            <b>{{ $r->title }}</b>
                            <span>{{ $r->submitted_at->format('M j') }}</span>
                        </a>
                    @empty
                        <p class="empty">No reports submitted.</p>
                    @endforelse
                </section>
            </div>

        @else
            <div class="grid2">
                <section class="panel">
                    <h2>Recent teaching reports</h2>
                    @forelse($data['recentReports'] as $r)
                        <a class="row" href="{{ route('reports.show', $r) }}">
                            <b>{{ $r->title }}</b>
                            <span>{{ $r->teacher->name }} · {{ $r->submitted_at->format('M j') }}</span>
                        </a>
                    @empty
                        <p class="empty">No recent reports.</p>
                    @endforelse
                </section>

                <section class="panel">
                    <h2>Recent evaluations</h2>
                    @forelse($data['recentEvaluations'] as $e)
                        <div class="row">
                            <b>{{ $e->teacher->name }}</b>
                            <span>{{ $e->score }} / 5 · {{ $e->evaluated_at->format('M j') }}</span>
                        </div>
                    @empty
                        <p class="empty">No evaluations yet.</p>
                    @endforelse
                </section>
            </div>
        @endif
    </div>

@endsection
