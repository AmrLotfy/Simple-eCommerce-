$(function () {
    'use strict';
    // dashboard

    $('.toggle-info').click(function () {
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(250);

        if($(this).hasClass('selected')){
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }
        else{
            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }
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

    // convert password to text on hover

    var passfield = $('.password');

    $('.show-pass').hover(function(){

        passfield.attr('type' , 'text');
    }, function () {

        passfield.attr('type' , 'password');
    });

    // confirmation message

    $('.confirm').click(function () {

       return confirm('Are you sure ?!');
    });
});
