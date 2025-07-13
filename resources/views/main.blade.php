<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>curely</title>
    <link rel="stylesheet" href="{{ asset("css/global.css") }}">
    <link rel="stylesheet" href="{{ asset("css/main.css") }}">
    <style>
        .tab-bar li:nth-child(1) img {
            filter: grayscale(0) !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <x-layout.header  />
        <main>
            <section>
                <div class="main-section-title">어디가 아프신지<br/>말씀해 주실 수 있나요?</div>
                <form id="symptomForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="store" value="true">
                    <div class="search">
                        <input type="text" name="symptom" placeholder="증상을 입력해주세요.">
                        <button type="submit" class="search-btn">search</button>
                    </div>
                </form>
                <ul id="search-result-list" class="search-result">
                </ul>
            </section>
            <section>
                <div class="section-top">
                    <div class="icon"><img src="{{ asset("img/icon/recent.svg") }}" alt=""></div>
                    <div class="section-title">최근 검색어</div>
                </div>
                <ul class="recent-list" id="recent-keywords">
                    {{-- 초기 서버 렌더링 시 보여줄 placeholder --}}
                </ul>
            </section>
            <section>
                <div class="section-top">
                    <div class="icon"><img src="{{ asset("img/icon/leaf.svg") }}" alt=""></div>
                    <div class="section-title">오늘의 팁</div>
                </div>
                <div class="tip-of-today">
                    <div class="tip">상쾌함을 위해 짧게 걷는 것을 추천해요!</div>
                    <button type="button" class="tip-btn"><img src="{{ asset("img/icon/re.svg") }}" alt=""></button>
                </div>
            </section>
        </main>
        <x-layout.tab-bar />
    </div>
    <noscript>
        <ul class="recent-list">
            <li>
                <a href="#">
                    <span class="date">2025.06.12</span>
                    <div class="separate-bar"></div>
                    <span class="recent-word">두통</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="date">2025.06.12</span>
                    <div class="separate-bar"></div>
                    <span class="recent-word">두통</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="date">2025.06.12</span>
                    <div class="separate-bar"></div>
                    <span class="recent-word">두통</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="date">2025.06.12</span>
                    <div class="separate-bar"></div>
                    <span class="recent-word">두통</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="date">2025.06.12</span>
                    <div class="separate-bar"></div>
                    <span class="recent-word">두통</span>
                </a>
            </li>
            <li class="more">
                <a href="#">더보기</a>
            </li>
        </ul>
    </noscript>
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>