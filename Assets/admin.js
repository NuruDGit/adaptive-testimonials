/**
 * Adaptive Testimonials Admin JavaScript
 */

jQuery(document).ready(function($) {
    
    // Initialize admin functionality
    initAdmin();
    
    function initAdmin() {
        // Handle testimonial deletion
        $('.delete-testimonial').on('click', function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to delete this testimonial? This action cannot be undone.')) {
                return;
            }
            
            const $button = $(this);
            const testimonialId = $button.data('id');
            const $row = $button.closest('tr');
            
            // Disable button and show loading state
            $button.prop('disabled', true).text('Deleting...');
            
            // Send AJAX request
            $.post(atAjax.ajaxurl, {
                action: 'delete_testimonial',
                id: testimonialId,
                nonce: atAjax.nonce
            })
            .done(function(response) {
                if (response.success) {
                    // Remove row with animation
                    $row.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Check if table is empty
                        if ($('.wp-list-table tbody tr').length === 0) {
                            $('.at-list-section').append('<p>No testimonials found. Add your first one above!</p>');
                            $('.wp-list-table').remove();
                        }
                    });
                    
                    showMessage('Testimonial deleted successfully!', 'success');
                } else {
                    showMessage('Error deleting testimonial: ' + response.data, 'error');
                    $button.prop('disabled', false).text('Delete');
                }
            })
            .fail(function() {
                showMessage('Network error occurred. Please try again.', 'error');
                $button.prop('disabled', false).text('Delete');
            });
        });
        
        // Form validation and enhancement
        $('.at-form').on('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state on submit button
            const $submitBtn = $(this).find('input[type="submit"]');
            $submitBtn.val('Adding Testimonial...');
            
            // Re-enable button after form submission (in case of validation errors)
            setTimeout(() => {
                $submitBtn.val('Add Testimonial');
            }, 3000);
        });
        
        // Real-time character counter for testimonial content
        const $contentTextarea = $('#content');
        if ($contentTextarea.length) {
            const maxLength = 500; // Recommended max length
            
            $contentTextarea.after(`
                <div class="at-char-counter">
                    <span class="current">0</span> / ${maxLength} characters (recommended)
                </div>
            `);
            
            $contentTextarea.on('input', function() {
                const currentLength = $(this).val().length;
                const $counter = $('.at-char-counter .current');
                
                $counter.text(currentLength);
                
                if (currentLength > maxLength) {
                    $counter.parent().addClass('over-limit');
                } else {
                    $counter.parent().removeClass('over-limit');
                }
            });
        }
        
        // Image URL validation and preview
        $('#image_url').on('blur', function() {
            const url = $(this).val().trim();
            const $preview = $('#image-preview');
            
            // Remove existing preview
            $preview.remove();
            
            if (url && isValidImageUrl(url)) {
                $(this).after(`
                    <div id="image-preview" style="margin-top: 10px;">
                        <img src="${url}" style="max-width: 100px; max-height: 100px; border-radius: 50%; object-fit: cover;" 
                             onload="this.style.display='block'" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                        <span style="display: none; color: #d63638; font-size: 12px;">⚠ Image could not be loaded</span>
                    </div>
                `);
            }
        });
        
        // Add shortcode copy functionality
        addShortcodeCopy();
        
        // Auto-save draft functionality (optional enhancement)
        if (window.localStorage) {
            loadDraft();
            setupAutoSave();
        }
    }
    
    function validateForm() {
        let isValid = true;
        const errors = [];
        
        // Clear previous errors
        $('.at-error').removeClass('at-error');
        $('.at-error-message').remove();
        
        // Validate required fields
        const name = $('#name').val().trim();
        const content = $('#content').val().trim();
        
        if (!name) {
            $('#name').addClass('at-error');
            errors.push('Name is required');
            isValid = false;
        }
        
        if (!content) {
            $('#content').addClass('at-error');
            errors.push('Testimonial content is required');
            isValid = false;
        }
        
        // Validate content length
        if (content.length > 1000) {
            $('#content').addClass('at-error');
            errors.push('Testimonial content is too long (maximum 1000 characters)');
            isValid = false;
        }
        
        // Validate image URL if provided
        const imageUrl = $('#image_url').val().trim();
        if (imageUrl && !isValidImageUrl(imageUrl)) {
            $('#image_url').addClass('at-error');
            errors.push('Please enter a valid image URL');
            isValid = false;
        }
        
        // Display errors
        if (!isValid) {
            const errorHtml = '<div class="at-error-message notice notice-error"><p>' + errors.join('<br>') + '</p></div>';
            $('.at-form').prepend(errorHtml);
            
            // Scroll to first error
            $('html, body').animate({
                scrollTop: $('.at-error').first().offset().top - 100
            }, 500);
        }
        
        return isValid;
    }
    
    function isValidImageUrl(url) {
        const imageExtensions = /\.(jpg|jpeg|png|gif|webp|svg)(\?.*)?$/i;
        const urlPattern = /^https?:\/\/.+/;
        
        return urlPattern.test(url) && (imageExtensions.test(url) || url.includes('gravatar.com'));
    }
    
    function showMessage(message, type) {
        // Remove existing messages
        $('.at-message').remove();
        
        const messageHtml = `
            <div class="at-message ${type} notice notice-${type} is-dismissible">
                <p>${message}</p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Dismiss this notice.</span>
                </button>
            </div>
        `;
        
        $('.wrap h1').after(messageHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            $('.at-message').fadeOut();
        }, 5000);
        
        // Handle dismiss button
        $('.notice-dismiss').on('click', function() {
            $(this).parent().fadeOut();
        });
    }
    
    function addShortcodeCopy() {
        $('.at-shortcode-info p').each(function() {
            const shortcode = $(this).find('strong').next().text().trim();
            if (shortcode.startsWith('[testimonials')) {
                $(this).append(`
                    <button type="button" class="button button-small copy-shortcode" 
                            data-shortcode="${shortcode}" style="margin-left: 10px;">
                        Copy
                    </button>
                `);
            }
        });
        
        $('.copy-shortcode').on('click', function() {
            const shortcode = $(this).data('shortcode');
            const $button = $(this);
            
            // Copy to clipboard
            if (navigator.clipboard) {
                navigator.clipboard.writeText(shortcode).then(() => {
                    $button.text('Copied!');
                    setTimeout(() => $button.text('Copy'), 2000);
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = shortcode;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                $button.text('Copied!');
                setTimeout(() => $button.text('Copy'), 2000);
            }
        });
    }
    
    function loadDraft() {
        const draft = localStorage.getItem('at_testimonial_draft');
        if (draft) {
            const data = JSON.parse(draft);
            
            if (confirm('You have an unsaved draft. Would you like to restore it?')) {
                $('#name').val(data.name || '');
                $('#company').val(data.company || '');
                $('#position').val(data.position || '');
                $('#content').val(data.content || '');
                $('#rating').val(data.rating || 5);
                $('#image_url').val(data.image_url || '');
                $('#is_featured').prop('checked', data.is_featured || false);
                
                // Trigger character counter update
                $('#content').trigger('input');
            }
            
            // Clear draft after restoration or dismissal
            localStorage.removeItem('at_testimonial_draft');
        }
    }
    
    function setupAutoSave() {
        $('.at-form input, .at-form textarea, .at-form select').on('input change', debounce(function() {
            const draft = {
                name: $('#name').val(),
                company: $('#company').val(),
                position: $('#position').val(),
                content: $('#content').val(),
                rating: $('#rating').val(),
                image_url: $('#image_url').val(),
                is_featured: $('#is_featured').is(':checked')
            };
            
            // Only save if at least name or content has value
            if (draft.name.trim() || draft.content.trim()) {
                localStorage.setItem('at_testimonial_draft', JSON.stringify(draft));
            }
        }, 1000));
        
        // Clear draft on successful form submission
        $('.at-form').on('submit', function() {
            setTimeout(() => {
                localStorage.removeItem('at_testimonial_draft');
            }, 1000);
        });
    }
    
    // Utility function for debouncing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Add custom admin styles
    if (!$('#at-admin-dynamic-styles').length) {
        $('head').append(`
            <style id="at-admin-dynamic-styles">
                .at-error {
                    border-color: #d63638 !important;
                    box-shadow: 0 0 0 1px #d63638;
                }
                
                .at-char-counter {
                    font-size: 12px;
                    color: #646970;
                    margin-top: 5px;
                }
                
                .at-char-counter.over-limit {
                    color: #d63638;
                    font-weight: 600;
                }
                
                .copy-shortcode {
                    transition: all 0.2s ease;
                }
                
                .copy-shortcode:hover {
                    background: #0073aa;
                    color: white;
                    border-color: #0073aa;
                }
                
                .at-error-message {
                    margin-bottom: 15px;
                }
                
                #image-preview {
                    border: 1px solid #c3c4c7;
                    padding: 10px;
                    border-radius: 4px;
                    background: #f6f7f7;
                }
            </style>
        `);
    }
    
});