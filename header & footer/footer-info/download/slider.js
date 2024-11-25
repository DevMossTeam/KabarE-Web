function selectSlide(index) {
    let slides = document.querySelectorAll('.slide');
    let dots = document.querySelectorAll('.dot');
    slides.forEach((slide, i) => {
        slide.classList.remove('active');
        dots[i].classList.remove('active');
        if (i === index) {
            slide.classList.add('active');
            dots[i].classList.add('active');
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
    const images = document.querySelectorAll('.home-image, .wellcome-image');
    const slides = document.querySelectorAll('.slide');
    let currentIndex = 0;
    let slideInterval;

    function checkVisibility() {
        images.forEach(image => {
            const rect = image.getBoundingClientRect();
            if (rect.top >= 0 && rect.bottom <= window.innerHeight) {
                image.style.opacity = 1;
                image.style.animationPlayState = 'running';
            } else {
                image.style.opacity = 0;
                image.style.animationPlayState = 'paused';
            }
        });
    }

    function autoSlide() {
        slideInterval = setInterval(() => {
            currentIndex = (currentIndex + 1) % slides.length;
            selectSlide(currentIndex);
        }, 5000);
    }

    slides.forEach((slide, index) => {
        slide.addEventListener('click', () => {
            clearInterval(slideInterval);
            selectSlide(index);
            currentIndex = index;
            autoSlide(); // Restart auto slide after manual selection
        });
    });

    window.addEventListener('scroll', checkVisibility);
    window.addEventListener('resize', checkVisibility);
    checkVisibility(); // Initial check
    autoSlide(); // Start auto slide
});