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

