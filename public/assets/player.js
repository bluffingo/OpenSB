if (typeof playerConfig === 'undefined') {
    playerConfig = {
        "url": "https://squarebracket.pw/dynamic/videos/V05kmlJpDzC.converted.mp4",
    };
}

document.addEventListener("DOMContentLoaded", () => {
    console.debug("Video player");
    let wrapper = document.getElementById('video-wrapper');
    fetchWithRetry(`/html5_player_template`, {
        method: "GET",
        headers: {
            "Content-type": "text/html; charset=UTF-8"
        },
        cache: "force-cache"
    })
        .then(response => response.text())
        .then(html => {
            wrapper.insertAdjacentHTML('afterbegin', html);
            createVideoElement();
        });
});

function getTimeFromNumber(number) {
    const pad = (num) => String(Math.floor(num)).padStart(2, '0');
    
    return [
        number / 3600,        // h
        (number % 3600) / 60, // m
        number % 60           // s
    ].map(pad).join(':');
}

function createVideoElement() {
    const container = document.querySelector('.video-container');
    const video = document.createElement('video');
    video.setAttribute('x-webkit-airplay', 'allow'); // allow airplay
    video.className = 'video-stream main-video';
    video.src = playerConfig.url;
    container.appendChild(video);

    // progress bar/scrubber
    const progressScrubber = document.querySelector('.player-scrubber');
    const progressContainer = document.querySelector('.player-progress');
    const progressBarElapsed = document.querySelector('.player-progress-bar-elapsed');

    const progressTimeDisplay = document.querySelector('.player-time-display');

    progressContainer.addEventListener('click', (event) => {
        const rect = progressContainer.getBoundingClientRect();
        const radius = progressScrubber.offsetWidth / 2;
        const trackWidth = rect.width;

        // figure this shit out from the event's location
        const location = Math.min(Math.max(event.clientX - rect.left - radius, 0), trackWidth - 2 * radius);
        const percentage = location / (trackWidth - 2 * radius);
        video.currentTime = percentage * video.duration;
    });

    // time update
    video.addEventListener('timeupdate', () => {
        progressTimeDisplay.textContent = getTimeFromNumber(video.currentTime);
        const progressPercentage = (video.currentTime / video.duration) * 100;

        const scrubberRadius = progressScrubber.offsetWidth / 2;
        const leftPx = scrubberRadius + (progressPercentage / 100) * (progressContainer.clientWidth - 2 * (scrubberRadius));
        progressScrubber.style.left = `${leftPx}px`;
        
        progressBarElapsed.style.width = `${progressPercentage}%`;
    });

    // buttons
    const playButton = document.querySelector('.player-button-play');
    playButton.addEventListener('click', () => {
        if (video.paused) {
            video.play();
        } else {
            video.pause();
        }
    });

    video.addEventListener('play', () => {
        playButton.classList.remove('player-button-play');
        playButton.classList.add('player-button-pause');
    });

    video.addEventListener('pause', () => {
        playButton.classList.remove('player-button-pause');
        playButton.classList.add('player-button-play');
    });
}