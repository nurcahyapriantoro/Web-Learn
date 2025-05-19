<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-secondary inline-flex items-center px-4 py-2 bg-secondary-100 border border-secondary-200 rounded-lg font-semibold text-sm text-primary-800 uppercase tracking-widest shadow-sm hover:bg-secondary-200 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:ring-offset-2 disabled:opacity-25 transition-all duration-300 transform hover:-translate-y-1 ease-in-out']) }}>
    {{ $slot }}
</button>
