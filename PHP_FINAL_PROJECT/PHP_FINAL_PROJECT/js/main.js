/**
 * Main JavaScript file for PHP CRUD Application
 * Contains utility functions and interactive features
 */

// DOM Content Loaded Event
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize form validation
    initializeFormValidation();
    
    // Initialize image preview
    initializeImagePreview();
    
    // Initialize confirmation dialogs
    initializeConfirmationDialogs();
    
    // Initialize auto-hide alerts
    initializeAutoHideAlerts();
    
    // Initialize smooth scrolling
    initializeSmoothScrolling();
    
    console.log('PHP CRUD App initialized successfully');
});

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Initialize form validation with Bootstrap classes
 */
function initializeFormValidation() {
    // Get all forms with validation
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Focus on first invalid field
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
            
            form.classList.add('was-validated');
        });
    });
    
    // Real-time validation for password confirmation
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm_password');
    
    if (passwordField && confirmPasswordField) {
        function validatePasswords() {
            if (confirmPasswordField.value !== passwordField.value) {
                confirmPasswordField.setCustomValidity('Passwords do not match');
            } else {
                confirmPasswordField.setCustomValidity('');
            }
        }
        
        passwordField.addEventListener('input', validatePasswords);
        confirmPasswordField.addEventListener('input', validatePasswords);
    }
}

/**
 * Initialize image preview functionality
 */
function initializeImagePreview() {
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    
    imageInputs.forEach(input => {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    showAlert('File size must be less than 2MB', 'danger');
                    input.value = '';
                    return;
                }
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    showAlert('Please select a valid image file', 'danger');
                    input.value = '';
                    return;
                }
                
                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Find or create preview element
                    let preview = document.getElementById(input.id + '_preview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = input.id + '_preview';
                        preview.className = 'img-thumbnail mt-2';
                        preview.style.maxWidth = '150px';
                        preview.style.maxHeight = '150px';
                        input.parentNode.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
}

/**
 * Initialize confirmation dialogs for delete actions
 */
function initializeConfirmationDialogs() {
    const deleteLinks = document.querySelectorAll('a[href*="delete"], .btn-danger[href]');
    
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            if (!link.hasAttribute('data-confirmed')) {
                event.preventDefault();
                
                const message = link.dataset.confirmMessage || 'Are you sure you want to delete this item? This action cannot be undone.';
                
                if (confirm(message)) {
                    link.setAttribute('data-confirmed', 'true');
                    link.click();
                }
            }
        });
    });
}

/**
 * Initialize auto-hide alerts
 */
function initializeAutoHideAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-danger)');
    
    alerts.forEach(alert => {
        // Auto-hide success alerts after 5 seconds
        if (alert.classList.contains('alert-success')) {
            setTimeout(() => {
                fadeOut(alert);
            }, 5000);
        }
    });
}

/**
 * Initialize smooth scrolling for anchor links
 */
function initializeSmoothScrolling() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                event.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/**
 * Utility function to show alerts
 */
function showAlert(message, type = 'info', duration = 5000) {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertContainer.style.top = '20px';
    alertContainer.style.right = '20px';
    alertContainer.style.zIndex = '9999';
    alertContainer.style.minWidth = '300px';
    
    alertContainer.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertContainer);
    
    // Auto-remove after duration
    if (duration > 0) {
        setTimeout(() => {
            if (alertContainer.parentNode) {
                fadeOut(alertContainer);
            }
        }, duration);
    }
}

/**
 * Utility function to fade out elements
 */
function fadeOut(element) {
    element.style.transition = 'opacity 0.5s ease-out';
    element.style.opacity = '0';
    
    setTimeout(() => {
        if (element.parentNode) {
            element.parentNode.removeChild(element);
        }
    }, 500);
}

/**
 * Loading spinner utility
 */
function showLoadingSpinner(element) {
    const spinner = document.createElement('span');
    spinner.className = 'spinner me-2';
    spinner.setAttribute('role', 'status');
    
    element.insertBefore(spinner, element.firstChild);
    element.disabled = true;
}

function hideLoadingSpinner(element) {
    const spinner = element.querySelector('.spinner');
    if (spinner) {
        spinner.remove();
    }
    element.disabled = false;
}

/**
 * Form submission with loading state
 */
function handleFormSubmission() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && form.checkValidity()) {
                showLoadingSpinner(submitBtn);
                
                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    hideLoadingSpinner(submitBtn);
                }, 10000);
            }
        });
    });
}

/**
 * Initialize search functionality
 */
function initializeSearch() {
    const searchInputs = document.querySelectorAll('[data-search]');
    
    searchInputs.forEach(input => {
        const targetSelector = input.dataset.search;
        const targetElements = document.querySelectorAll(targetSelector);
        
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            targetElements.forEach(element => {
                const text = element.textContent.toLowerCase();
                const shouldShow = text.includes(searchTerm);
                element.style.display = shouldShow ? '' : 'none';
            });
        });
    });
}

/**
 * Initialize character counter for textareas
 */
function initializeCharacterCounter() {
    const textareas = document.querySelectorAll('textarea[maxlength]');
    
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');
        const counter = document.createElement('small');
        counter.className = 'text-muted float-end';
        textarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${remaining} characters remaining`;
            
            if (remaining < 50) {
                counter.className = 'text-warning float-end';
            } else if (remaining < 20) {
                counter.className = 'text-danger float-end';
            } else {
                counter.className = 'text-muted float-end';
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter(); // Initial call
    });
}

/**
 * Utility function to format file sizes
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Initialize file size display
 */
function initializeFileSizeDisplay() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                let sizeDisplay = document.getElementById(this.id + '_size');
                if (!sizeDisplay) {
                    sizeDisplay = document.createElement('small');
                    sizeDisplay.id = this.id + '_size';
                    sizeDisplay.className = 'text-muted d-block mt-1';
                    this.parentNode.appendChild(sizeDisplay);
                }
                sizeDisplay.textContent = `File size: ${formatFileSize(file.size)}`;
            }
        });
    });
}

// Initialize additional features when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    handleFormSubmission();
    initializeSearch();
    initializeCharacterCounter();
    initializeFileSizeDisplay();
});
