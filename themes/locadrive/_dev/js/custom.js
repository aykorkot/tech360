import $ from 'jquery';
import './validation';
import './sliders';
import './components/_video'

$(document).ready(() => {

    // Search filter
    $(document).on('click', '#search_filter_toggler', () => {
        $('body').addClass('hidden');
        $('#search_filters_wrapper').addClass('active');
    });

    $(document).on('click', '#search_filters_close', () => {
        $('#search_filters_wrapper').removeClass('active');
        $('body').removeClass('hidden');
    });

    // $(document).on('click', '.facet-title', function () {
    //     $(this).toggleClass('active');
    //     $(this).parent().find('.facet-content').slideToggle();
    // });

    // Header Search
    $(document).on('click', '.search-widgets .header__tools--link', () => {
        $('.search-widgets__popup').toggleClass('active');
    });

    // Sticky menu
    var header = $('#header');
    var backToTop = $('.back__toTop');
    // $(window).scroll(function() {
    //     var scroll = $(this).scrollTop();
    //     if (scroll >= 200) {
    //         header.addClass("sticky");
    //         backToTop.addClass("is-visible");
    //     } else {
    //         header.removeClass("sticky");
    //         backToTop.removeClass("is-visible");
    //     }
    // });

    // Back To Top
    $(document).on('click', '.backToTop', function (ev) {
        ev.preventDefault();
        $('html, body').animate({
            scrollTop: 0,
        }, 700);
    });

    // Move element on resize
    const mq = window.matchMedia( "(min-width: 992px)" );
    const headerNav = $('.header__nav');
    const headerMenu = $('.header__menu');
    const headerMenuRight = $('.header__menu--menu');
    const currencySelector = $('.currency-selector');
    function checkResize () {
        if (mq.matches) {
            // window width >= 992px
            headerMenu.append(headerMenuRight);
            headerMenu.append(currencySelector);
        } else {
            // window width < 992px
            headerNav.append(headerMenuRight);
            headerNav.append(currencySelector);
        }
    }
    checkResize();


    // Header menu - Handle click event for showing submenu
    $(document).on("click", "#header .ets_mm_megamenu .mm_menus_li.mm_has_sub > a", function (e) {
        e.preventDefault();
        $(this).next().slideToggle();
    });

    // Menu
    $(document).on('click', '.header__toggle--button', () => {
        $('.header__toggle--button').toggleClass('active');
        $('.header__nav').toggleClass('menu-active');
    });

    // Accordions
    $(document).on('click', '.accordion__title', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active').next().slideUp();
        } else {
            $(this).addClass('active').next().slideDown();
        }
    });

    // Back To Top
    $(document).on('click', '.back__toTop', function (e) {
        e.preventDefault();
        console.log('Back__toTop clicked!');
        $('html,body').animate({
            scrollTop: 0
        }, 1000);
    });
    
    $(document).on("click", ".notification__close button", function(evt) {
        evt.preventDefault();
        $(this).parents('.notification__item').removeClass('active');
    });

    $(document).on("click", ".popup__show", function () {
        const getPopupID = $(this).attr('data-popup');
        $("body").addClass("hidden");
        $(`#${getPopupID}`).addClass('active');
    });

    $(document).on("click", ".popup__close", function () {
        $("body").removeClass("hidden");
        $(this).parents(".popup").removeClass("active");
    });

    $('.c-title-description').each(function () {
        const $container = $(this);
        const $paragraph = $container.find('p').first();
        const $toggle = $container.find('.toggle-more');
    
        const lineHeight = parseFloat($paragraph.css('line-height')) || 24; // fallback 24px
        const maxVisibleHeight = lineHeight * 3;
    
        if ($paragraph[0].scrollHeight <= maxVisibleHeight + 1) {
          $toggle.hide(); // cacher si le texte tient sur 3 lignes ou moins
        }
    
        $toggle.click(function (e) {
          e.preventDefault();
          $paragraph.toggleClass('expanded');
          $toggle.text($paragraph.hasClass('expanded') ? '- Voir moins' : '+ Voir plus');
        });
      });

});