# CLAUDE.md

Guidance for AI agents (and humans) working in this repository.

## What this is

A Nette 8.2+ web-application skeleton: Nette 3.2 (application/DI/forms/security),
Doctrine ORM via Nettrine, Latte templates, Vite-built assets, ublaboo/contributte
datagrid, and contributte/translation. It ships with working user
registration/login and a Tabler-based admin UI.

## Commands

Run from the repository root.

| Task | Command |
| --- | --- |
| Static analysis (PHPStan level 8) | `composer phpstan` |
| Code-style check | `composer cs:check` |
| Code-style autofix | `composer cs:fix` |
| Tests (Nette Tester) | `composer tester` |
| Reset DB schema + fixtures (**destructive**) | `composer db:reset` |
| Lint Latte templates | `./latte-lint private/app` |
| Console (Doctrine, migrations, fixtures) | `php private/cli/console.php <command>` |
| Frontend dev server (Vite, HMR) | `npm run dev` |
| Build assets for production | `npm run build` |

**Before considering a change done, run `composer phpstan`, `composer cs:check`
and `composer tester`.** All three must be clean. PHPStan runs at level 8 with
the Nette and Doctrine extensions, so keep types honest.

Note: `composer` is configured with `vendor-dir: private/vendor`, so binaries
live under `private/vendor/bin/` (directories structure from ISPConfig).

## Layout

- `private/app/` — application code (PSR-4 `App\`).
  - `Bootstrap.php` — single `boot()` entry point used by web, CLI and latte-lint.
  - `Core/` — framework wiring (router, logger).
  - `Model/<Domain>/` — domain code grouped by bounded context (currently `User`,
    plus `Core` for cross-cutting entities like `Log`). Inside a domain:
    `Entity/`, `Repository/`, `Facade/`, `Service/`, `Enum/`, `Exception/`,
    `Event/`, `Doctrine/` (custom DBAL types).
  - `Presentation/` — presenters and their Latte templates live side by side,
    split into `Admin/`, `Front/` and `Error/` modules.
  - `lang/` — translation catalogues (`<group>.<locale>.neon`).
- `private/config/` — `common.neon` (framework + extensions), `services.neon`
  (DI services + autoregistration), `local.neon` (untracked, machine-specific).
- `private/migrations/` — Doctrine migrations.
- `web/` — public document root (`index.php`, compiled `assets/`).
- `tests/` — Nette Tester tests, mirroring the `private/app` namespace layout.

## Conventions

Follow the surrounding code; the points below are the ones that are easy to get
wrong.

- **Service registration is automatic.** `services.neon` has a `search:` block
  that registers every class whose name ends in `Facade`, `Factory`,
  `Repository`, `Service`, `Authenticator`, `Mailer`, `Logger`, `Fixture` or
  `Subscriber`. Name new services accordingly and they wire up by autowiring —
  do **not** add them to `services.neon` by hand. Only services that need
  explicit constructor arguments (e.g. `MailerFactory(%mailer%)`) are listed
  manually.
- **Dependency injection in presenters:** concrete presenters use constructor
  injection (and must call `parent::__construct()`); `#[Inject]` property
  injection is reserved for the `Base*Presenter` classes where inheritance makes
  a constructor awkward.
- **Forms** are built in dedicated `*FormFactory` classes that return a
  `Nette\Application\UI\Form`; the presenter wires `onSuccess`/save callbacks.
  Translatable forms go through `App\Model\Factory\FormFactory` (sets the
  translator). Never trust hidden form fields for identity — pass IDs into the
  factory's `create()` from the presenter instead.
- **DTOs** crossing a facade boundary are immutable, constructor-promoted
  classes (see `RegistrationData`, `UserUpdateData`). Facades take a DTO, not a
  loose array.
- **Entities** use public typed properties (Doctrine attribute mapping). The
  `User::$roles` array is persisted through the custom `UserRolesType`
  (`type: UserRolesType::Name`) so it stays `UserRole[]` after hydration —
  don't switch it back to plain `json`.
- **Logging** goes through `Psr\Log\LoggerInterface` (bound to the
  `DatabaseLogger`, which writes to the `log` table). Inject the interface.
- **Domain events:** side effects that aren't part of the core use case are
  decoupled via `contributte/event-dispatcher`. Example: registration dispatches
  `UserRegisteredEvent`, and `WelcomeEmailSubscriber` sends the welcome mail.
  Add an `EventSubscriberInterface` named `*Subscriber` and it autoregisters.
- **Translations:** message keys are `<group>.<key>`, e.g. `forms.email`,
  `strings.login_success`, `messages.change_language`. Add the key to every
  `<group>.<locale>.neon` file. User-facing strings belong here; internal
  exception messages stay in English in code.
- **`strict_types`** is mandatory and enforced by CS Fixer (`declare_strict_types`).

## Language

Everything committed to the repo (code, comments, identifiers, commit messages,
docs) is written in **English**. The app's user-facing copy is translated via
the catalogues in `private/app/lang/` (English + Czech).
