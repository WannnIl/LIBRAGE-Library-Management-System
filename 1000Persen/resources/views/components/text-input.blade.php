@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-gray-300 bg-sky-100 text-black focus:border-blue-500 rounded-lg shadow-lg py-2']) }}>
