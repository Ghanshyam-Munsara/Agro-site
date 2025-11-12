// Mobile menu toggle
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

if (hamburger && navMenu) {
    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        
        // Animate hamburger
        hamburger.classList.toggle('active');
    });

    // Close menu when clicking on a link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            hamburger.classList.remove('active');
        });
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
            navMenu.classList.remove('active');
            hamburger.classList.remove('active');
        }
    });
}

// Smooth scroll for anchor links (if any)
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Hero Slider Functionality
let currentSlide = 0;
const slides = document.querySelectorAll('.hero-slide');
const dots = document.querySelectorAll('.dot');
const totalSlides = slides.length;
let slideInterval;

function showSlide(index) {
    // Remove active class from all slides and dots
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    // Add active class to current slide and dot
    if (slides[index]) {
        slides[index].classList.add('active');
    }
    if (dots[index]) {
        dots[index].classList.add('active');
    }
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    showSlide(currentSlide);
}

function goToSlide(index) {
    currentSlide = index;
    showSlide(currentSlide);
    resetSlider();
}

function startSlider() {
    slideInterval = setInterval(nextSlide, 5000); // 5 seconds
}

function resetSlider() {
    clearInterval(slideInterval);
    startSlider();
}

// Initialize slider
if (slides.length > 0) {
    showSlide(0);
    startSlider();
    
    // Navigation arrows
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetSlider();
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetSlider();
        });
    }
    
    // Navigation dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
        });
    });
    
    // Pause on hover
    const sliderSection = document.querySelector('.hero-slider-section');
    if (sliderSection) {
        sliderSection.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });
        
        sliderSection.addEventListener('mouseleave', () => {
            startSlider();
        });
    }
}

// Contact form handling
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone') ? document.getElementById('phone').value : '';
        const subject = document.getElementById('subject') ? document.getElementById('subject').value : '';
        const message = document.getElementById('message').value;
        
        // Simple validation
        if (name && email && message) {
            // Check if subject is required and filled
            const subjectField = document.getElementById('subject');
            if (subjectField && subjectField.hasAttribute('required') && !subject) {
                alert('Please select a subject.');
                return;
            }
            
            // Show success message (in a real application, you would send this to a server)
            let successMessage = 'Thank you, ' + name + '! Your message has been received.';
            if (subject) {
                successMessage += ' Subject: ' + subjectField.options[subjectField.selectedIndex].text + '.';
            }
            successMessage += ' We will get back to you soon at ' + email + '.';
            alert(successMessage);
            
            // Reset form
            contactForm.reset();
        } else {
            alert('Please fill in all required fields.');
        }
    });
}

