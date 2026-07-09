<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Academic Years') }}
            </h2>
            @can('create', \App\Models\AcademicYear::class)
                <a href="{{ route('academics.years.create') }}" wire:navigate>
                    <x-primary-button>{{ __('Add Year') }}</x-primary-button>
                </a>
            @endcan
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/40 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Name') }}</th>
                        <th class="px-6 py-3">{{ __('Start') }}</th>
                        <th class="px-6 py-3">{{ __('End') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($years as $year)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors" wire:key="year-{{ $year->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $year->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $year->start_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $year->end_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @can('update', $year)
                                    <a href="{{ route('academics.years.edit', $year) }}" wire:navigate class="text-navy-600 dark:text-navy-400 hover:underline">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete', $year)
                                    <button wire:click="delete({{ $year->id }})" wire:confirm="{{ __('Delete this academic year?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-sm text-center text-gray-500 dark:text-gray-400">{{ __('No academic years yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
