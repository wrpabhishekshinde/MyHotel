let currentIndex = 0;
const slides = document.querySelectorAll('.slide');

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.style.left = (i - index) * 100 + '%';
    });
}

function goNext() {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
}

function goPrev() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(currentIndex);
}

// Initialize the slideshow
showSlide(currentIndex);
