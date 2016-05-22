var $error;

$(document).ready(function() {
    $error = $('#error-log');

    /* Append Form Handler
     --Start--
    */

    $('.append-form').submit(function (e) {
        e.preventDefault();

        $error.hide();

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize()
        })
        .done(function(response) {
            handleErrorBar((response.success ? 'add' : 'remove') + 'Class', response.output);
        })
        .fail(function() {
            handleErrorBar('removeClass', ['Dogodila se neočekivana greška. Pokušajte kasnije!']);
        });
    });

    /* Append Form Handler
     --End--
    */
});

/* Error Handler
    --Start--
*/

var handleErrorBar = function(method, response) {
    var responseMarkup = '';

    $.each(response, function (i, val) {
        responseMarkup += '<div>' + val + '</div>';
    });

    if(method == 'addClass') setTimeout(function() {
        //window.location.reload();
        $.handleError(false);
    }, 2000);

    $error[method]('success').html(responseMarkup);
    $.handleError(true);
};

$.handleError = function(status) {
    if(status && $error.is(':hidden')) $error.fadeIn(500);
    else if(!status && $error.is(':visible')) $error.fadeOut(500);
};

/* Error Handler
 --End--
*/