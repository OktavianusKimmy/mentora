const MODES = {
    pomodoro25: {
        key: 'pomodoro25',
        name: 'Pomodoro 25/5',
        sequence: [{
                type: 'study',
                duration: 25 * 60,
                label: 'Study 25 Menit',
                saveMinutes: 25
            },
            {
                type: 'break',
                duration: 5 * 60,
                label: 'Break 5 Menit',
                saveMinutes: 0
            },
            {
                type: 'study',
                duration: 25 * 60,
                label: 'Study 25 Menit',
                saveMinutes: 25
            },
            {
                type: 'break',
                duration: 5 * 60,
                label: 'Break 5 Menit',
                saveMinutes: 0
            },
            {
                type: 'study',
                duration: 25 * 60,
                label: 'Study 25 Menit',
                saveMinutes: 25
            },
            {
                type: 'break',
                duration: 5 * 60,
                label: 'Break 5 Menit',
                saveMinutes: 0
            },
            {
                type: 'study',
                duration: 25 * 60,
                label: 'Study 25 Menit',
                saveMinutes: 25
            },
            {
                type: 'long_break',
                duration: 30 * 60,
                label: 'Long Break 30 Menit',
                saveMinutes: 0
            },
        ],
    },
    deep50: {
        key: 'deep50',
        name: 'Deep 50/10',
        sequence: [{
                type: 'study',
                duration: 50 * 60,
                label: 'Study 50 Menit',
                saveMinutes: 50
            },
            {
                type: 'break',
                duration: 10 * 60,
                label: 'Break 10 Menit',
                saveMinutes: 0
            },
            {
                type: 'study',
                duration: 50 * 60,
                label: 'Study 50 Menit',
                saveMinutes: 50
            },
            {
                type: 'long_break',
                duration: 30 * 60,
                label: 'Long Break 30 Menit',
                saveMinutes: 0
            },
        ],
    },
};

let currentMode = MODES.pomodoro25;
let currentStepIndex = 0;
let currentStep = currentMode.sequence[0];
let secondsLeft = currentStep.duration;
let totalSeconds = currentStep.duration;
let timer = null;
let isRunning = false;
let isFocusMode = false;
let expectedEndTime = 0; // Tambahan untuk timer yang kebal tab throttling

const timerDisplay = document.getElementById('timerDisplay');
const sessionTitle = document.getElementById('sessionTitle');
const sessionTypeLabel = document.getElementById('sessionTypeLabel');
const statusText = document.getElementById('statusText');
const activeModeText = document.getElementById('activeModeText');
const nextBreakText = document.getElementById('nextBreakText');
const stateText = document.getElementById('stateText');
const progressCircle = document.getElementById('progressCircle');
const stepCounter = document.getElementById('stepCounter');
const timerCard = document.getElementById('timerCard');

const startBtn = document.getElementById('startBtn');
const pauseBtn = document.getElementById('pauseBtn');
const resetBtn = document.getElementById('resetBtn');

const modePomodoro = document.getElementById('modePomodoro');
const modeDeep50 = document.getElementById('modeDeep50');
const focusModeBtn = document.getElementById('focusModeBtn');

const focusOverlay = document.getElementById('focusOverlay');
const exitFocusModeBtn = document.getElementById('exitFocusModeBtn');
const focusOverlayType = document.getElementById('focusOverlayType');
const focusOverlayTitle = document.getElementById('focusOverlayTitle');
const focusOverlayTimerDisplay = document.getElementById('focusOverlayTimerDisplay');
const focusOverlayStatusText = document.getElementById('focusOverlayStatusText');
const focusOverlayProgressCircle = document.getElementById('focusOverlayProgressCircle');
const focusOverlayStepCounter = document.getElementById('focusOverlayStepCounter');
const focusOverlayNextBreakText = document.getElementById('focusOverlayNextBreakText');
const focusOverlayStartBtn = document.getElementById('focusOverlayStartBtn');
const focusOverlayPauseBtn = document.getElementById('focusOverlayPauseBtn');
const focusOverlayResetBtn = document.getElementById('focusOverlayResetBtn');

const alarmSound = document.getElementById('alarmSound');

const modal = document.getElementById('sessionModal');
const modalTitle = document.getElementById('modalTitle');
const modalDescription = document.getElementById('modalDescription');
const modalPrimaryBtn = document.getElementById('modalPrimaryBtn');
const modalSecondaryBtn = document.getElementById('modalSecondaryBtn');

const todayMinutesEl = document.getElementById('todayMinutes');
const todaySessionsEl = document.getElementById('todaySessions');

const CIRCLE_LENGTH = 427;

