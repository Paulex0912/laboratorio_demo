<header class="flex items-center justify-between px-6 py-4 bg-white shadow-sm border-b border-gray-100">
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
    </div>

    <div class="flex items-center">
        <!-- Notificaciones o Búsquedas podrían ir aquí -->

        <!-- Perfil Dropdown -->
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="flex items-center gap-2 focus:outline-none transition ease-in-out duration-150">
                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-[#6B46C1] font-bold shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:flex flex-col text-left">
                        <span class="text-sm font-semibold text-gray-700 leading-tight">{{ Auth::user()->name }}</span>
                        <span class="text-xs text-gray-500 leading-none">{{ Auth::user()->roles->pluck('name')->join(', ') }}</span>
                    </div>
                    <svg class="fill-current h-4 w-4 text-gray-400 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
