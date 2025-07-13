<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>curely</title>
    <link rel="stylesheet" href="{{ asset("css/global.css") }}">
    <link rel="stylesheet" href="{{ asset("css/auth.css") }}">
</head>
<body>
    <div class="container">
        <x-layout.header  />
        <main>
            <div class="form-top">
                <div class="form-title">AI 진단부터 일상 관리까지<br>함께 시작해봐요.</div>
                <div class="form-sub-title">AI 기반 맞춤 건강 솔루션을 지금 시작해보세요.</div>
            </div>
            <div class="form-img">
                <img src="{{ asset("img/src/form.png") }}" alt="">
            </div>
            <div class="button-group">
                <a href="{{ url("login") }}">로그인</a>
                <a href="{{ url("register") }}">회원가입</a>
            </div>
        </main>
    </div>
</body>
</html>