document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('symptomForm');
    const resultList = document.getElementById('search-result-list');

    if (!form || !resultList) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const csrf = document.querySelector('input[name="_token"]').value;

        try {
            const response = await fetch("/api/search", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrf,
                },
                body: formData
            });

            const text = await response.text();
            console.log("📦 서버 응답 원본:", text);

            let result;

            try {
                result = JSON.parse(text);
            } catch (e) {
                console.error("❌ JSON 파싱 실패:", e.message);
                alert("서버 오류: 올바르지 않은 응답 형식입니다.");
                return;
            }
            resultList.innerHTML = '';

            result.keywords.forEach(keyword => {
                const li = document.createElement('li');
                const p = document.createElement("p")
                p.textContent = keyword;

                li.append(p)

                const saveBtn = document.createElement('button');
                saveBtn.type = 'button';
                saveBtn.textContent = '저장';
                saveBtn.style.marginLeft = '12px';
                saveBtn.addEventListener('click', () => {
                    const textValue = saveBtn.parentNode.querySelector("p").textContent
                    controlLocalData(textValue)
                });

                li.appendChild(saveBtn);
                resultList.appendChild(li);
            });
        } catch (err) {
            console.error('에러 발생:', err);
        }
    });
});


function appendRecentList() {
    const recentList = document.querySelector(".recent-list");
    const loadList = JSON.parse(localStorage.getItem("list")) || [];

    recentList.innerHTML = '';

    loadList.forEach(item => {
        if (!item || !item.name || item.name.trim() === '') return;

        const li = document.createElement("li");
        li.textContent = `${item.name} - ${item.date}`;
        recentList.append(li);
    });
}

appendRecentList();

function controlLocalData(name) {
    if (!name || name.trim() === '') return;

    const arrayList = JSON.parse(localStorage.getItem("list")) || [];
    const today = new Date().toLocaleDateString();
    arrayList.push({ name, date: today });
    localStorage.setItem("list", JSON.stringify(arrayList));

    const recentList = document.querySelector(".recent-list");

    const li = document.createElement("li");
    li.textContent = `${name} - ${today}`;
    recentList.append(li);
}
