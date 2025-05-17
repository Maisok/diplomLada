<footer class="bg-[#1a1a1a] text-white py-12">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Логотип и описание -->
            <div>
                <h3 class="text-2xl font-bold mb-4">Beauty Bar</h3>
                <p class="text-gray-400 max-w-xs">
                    Совершенный подход к вашей красоте и уходу. Мы любим делать вас еще красивее.
                </p>
            </div>

            <!-- Навигация -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Навигация</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('welcome') }}" class="hover:text-pink-400 transition">Главная</a></li>
                    <li class="relative">
                        <a href="{{ route('services') }}" class="hover:text-pink-400 transition flex items-center">
                            Услуги
                            @auth
                                @if(auth()->user()->unreadQuestionNotifications()->exists())
                                    <span class="ml-2 inline-block h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
                                @endif
                            @endauth
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Аккаунт -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Аккаунт</h4>
                <ul class="space-y-2">
                    @guest
                        <li><a href="{{ route('login') }}" class="hover:text-pink-400 transition">Войти</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-pink-400 transition">Регистрация</a></li>
                    @endguest

                    @auth
                        <li><a href="{{ route('dashboard') }}" class="hover:text-pink-400 transition">Личный кабинет</a></li>
                        @if(auth()->user()->role === 'admin')
                            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-pink-400 transition">Админка</a></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-left hover:text-pink-400 transition w-full text-left">
                                    Выйти
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
    

        </div>

        <!-- Подпись внизу -->
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
            <p>© 2025 Beauty Bar. Все права защищены.</p>
        </div>
    </div>
</footer>