function formatTime(t) {
    const m = String(Math.floor(t / 60)).padStart(2, '0');
    const s = String(t % 60).padStart(2, '0');
    return `${m}:${s}`;
}

function getTypeText(type) {
    if (type === 'study') return 'Focus Session';
    if (type === 'break') return 'Short Break';
    return 'Long Break';
}

function getNextStep() {
    let next = currentStepIndex + 1;
    if (next >= currentMode.sequence.length) next = 0;
    return currentMode.sequence[next];
}

function getStudyProgress() {
    const studies = currentMode.sequence.filter(step => step.type === 'study');
    const total = studies.length;

    let completedOrCurrent = 0;
    for (let i = 0; i <= currentStepIndex; i++) {
        if (currentMode.sequence[i].type === 'study') {
            completedOrCurrent++;
        }
    }

    if (currentStep.type !== 'study') {
        completedOrCurrent = Math.max(1, completedOrCurrent);
    }

    return {
        current: Math.min(completedOrCurrent, total),
        total,
    };
}

function updateCircleElement(circleEl) {
    if (!circleEl) return;
    const progress = (totalSeconds - secondsLeft) / totalSeconds;
    const offset = CIRCLE_LENGTH - (progress * CIRCLE_LENGTH);

    circleEl.style.strokeDasharray = CIRCLE_LENGTH;
    circleEl.style.strokeDashoffset = offset;

    if (currentStep.type === 'study') {
        circleEl.style.stroke = '#FACC15';
    } else if (currentStep.type === 'break') {
        circleEl.style.stroke = '#4ADE80';
    } else {
        circleEl.style.stroke = '#A78BFA';
    }
}

function updateModeButtons() {
    const activeClass = 'mode-btn px-5 py-3 rounded-2xl text-sm font-bold transition-all duration-200 border border-blue-200 bg-blue-600 text-white shadow-md scale-[1.02]';
    const inactiveClass = 'mode-btn px-5 py-3 rounded-2xl text-sm font-bold transition-all duration-200 border border-transparent bg-gray-100 text-gray-700 hover:bg-gray-200';

    if (modePomodoro) modePomodoro.className = currentMode.key === 'pomodoro25' ? activeClass : inactiveClass;
    if (modeDeep50) modeDeep50.className = currentMode.key === 'deep50' ? activeClass : inactiveClass;
}

function updateButtonState(buttonStart, buttonPause) {
    if (!buttonStart || !buttonPause) return;
    if (isRunning) {
        buttonStart.disabled = true;
        buttonStart.className = 'px-6 py-3 rounded-2xl bg-yellow-200 text-gray-500 font-extrabold transition-all duration-200 shadow-sm cursor-not-allowed';
        buttonPause.className = 'px-6 py-3 rounded-2xl bg-white/25 text-white font-semibold hover:bg-white/30 transition-all duration-200 active:scale-[0.98] ring-2 ring-white/20';
    } else {
        buttonStart.disabled = false;
        buttonStart.className = 'px-6 py-3 rounded-2xl bg-yellow-300 text-gray-900 font-extrabold hover:bg-yellow-200 transition-all duration-200 shadow-sm active:scale-[0.98]';
        buttonPause.className = 'px-6 py-3 rounded-2xl bg-white/15 text-white font-semibold hover:bg-white/20 transition-all duration-200 active:scale-[0.98]';
    }
}

function updateCardAppearance() {
    if (timerCard) {
        if (currentStep.type === 'study') {
            timerCard.className = 'rounded-[32px] bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-500 p-8 md:p-10 text-white shadow-lg transition-all duration-300';
        } else if (currentStep.type === 'break') {
            timerCard.className = 'rounded-[32px] bg-gradient-to-br from-emerald-500 via-green-500 to-teal-500 p-8 md:p-10 text-white shadow-lg transition-all duration-300';
        } else {
            timerCard.className = 'rounded-[32px] bg-gradient-to-br from-violet-600 via-purple-500 to-fuchsia-500 p-8 md:p-10 text-white shadow-lg transition-all duration-300';
        }
    }

    if (focusOverlay) {
        if (currentStep.type === 'study') {
            focusOverlay.className = 'fixed inset-0 z-[60] bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 text-white flex';
        } else if (currentStep.type === 'break') {
            focusOverlay.className = 'fixed inset-0 z-[60] bg-gradient-to-br from-emerald-600 via-green-600 to-teal-600 text-white flex';
        } else {
            focusOverlay.className = 'fixed inset-0 z-[60] bg-gradient-to-br from-violet-700 via-purple-600 to-fuchsia-700 text-white flex';
        }

        if (!isFocusMode) {
            focusOverlay.classList.add('hidden');
            focusOverlay.classList.remove('flex');
        }
    }
}

