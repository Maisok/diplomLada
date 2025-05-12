
<html lang="ru" x-data="{ mobileMenuOpen: false }">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <header class="bg-white shadow-sm py-4 relative">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('img/logo.png') }}" alt="BB Logo" class="h-10">
                <span class="ml-3 text-xl font-light hidden sm:inline">Beauty Bar</span>
            </div>
            
            <!-- Кнопка мобильного меню -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <!-- Десктопное меню -->
            <nav class="hidden md:flex space-x-8 items-center">
                <a href="{{ route('welcome') }}" class="nav-link">Главная</a>
                
                <a href="{{ route('services') }}" class="relative p-2 flex items-center">
                    <span class="mr-2">Услуги</span>
                    @auth
                    @if(auth()->user()->unreadQuestionNotifications()->exists())
                    <span class="absolute -top-1 -right-1 block h-3 w-3 rounded-full bg-red-500"></span>
                    @endif
                    @endauth
                </a>
                
                @guest
                <a href="{{ route('login') }}" class="nav-link">Войти</a>
                <a href="{{ route('register') }}" class="nav-link">Регистрация</a>
                @endguest
                
                @auth
                <a href="{{ route('dashboard') }}" class="nav-link">Личный кабинет</a>
                
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">Админка</a>
                    @endif
                @endauth
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link">Выйти</button>
                </form>
                @endauth
            </nav>
        </div>
        
        <!-- Мобильное меню -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="md:hidden absolute top-full left-0 right-0 bg-white shadow-lg z-50 py-4 px-6">
            <div class="flex flex-col space-y-4">
                <a href="{{ route('welcome') }}" class="py-2 hover:text-blue-500">Главная</a>
                <a href="{{ route('services') }}" class="py-2 hover:text-blue-500 flex items-center">
                    Услуги
                    @auth
                    @if(auth()->user()->unreadQuestionNotifications()->exists())
                    <span class="ml-2 inline-block h-2 w-2 rounded-full bg-red-500"></span>
                    @endif
                    @endauth
                </a>
                
                @guest
                <a href="{{ route('login') }}" class="py-2 hover:text-blue-500">Войти</a>
                <a href="{{ route('register') }}" class="py-2 hover:text-blue-500">Регистрация</a>
                @endguest
                
                @auth
                <a href="{{ route('dashboard') }}" class="py-2 hover:text-blue-500">Личный кабинет</a>
                
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="py-2 hover:text-blue-500">Админка</a>
                @endif
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="py-2 hover:text-blue-500 w-full text-left">Выйти</button>
                </form>
                @endauth
            </div>
        </div>
    </header>
