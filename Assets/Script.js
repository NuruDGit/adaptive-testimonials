/**
 * Adaptive Testimonials Frontend JavaScript
 */

jQuery(document).ready(function($) {
    
    // Initialize testimonials functionality
    initTestimonials();
    
    function initTestimonials() {
        // Add intersection observer for animations
        if ('IntersectionObserver' in window) {
            const testimonialObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('at-visible');
                    }
                });
            }, {
                rootMargin: '50px 0px -50px 0px'
            });
            
            // Observe all testimonials
            $('.at-testimonial').each(function() {
                testimonialObserver.observe(this);
            });
        }
        
        // Handle testimonial interactions
        $('.at-testimonial').on('click', function(e) {
            // Add click analytics or other interactions here
            $(this).addClass('at-clicked');
            setTimeout(() => {
                $(this).removeClass('at-clicked');
            }, 300);
        });
        
        // Keyboard accessibility
        $('.at-testimonial').on('keypress', function(e) {
            if (e.which === 13 || e.which === 32) { // Enter or Space
                e.preventDefault();
                $(this).trigger('click');
            }
        });
        
        // Touch/swipe support for mobile (basic implementation)
        let startX = 0;
        let startY = 0;
        
        $('.at-testimonials').on('touchstart', function(e) {
            const touch = e.originalEvent.touches[0];
            startX = touch.clientX;
            startY = touch.clientY;
        });
        
        $('.at-testimonials').on('touchend', function(e) {
            if (!startX || !startY) return;
            
            const touch = e.originalEvent.changedTouches[0];
            const diffX = startX - touch.clientX;
            const diffY = startY - touch.clientY;
            
            // Reset values
            startX = 0;
            startY = 0;
            
            // Handle swipe gestures (basic implementation)
            if (Math.abs(diffX) > Math.abs(diffY)) {
                if (Math.abs(diffX) > 50) { // Minimum swipe distance
                    if (diffX > 0) {
                        // Swipe left - could implement carousel navigation
                        $(this).trigger('swipeleft');
                    } else {
                        // Swipe right - could implement carousel navigation
                        $(this).trigger('swiperight');
                    }
                }
            }
        });
        
        // Auto-resize testimonial text for better readability
        autoResizeTestimonials();
        
        // Re-check on window resize
        $(window).on('resize', debounce(autoResizeTestimonials, 250));
    }
    
    function autoResizeTestimonials() {
        $('.at-testimonial-text').each(function() {
            const $this = $(this);
            const maxHeight = 150; // Maximum height before truncating
            
            if (this.scrollHeight > maxHeight) {
                $this.addClass('at-long-text');
                
                // Add read more functionality if text is too long
                if (!$this.next('.at-read-more').length) {
                    const originalText = $this.text();
                    const truncatedText = originalText.substring(0, 150) + '...';
                    
                    $this.text(truncatedText);
                    $this.after('<button class="at-read-more" type="button">Read More</button>');
                    
                    $this.next('.at-read-more').on('click', function() {
                        if ($this.hasClass('at-expanded')) {
                            $this.text(truncatedText);
                            $(this).text('Read More');
                            $this.removeClass('at-expanded');
                        } else {
                            $this.text(originalText);
                            $(this).text('Read Less');
                            $this.addClass('at-expanded');
                        }
                    });
                }
            }
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
    
    // Add custom CSS for dynamic elements
    if (!$('#at-dynamic-styles').length) {
        $('head').append(`
            <style id="at-dynamic-styles">
                .at-testimonial.at-clicked {
                    transform: scale(0.98);
                }
                
                .at-long-text {
                    position: relative;
                }
                
                .at-read-more {
                    background: none;
                    border: none;
                    color: var(--wp--preset--color--primary, #0073aa);
                    cursor: pointer;
                    font-size: 0.9em;
                    text-decoration: underline;
                    margin-top: 0.5rem;
                    display: block;
                    padding: 0;
                }
                
                .at-read-more:hover {
                    color: var(--wp--preset--color--primary-hover, #005a87);
                }
                
                .at-visible {
                    opacity: 1 !important;
                    transform: translateY(0) !important;
                }
                
                @media (prefers-reduced-motion: reduce) {
                    .at-testimonial.at-clicked {
                        transform: none;
                    }
                }
            </style>
        `);
    }
    
    // Accessibility improvements
    $('.at-testimonial').attr('role', 'article');
    $('.at-testimonial').attr('tabindex', '0');
    $('.at-testimonial-text').attr('role', 'blockquote');
    
    // Add ARIA labels for screen readers
    $('.at-testimonial-rating .at-star').each(function(index, star) {
        const rating = $(star).parent().find('.at-star').length;
        if (index === 0) {
            $(star).parent().attr('aria-label', `Rating: ${rating} out of 5 stars`);
        }
    });
    
});