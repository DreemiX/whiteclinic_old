jQuery(document).ready(function ($) {
    "use strict";

    //scroll to top
    $(window).scroll(function () {
        if ($(this).scrollTop() > 500) {
            $('#scroller').fadeIn().css({'transform': 'scale(1)', 'opacity': '1'});
        } else {
            $('#scroller').fadeOut().css({'transform': 'scale(2)', 'opacity': '0'});

        }
    });
    $('#scroller').on('click', function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });
    /* Sticky Top menu*/
    let $menu = $("#stickymenu");
    if($(window).width()>999){
        $(window).scroll(function(){
            if ( $(this).scrollTop() > 100 ){
                $menu.addClass("fixed animated slideInDown");
            } else if($(this).scrollTop() <= 100 && $menu.hasClass("fixed animated slideInDown")) {
                $menu.removeClass("fixed animated slideInDown");
            }
        });
    }
    /* Search Line in menu*/
    $('.open-search, .search-sbmt-close').on('click', function () {
        $('.search-block').toggleClass('opened-search');
        $('.open-search').toggleClass('opacity0');
    });
    /* Show menu container */
    $('#show').on('click', function () {
        $('.menu-container').css('top', '0');
    });
    $('#hide').on('click', function () {
        $('.menu-container').css('top', '-3000px');
    });



    /**
     * File skip-link-focus-fix.js.
     *
     * Helps with accessibility for keyboard only users.
     *
     * Learn more: https://git.io/vWdr2
     */
    ( function() {
        var isWebkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
            isOpera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
            isIe     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

        if ( ( isWebkit || isOpera || isIe ) && document.getElementById && window.addEventListener ) {
            window.addEventListener( 'hashchange', function() {
                var id = location.hash.substring( 1 ),
                    element;

                if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
                    return;
                }

                element = document.getElementById( id );

                if ( element ) {
                    if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
                        element.tabIndex = -1;
                    }

                    element.focus();
                }
            }, false );
        }
    })();

    let loaderbgr = $('.loaderbgr');
    if(loaderbgr.length){
        /* Page loading animation */
        setTimeout(function(){loaderbgr.fadeOut()}, 500);
    }
});

