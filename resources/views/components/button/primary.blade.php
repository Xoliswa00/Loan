<button 
    type="{{ $type ?? 'button' }}" 
    {{ $attributes->merge(['class' => 'w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2']) }}
>
    {{ $slot }}
</button>
