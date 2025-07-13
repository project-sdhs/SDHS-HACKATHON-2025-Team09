<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <title>주변 병원 찾기</title>
  <link rel="stylesheet" href="./css/global.css">
  <link rel="stylesheet" href="./css/hospital.css">
  <style>
    #map { width: 100%; height: 350px; margin-top: 10px; }
    #hospital-list { margin-top: 10px; }
    main li { margin-bottom: 8px; }
  </style>
</head>
<body>
    <div class="container">
      <x-layout.header  />
        <main>
            <p>근처에 있는 병원이에요!</p>
            <ul id="hospital-list">
            </ul>
        </main>
        <x-layout.tab-bar />
    </div>

    <script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=eca4dc64df12fedd2cc585e72c3b0a48&libraries=services"></script>
    <script src="./js/geolocation.js"></script>
</body>
</html>
