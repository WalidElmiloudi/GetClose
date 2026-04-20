<header class="h-20 w-full shadow-sm sticky top-0 z-9999 bg-white">
    <div class="grid grid-cols-4 w-full h-full gap-2 p-2 lg:grid-cols-6">
        <div class="col-span-1 flex justify-center items-center">
            <img class="w-20 h-10 lg:hidden" src="{{ asset('images/logo.jpg') }}" alt="GetClose logo">
            <h1 class="hidden lg:block text-red-700 font-bold text-4xl font-[Open_Sans]">GetClose.</h1>
        </div>
        <div class="col-span-3 h-full w-full flex items-center justify-center lg:col-span-4">
            <form class="h-[90%] w-[95%] flex items-center gap-2">
                <input class="bg-gray-100 h-[85%] w-[90%] rounded-full p-4 text-lg focus:bg-gray-200 lg:text-2xl"
                    type="search" placeholder="Search..." required>
                <button
                    class="bg-red-500 h-11 w-11 rounded-full cursor-pointer flex justify-center items-center hover:bg-red-700"
                    type="submit"><i class="fa-solid fa-magnifying-glass text-white text-lg"></i></button>
            </form>
        </div>
        @if (!auth()->check())
            <div class="hidden lg:flex col-span-1 items-center">
                <a href="{{ route('login') }}"
                    class="h-[90%] w-[50%] bg-red-700 rounded-tl-full rounded-bl-full flex justify-center items-center text-xl font-bold text-white hover:bg-red-900 cursor-pointer">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="h-[90%] w-[50%] border-4 border-red-700 rounded-tr-full rounded-br-full flex justify-center items-center text-xl font-bold text-red-700 hover:text-red-900 cursor-pointer">
                    Sign up
                </a>
            </div>
        @else
            <div class="hidden lg:flex col-span-1 items-center gap-4">
                <h1 class="hidden lg:block font-bold text-lg">{{auth()->user()->name}}</h1>
                <a href="{{ route('logout') }}"
                    class="h-[90%] w-[50%] border-4 border-red-700 rounded-full rounded-br-full flex justify-center items-center text-xl font-bold text-red-700 hover:bg-red-700 hover:text-white ease-in-out duration-150 cursor-pointer">
                    logout
                </a>
            </div>
        @endif
    </div>
</header>
