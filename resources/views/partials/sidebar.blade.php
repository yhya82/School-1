<div class="h-full flex flex-col bg-navy-900 border-r border-navy-800 w-64">
    <div class="h-16 flex items-center px-4 border-b border-navy-800">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
            <x-application-logo class="block h-8 w-auto fill-current text-white" />
            <span class="text-white font-semibold tracking-wide">{{ config('app.name') }}</span>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
        <div class="space-y-1">
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-sidebar-link>
        </div>

        @role('admin|teacher')
            <div>
                <p class="px-3 text-xs font-semibold text-navy-400 uppercase tracking-wider">{{ __('Academics') }}</p>
                <div class="mt-1 space-y-1">
                    <x-sidebar-link :href="route('academics.years.index')" :active="request()->routeIs('academics.years.*')" wire:navigate>
                        {{ __('Academic Years') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('academics.classes.index')" :active="request()->routeIs('academics.classes.*')" wire:navigate>
                        {{ __('Classes') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('academics.subjects.index')" :active="request()->routeIs('academics.subjects.*')" wire:navigate>
                        {{ __('Subjects') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('academics.class-subjects.index')" :active="request()->routeIs('academics.class-subjects.*')" wire:navigate>
                        {{ __('Assignments') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('academics.timetable.index')" :active="request()->routeIs('academics.timetable.*')" wire:navigate>
                        {{ __('Timetable') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('academics.exams.index')" :active="request()->routeIs('academics.exams.*') || request()->routeIs('academics.exam-results.*')" wire:navigate>
                        {{ __('Exams') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('academics.attendance.index')" :active="request()->routeIs('academics.attendance.*')" wire:navigate>
                        {{ __('Attendance') }}
                    </x-sidebar-link>
                </div>
            </div>

            <div>
                <p class="px-3 text-xs font-semibold text-navy-400 uppercase tracking-wider">{{ __('Students') }}</p>
                <div class="mt-1 space-y-1">
                    <x-sidebar-link :href="route('students.students.index')" :active="request()->routeIs('students.students.*')" wire:navigate>
                        {{ __('Students') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('students.guardians.index')" :active="request()->routeIs('students.guardians.*')" wire:navigate>
                        {{ __('Guardians') }}
                    </x-sidebar-link>
                </div>
            </div>

            <div>
                <p class="px-3 text-xs font-semibold text-navy-400 uppercase tracking-wider">{{ __('Staff') }}</p>
                <div class="mt-1 space-y-1">
                    <x-sidebar-link :href="route('staff.staff.index')" :active="request()->routeIs('staff.staff.*')" wire:navigate>
                        {{ __('Staff') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('staff.attendance.index')" :active="request()->routeIs('staff.attendance.*')" wire:navigate>
                        {{ __('Attendance') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('staff.leaves.index')" :active="request()->routeIs('staff.leaves.*')" wire:navigate>
                        {{ __('Leaves') }}
                    </x-sidebar-link>
                </div>
            </div>
        @endrole

        @role('admin')
            <div>
                <p class="px-3 text-xs font-semibold text-navy-400 uppercase tracking-wider">{{ __('Finance') }}</p>
                <div class="mt-1 space-y-1">
                    <x-sidebar-link :href="route('finance.fee-structures.index')" :active="request()->routeIs('finance.fee-structures.*')" wire:navigate>
                        {{ __('Fee Structures') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('finance.invoices.index')" :active="request()->routeIs('finance.invoices.*')" wire:navigate>
                        {{ __('Invoices') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('finance.payments.index')" :active="request()->routeIs('finance.payments.*')" wire:navigate>
                        {{ __('Payments') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('finance.expenses.index')" :active="request()->routeIs('finance.expenses.*')" wire:navigate>
                        {{ __('Expenses') }}
                    </x-sidebar-link>
                </div>
            </div>
        @endrole
    </nav>
</div>
