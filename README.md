# AFHM Web English Department Management System

AFHM English Program is a Laravel 13 / Blade application for school English-department administration, supervision, timetables, teaching reports, evaluations, feedback, protected teaching materials, performance reporting, and database notifications.

## Roles and features

- **Admin:** user/role management, activation, supervisor assignments, timetable CRUD with collision checks, materials, all reports, and analytics.
- **School Manager:** dashboards, reports, materials, analytics, and generated performance reports.
- **Teacher:** assigned timetable, materials, weekly/monthly reports and attachments, own supervisor, feedback, and evaluations.
- **Supervisor:** assigned teachers and reports, evaluations, feedback, performance context, materials, and notifications.

All restricted routes are protected server-side. Private files are downloaded through authorized controllers. Scores use a 1–5 range and the work week is Sunday–Thursday. Performance exports use printable HTML to avoid a PDF package dependency.

## Stack and installation

PHP 8.3+, Laravel 13, MySQL, Blade, Vite 8, Tailwind CSS 4 tooling, PHPUnit 12, and Laravel database notifications.

```bash
composer install
copy .env.example .env
php artisan key:generate
npm install
```

Create a MySQL database named `afhm` and configure the `DB_*` values in `.env`. For lightweight testing, SQLite is also supported by setting `DB_CONNECTION=sqlite` and creating `database/database.sqlite`.

```bash
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Files use Laravel's private local disk, so `storage:link` is not required. Database notifications work synchronously. Mail defaults to the log driver; configure `MAIL_*` environment values if email channels are added. Never commit secrets.

## Demo credentials

All seeded accounts use password `password`.

| Role | Email |
|---|---|
| Admin | admin@afhm.test |
| School Manager | manager@afhm.test |
| Supervisor | supervisor@afhm.test |
| Teacher | teacher@afhm.test |

Additional teachers, another supervisor, schedules, reports, feedback, evaluations, and material metadata make dashboards useful immediately.

## Development and testing

```bash
npm run dev
php artisan test
php artisan route:list --except-vendor
php artisan optimize:clear
```

Uploads accept PDF, Word, PowerPoint, and common image formats with size limits. Seeded material records demonstrate metadata but do not ship binary documents, so those placeholder downloads return 404. User deletion is blocked when historical records exist; deactivation is the archival behavior. Deleting a supervisor nulls teacher assignments rather than deleting teachers.
