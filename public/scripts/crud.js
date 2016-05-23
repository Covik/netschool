

(function($) {

    var methods = {
        init: function(options) {

            if(typeof this.crudInstance !== 'undefined') return false;

            var o = $.extend($.fn.crud.defaults, options);

            this.crudInstance = new CRUD(this, o);
        }
    };

    $.fn.crud = function(method) {
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.crud' );
        }
    };

    $.fn.crud.defaults = {
        def: [],
        update: true,
        delete: true,
        updateOptions: {
            url: null,
            type: null,
            element: null
        },
        deleteOptions: {
            url: null,
            type: null,
            element: null
        }
    };

    function CRUD(c, o) {
        this.container = c;
        this.options = o;
        this.props = {
            currentUpdate: {
                inProgress: false,
                actionsHTML: '',
                id: null,
                attributes: {}
            },
            deleteInProgress: false
        };

        this.init();
    }

    CRUD.prototype = {
        init: function() {
            this.events();

            return this;
        },
        events: function () {
            var o = this.options,
                parent = this;

            $(document).on('click', o.updateOptions.element, function (e) {
                e.preventDefault();
                parent._updateStart($(this));
            });

            $(document).on('click', '.crud-cancel-update', function (e) {
                e.preventDefault();
                parent._updateReset($(this));
            });

            $(document).on('click', '.crud-save-update', function (e) {
                e.preventDefault();
                parent._updateSave($(this));
            });


            $(document).on('click', o.deleteOptions.element, function (e) {
                e.preventDefault();
                parent._deleteStart($(this));
            });
        },
        _getURLWithID: function(url, id) {
            return url.replace(/\{id\}/g, id);
        },

        _updateStart: function ($el) {
            if(this.props.currentUpdate.inProgress) return false;

            var o = this.options,
                $itemParent = $el.parentsUntil('[data-crud-id]').parent();


            this.props.currentUpdate.inProgress = true;
            this.props.currentUpdate.id = parseInt($itemParent.attr('data-crud-id'), 10);
            for(var dNum in o.def) {
                var d = o.def[dNum],
                    $el = $itemParent.find('[data-crud-ref="'+ dNum +'"]');

                this.props.currentUpdate.attributes[dNum] = $el.text();

                var inputHTML = '<input type="'+ d.type +'"'+(d.length > 0 ? ' maxlength="'+ d.length +'"' : '')+' value="'+ $el.text() +'" />';

                if(d.hasOwnProperty('select') && d.select.length > 0) {
                    inputHTML = '<select name="'+dNum+'">';
                    for(var option in d.select) {
                        var opt = d.select[option];
                        inputHTML += '<option value="'+ opt +'"'+ (opt == $el.text() ? ' selected' : '') +'>'+ opt +'</option>';
                    }
                    inputHTML += '</select>'
                }

                $el.html(inputHTML);
            }

            var $actions = $itemParent.find('[data-crud-actions]');

            this.props.currentUpdate.actionsHTML = $actions.html();
            $actions.html('<button class="crud-button crud-save-update"><i class="glyphicon glyphicon-ok"></i></button> <button class="crud-button crud-cancel-update"><i class="glyphicon glyphicon-remove"></i></button>');
        },
        _updateReset: function ($el, successfulReset) {
            var id = this.props.currentUpdate.id;

            var $itemParent = $('[data-crud-id="'+ id +'"]');

            var attrs = successfulReset ? this.__getUpdateData() : this.props.currentUpdate.attributes;
            for(var i in attrs) {
                var val = attrs[i];

                $itemParent.find('[data-crud-ref="'+ i +'"]').html(val);
            }

            $itemParent.find('[data-crud-actions]').html(this.props.currentUpdate.actionsHTML);
            this.props.currentUpdate = {
                inProgress: false,
                actionsHTML: '',
                id: null,
                attributes: {}
            };
        },
        _updateSave: function() {
            var parent = this;

            var data = this.__getUpdateData();

            $.ajax({
                type: parent.options.updateOptions.type,
                url: parent._getURLWithID(parent.options.updateOptions.url, parent.props.currentUpdate.id),
                data: data,
                dataType: 'json'
            })
            .done(function(response) {
                handleErrorBar((response.success ? 'add' : 'remove') + 'Class', response.output);
                parent._updateReset(null, true);
            })
            .fail(function() {
                handleErrorBar('removeClass', ['Dogodila se neočekivana greška. Pokušajte kasnije!']);
            });
        },
        __getUpdateData: function () {
            var o = this.options,
                $itemParent = $('[data-crud-id="'+ this.props.currentUpdate.id +'"]'),
                data = {};

            for(var i in o.def) {
                var d = o.def[i],
                    value = null,
                    $el = $itemParent.find('[data-crud-ref="'+ i +'"]');

                if(d.select && d.select.length > 0) value = $el.find('option:selected').val();
                else value = $el.find('input,textarea').val();

                data[i] = value;
            }

            return data;
        },

        _deleteStart: function($el) {
            if(this.props.deleteInProgress) return false;

            var parent = this;

            var $itemParent = $el.parentsUntil('[data-crud-id]').parent(),
                id = parseInt($itemParent.attr('data-crud-id'), 10);

            var $box = $('<div />', {class: 'crud-delete-box opened', html: '<div>Zaista obrisati stavku?</div>'}).appendTo($el.parent());
            var $boxYes = $('<button />', {html: 'Da'}).appendTo($box);
            var $boxNo = $('<button />', {html: 'Ne'}).appendTo($box);

            $box.slideDown(500);

            this.props.deleteInProgress = true;

            $boxNo.click(function () {
                $box.slideUp(500);
                parent.props.deleteInProgress = false;
            });
            
            $boxYes.click(function() {
                $.ajax({
                    type: parent.options.deleteOptions.type,
                    url: parent._getURLWithID(parent.options.deleteOptions.url, id)
                })
                .done(function(response) {
                    handleErrorBar((response.success ? 'add' : 'remove') + 'Class', response.output);
                    $itemParent.fadeOut(500, function() {
                        $(this).remove();
                    });
                    parent.props.deleteInProgress = false;
                })
                .fail(function() {
                    handleErrorBar('removeClass', ['Dogodila se neočekivana greška. Pokušajte kasnije!']);
                });
            });
        }
    };

}) (jQuery);