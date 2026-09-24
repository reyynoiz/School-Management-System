<!-- Sidebar Navigasi Kiri -->
<aside 
    x-show="open" 
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-300 transform"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 shadow-sm flex flex-col justify-between"
>
    <div>
        <!-- Logo & Header Sidebar -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-gray-800">
                <x-application-logo class="block h-8 w-auto fill-current text-indigo-600" />
                <span class="text-lg">School App</span>
            </a>
            <!-- Tombol Tutup Sidebar -->
            <button @click="open = false" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Menu Links -->
        <nav class="px-3 mt-4 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-lg">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')" class="rounded-lg">
                {{ __('Students') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('teachers.index')" :active="request()->routeIs('teachers.*')" class="rounded-lg">
                {{ __('Teachers') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.*')" class="rounded-lg">
                {{ __('Classes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('subjects.index')" :active="request()->routeIs('subjects.*')" class="rounded-lg">
                {{ __('Subjects') }}
            </x-responsive-nav-link>
        </nav>
    </div>

    <!-- User Info & Logout -->
    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
        <div class="mb-3 px-2">
            <div class="font-medium text-sm text-gray-800">{{ Auth::user()->name }}</div>
            <div class="font-medium text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
        </div>
        <div class="space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')" class="rounded-lg py-1.5">
                {{ __('Profile') }}
            </x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="rounded-lg py-1.5 text-red-600 hover:text-red-700">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</aside>