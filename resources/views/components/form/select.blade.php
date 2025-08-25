<div class="mb-4">
    <label for="{{ $attributes->get('id') }}" class="block text-sm font-medium text-gray-700">
        {{ $label ?? 'Label' }}
    </label>

    <select
        {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500']) }}
    >
        @foreach($options ?? [] as $value => $text)
            <option value="{{ $value }}" {{ (string)$value === (string)$selected ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>

    @if ($hint ?? false)
        <p class="mt-2 text-sm text-gray-500">{{ $hint }}</p>
    @endif
</div>
