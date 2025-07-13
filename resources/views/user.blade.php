<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>curely</title>
    <link rel="stylesheet" href="{{ asset("css/global.css") }}">
    <link rel="stylesheet" href="{{ asset("css/main.css") }}">
    <style>
        .tab-bar li:nth-child(3) img {
            filter: grayscale(0) !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <x-layout.header  />
        <main>
            <div class="user-card">
                @if (Auth::check())
                    <div class="user-name">{{ Auth::user()->name }}님의 정보</div>
                    <ul>
                        <li>
                            <div class="user-title">아이디</div>
                            <div class="user-value">{{ Auth::user()->user_id }}</div>
                        </li>
                        <li>
                            <div class="user-title">생년월일</div>
                            <div class="user-value">{{ Auth::user()->birth->format('Y년 m월 d일') }}</div>
                        </li>
                        <li>
                            <div class="user-title">성별</div>
                            <div class="user-value">{{ Auth::user()->gender }}</div>
                        </li>
                    </ul>
                @endif
            </div>
            <div class="bottom">
                <a href="{{ route('user.medical.all.pdf') }}">
                    <button class="print">전체 진료 데이터 PDF 출력</button>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout" type="submit">로그아웃</button>
                </form>
            </div>
        </main>
        <x-layout.tab-bar />
    </div>
</body>
</html>