// const status = document.getElementById('status'); // status 요소는 사용하지 않습니다.
const hospitalList = document.getElementById('hospital-list');

// 페이지 로드 시 자동으로 위치 정보를 가져와 병원 검색
document.addEventListener('DOMContentLoaded', () => {
    hospitalList.innerHTML = ''; // 기존 목록 초기화

    // Geolocation API 지원 여부 확인
    if (!navigator.geolocation) {
        console.error('브라우저가 Geolocation을 지원하지 않아 위치를 찾을 수 없습니다.');
        // 사용자에게 직접 메시지 표시: 브라우저가 위치 정보를 지원하지 않음
        hospitalList.innerHTML = '<li>죄송합니다. 브라우저가 위치 정보를 지원하지 않습니다.</li>'; 
        return;
    }

    // 현재 위치 정보 가져오기
    navigator.geolocation.getCurrentPosition(
        position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = position.coords.accuracy;

            console.log(`내 위치: 위도 ${lat}, 경도 ${lng}, 정확도 ±${accuracy}m`);
            
            // 위치 정보를 성공적으로 가져오면 병원 검색 시작
            searchHospitals(lat, lng);
        },
        error => {
            let errorMessage = '위치 정보를 가져올 수 없습니다. 권한이 거부되었거나 오류가 발생했습니다.';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage = '위치 정보 사용이 거부되었습니다. 브라우저 설정에서 위치 접근을 허용해주세요.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = '위치 정보를 사용할 수 없습니다.';
                    break;
                case error.TIMEOUT:
                    errorMessage = '위치 정보를 가져오는 시간이 초과되었습니다.';
                    break;
                case error.UNKNOWN_ERROR:
                    errorMessage = '알 수 없는 오류가 발생했습니다.';
                    break;
            }
            console.error('Geolocation error:', errorMessage, error);
            // 사용자에게 직접 메시지 표시: 위치 정보 가져오기 오류
            hospitalList.innerHTML = `<li>오류: ${errorMessage}</li>`; 
        },
        {
            enableHighAccuracy: true, // 더 정확한 위치 정보 요청
            timeout: 10000,           // 10초 내에 위치 정보 응답 없으면 타임아웃
            maximumAge: 0             // 캐시된 위치 정보 사용 안 함
        }
    );
});

/**
 * 두 지점(위도, 경도) 간의 거리를 미터 단위로 계산합니다.
 * @param {number} lat1 - 첫 번째 지점의 위도
 * @param {number} lng1 - 첫 번째 지점의 경도
 * @param {number} lat2 - 두 번째 지점의 위도
 * @param {number} lng2 - 두 번째 지점의 경도
 * @returns {number} 두 지점 간의 거리 (미터)
 */
