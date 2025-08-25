<div>
    @props(['label', 'path'])

<div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4 border border-gray-200 dark:border-gray-600">
    <p class="font-semibold text-sm text-gray-700 dark:text-gray-200 mb-2">{{ $label }}</p>
    <div class="flex justify-between items-center">
        <a href="{{ Storage::url($path) }}" target="_blank" class="text-indigo-600 dark:text-indigo-300 text-sm underline">
            View Document
        </a>
        <a href="{{ Storage::url($path) }}" download class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            ⬇️ Download
        </a>
    </div>
</div>

    <!-- Simplicity is an acquired taste. - Katharine Gerould -->
</div>