<button
    {{
        $attributes->merge([
            'type' => 'submit',
            'class' => 'inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 rounded-xl font-semibold text-sm text-white tracking-wide shadow-lg shadow-blue-500/30 hover:from-blue-500 hover:via-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:ring-offset-white transition-all duration-200'
        ])
    }}
>
    {{ $slot }}
</button>
