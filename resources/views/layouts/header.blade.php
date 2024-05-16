<header data-title="Начало работы" data-position="bottom-middle-aligned" data-step="1"
        data-intro="Если хотите ознакомиться с работой сайта нажмите 'Далее' или стрелку вправо на клавиатуре. Иначе просто закройте окно нажав на 'х'.">
    <div class="px-3 py-3 header-bg text-black shadow-sm">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="{{ url('/') }}"
                   class="navbar-brand d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none"
                   data-toggle="tooltip" title="Перейти на главную"> <img
                        src="{{ asset('/storage/apm_apm.png') }}" alt="лого вход">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small" data-step="2"
                    data-intro="Это панель навигации по основным страницам системы. Можно выбрать любой необходимый реестр и перейти в него">
                    <li>
                        <a href="/home" class="nav-link text-white">
                            <i class="bi bi-card-list me-1"></i>
                            Реестр объектов
                            <span class="hover-bar"></span>
                        </a>
                    </li>
                    <li>
                        <a href="/pageReestrGraph" class="nav-link text-white">
                            <i class="bi bi-bar-chart me-1"></i>
                            Реестр графиков
                            <span class="hover-bar"></span>
                        </a>
                    </li>
                    <li>
                        <a href="/pageReestrCalendar" class="nav-link text-white">
                            <i class="bi bi-calendar-event me-1"></i>
                            Реестр календарей
                            <span class="hover-bar"></span>
                        </a>
                    </li>
                    <li>
                        <a href="/reestr-work-orders" class="nav-link text-white">
                            <i class="bi bi-app-indicator me-1"></i>
                            Реестр заказов
                            <span class="hover-bar"></span>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto d-flex flex-row gap-3 text-white" data-step="3"
                    data-intro="Нажав здесь можно перейти в свой профиль или выйти из системы">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Вход') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <button data-step="4"
                                    data-intro="Нажав здесь можно заново пройти обучение"
                                    id="restartTutorialBtn" class="ms-4 btn btn-light" data-toggle="tooltip" title="пройти обучение">
                                ?</button>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/home/profile" target="_blank">
                                    Личный кабинет
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Выход') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</header>
