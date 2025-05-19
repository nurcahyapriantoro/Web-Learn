<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700 hover:shadow-blue-glow focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2 transition-all duration-300 transform hover:-translate-y-1 ease-in-out']) }}>
    {{ $slot }}
</button>
