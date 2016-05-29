$(document).ready(function () {
    $('#file__upload__button').click(function () {
        $('#hiddenfileinput').click();
    });

    $('#hiddenfileinput').change(function (e) {
        $(this.files).each(function(i, file) {
           fu.files.push(file);
        });
        $.openFileUpload();
    });

    $(document).on('click', '.file__upload__remove', function () {
        var i = $(this).parent().index();
        fu.files.splice(i, 1);
        $(this).parent().remove();
        $('#file__upload__count').html(fu.files.length);

        if(fu.files.length < 1) $.closeFileUpload(false);
    })
    .on('click', '#file__upload__more', function () {
        $('#hiddenfileinput').click();
    })
    .on('click', '#file__upload__cancel', function () {
       $.closeFileUpload();
    })
    .on('click', '#file__upload__start', function () {
        if(fu.startedUpload !== false) return false;
        $.uploadFile(0);
    });
});

var fu = {
    opened: false,
    files: [],
    successful: 0,
    failed: 0,
    startedUpload: false
};

$.openFileUpload = function () {
    var filesMarkup = '';

    $(fu.files).each(function (i, file) {
        filesMarkup += '<li>'+ file.name +' <span class="file__upload__remove"><i class="glyphicon glyphicon-remove"></i></span><div class="file__upload__progress"></div></li>';
    });

    if(fu.opened) {
        $('#file__upload__list').empty().append(filesMarkup);
        $('#file__upload__count').html(fu.files.length);
        return this;
    }

    $('body').append('<div id="file__upload__overlay">'+
        '<div id="file__upload">'+
            '<h1>Prijenos datoteka u predmet "'+ $('.csf__subject[data-id="'+ currentSubject +'"]').find('.csf__subject__name').text().toLowerCase() +'": <span id="file__upload__count">'+ fu.files.length +'</span></h1>'+
            '<ul id="file__upload__list">'+ filesMarkup +'</ul>'+
            '<div id="file__upload__actions">'+
                '<button id="file__upload__start"><i class="glyphicon glyphicon-ok"></i> Započni</button>'+
                ' <button id="file__upload__cancel"><i class="glyphicon glyphicon-remove"></i> Odustani</button> '+
                '<button id="file__upload__more"><i class="glyphicon glyphicon-plus-sign"></i> Dodaj još</button>'+
            '</div>'
        +'</div>'
    +'</div>');

    fu.opened = true;

    return this;
};

$.closeFileUpload = function (withLog) {
    if(!fu.opened) return this;

    $('#file__upload__overlay').fadeOut(500, function () {
        $(this).remove();
    });

    if(withLog) handleErrorBar('addClass', ['Uspješno preneseno: ' + fu.successful, 'Ne uspješno preneseno: ' + fu.failed]);

    fu.files = [];
    fu.successful = 0;
    fu.failed = 0;
    fu.startedUpload = false;
    fu.xhr = null;
    fu.opened = false;

    return this;
};

$.uploadFile = function (i) {
    if(!fu.opened || fu.files.length < 1 || fu.files[i] == null) return this;

    fu.startedUpload = true;

    $('#file__upload__start').attr('disabled', true);

    var $item = $('#file__upload__list').find('> li:eq('+ i +')').find('.file__upload__progress');

    var handleProgress = function (e) {
        if(e.lengthComputable) {
            var percent = (e.loaded / e.total) * 100;
            $item.width(percent + '%');
        }
    };


    /* Start */
    var formdata = new FormData(),
        file = fu.files[i];

    formdata.append('file', file, file.name);
    formdata.append('class', currentClass);
    formdata.append('subject', currentSubject);

    $.ajax({
        url: '/files/store',
        type: 'POST',
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){
                myXhr.upload.addEventListener('progress', handleProgress, false);
            }
            return myXhr;
        }
    })
    .done(function (response) {
        if(response.success) {
            fu.successful ++;
            $item.addClass('success');

            var $targetClass = $('.csf__class[data-id="'+ currentClass +'"]'),
                $subjectCount = $targetClass.find('.csf__subject[data-id="'+ currentSubject +'"] .csf__subject__count > div'),
                $targetSubject = $targetClass.find('.csf__subject__content[data-id="'+ currentSubject +'"] tbody');

            if($targetSubject.find('.table__empty').length > 0) $targetSubject.find('.table__empty').remove();

            $targetSubject.append(response.html);

            $subjectCount.text(parseInt($subjectCount.text(), 10) + 1);
        }
        else {
            fu.failed ++;
            $item.addClass('error');
        }
    })
    .fail(function () {
        fu.failed ++;
        $item.addClass('error');
    })
    .always(function () {
        if(i == fu.files.length - 1) {
            setTimeout(function() {
                $.closeFileUpload(true);
            }, 2000);
            return this;
        }

        i ++;
        $.uploadFile(i);
    });
};