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


    /* Select subject courses control
      --Start--
     */

    var coursesSelectedAll = false;
    $('#subject__courses__checkbox__control').click(function() {
        var $el = $('#subject__courses').find('input:checkbox');

        if(!coursesSelectedAll) $(this).html('<i class="glyphicon glyphicon-unchecked"></i> Odznači sve');
        else $(this).html('<i class="glyphicon glyphicon-check"></i> Označi sve');

        coursesSelectedAll = !coursesSelectedAll;

        $el.each(function() {
            $(this)[0].checked = coursesSelectedAll;
        });
    });

    /* Select subject courses control
     --End--
     */

    /* Subject courses save
     --Start--
     */

    $('#subject__courses__save').click(function () {
        var data = {};

        $('#subject__courses').find('> li').each(function () {
            var values = [];

            $(this).find('input:checkbox').each(function () {
               values.push($(this).is(':checked'));
            });

            data[$(this).attr('data-id')] = values;
        });

        $.ajax({
            type: 'POST',
            data: {courses: data}
        })
        .done(function(response) {
            handleErrorBar((response.success ? 'add' : 'remove') + 'Class', response.output);
        })
        .fail(function() {
            handleErrorBar('removeClass', ['Dogodila se neočekivana greška. Pokušajte kasnije!']);
        });
    });

    /* Subject courses save
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