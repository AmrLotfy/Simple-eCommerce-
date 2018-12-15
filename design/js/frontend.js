$(function () {
    'use strict';

    // switch between login and signup
    $('.login-page h1 span').click(function () {

        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.'+$(this).data('class')).fadeIn(150);

    });

    //trigger selectboxit

    $("select").selectBoxIt({

        autoWidth:false
    });

    // hold placeholder on Form Focus
    $('[placeholder]').focus(function () {

        $(this).attr('data-text' ,$(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });



    // confirmation message

    $('.confirm').click(function () {

       return confirm('Are you sure ?!');
    });

    // live create new ad

    $('.live-name').keyup(function () {
       $('.live-preview .caption h3').text($(this).val());
    });
    $('.live-desc').keyup(function () {
        $('.live-preview .caption p').text($(this).val());
    });
    $('.live-price').keyup(function () {
        $('.live-preview .price-tag').text('$'+$(this).val());
    });

});