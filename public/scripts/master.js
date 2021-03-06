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
            handleErrorBar((response.success ? 'add' : 'remove') + 'Class', response.output, true);
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

    /* Subject files tabs
     --Start--
     */

    var csfSubjectActive = 'csf__subject--active',
        csfSubjectShown = 'csf__subject__shown',
        csfSubjectEvents = 'webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend';

    $(document).on('click', '.csf__subject:not(.'+ csfSubjectActive +')', function (e) {
        var i = $(this).index();
        $('.' + csfSubjectActive).removeClass(csfSubjectActive);
        $(this).addClass(csfSubjectActive);

        var $content = $('#csf__files__list'),
            $last = $content.find('.' + csfSubjectShown);

        $content.find('> li').removeClass('z2').eq(i).addClass('csf__subject__shown z2').unbind(csfSubjectEvents).one(csfSubjectEvents, function() {
            $content.find('> li').not('.z2').removeClass(csfSubjectShown);
        });

        currentSubject = $(this).attr('data-id');
    });

    /* Subject files tabs
     --End--
     */

    /* Class list tabs
     --Start--
     */

    var csfClassActive = 'csf__class--active';
    $(document).on('click', '.csf__class__item:not(.'+ csfClassActive +')', function (e) {
        var i = $(this).index();
        $('.' + csfClassActive).removeClass(csfClassActive);
        $(this).addClass(csfClassActive);
        var $content = $('#csf__classes__content').find('> ul > li').hide().eq(i);
        $content.show();
        currentClass = $(this).attr('data-id');
        currentSubject = $content.find('#csf__subjects li.' + csfSubjectActive).attr('data-id');
    });

    /* Class list tabs
     --End--
     */

    /* Single class tabs
     --Start--
     */

    var scClassActive = 'class__tab__item--active';
    $(document).on('click', '#class__tab__items > li:not(.'+ csfClassActive +')', function (e) {
        var i = $(this).index();
        $('.' + scClassActive).removeClass(scClassActive);
        $(this).addClass(scClassActive);
        $('#class__tabs').find('.class__tab__content').hide().eq(i).show();
    });

    /* Single class tabs
     --End--
     */
});

/* Error Handler
    --Start--
*/

var handleErrorBar = function(method, response, refreshSuccess) {
    var responseMarkup = '';

    $.each(response, function (i, val) {
        responseMarkup += '<div>' + val + '</div>';
    });

    if(method == 'addClass') setTimeout(function() {
        if(refreshSuccess) window.location.reload();
        else $.handleError(false);
    }, 2000);
    else setTimeout(function () {
        $.handleError(false);
    }, 7000);

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