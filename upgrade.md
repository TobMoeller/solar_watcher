  # Upgrade Solar Watcher To Laravel 13, Livewire 4, PHP 8.5

  ## Summary

  Upgrade the app directly from Laravel 11/Livewire 3 to Laravel 13/Livewire 4, preserving the current Jetstream/Fortify/Sanctum stack and existing guest dashboard/API behavior. The target runtime is PHP 8.5 only, so the implementation environment and CI must run PHP 8.5 before Composer dependencies are solved.

  Official references used: Laravel 13 release/upgrade docs and Livewire 4 upgrade docs.

  ## Key Changes

  - Set composer.json PHP requirement to ^8.5.
  - Update core/runtime constraints:
      - laravel/framework:^13.0
      - livewire/livewire:^4.0
      - laravel/jetstream:^5.5
      - laravel/sanctum:^4.3
      - laravel/tinker:^3.0

  - Update dev tooling for Laravel 13/PHP 8.5:
      - pestphp/pest:^4.0
      - pestphp/pest-plugin-laravel:^4.0
      - phpunit/phpunit:^12.0 if explicitly present after solve
      - larastan/larastan:^3.10
      - update barryvdh/laravel-debugbar to ^4.3
      - update barryvdh/laravel-ide-helper to latest ^3
      - remove or replace spatie/laravel-ignition if Composer cannot resolve a stable Laravel 13-compatible release
      - update or remove nunomaduro/collision because the installed 8.4.0 conflicts with Laravel 12+

  - Run Composer as a full dependency solve, not a lockfile patch:
      - composer update -W
      - if needed, first use targeted composer require ... --dev -W commands to adjust constraints cleanly.

  ## Application Compatibility Work

  - Keep Jetstream’s published Blade views and Livewire-backed profile/API token screens.
  - Update config/sanctum.php from Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class to the Laravel 13 request-forgery middleware equivalent.
  - Add Laravel 13 cache hardening to config/cache.php, especially serializable_classes => false, unless real cached PHP objects are found.
  - Preserve existing cache/session prefixes unless intentionally changing environment defaults; current CACHE_PREFIX behavior already pins the old underscore format.
  - Audit and adjust for Laravel 12 and 13 breaking changes:
      - no direct upsert(..., []) usage found, but keep this in verification.
      - no QueueBusy, JobAttempted, old pagination view names, array_first, array_last, or Livewire JS hooks found.
      - Livewire usage is mostly standard, but re-test wire:model behavior in Jetstream views and the custom chart integration.

  - Review Livewire 4 JS imports:
      - current code imports Livewire/Alpine from vendor/livewire/livewire/dist/livewire.esm.
      - keep this approach if Livewire 4 still exposes the same ESM path after install; otherwise switch to the documented Livewire 4 asset/bootstrap pattern while preserving Alpine.data('inverterCharts', ...).

  - Update frontend dependencies with npm install, then refresh package-lock.json; keep Chart.js and Vite unless the dependency solve requires newer major versions.

  ## Test Plan

  - Before upgrade, make baseline tests reproducible by adding a test APP_KEY to phpunit.xml or test bootstrap. Current tests pass only when APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= is supplied.
  - Run after dependency update:
      - composer validate
      - php artisan optimize:clear
      - php artisan test
      - vendor/bin/phpstan analyse --memory-limit=1G
      - vendor/bin/pint --test
      - npm install
      - npm run build

  - Manually smoke-test:
      - guest inverter list
      - guest inverter detail
      - combined inverter chart page
      - login/profile pages
      - API token creation/update/delete
      - Sanctum-authenticated API v1 CRUD endpoints

  - Treat PHP 8.5 deprecations as upgrade issues, not noise; the current PHP 8.4 run already shows dependency deprecations from old packages.

  ## Assumptions

  - PHP 8.5 will be available locally and in deployment/CI before the final Composer lockfile is generated.
  - Jetstream remains the auth/profile/API-token scaffold; no starter-kit migration is included.
  - The upgrade may remove spatie/laravel-ignition if Laravel 13 already provides the needed exception UI or if no stable compatible release resolves.
  - No Laravel Boost files will be added as part of this upgrade.

