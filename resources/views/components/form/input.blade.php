<div class="mb-4">
    <label for="{{ $attributes['id'] }}" class="block text-sm font-medium text-gray-700">
        {{ $label ?? 'Label' }}
    </label>
    <input 
        {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500']) }} 
    >
    @if ($hint ?? false)
        <p class="text-sm text-gray-500">{{ $hint }}</p>
    @endif
</div>
