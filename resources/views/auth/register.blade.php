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
            <form action="register" method="POST">
                @csrf
                <div class="form-input">
                    <input type="text" name="user_id" placeholder="아이디" required>
                    <input type="password" name="password" placeholder="비밀번호" required>
                    <input type="password" name="password_confirmation" placeholder="비밀번호 확인" required>
                    <input type="text" name="name" placeholder="이름" required>
                    <div class="form-group">
                        <input type="date" name="birth" placeholder="생년월일 예) 2000.01.01" required>
                        <select name="gender" required>
                            <option value="" disabled selected hidden>성별 선택</option>
                            <option value="남성">남성</option>
                            <option value="여성">여성</option>
                        </select>
                    </div>
                </div>
                <div class="bottom">
                    <button type="submit">회원가입</button>
                    <a href="{{ asset("login") }}">계정이 이미 있나요?</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>