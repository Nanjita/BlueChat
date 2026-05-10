<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-sky-600 to-blue-700 px-5 py-3 text-xs font-semibold tracking-widest text-white shadow-sm hover:from-sky-500 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
