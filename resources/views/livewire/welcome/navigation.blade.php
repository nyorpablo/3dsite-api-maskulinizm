<div>
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
            <a href="{{ url('/') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Odysse</span>
            </a>
            <div class="flex items-center space-x-6 rtl:space-x-reverse">
                
                @auth
                    <a
                        href="{{ url('/dashboard') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        Dashboard
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white @if(request()->is('login')) md:text-blue-700 @endif"
                    >
                        Log in
                    </a>
    
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white @if(request()->is('register')) md:text-blue-700 @endif"
                        >
                            Register
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>
    <nav class="bg-gray-50 dark:bg-gray-700">
        <div class="max-w-screen-xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-900 dark:text-white hover:underline @if(request()->is('/')) md:text-blue-700 @endif" aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-900 dark:text-white hover:underline @if(request()->is('documentation')) md:text-blue-700 @endif" aria-current="page">Documentation</a>
                    </li>
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-900 dark:text-white hover:underline @if(request()->is('about')) md:text-blue-700 @endif" aria-current="page">About</a>
                    </li>
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-900 dark:text-white hover:underline @if(request()->is('contact')) md:text-blue-700 @endif" aria-current="page">Contact</a>
                    </li>
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-900 dark:text-white hover:underline @if(request()->is('support')) md:text-blue-700 @endif" aria-current="page">Support</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>