function updateDisplay() {
    const next = getNextStep();
    const progress = getStudyProgress();
    const timeText = formatTime(secondsLeft);
    const status = stateText ? stateText.textContent : 'Ready';

    if (timerDisplay) timerDisplay.textContent = timeText;
    if (sessionTitle) sessionTitle.textContent = currentStep.label;
    if (sessionTypeLabel) sessionTypeLabel.textContent = getTypeText(currentStep.type);
    if (activeModeText) activeModeText.textContent = currentMode.name;
    if (nextBreakText) nextBreakText.textContent = next.label;
    if (stepCounter) stepCounter.textContent = `Session ${progress.current} / ${progress.total}`;

    if (focusOverlayTimerDisplay) focusOverlayTimerDisplay.textContent = timeText;
    if (focusOverlayTitle) focusOverlayTitle.textContent = currentStep.label;
    if (focusOverlayType) focusOverlayType.textContent = getTypeText(currentStep.type);
    if (focusOverlayNextBreakText) focusOverlayNextBreakText.textContent = next.label;
    if (focusOverlayStepCounter) focusOverlayStepCounter.textContent = `Session ${progress.current} / ${progress.total}`;
    if (focusOverlayStatusText && statusText) focusOverlayStatusText.textContent = statusText.textContent;

    updateCircleElement(progressCircle);
    updateCircleElement(focusOverlayProgressCircle);

    updateModeButtons();
    updateButtonState(startBtn, pauseBtn);
    updateButtonState(focusOverlayStartBtn, focusOverlayPauseBtn);
    updateCardAppearance();
}

function setStateText(text) {
    if (stateText) stateText.textContent = text;
    if (focusOverlayStatusText && statusText) focusOverlayStatusText.textContent = statusText.textContent;
}

function stopTimer() {
    if (timer) {
        clearInterval(timer);
        timer = null;
    }
    isRunning = false;
    updateDisplay();
}

function resetCurrentStep() {
    stopTimer();
    secondsLeft = currentStep.duration;
    totalSeconds = currentStep.duration;
    setStateText('Ready');
    if (statusText) statusText.textContent = 'Timer di-reset.';
    updateDisplay();
}

function switchMode(modeKey) {
    stopTimer();

    currentMode = MODES[modeKey];
    currentStepIndex = 0;
    currentStep = currentMode.sequence[0];
    secondsLeft = currentStep.duration;
    totalSeconds = currentStep.duration;

    setStateText('Ready');
    if (statusText) statusText.textContent = `${currentMode.name} dipilih.`;

    updateDisplay();
}

function nextStep() {
    currentStepIndex += 1;

    if (currentStepIndex >= currentMode.sequence.length) {
        currentStepIndex = 0;
    }

    currentStep = currentMode.sequence[currentStepIndex];
    secondsLeft = currentStep.duration;
    totalSeconds = currentStep.duration;

    setStateText('Ready');
    if (statusText) statusText.textContent = `${currentStep.label} siap dimulai.`;
    updateDisplay();
}

function playAlarm() {
    if (!alarmSound) return;

    // Paksa browser buat "sadar" ada audio yang harus dimainin
    alarmSound.pause();
    alarmSound.currentTime = 0;

    // Pake play() yang bentuknya promise biar kita tau kenapa dia gagal
    alarmSound.play().then(() => {
        console.log('Alarm bunyi!');
    }).catch(error => {
        // Biasanya error "NotAllowedError" karena user belum klik apa-apa di web
        console.error('Alarm diblokir browser:', error);

        // Cadangan: Munculin alert biar user klik OK, 
        // setelah klik biasanya audio bakal "terbuka" kuncinya
        alert("Sesi selesai! (Browser mematikan suara otomatis)");
    });
}

function openModal(title, desc, nextLabel) {
    if (!modal) return;

    if (modalTitle) modalTitle.textContent = title;
    if (modalDescription) modalDescription.textContent = desc;

    if (modalPrimaryBtn) {
        modalPrimaryBtn.textContent = `Mulai ${nextLabel}`;
        modalPrimaryBtn.onclick = () => {
            closeModal();
            nextStep();
        };
    }

    if (modalSecondaryBtn) {
        modalSecondaryBtn.onclick = () => {
            closeModal();
        };
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function saveSession(minutes) {
    if (!minutes || minutes <= 0) return true;

    try {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = metaTag ? metaTag.getAttribute('content') : '';

        const response = await fetch('/study-room/session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                duration: minutes
            }),
        });

        if (!response.ok) {
            throw new Error('Failed to save session');
        }

        if (todayMinutesEl && todaySessionsEl) {
            const currentMinutes = parseInt(todayMinutesEl.textContent || '0', 10);
            const currentSessions = parseInt(todaySessionsEl.textContent || '0', 10);
            todayMinutesEl.textContent = currentMinutes + minutes;
            todaySessionsEl.textContent = currentSessions + 1;
        }

        return true;
    } catch (error) {
        console.error(error);
        alert('Session selesai, tapi gagal disimpan ke database.');
        return false;
    }
}