function getDistance(lat1, lng1, lat2, lng2) {
    function deg2rad(deg) { return deg * (Math.PI / 180); }
    const R = 6371e3; // 지구 반지름 (미터)
    const dLat = deg2rad(lat2 - lat1);
    const dLng = deg2rad(lng2 - lng1);

    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
              Math.sin(dLng / 2) * Math.sin(dLng / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

// ⚠️ 중요: 이 REST_API_KEY는 카카오 개발자 센터에서 발급받은 본인의 키로 교체해야 합니다.
// 클라이언트 측에 노출되므로, 실제 서비스에서는 보안을 위해 서버 측에서 관리하는 것이 좋습니다.
const REST_API_KEY = '87bd95a8fb88a4f1549ed0b614cd413c'; // 여기에 실제 키를 넣어주세요!

/**
 * 주어진 위도, 경도 기준으로 주변 병원을 검색하고 목록에 표시합니다.
 * 동물병원은 결과에서 제외됩니다.
 * @param {number} lat - 검색 기준 위도
 * @param {number} lng - 검색 기준 경도
 */
function searchHospitals(lat, lng) {
    const url = `https://dapi.kakao.com/v2/local/search/keyword.json?query=병원&y=${lat}&x=${lng}&radius=2000&size=15&sort=distance`;

    fetch(url, {
        method: 'GET',
        headers: { Authorization: `KakaoAK ${REST_API_KEY}` }
    })
    .then(res => {
        if (!res.ok) {
            if (res.status === 401) {
                console.error('API 키 인증 실패! 카카오 개발자 센터에서 REST API 키를 확인해주세요.');
                throw new Error('API 키 인증 실패! (콘솔 확인)');
            }
            console.error(`HTTP 오류: ${res.status} ${res.statusText}`);
            throw new Error(`HTTP 오류: ${res.status} (콘솔 확인)`);
        }
        return res.json();
    })
    .then(data => {
        if (!data.documents || data.documents.length === 0) {
            console.log('현재 위치 근처 2km 이내에 검색된 병원이 없습니다.');
            hospitalList.innerHTML = '<li>주변에 병원을 찾을 수 없습니다.</li>';
            return;
        }

        const filteredHospitals = data.documents.filter(item => {
            const isAnimalHospitalCategory = item.category_name && item.category_name.includes('동물병원');
            const isAnimalHospitalName = item.place_name.includes('동물병원') || item.place_name.includes('동물메디컬'); 
            return !isAnimalHospitalCategory && !isAnimalHospitalName;
        });

        if (filteredHospitals.length === 0) {
            console.log('주변에 일반 병원을 찾을 수 없습니다 (동물병원 제외).');
            hospitalList.innerHTML = '<li>주변에 일반 병원을 찾을 수 없습니다.</li>';
            return;
        }

        const limitedHospitals = filteredHospitals.slice(0, 10);

        console.log(`주변 일반 병원 ${limitedHospitals.length}곳을 찾았습니다. (거리순, 동물병원 제외)`);

        hospitalList.innerHTML = '';

        limitedHospitals.forEach(item => {
            const distance = getDistance(lat, lng, parseFloat(item.y), parseFloat(item.x));
            const li = document.createElement('li');

            const hospitalType = item.category_name ? item.category_name.split('>').pop().trim() : '';

            li.setAttribute('data-lat', item.y);
            li.setAttribute('data-lng', item.x);
            li.setAttribute('data-name', item.place_name); 

            li.innerHTML = `
                <span>
                    <span class="flex-row">
                        <span class="name">${item.place_name}</span>
                        <span class="type">${hospitalType || '병원'}</span>
                    </span>
                    <span class="flex-row details">
                        <span class="distance">${Math.round(distance)}m</span>
                        <span class="separate">•</span>
                        <span class="address">${item.road_address_name || item.address_name}</span>
                    </span>
                </span>
                <button class="map-button"><img src="./img/icon/marker.svg" alt="지도에서 보기"></button>
            `;
            hospitalList.appendChild(li);
        });

        // 모든 li 요소를 추가한 후, 각 마커 버튼에 이벤트 리스너를 연결
        addMapButtonListeners();
    })
    .catch(err => {
        console.error('병원 검색 중 오류가 발생했습니다:', err.message || err);
        hospitalList.innerHTML = `<li>병원 검색 중 오류가 발생했습니다: ${err.message || '알 수 없는 오류'}</li>`;
    });
}

/**
 * 병원 목록의 각 마커 버튼에 클릭 이벤트 리스너를 추가합니다.
 * 클릭 시 네이버 지도로 바로 연결됩니다.
 */
function addMapButtonListeners() {
    const mapButtons = document.querySelectorAll('.map-button'); // 모든 .map-button 선택자 사용

    mapButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            // 버튼의 부모 li 요소에서 데이터 속성 가져오기
            const listItem = event.target.closest('li');
            if (listItem) {
                const name = listItem.getAttribute('data-name');
                // 네이버 지도로 연결: 병원 이름으로 검색
                // 네이버 지도는 좌표로 직접 마커를 찍는 URL 방식보다는 이름으로 검색하는 것이 가장 일반적이고 안정적입니다.
                const mapUrl = `https://map.naver.com/v5/search/${encodeURIComponent(name)}`;
                
                window.open(mapUrl, '_blank'); // 새 탭에서 지도 열기
            } else {
                console.error('지도 정보를 찾을 수 없습니다.');
                alert('이 병원의 지도 정보를 찾을 수 없습니다.');
            }
        });
    });
}