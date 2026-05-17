/**
 * assets/js/popups.js
 * PNGCJE Custom Popup Engine
 * Handles: all trigger types, frequency cookies, animations, accessibility
 */
(function ($) {
    'use strict';

    if (typeof pngcjePopups === 'undefined' || !pngcjePopups.length) return;

    var COOKIE_PREFIX = 'pngcje_popup_';
    var triggered     = {};

    // ============================================================
    // COOKIE HELPERS
    // ============================================================
    function setCookie(name, value, days) {
        var expires = '';
        if (days) {
            var d = new Date();
            d.setTime(d.getTime() + (days * 86400000));
            expires = '; expires=' + d.toUTCString();
        }
        document.cookie = COOKIE_PREFIX + name + '=' + value + expires + '; path=/; SameSite=Lax';
    }
    function getCookie(name) {
        var val = '; ' + document.cookie;
        var parts = val.split('; ' + COOKIE_PREFIX + name + '=');
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    // ============================================================
    // FREQUENCY CHECK — should this popup show?
    // ============================================================
    function shouldShow(popup) {
        var id = popup.id;
        var freq = popup.frequency;

        switch (freq) {
            case 'always':
                return true;
            case 'session':
                return !sessionStorage.getItem(COOKIE_PREFIX + id);
            case 'daily':
                var last = getCookie(id + '_daily');
                if (!last) return true;
                return (Date.now() - parseInt(last, 10)) > 86400000;
            case 'weekly':
                var lastW = getCookie(id + '_weekly');
                if (!lastW) return true;
                return (Date.now() - parseInt(lastW, 10)) > 604800000;
            case 'once':
                return !getCookie(id + '_once');
            default:
                return !sessionStorage.getItem(COOKIE_PREFIX + id);
        }
    }

    // ============================================================
    // MARK AS SHOWN
    // ============================================================
    function markShown(popup) {
        var id   = popup.id;
        var freq = popup.frequency;
        switch (freq) {
            case 'session': sessionStorage.setItem(COOKIE_PREFIX + id, '1'); break;
            case 'daily':   setCookie(id + '_daily',  Date.now(), 1); break;
            case 'weekly':  setCookie(id + '_weekly', Date.now(), 7); break;
            case 'once':    setCookie(id + '_once',   '1', 365); break;
        }
    }

    // ============================================================
    // OPEN POPUP
    // ============================================================
    function popupUsesFixedHeight(popup) {
        return popup && popup.height && String(popup.height).toLowerCase() !== 'auto';
    }

    function openPopup(id) {
        var $popup   = $('#pngcje-popup-' + id);
        var $overlay = $('#pngcje-overlay-' + id);
        if (!$popup.length) return;
        var popupConfig = pngcjePopups.find(function (p) { return p.id == id; });

        $('body').addClass('pngcje-popup-open');
        $overlay.addClass('active').attr('aria-hidden', 'false');
        $popup.addClass('active').attr('aria-hidden', 'false');
        if (popupUsesFixedHeight(popupConfig)) {
            $popup.addClass('pngcje-popup--fit-bounds');
        }

        // Focus trap
        setTimeout(function () {
            var $focusable = $popup.find('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])').filter(':visible');
            if ($focusable.length) $focusable.first().focus();
        }, 100);

        // Keyboard trap
        $popup.on('keydown.popup', function (e) {
            if (e.key === 'Escape') {
                closePopup(id);
                return;
            }
            if (e.key === 'Tab') {
                var $f = $popup.find('a, button, input, textarea, select').filter(':visible');
                var first = $f.first()[0];
                var last  = $f.last()[0];
                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault(); last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault(); first.focus();
                }
            }
        });

        triggered[id] = true;
    }

    // ============================================================
    // CLOSE POPUP
    // ============================================================
    function closePopup(id) {
        var $popup   = $('#pngcje-popup-' + id);
        var $overlay = $('#pngcje-overlay-' + id);

        $popup.removeClass('active').attr('aria-hidden', 'true');
        $overlay.removeClass('active').attr('aria-hidden', 'true');
        $popup.removeClass('pngcje-popup--fit-bounds');
        $popup.off('keydown.popup');

        setTimeout(function () {
            $('body').removeClass('pngcje-popup-open');
        }, 350);
    }

    // ============================================================
    // INIT EACH POPUP
    // ============================================================
    $.each(pngcjePopups, function (i, popup) {
        if (!shouldShow(popup)) return;

        switch (popup.trigger) {

            case 'page_load':
                setTimeout(function () {
                    if (!triggered[popup.id]) {
                        openPopup(popup.id);
                        markShown(popup);
                    }
                }, popup.delay || 2000);
                break;

            case 'exit_intent':
                $(document).one('mouseleave', function (e) {
                    if (e.clientY <= 0 && !triggered[popup.id]) {
                        openPopup(popup.id);
                        markShown(popup);
                    }
                });
                break;

            case 'scroll_depth':
                var depth    = popup.scrollDepth || 50;
                var $doc     = $(document);
                var $win     = $(window);
                var scrollFn = function () {
                    var scrolled = ($win.scrollTop() + $win.height()) / $doc.height() * 100;
                    if (scrolled >= depth && !triggered[popup.id]) {
                        openPopup(popup.id);
                        markShown(popup);
                        $win.off('scroll.popup' + popup.id);
                    }
                };
                $win.on('scroll.popup' + popup.id, scrollFn);
                break;

            case 'on_click':
                if (popup.clickSelector) {
                    $(document).on('click', popup.clickSelector, function (e) {
                        e.preventDefault();
                        openPopup(popup.id);
                        markShown(popup);
                    });
                }
                break;

            case 'manual':
                // Triggered by [pngcje_popup_trigger] shortcode or data-open-popup attribute
                break;
        }
    });

    // ============================================================
    // GLOBAL CLOSE HANDLERS
    // ============================================================
    $(document).on('click', '.pngcje-popup__close', function () {
        closePopup($(this).data('popup'));
    });

    $(document).on('click', '.pngcje-popup-overlay', function () {
        var id = $(this).data('popup');
        // Check closeOnOverlay setting
        var popup = pngcjePopups.find(function (p) { return p.id == id; });
        if (popup && popup.closeOnOverlay) closePopup(id);
    });

    // Manual trigger via data-open-popup attribute
    $(document).on('click', '[data-open-popup]', function (e) {
        e.preventDefault();
        openPopup($(this).data('open-popup'));
    });

    // Global Escape key
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            $('.pngcje-popup.active').each(function () {
                closePopup($(this).data('popup-id'));
            });
        }
    });

})(jQuery);
