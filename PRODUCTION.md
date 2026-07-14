Production Preparation Summary
=============================

This document summarizes the code changes made to prepare the application for production and explains each optimization.

1) Database indexes
- Added indexes on `visitors.phone`, `visitors.church_id`, `visitors.status` and other frequently filtered/sorted columns. Reason: improves lookup speed for common queries by using B-tree indexes and reduces full table scans for large datasets.

2) Activity & Audit logs
- Introduced `activities` (user-friendly) and `audits` (detailed old/new values) tables plus `AuditableObserver`. Reason: provides an audit trail for compliance and debugging while keeping recent activity accessible.

3) Queries & Caching
- Implemented caching in reports (TTL configurable). Reason: expensive aggregation queries are cached to improve dashboard responsiveness.

4) Event-driven & Queues
- All SMS sending is queued (already implemented) and retry job scheduled. Reason: avoids synchronous network calls in web requests and improves system resiliency.

5) Exception handling & logging
- Added `app/Exceptions/Handler.php` to enrich logs with request context and return JSON for API requests. Reason: better observability and more actionable logs in production.

6) Authorization
- Added `AuthServiceProvider` and basic policies scaffolding for `Visitor` and `FollowUp`. Reason: infrastructure for role/ownership checks; refine policies to match your RBAC model.

7) Architecture scaffolding
- Added `zones`, `departments`, `cells`, and `attendances` tables and models to prepare for multi-zone/multi-church features. Reason: additive scaffolding that won't break existing code.

8) Index + migration strategy
- All changes are additive (new migrations). No existing migrations modified. Reason: safe deploy path; run `php artisan migrate` to apply.

How to apply in production
- Set app in maintenance mode, push code, run migrations, warm cache, restart queue workers, bring app back.

Commands
```bash
php artisan down
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder --force
php artisan cache:clear
php artisan config:cache
php artisan queue:restart
php artisan up
```

Notes & Next steps
- Implement role system and tighten policies.
- Add monitoring (Sentry, Papertrail).
- Add Content Security Policy and HSTS at the webserver level.
- Implement backups and health checks.
