
class FormHandler {
    constructor() {
        this.init();
    }

    init() {
        this.setupContactForm();
        this.setupFeedbackForm();
        this.setupRatingStars();
    }

    setupContactForm() {
        const contactForm = document.querySelector('form[action*="submit.php"]');
        if (!contactForm) return;

        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const formData = new FormData(contactForm);
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            
            if (!this.validateContactForm(formData)) {
                return;
            }

            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

            
            fetch(contactForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data;
                const script = tempDiv.querySelector('script');
                if (script) {
                    eval(script.textContent);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting form. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }

    setupFeedbackForm() {
        const feedbackForm = document.querySelector('form[action*="feedback.php"]');
        if (!feedbackForm) return;

        feedbackForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const formData = new FormData(feedbackForm);
            const submitBtn = feedbackForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            
            if (!formData.get('rating')) {
                alert('Please select a rating before submitting.');
                return;
            }

            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

            
            fetch(feedbackForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                
                const newWindow = window.open('', '_blank');
                newWindow.document.write(data);
                newWindow.document.close();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting feedback. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }

    setupRatingStars() {
        const ratingInputs = document.querySelectorAll('input[name="rating"]');
        const ratingLabels = document.querySelectorAll('.rating-wrapper label');
        
        ratingInputs.forEach((input, index) => {
            input.addEventListener('change', () => {
                
                ratingLabels.forEach((label, labelIndex) => {
                    if (labelIndex <= index) {
                        label.style.color = '#c5a059';
                        label.style.textShadow = '0 0 10px rgba(197, 160, 89, 0.5)';
                    } else {
                        label.style.color = '#666';
                        label.style.textShadow = 'none';
                    }
                });
            });
        });

        
        ratingLabels.forEach((label, index) => {
            label.addEventListener('mouseenter', () => {
                if (!ratingInputs[index].checked) {
                    ratingLabels.forEach((hoverLabel, hoverIndex) => {
                        if (hoverIndex <= index) {
                            hoverLabel.style.color = '#c5a059';
                            hoverLabel.style.cursor = 'pointer';
                        }
                    });
                }
            });

            label.addEventListener('mouseleave', () => {
                if (!ratingInputs[index].checked) {
                    ratingLabels.forEach((hoverLabel, hoverIndex) => {
                        hoverLabel.style.color = '#666';
                        hoverLabel.style.cursor = 'pointer';
                    });
                }
            });
        });
    }

    validateContactForm(formData) {
        const name = formData.get('full_name')?.trim();
        const email = formData.get('email_address')?.trim();
        const phone = formData.get('phone_no')?.trim();
        const message = formData.get('message')?.trim();

        
        if (!name || name.length < 2) {
            alert('Please enter a valid name (at least 2 characters).');
            return false;
        }

        if (!email || !this.isValidEmail(email)) {
            alert('Please enter a valid email address.');
            return false;
        }

        if (!phone || phone.length < 10) {
            alert('Please enter a valid phone number (at least 10 digits).');
            return false;
        }

        if (!message || message.length < 10) {
            alert('Please enter a message (at least 10 characters).');
            return false;
        }

        return true;
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
}


document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.formHandler === 'undefined') {
        window.formHandler = new FormHandler();
    }
});