async function handleSessionComplete() {
    // 1. Matikan timer & Bunyiin alarm SECEPAT MUNGKIN
    stopTimer();
    playAlarm();

    console.log("Mencoba membunyikan alarm..."); // Cek ini di console

    const next = getNextStep();

    if (currentStep.type === 'study') {
        setStateText('Completed');
        if (statusText) statusText.textContent = 'Sesi fokus selesai.';

        // 2. Munculkan modal segera (User interface dulu)
        openModal(
            'Sesi fokus selesai 🎉',
            `${currentStep.label} selesai. Sekarang lanjut ke ${next.label}.`,
            next.label
        );

        // 3. Simpan ke database tanpa 'await' di depannya
        // Biarkan dia jalan di background supaya tidak nge-freeze UI/Suara
        saveSession(currentStep.saveMinutes);

        updateDisplay();
        return;
    }

    // Bagian break
    if (currentStep.type === 'break') {
        setStateText('Break Complete');
        if (statusText) statusText.textContent = 'Break selesai.';

        openModal(
            'Break selesai ⚡',
            `Saatnya balik fokus. Step berikutnya: ${next.label}.`,
            next.label
        );
        updateDisplay();
        return;
    }

    // Bagian cycle selesai
    setStateText('Cycle Complete');
    if (statusText) statusText.textContent = 'Long break selesai.';
    openModal(
        'Cycle selesai 🔥',
        `Long break selesai. Sequence akan kembali ke awal: ${next.label}.`,
        next.label
    );
    updateDisplay();
}

function startTimer() {
    if (isRunning) return;

    isRunning = true;
    setStateText('Running');
    if (statusText) statusText.textContent = `${currentStep.label} sedang berjalan...`;

    // Kalkulasi kapan timer ini seharusnya selesai (kebal tab throttling)
    expectedEndTime = Date.now() + (secondsLeft * 1000);
    updateDisplay();

    timer = setInterval(async () => {
        const now = Date.now();
        secondsLeft = Math.max(0, Math.round((expectedEndTime - now) / 1000));

        if (secondsLeft <= 0) {
            secondsLeft = 0;
            updateDisplay();
            await handleSessionComplete();
            return;
        }

        updateDisplay();
    }, 1000);
}

function pauseTimer() {
    if (!isRunning) return;

    // Pastikan sisa detik akurat pas dipause
    secondsLeft = Math.max(0, Math.round((expectedEndTime - Date.now()) / 1000));
    stopTimer();

    setStateText('Paused');
    if (statusText) statusText.textContent = 'Timer dijeda. Tekan Start untuk lanjut.';
    updateDisplay();
}

function resetTimer() {
    resetCurrentStep();
}

function enterFocusMode() {
    isFocusMode = true;
    if (focusOverlay) {
        focusOverlay.classList.remove('hidden');
        focusOverlay.classList.add('flex');
    }
    updateDisplay();
}

function exitFocusMode() {
    isFocusMode = false;
    if (focusOverlay) {
        focusOverlay.classList.add('hidden');
        focusOverlay.classList.remove('flex');
    }
}

// Menghapus semua optional chaining (?.) untuk mencegah error Vite
if (modePomodoro) modePomodoro.addEventListener('click', () => switchMode('pomodoro25'));
if (modeDeep50) modeDeep50.addEventListener('click', () => switchMode('deep50'));

if (startBtn) startBtn.addEventListener('click', startTimer);
if (pauseBtn) pauseBtn.addEventListener('click', pauseTimer);
if (resetBtn) resetBtn.addEventListener('click', resetTimer);

if (focusOverlayStartBtn) focusOverlayStartBtn.addEventListener('click', startTimer);
if (focusOverlayPauseBtn) focusOverlayPauseBtn.addEventListener('click', pauseTimer);
if (focusOverlayResetBtn) focusOverlayResetBtn.addEventListener('click', resetTimer);

if (focusModeBtn) focusModeBtn.addEventListener('click', enterFocusMode);
if (exitFocusModeBtn) exitFocusModeBtn.addEventListener('click', exitFocusMode);

if (modal) {
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
}

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && isFocusMode) {
        exitFocusMode();
    }
});

switchMode('pomodoro25');
updateDisplay();
