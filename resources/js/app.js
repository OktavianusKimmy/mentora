import './bootstrap';

document.addEventListener("DOMContentLoaded", function () {
    console.log("JS Loaded"); // <- buat test

    const burger = document.getElementById("burger");
    const menu = document.getElementById("mobileMenu");

    if (burger && menu) {
        burger.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });
    }
});

if (!window.countdownRunning) {

    window.countdownRunning = true;

    const examDate = new Date(2026, 7, 17, 7, 0, 0).getTime();

    const daysEl = document.getElementById("days");
    const hoursEl = document.getElementById("hours");
    const minutesEl = document.getElementById("minutes");
    const secondsEl = document.getElementById("seconds");

    function updateCountdown() {
        const now = Date.now();
        const distance = examDate - now;

        if (distance <= 0) return;

        const totalSeconds = Math.floor(distance / 1000);

        const days = Math.floor(totalSeconds / 86400);
        const hours = Math.floor((totalSeconds % 86400) / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        updateNumber(daysEl, days);
        updateNumber(hoursEl, hours);
        updateNumber(minutesEl, minutes);
        updateNumber(secondsEl, seconds);
    }

    function updateNumber(element, value) {
        const newVal = String(value).padStart(2, '0');

        if (element.textContent !== newVal) {
            element.classList.add("scale-110");

            setTimeout(() => {
                element.textContent = newVal;
                element.classList.remove("scale-110");
            }, 100);
        }
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
}