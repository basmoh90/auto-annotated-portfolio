/**
 * Arabic Local Services Theme JavaScript
 * 
 * @package Arabic_Local_Services
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        
        // Mobile menu toggle
        $('.menu-toggle').on('click', function() {
            $(this).toggleClass('active');
            $('.main-navigation ul').toggleClass('active');
        });

        // FAQ Accordion
        $('.faq-question').on('click', function() {
            var $answer = $(this).next('.faq-answer');
            var $item = $(this).parent('.faq-item');
            
            // Close other open items
            $('.faq-item').not($item).removeClass('active');
            $('.faq-answer').not($answer).removeClass('active');
            
            // Toggle current item
            $item.toggleClass('active');
            $answer.toggleClass('active');
        });

        // Smooth scrolling for anchor links
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            var target = $(this.getAttribute('href'));
            if (target.length) {
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
            }
        });

        // Contact form submission
        $('#contact-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            var originalText = $submitBtn.text();
            
            // Show loading state
            $submitBtn.text('جاري الإرسال...').prop('disabled', true);
            
            // Collect form data
            var formData = {
                action: 'contact_form',
                nonce: arabic_local_services_ajax.nonce,
                name: $form.find('#name').val(),
                email: $form.find('#email').val(),
                phone: $form.find('#phone').val(),
                service: $form.find('#service').val(),
                message: $form.find('#message').val()
            };
            
            // Send AJAX request
            $.ajax({
                url: arabic_local_services_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showNotification(response.data, 'success');
                        $form[0].reset();
                    } else {
                        showNotification(response.data, 'error');
                    }
                },
                error: function() {
                    showNotification('حدث خطأ في إرسال الطلب. يرجى المحاولة مرة أخرى.', 'error');
                },
                complete: function() {
                    $submitBtn.text(originalText).prop('disabled', false);
                }
            });
        });

        // Lazy loading animation
        function animateOnScroll() {
            $('.loading').each(function() {
                var $element = $(this);
                var elementTop = $element.offset().top;
                var elementBottom = elementTop + $element.outerHeight();
                var viewportTop = $(window).scrollTop();
                var viewportBottom = viewportTop + $(window).height();
                
                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $element.addClass('loaded');
                }
            });
        }

        // Initialize animations
        animateOnScroll();
        $(window).on('scroll', animateOnScroll);

        // Service card hover effects
        $('.service-card').on('mouseenter', function() {
            $(this).addClass('hovered');
        }).on('mouseleave', function() {
            $(this).removeClass('hovered');
        });

        // Testimonial slider (if multiple testimonials)
        if ($('.testimonial-card').length > 3) {
            initTestimonialSlider();
        }

        // WhatsApp button animation
        $('.whatsapp-button').on('mouseenter', function() {
            $(this).addClass('pulse');
        }).on('mouseleave', function() {
            $(this).removeClass('pulse');
        });

        // Call button animation
        $('.call-button').on('mouseenter', function() {
            $(this).addClass('pulse');
        }).on('mouseleave', function() {
            $(this).removeClass('pulse');
        });

        // Back to top button
        var $backToTop = $('<a href="#" class="back-to-top" aria-label="العودة للأعلى"><i class="fas fa-chevron-up"></i></a>');
        $('body').append($backToTop);
        
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                $backToTop.addClass('visible');
            } else {
                $backToTop.removeClass('visible');
            }
        });
        
        $backToTop.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 800);
        });

        // Search functionality
        $('.search-toggle').on('click', function(e) {
            e.preventDefault();
            $('.search-form').toggleClass('active');
            if ($('.search-form').hasClass('active')) {
                $('.search-form input[type="search"]').focus();
            }
        });

        // Close search on escape key
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27) { // Escape key
                $('.search-form').removeClass('active');
            }
        });

        // Form validation
        $('input[required], textarea[required]').on('blur', function() {
            validateField($(this));
        });

        // Auto-hide notifications
        setTimeout(function() {
            $('.notification').fadeOut();
        }, 5000);

    });

    // Helper functions
    function showNotification(message, type) {
        var $notification = $('<div class="notification notification-' + type + '">' + message + '</div>');
        $('body').append($notification);
        
        // Show notification
        setTimeout(function() {
            $notification.addClass('show');
        }, 100);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 5000);
    }

    function validateField($field) {
        var value = $field.val().trim();
        var isValid = true;
        var errorMessage = '';
        
        // Remove existing error
        $field.removeClass('error').siblings('.error-message').remove();
        
        // Check if required field is empty
        if ($field.prop('required') && !value) {
            isValid = false;
            errorMessage = 'هذا الحقل مطلوب';
        }
        
        // Validate email
        if ($field.attr('type') === 'email' && value) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'يرجى إدخال بريد إلكتروني صحيح';
            }
        }
        
        // Validate phone
        if ($field.attr('type') === 'tel' && value) {
            var phoneRegex = /^[\+]?[0-9\s\-\(\)]{8,}$/;
            if (!phoneRegex.test(value)) {
                isValid = false;
                errorMessage = 'يرجى إدخال رقم هاتف صحيح';
            }
        }
        
        // Show error if invalid
        if (!isValid) {
            $field.addClass('error');
            $field.after('<span class="error-message">' + errorMessage + '</span>');
        }
        
        return isValid;
    }

    function initTestimonialSlider() {
        var $testimonials = $('.testimonial-card');
        var currentIndex = 0;
        var totalTestimonials = $testimonials.length;
        
        // Create navigation dots
        var $dots = $('<div class="testimonial-dots"></div>');
        for (var i = 0; i < totalTestimonials; i++) {
            $dots.append('<span class="dot' + (i === 0 ? ' active' : '') + '" data-index="' + i + '"></span>');
        }
        $('.testimonials-section .container').append($dots);
        
        // Show first 3 testimonials
        $testimonials.hide();
        $testimonials.slice(0, 3).show();
        
        // Dot click handler
        $dots.on('click', '.dot', function() {
            var index = $(this).data('index');
            showTestimonials(index);
        });
        
        function showTestimonials(startIndex) {
            $testimonials.hide();
            $testimonials.slice(startIndex, startIndex + 3).show();
            $dots.find('.dot').removeClass('active');
            $dots.find('.dot[data-index="' + startIndex + '"]').addClass('active');
        }
        
        // Auto-advance every 5 seconds
        setInterval(function() {
            currentIndex = (currentIndex + 1) % totalTestimonials;
            showTestimonials(currentIndex);
        }, 5000);
    }

    // Performance optimization: Debounce scroll events
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    // Apply debouncing to scroll events
    $(window).on('scroll', debounce(function() {
        // Scroll-based animations and effects
    }, 10));

    // Preload critical images
    function preloadImages() {
        var images = [
            'assets/images/about-us.jpg',
            'assets/images/hero-bg.jpg'
        ];
        
        images.forEach(function(src) {
            var img = new Image();
            img.src = src;
        });
    }

    // Initialize preloading
    preloadImages();

    // Service worker registration (if available)
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js')
                .then(function(registration) {
                    console.log('ServiceWorker registration successful');
                })
                .catch(function(err) {
                    console.log('ServiceWorker registration failed');
                });
        });
    }

})(jQuery);