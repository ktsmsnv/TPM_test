<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link type="image/x-icon" rel="shortcut icon" href="{{ URL::asset('storage/favicon.ico') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TRM') }}</title>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @vite(['node_modules/intro.js/introjs.css', 'node_modules/intro.js/intro.js'])

    <!-- Подключаем скрипты Bootstrap Table -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.2/dist/bootstrap-table.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.28.0/tableExport.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.2/dist/bootstrap-table.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.2/dist/extensions/export/bootstrap-table-export.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.3/dist/bootstrap-table-locale-all.min.js"></script>
    <!-- Подключаем библиотеку FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.js'></script>
    <!-- icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>
<div id="app">
    @if(Route::currentRouteName() !== 'register')
        @include('layouts.header')
    @endif
    <main>
        {{--        <ul class="breadcrumbs container">--}}
        {{--            @if(Route::currentRouteName() !== 'register')--}}
        {{--                @foreach ($breadcrumbs as $crumb)--}}
        {{--                    <li>--}}
        {{--                        @if ($loop->last)--}}
        {{--                            <span style="color: grey;">{{ $crumb->title }}</span>--}}
        {{--                        @else--}}
        {{--                            <a href="{{ $crumb->url }}">{{ $crumb->title }}</a>--}}
        {{--                        @endif--}}
        {{--                    </li>--}}
        {{--                @endforeach--}}
        {{--            @endif--}}
        {{--        </ul>--}}

        @yield('content')
    </main>
</div>

{{-- всплывающие подсказки --}}
<script>
    // после загрузки страницы
    document.addEventListener('DOMContentLoaded', function () {
        $(document).ready(function () {
            // инициализации подсказок для всех элементов на странице, имеющих атрибут data-toggle="tooltip"
            $('[data-toggle="tooltip"]').tooltip({
                placement: 'bottom'
            });
        });
    });

    $(function () {
        ToolTipStyling = function () {
            $('.tool-tip,[data-toggle="tooltip"]').tooltip({
                container: 'body',
                animation: true,
                delay: {
                    show: 100,
                    hide: 100
                }
            });
        };
        ToolTipStyling();
        $('table').on('post-body.bs.table', function () {
            ToolTipStyling();
        });
        tooltipTitleSetter = function (that) {
            return $(that).val() === "" ? ' ' : $(that).val();
        };
    });
    $('textarea.tool-tip').hover(function () {
        $(this).attr("title", tooltipTitleSetter(this))
            .tooltip('fixTitle');
    });
</script>
{{-- активный header  --}}
<script>
    // Получаем текущий путь страницы
    const currentPath = window.location.pathname;

    // Получаем все ссылки в навигации
    const navLinks = document.querySelectorAll('.nav-link');

    // Проходим по каждой ссылке и добавляем класс active, если её href совпадает с текущим путём
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
</script>
{{-- intro.js --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Проверяем, было ли уже пройдено обучение
        var tutorialCompleted = localStorage.getItem('tutorialCompleted');

        // Устанавливаем массив URL-адресов страниц
        var pages = [
            '/home',
            '/pageReestrGraph',
            '/pageReestrCalendar',
            '/reestr-work-orders',
            '/home/profile',
        ];

        // Функция запуска тура
        function startTour() {
            var currentPageIndex = pages.indexOf(window.location.pathname);
            var tour = introJs()
                .setOptions({
                    nextLabel: 'Далее',
                    prevLabel: 'Назад',
                    doneLabel: 'Завершить',
                })
                .oncomplete(function () {
                    // Увеличиваем индекс страницы для перехода на следующую страницу
                    currentPageIndex++;
                    if (currentPageIndex < pages.length) {
                        // Перенаправляем пользователя на следующую страницу
                        window.location.href = pages[currentPageIndex];
                    } else {
                        // Если это последняя страница в массиве, завершаем тур и сохраняем информацию о завершении обучения
                        localStorage.setItem('tutorialCompleted', 'true');
                        alert('Обучение завершено');
                        window.location.href = '/home';
                    }
                });
            tour.start();
        }

        // Если обучение не завершено, запускаем тур
        if (!tutorialCompleted) {
            startTour();
        }

        // Получаем кнопку по ее ID
        var restartTutorialBtn = document.getElementById('restartTutorialBtn');
        if (restartTutorialBtn) {
            // Добавляем обработчик события на клик кнопки
            restartTutorialBtn.addEventListener('click', function () {
                // Сбрасываем флаг завершения обучения и запускаем тур заново
             //   localStorage.removeItem('tutorialCompleted');
                startTour();
            });
        }
    });
</script>



</body>
</html>
