@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-navy-500 dark:focus:border-navy-600 focus:ring-navy-500 dark:focus:ring-navy-600 rounded-md shadow-sm']) }}>
