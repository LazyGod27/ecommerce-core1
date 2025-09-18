
const header = document.querySelector("header");
const menu = document.querySelector('#menu-icon');
const navbar = document.querySelector('.navbar');

window.addEventListener("scroll", function(){
    header.classList.toggle("sticky", window.scrollY > 0);
});

menu.onclick = () => {
    menu.classList.toggle('bx-x');
    navbar.classList.toggle('open');
};

window.onscroll = () => {
    menu.classList.remove('bx-x');
    navbar.classList.remove('open');
};


const sr = ScrollReveal({
    distance: '60px',
    duration: 2500,
    delay: 400,
    reset: true
});
sr.reveal('.home-text', { delay: 200, origin: 'top' });
sr.reveal('.home-img', { delay: 300, origin: 'top' });
sr.reveal('.feature, .product, .cta-content, .contact', { delay: 200, origin: 'top' });

document.addEventListener("DOMContentLoaded", function() {
    const dropdown = document.querySelector(".dropdown > a");
    const dropdownMenu = document.querySelector(".dropdown-menu");

    if (dropdown && dropdownMenu) {
        dropdown.addEventListener("click", function(e) {
            e.preventDefault();
            dropdownMenu.classList.toggle("show");
        });
        document.addEventListener("click", function(e) {
            if (!dropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove("show");
            }
        });
    }
});

let slides = document.querySelectorAll(".hero-slides .slide");
let dotsContainer = document.querySelector(".hero-dots");
let currentSlide = 0;
let slideInterval;

function showSlide(index) {
    if (slides.length === 0) return;
    slides.forEach(slide => slide.classList.remove("active"));
    const dots = document.querySelectorAll(".hero-dots .dot");
    dots.forEach(dot => dot.classList.remove("active"));

    currentSlide = (index + slides.length) % slides.length;
    slides[currentSlide].classList.add("active");
    if (dots.length > 0) {
        dots[currentSlide].classList.add("active");
    }
}

function nextSlide() {
    showSlide(currentSlide + 1);
}

function resetInterval() {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, 3000);
}
if (slides.length > 0) {
    showSlide(0);
    resetInterval();
}

if (dotsContainer) {
    dotsContainer.querySelectorAll(".dot").forEach(dot => {
        dot.addEventListener("click", () => {
            const index = parseInt(dot.dataset.index);
            showSlide(index);
            resetInterval();
        });
    });
}

const userIcon = document.querySelector('.ri-user-line');
const loginModal = document.getElementById('loginModal');
const closeLogin = document.getElementById('closeLogin');

if (userIcon && loginModal && closeLogin) {
    userIcon.parentElement.addEventListener('click', e => {
        e.preventDefault();
        loginModal.style.display = 'flex';
        setTimeout(() => loginModal.classList.add('show'), 10);
    });
    closeLogin.addEventListener('click', () => {
        loginModal.classList.remove('show');
        setTimeout(() => loginModal.style.display = 'none', 400);
    });
    loginModal.addEventListener('click', e => {
        if (e.target === loginModal) {
            loginModal.classList.remove('show');
            setTimeout(() => loginModal.style.display = 'none', 400);
        }
    });
}
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

if (togglePassword && passwordInput) {
    togglePassword.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        togglePassword.className = isPassword ? 'ri-eye-off-line' : 'ri-eye-line';
    });
}

const signupModal = document.getElementById('signupModal');
const closeSignup = document.getElementById('closeSignup');
const openSignup = document.getElementById('openSignup');
const openLoginFromSignup = document.getElementById('openLoginFromSignup');

if (signupModal && closeSignup && openSignup && openLoginFromSignup) {
    openSignup.addEventListener('click', e => {
        e.preventDefault();
        loginModal.classList.remove('show');
        setTimeout(() => {
            loginModal.style.display = 'none';
            signupModal.style.display = 'flex';
            setTimeout(() => signupModal.classList.add('show'), 10);
        }, 400);
    });
    closeSignup.addEventListener('click', () => {
        signupModal.classList.remove('show');
        setTimeout(() => signupModal.style.display = 'none', 400);
    });
    openLoginFromSignup.addEventListener('click', e => {
        e.preventDefault();
        signupModal.classList.remove('show');
        setTimeout(() => {
            signupModal.style.display = 'none';
            loginModal.style.display = 'flex';
            setTimeout(() => loginModal.classList.add('show'), 10);
        }, 400);
    });
    signupModal.addEventListener('click', e => {
        if (e.target === signupModal) {
            signupModal.classList.remove('show');
            setTimeout(() => signupModal.style.display = 'none', 400);
        }
    });
}

const toggleSignupPassword = document.querySelector('.toggleSignupPassword');
if (toggleSignupPassword) {
    toggleSignupPassword.addEventListener('click', function() {
        const passwordInput = document.getElementById('newPassword');
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        this.className = isPassword ? 'ri-eye-off-line toggleSignupPassword' : 'ri-eye-line toggleSignupPassword';
    });
}
