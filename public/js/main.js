function randomTip() {
    const tip = document.querySelector(".tip")
    const messages = ["1분간 눈을 감고 깊게 숨을 쉬어보세요.", "상쾌함을 위해 짧게 걷는 것을 추천해요!", "자리에서 일어나 가볍게 스트레칭을 해보세요", "물을 충분히 마셔보세요."]
    const button = document.querySelector(".tip-btn")
    
    button.addEventListener("click", () => {
        const randomIndex = Math.floor(Math.random() * messages.length)

        tip.textContent = messages[randomIndex]
    })
}

randomTip()

// function loadingToRecent() {
//     const results = document.querySelectorAll(".search-result li")
//     const recentList = document.querySelector(".recent-list")
//     results.forEach((result, i) => {
//         result.addEventListener("click", () => {
//             // Append a cloned node instead of moving the original element
//             recentList.append(results[i].cloneNode(true))
//         })
//     })
// }

// loadingToRecent()