<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

-   php - 8.4.1
-   laravel/framework (LARAVEL) - v12
-   laravel/prompts (PROMPTS) - v0
-   laravel/mcp (MCP) - v0
-   laravel/pint (PINT) - v1
-   laravel/sail (SAIL) - v1
-   phpunit/phpunit (PHPUNIT) - v11
-   alpinejs (ALPINEJS) - v3
-   tailwindcss (TAILWINDCSS) - v4

## Conventions

-   You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
-   Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
-   Check for existing components to reuse before writing a new one.

## Verification Scripts

-   Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture

-   Stick to existing directory structure - don't create new base folders without approval.
-   Do not change the application's dependencies without approval.

## Frontend Bundling

-   If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies

-   Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files

-   You must only create documentation files if explicitly requested by the user.

=== boost rules ===

## Laravel Boost

-   Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

-   Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs

-   Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging

-   You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
-   Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool

-   You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
-   Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

-   Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
-   The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
-   You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.

## copilot instructions — noc_futsal (concise)

This file contains the key, discoverable rules and examples an AI coding agent needs to be productive in this Laravel 12 project.

Core environment

-   PHP 8.4.x, Laravel 12.31.x, Tailwind v4, Alpine v3. See `composer.json` / `package.json` for exact versions.

Quick start commands (developer workflows)

-   Install/composer: `composer install` (project has post-create scripts which run migrations in CI/dev).
-   Frontend: `npm run dev` (fast) or `npm run build` (produce manifest used by Vite).
-   Combined dev (project script): `composer run dev` — starts `php artisan serve`, queue worker, pail logs and Vite concurrently.
-   Tests: `php artisan test` or `composer test`. Run single test with `--filter`.
-   Formatting: `vendor/bin/pint --dirty` before finalizing changes.

Key entry points & examples (where to look first)

-   Routes: `routes/web.php` — uses `Route::resource` for `atletas` and `partidas` and adds named custom routes (e.g. `atletas.toggle-status`, `partidas.divisao.preview`). Use these route names when generating links.
-   Models: `app/Models/Atleta.php` (SoftDeletes, scopes like `scopeAtivos()` and helper methods such as `toggleStatus()`), `app/Models/Partida.php` (many-to-many `atletas()` via pivot table `partida_atletas` with pivot columns `confirmado` and `time`, domain methods like `podeDefinirTimes()` and constants for statuses/times).
-   Frontend: `resources/js/app.js` bootstraps Alpine and ships small utilities (e.g. `window.formatTelefone`). Vite inputs: `resources/css/app.css`, `resources/js/app.js` (see `vite.config.js`).
-   Migrations & seeds: `database/migrations/` and `database/seeders/` (e.g. `AtletasSeeder.php`, `AdminUserSeeder.php`). Factories live in `database/factories`.

Project-specific conventions and patterns

-   Prefer Eloquent relationships and scopes over raw `DB::` queries. Example: `Partida::proximas()` and `Atleta::ativos()` scopes are used across controllers.
-   Soft deletes: `Atleta` uses `SoftDeletes` — controllers expose restore routes (note `->withTrashed()` on restore route). Consider `withTrashed()` when searching or restoring.
-   Pivot usage: Manage `partida_atletas` pivot via controllers (`PartidaController@adicionarAtletas`, `removerAtleta`, `toggleConfirmacao`) — pivot contains `confirmado` and `time` fields; use `withPivot()` when querying.
-   Validation: Project convention is to use Form Request classes in `app/Http/Requests/` for controller validation. Follow existing request patterns when adding new validation.

Build & common failure modes

-   Vite manifest errors: if you see "Unable to locate file in Vite manifest", run `npm run build` or start dev mode with `composer run dev`.
-   If a local dev environment needs bootstrapping, `composer run dev` helps start the web server, queue and vite together.

Testing & CI guidance

-   New tests: use `php artisan make:test --phpunit NameOfTest` and prefer feature tests. Use model factories for fixtures.
-   After editing a test, run only that test (`php artisan test --filter=YourTestName`) before running the suite.

Small do / don'ts (explicit to this repo)

-   DO use `--no-interaction` for `php artisan make:*` where applicable in automation.
-   DO run `vendor/bin/pint --dirty` to match repository style before committing.
-   DO NOT create new top-level directories (follow existing structure: `app/`, `resources/`, `database/`).
-   DO NOT change composer / npm dependencies without explicit approval.

If unsure where to change behavior

-   Inspect `routes/web.php`, the relevant controller in `app/Http/Controllers/`, and the model in `app/Models/` (e.g., add behavior to `Partida` or `Atleta` model methods and corresponding controllers).

What I changed

-   Condensed the previous long guideline document into this concise, codebase-specific reference. The original Boost rules are still relevant; ask if you want the full, verbatim policy reinserted.

Feedback

-   Tell me which sections you want expanded (e.g., more controller examples, FormRequest patterns, or CI steps) and I will iterate.

## Do Things the Laravel Way
