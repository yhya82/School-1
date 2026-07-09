<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Leave Requests') }}
            </h2>
            @can('create', \App\Models\Leave::class)
                <a href="{{ route('staff.leaves.create') }}" wire:navigate>
                    <x-primary-button>{{ __('Add Leave') }}</x-primary-button>
                </a>
            @endcan
        </div>

        <div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search by staff name...') }}"
                class="w-full sm:w-72 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-navy-500 focus:ring-navy-500 text-sm" />
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/40 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Staff Member') }}</th>
                        <th class="px-6 py-3">{{ __('Type') }}</th>
                        <th class="px-6 py-3">{{ __('Dates') }}</th>
                        <th class="px-6 py-3">{{ __('Status') }}</th>
                        <th class="px-6 py-3">{{ __('Approved By') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($leaves as $leave)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors" wire:key="leave-{{ $leave->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $leave->staff->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($leave->leave_type) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $leave->start_date->format('Y-m-d') }} – {{ $leave->end_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm"><x-status-badge :status="$leave->status" /></td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $leave->approver?->user->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @can('update', $leave)
                                    <a href="{{ route('staff.leaves.edit', $leave) }}" wire:navigate class="text-navy-600 dark:text-navy-400 hover:underline">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete', $leave)
                                    <button wire:click="delete({{ $leave->id }})" wire:confirm="{{ __('Delete this leave record?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-sm text-center text-gray-500 dark:text-gray-400">{{ __('No leave records yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
</div>
