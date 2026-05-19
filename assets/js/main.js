/**
 * PNGCJE Theme — main.js
 * Handles: sticky header, mobile nav drawer, search overlay,
 * scroll-reveal animations, announcement bar, accordion
 */

(function($) {
    'use strict';

    /* ============================================================
       STICKY HEADER — add .scrolled class on scroll
       ============================================================ */
    const $header = $('#site-header');
    let lastScroll = 0;

    function handleHeaderScroll() {
        const currentScroll = window.pageYOffset;
        if (currentScroll > 60) {
            $header.addClass('scrolled');
        } else {
            $header.removeClass('scrolled');
        }
        lastScroll = currentScroll;
    }

    window.addEventListener('scroll', handleHeaderScroll, { passive: true });
    handleHeaderScroll(); // Run on load

    /* ============================================================
       MOBILE NAV DRAWER
       ============================================================ */
    const $mobileToggle  = $('#mobile-menu-toggle');
    const $drawer        = $('#mobile-nav-drawer');
    const $drawerOverlay = $('#mobile-nav-overlay');
    const $drawerClose   = $('#mobile-nav-close');
    let drawerOpen       = false;

    function openDrawer() {
        drawerOpen = true;
        $drawer.addClass('open').attr('aria-hidden', 'false');
        $drawerOverlay.addClass('active');
        $mobileToggle.addClass('active').attr('aria-expanded', 'true');
        $('body').css('overflow', 'hidden');
        $drawerClose.focus();
    }

    function closeDrawer() {
        drawerOpen = false;
        $drawer.removeClass('open').attr('aria-hidden', 'true');
        $drawerOverlay.removeClass('active');
        $mobileToggle.removeClass('active').attr('aria-expanded', 'false');
        $('body').css('overflow', '');
        $mobileToggle.focus();
    }

    $mobileToggle.on('click', function() {
        drawerOpen ? closeDrawer() : openDrawer();
    });

    $drawerClose.on('click', closeDrawer);
    $drawerOverlay.on('click', closeDrawer);

    // Mobile accordion for sub-menus
    $('.mobile-nav-menu .menu-item-has-children > a').on('click', function(e) {
        const $sub = $(this).next('.sub-menu');
        if ($sub.length) {
            e.preventDefault();
            const isOpen = $sub.is(':visible');
            $('.mobile-nav-menu .sub-menu').slideUp(200);
            if (!isOpen) $sub.slideDown(200);
        }
    });

    // Keyboard trap in drawer
    $drawer.on('keydown', function(e) {
        if (e.key === 'Escape') closeDrawer();
        if (e.key === 'Tab') {
            const focusable = $drawer.find('a, button, input, [tabindex]:not([tabindex="-1"])').filter(':visible');
            const first = focusable.first()[0];
            const last  = focusable.last()[0];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    });

    /* ============================================================
       SEARCH OVERLAY
       ============================================================ */
    const $searchToggle  = $('#search-toggle');
    const $searchOverlay = $('#search-overlay');
    const $searchClose   = $('#search-overlay-close');
    const $searchInput   = $('#search-input');

    function openSearch() {
        $searchOverlay.removeClass('hidden').addClass('active').removeAttr('hidden');
        $searchToggle.attr('aria-expanded', 'true');
        $('body').css('overflow', 'hidden');
        setTimeout(() => $searchInput.focus(), 100);
    }

    function closeSearch() {
        $searchOverlay.removeClass('active').attr('hidden', '');
        $searchToggle.attr('aria-expanded', 'false');
        $('body').css('overflow', '');
        $searchToggle.focus();
    }

    $searchToggle.on('click', openSearch);
    $searchClose.on('click', closeSearch);

    $searchOverlay.on('click', function(e) {
        if ($(e.target).is($searchOverlay)) closeSearch();
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            if ($searchOverlay.hasClass('active')) closeSearch();
            if (drawerOpen) closeDrawer();
        }
    });

    /* ============================================================
       ANNOUNCEMENT BAR — dismiss and remember via sessionStorage
       ============================================================ */
    const $announcement = $('#pngcje-announcement');
    const $dismiss      = $('#dismiss-announcement');

    if ($announcement.length) {
        const dismissed = sessionStorage.getItem('pngcje_announcement_dismissed');
        if (dismissed) {
            $announcement.hide();
        }
    }

    $dismiss.on('click', function() {
        $announcement.slideUp(250);
        sessionStorage.setItem('pngcje_announcement_dismissed', '1');
        // Adjust header spacer
        adjustHeaderSpacer();
    });

    function adjustHeaderSpacer() {
        const $spacer = $('.site-header-spacer');
        const headerH = $header.outerHeight();
        if ($spacer.length) $spacer.css('height', headerH + 'px');
    }

    // Recalculate spacer on resize
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(adjustHeaderSpacer, 100);
    });
    adjustHeaderSpacer();

    /* ============================================================
       SCROLL REVEAL — IntersectionObserver
       ============================================================ */
    if ('IntersectionObserver' in window) {
        const revealOpts = {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px',
        };
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, revealOpts);

        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
    } else {
        // Fallback: show all
        document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));
    }

    /* ============================================================
       COUNTER ANIMATION — stats strip
       ============================================================ */
    function animateCounter($el, target, duration = 1500) {
        const start    = 0;
        const startTime = performance.now();
        function update(currentTime) {
            const elapsed  = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const ease     = 1 - Math.pow(1 - progress, 3); // ease-out cubic
            const current  = Math.round(start + (target - start) * ease);
            $el.text(String(current));
            if (progress < 1) requestAnimationFrame(update);
        }
        requestAnimationFrame(update);
    }

    if ('IntersectionObserver' in window) {
        const statsObs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const $el = $(entry.target).find('.stats-strip__number');
                    const text = $el.text().replace(/[^0-9]/g, '');
                    if (text) animateCounter($el, parseInt(text, 10));
                    statsObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.stats-strip__item').forEach(el => statsObs.observe(el));
    }

    /* ============================================================
       HOMEPAGE HERO CAROUSEL
       ============================================================ */
    $('[data-hero-carousel]').each(function() {
        const $carousel = $(this);
        const $slides = $carousel.find('[data-hero-slide]');
        const $dots = $carousel.find('[data-hero-dot]');
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let current = 0;
        let timer = null;

        if ($slides.length <= 1) return;

        function showSlide(index) {
            current = (index + $slides.length) % $slides.length;
            $slides.removeClass('is-active').attr('aria-hidden', 'true');
            $slides.eq(current).addClass('is-active').attr('aria-hidden', 'false');
            $dots.removeClass('is-active').attr('aria-selected', 'false');
            $dots.eq(current).addClass('is-active').attr('aria-selected', 'true');
        }

        function stopAutoPlay() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        function startAutoPlay() {
            if (reduceMotion) return;
            stopAutoPlay();
            timer = setInterval(() => showSlide(current + 1), 6500);
        }

        $carousel.find('[data-hero-prev]').on('click', function() {
            showSlide(current - 1);
            startAutoPlay();
        });

        $carousel.find('[data-hero-next]').on('click', function() {
            showSlide(current + 1);
            startAutoPlay();
        });

        $dots.on('click', function() {
            showSlide(parseInt($(this).data('hero-dot'), 10));
            startAutoPlay();
        });

        $carousel.on('mouseenter focusin', stopAutoPlay);
        $carousel.on('mouseleave focusout', startAutoPlay);
        startAutoPlay();
    });

    /* ============================================================
       RESOURCES FILTER — isotope-style show/hide
       ============================================================ */
    $(document).on('click', '.filter-btn', function() {
        const filter = $(this).data('filter');
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');

        if (filter === '*') {
            $('.resource-item').show().css('opacity', 1);
        } else {
            $('.resource-item').each(function() {
                const cats = $(this).data('categories') || '';
                if (cats.includes(filter)) {
                    $(this).show().css('opacity', 1);
                } else {
                    $(this).hide();
                }
            });
        }
    });

    /* ============================================================
       SMOOTH ANCHOR SCROLL
       ============================================================ */
    $('a[href^="#"]').not('[href="#"]').on('click', function(e) {
        const target = $($(this).attr('href'));
        if (target.length) {
            e.preventDefault();
            const offset = $header.outerHeight() + 20;
            $('html, body').animate({
                scrollTop: target.offset().top - offset
            }, 500);
        }
    });

    /* ============================================================
       ACTIVE NAV ITEM — highlight current page
       ============================================================ */
    const currentPath = window.location.pathname;
    $('.primary-nav a, .mobile-nav-menu a').each(function() {
        try {
            const linkPath = new URL($(this).attr('href'), window.location.origin).pathname;
            if (currentPath === linkPath || (linkPath !== '/' && currentPath.startsWith(linkPath))) {
                $(this).closest('li').addClass('current-menu-item');
            }
        } catch(e) {}
    });

    /* ============================================================
       VIDEO MESSAGE LIGHTBOX — for Chief Justice / Judge video links
       ============================================================ */
    $(document).on('click', 'a[href$=".mp4"]', function(e) {
        e.preventDefault();
        const videoSrc = $(this).attr('href');
        const $modal = $('<div>', {
            class: 'video-modal',
            style: 'position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:9999;display:flex;align-items:center;justify-content:center;',
            role: 'dialog',
            'aria-modal': 'true',
            'aria-label': 'Video',
        });
        const $video = $('<video>', {
            src: videoSrc,
            controls: true,
            autoplay: true,
            style: 'max-width:min(900px,90vw);max-height:80vh;border-radius:8px;',
        });
        const $close = $('<button>', {
            text: '×',
            style: 'position:absolute;top:1.5rem;right:1.5rem;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;line-height:1;',
            'aria-label': 'Close video',
        });
        $modal.append($video, $close);
        $('body').append($modal).css('overflow', 'hidden');
        $close.focus();

        $close.on('click', closeModal);
        $modal.on('click', function(e) { if ($(e.target).is($modal)) closeModal(); });
        $(document).on('keydown.videoModal', function(e) { if (e.key === 'Escape') closeModal(); });

        function closeModal() {
            $video[0].pause();
            $modal.remove();
            $('body').css('overflow', '');
            $(document).off('keydown.videoModal');
        }
    });

})(jQuery);
