# UI Reference Guide

This is the reference for keeping the app's look consistent. When building a new page or component, check here first instead of inventing new colors/spacing.

## Color palette

**Navy** (primary brand color) — defined in `tailwind.config.js`, use as `navy-{shade}`:

| Shade | Hex | Typical use |
|---|---|---|
| navy-50 | `#eef2f8` | rarely used directly |
| navy-500 | `#3d5f99` | focus rings (`focus:ring-navy-500`) |
| navy-600 | `#2d4a80` | links, hover states, stat-card left border |
| navy-700 | `#243d69` | primary button background (light mode) |
| navy-800 | `#1c2f52` | sidebar background, button active state |
| navy-900 | `#16243f` | sidebar background (`bg-navy-900`) |
| navy-950 | `#0d1626` | rarely used |

**Gray** — the stock Tailwind gray scale, used for all neutral text/backgrounds/borders. Always pair a light-mode class with a `dark:` variant:
- Card background: `bg-white dark:bg-gray-800`
- Card border: `border-gray-100 dark:border-gray-700`
- Body text: `text-gray-900 dark:text-gray-100` (primary) / `text-gray-500 dark:text-gray-400` (secondary)
- Table header background: `bg-gray-50 dark:bg-gray-900/40`

**Never introduce a new color family** (no indigo, blue-as-primary, etc.) — navy is the only brand accent. Blue is reserved for the `graduated` status badge only (see below).

## Status badges

Use `<x-status-badge :status="$model->status" />` — **never** render a raw status as plain text (`{{ ucfirst($x->status) }}`). The component (`resources/views/components/status-badge.blade.php`) maps known values to colors:

| Color | Values |
|---|---|
| green | `active`, `present`, `approved`, `paid` |
| yellow | `pending`, `late`, `partial` |
| red | `absent`, `rejected`, `overdue`, `unpaid` |
| gray | `withdrawn`, `excused`, anything unrecognized |
| blue | `graduated` |

If a new status enum is added anywhere in the app, add its value to the `$colorMap` in that one file rather than hardcoding a badge elsewhere.

## Buttons

- `<x-primary-button>` — main call-to-action (Save, Add X, Create). Navy fill, already wired with `wire:loading.attr="disabled"` and a spinner — don't add loading states manually, it's automatic.
- `<x-secondary-button>` — secondary actions (Cancel). White/gray outline.
- Inline text actions in tables (Edit / Delete) are plain links/buttons, not full button components: `class="text-navy-600 dark:text-navy-400 hover:underline"` for Edit, `class="text-red-600 dark:text-red-400 hover:underline"` for Delete (paired with `wire:confirm="..."`).

## Page layout skeleton

Every full-page Livewire view follows this shape:

```blade
<div class="py-12">
    <div class="max-w-{4xl|5xl|6xl|7xl} mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Page Title') }}</h2>
            @can('create', \App\Models\X::class)
                <a href="{{ route('x.create') }}" wire:navigate><x-primary-button>{{ __('Add X') }}</x-primary-button></a>
            @endcan
        </div>
        {{-- content --}}
    </div>
</div>
```

Card wrapper (tables, stat panels, form containers): `bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700`.

## Index/list pages

Every list page follows the same structure — copy an existing one (e.g. `resources/views/livewire/students/student-index.blade.php`) rather than starting from scratch:

1. **Search box** (only if the list can realistically grow past a screen — skip for small/bounded tables like Academic Years):
   ```blade
   <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search by ...') }}"
       class="w-full sm:w-72 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-navy-500 focus:ring-navy-500 text-sm" />
   ```
   Component side: `use WithPagination;`, `public string $search = '';`, `updatingSearch()` calls `$this->resetPage()`, query uses `->when($this->search, fn ($q, $v) => ...)`.

2. **Table**: `min-w-full divide-y divide-gray-200 dark:divide-gray-700`, header row `bg-gray-50 dark:bg-gray-900/40 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider`, body rows `hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors`, always `wire:key="{entity}-{{ $row->id }}"`.

3. **Status column**: use `<x-status-badge>` (see above), never plain text.

4. **Pagination footer** (always paginate — 15 per page for admin CRUD lists, 20 for high-volume logs like Attendance/Invoices/Payments/Expenses):
   ```blade
   <div class="p-4">
       {{ $items->links() }}
   </div>
   ```

5. **Empty state**: a single `<tr>` with `colspan` spanning all columns, centered muted text, e.g. `{{ __('No students yet.') }}`.

## Forms

- Field wrapper: `<x-input-label for="field" value="{{ __('Label') }}" />` + `<x-text-input wire:model="field" id="field" class="mt-1 block w-full" />` + `<x-input-error :messages="$errors->get('field')" class="mt-2" />`.
- Create and Edit components for the same entity share one `_form.blade.php` (or `{entity}-form.blade.php`) partial via `@include`, so field markup is never duplicated between the two. If you're adding a new entity, create the shared partial from the start.
- Dropdowns not using `<x-text-input>`: `<select class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">`.

## Stat cards (dashboard)

`<x-stat-card :label="__('...')" :value="$count" :href="route('...')" />` — navy left border, large bold value, optional link wrapper. Don't build ad-hoc stat panels; extend this component if a new dashboard metric is needed.

## Sidebar

`resources/views/partials/sidebar.blade.php` — dark navy (`bg-navy-900`), grouped by module with `@role('...')` gates, links via `<x-sidebar-link>`. Add new module links to the existing group that matches, or create a new `<p class="px-3 text-xs font-semibold text-navy-400 uppercase tracking-wider">` group heading if it's a genuinely new area.
