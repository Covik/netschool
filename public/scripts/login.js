var activeTabClass = 'login__tab--active',
    passwordHidden = true,
    $error = null;

$(document).ready(function() {
    $error = $('#error-log');

    $('.login__tab').click(function () {
        if($(this).hasClass(activeTabClass)) return false;

        var i = $(this).parent().index();

        $('.'+activeTabClass).removeClass(activeTabClass);
        $(this).addClass(activeTabClass);
        $('.login__tab__content').stop().slideUp(300).delay(300).eq(i).slideDown(400);
    });

    $('#registration__passsword__hider').click(function() {
        passwordHidden = !passwordHidden;
        var $el = $(this).find('> i');
        if(passwordHidden) $el.removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
        else $el.removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close');
        $('#registration__password').attr('type', passwordHidden ? 'password' : 'text');
    });

    $('.lr__form').submit(function (e) {
        e.preventDefault();

        var data = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: data,
            dataType: 'json'
        })
        .done(function(response) {
            handleErrorBar((response.success ? 'add' : 'remove') + 'Class', response.output);
        })
        .fail(function() {
            handleErrorBar('removeClass', ['Dogodila se neočekivana greška. Pokušajte kasnije!']);
        });
    });

    var handleErrorBar = function(method, response) {
        var responseMarkup = '';

        $.each(response, function (i, val) {
            responseMarkup += '<div>' + val + '</div>';
        });

        if(method == 'addClass') setTimeout(function() {
            window.location.reload();
        }, 2000);

        $error[method]('success').html(responseMarkup);
        $.handleError(true);
    }
});

$.handleError = function(status) {
    if(status && $error.is(':hidden')) $error.fadeIn(500);
    else if(!status && $error.is(':visible')) $error.fadeOut(500);
};