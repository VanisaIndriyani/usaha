@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-black/60 dark:text-white/70']) }}>
    {{ $value ?? $slot }}
</label>
