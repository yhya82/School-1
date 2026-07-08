<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Fee Structures') }}
            </h2>
            @can('create', \App\Models\FeeStructure::class)
                <a href="{{ route('finance.fee-structures.create') }}" wire:navigate>
                    <x-primary-button>{{ __('Add Fee Structure') }}</x-primary-button>
                </a>
            @endcan
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">{{ __('Name') }}</th>
                        <th class="px-6 py-3">{{ __('Class') }}</th>
                        <th class="px-6 py-3">{{ __('Academic Year') }}</th>
                        <th class="px-6 py-3">{{ __('Amount') }}</th>
                        <th class="px-6 py-3">{{ __('Frequency') }}</th>
                        <th class="px-6 py-3">{{ __('Invoices') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($feeStructures as $feeStructure)
                        <tr wire:key="fee-structure-{{ $feeStructure->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $feeStructure->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $feeStructure->schoolClass->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $feeStructure->academicYear->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ number_format($feeStructure->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($feeStructure->frequency) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $feeStructure->invoices_count }}</td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @can('update', $feeStructure)
                                    <a href="{{ route('finance.fee-structures.edit', $feeStructure) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete', $feeStructure)
                                    <button wire:click="delete({{ $feeStructure->id }})" wire:confirm="{{ __('Delete this fee structure?') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No fee structures yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
