<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Welcome back, :name', ['name' => auth()->user()->name]) }}
        </h2>

        @if ($canViewAcademics)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card :label="__('Active Students')" :value="$studentCount" :href="route('students.students.index')" />
                <x-stat-card :label="__('Staff Members')" :value="$staffCount" :href="route('staff.staff.index')" />
                <x-stat-card :label="__('Classes')" :value="$classCount" :href="route('academics.classes.index')" />
                @if ($isAdmin)
                    <x-stat-card :label="__('Outstanding Invoices')" :value="$outstandingInvoices.' ('.number_format($outstandingAmount, 2).')'" :href="route('finance.invoices.index')" />
                @else
                    <x-stat-card :label="__('Sections')" :value="$sectionCount" :href="route('academics.classes.index')" />
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-medium text-gray-800 dark:text-gray-200">{{ __('Upcoming Exams') }}</h3>
                        <a href="{{ route('academics.exams.index') }}" wire:navigate class="text-sm text-navy-600 dark:text-navy-400 hover:underline">{{ __('View all') }}</a>
                    </div>
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($upcomingExams as $exam)
                            <li class="px-6 py-3 flex items-center justify-between text-sm">
                                <span class="text-gray-900 dark:text-gray-100">{{ $exam->name }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $exam->start_date->format('M j, Y') }}</span>
                            </li>
                        @empty
                            <li class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">{{ __('No upcoming exams.') }}</li>
                        @endforelse
                    </ul>
                </div>

                @if ($isAdmin)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <h3 class="font-medium text-gray-800 dark:text-gray-200">{{ __('Pending Leave Requests') }}</h3>
                            <a href="{{ route('staff.leaves.index') }}" wire:navigate class="text-sm text-navy-600 dark:text-navy-400 hover:underline">{{ __('View all') }}</a>
                        </div>
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($pendingLeaves as $leave)
                                <li class="px-6 py-3 flex items-center justify-between text-sm">
                                    <span class="text-gray-900 dark:text-gray-100">{{ $leave->staff->user->name }}</span>
                                    <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                        {{ ucfirst($leave->leave_type) }}, {{ $leave->start_date->format('M j') }} – {{ $leave->end_date->format('M j') }}
                                        <x-status-badge :status="$leave->status" />
                                    </span>
                                </li>
                            @empty
                                <li class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">{{ __('No pending leave requests.') }}</li>
                            @endforelse
                        </ul>
                    </div>
                @endif
            </div>
        @elseif ($isParent)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">{{ __('My Children') }}</h3>
                    <a href="{{ route('portal.children.index') }}" wire:navigate class="text-sm text-navy-600 dark:text-navy-400 hover:underline">{{ __('View all') }}</a>
                </div>
                <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($children ?? [] as $child)
                        <li class="px-6 py-3 flex items-center justify-between text-sm">
                            <span class="text-gray-900 dark:text-gray-100">{{ $child->user->name }}</span>
                            <span class="text-gray-500 dark:text-gray-400">{{ $child->schoolClass->name }} - {{ $child->section->name }}</span>
                        </li>
                    @empty
                        <li class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">{{ __('No children linked to your account yet.') }}</li>
                    @endforelse
                </ul>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                <p class="text-gray-600 dark:text-gray-400">{{ __("You're logged in.") }}</p>
            </div>
        @endif
    </div>
</div>
