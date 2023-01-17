jQuery(function ($) {

    $('#photo, #image').ace_file_input({
        no_file: 'No File ...',
        btn_choose: 'Choose',
        btn_change: 'Change',
        droppable: false,
        onchange: null,
        thumbnail: false,
        allowExt: ["jpeg", "jpg", "png", "gif", "bmp"],
        allowMime: ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp"]
    });
    $('#video_name_1').ace_file_input({
        no_file: 'No File ...',
        btn_choose: 'Choose',
        btn_change: 'Change',
        droppable: false,
        onchange: null,
        thumbnail: false,
        allowExt: ["mp4", "3gp", "mpeg", "flv", "avi", "mpg", "mkv", "webm", "mov", "wmv"]
    });



    /*limiter.js*/
    $('textarea.limited').inputlimiter({
        remText: '%n character%s remaining...',
        limitText: 'max allowed : %n.'
    });



    if (!ace.vars['touch']) {
        $('.chosen-select').chosen({allow_single_deselect: true});
        /*resize the chosen on window resize*/

        $(window)
                .off('resize.chosen')
                .on('resize.chosen', function () {
                    $('.chosen-select').each(function () {
                        var $this = $(this);
                        $this.next().css({'width': $this.parent().width()});
                    })
                }).trigger('resize.chosen');
        /*resize chosen on sidebar collapse/expand*/
        $(document).on('settings.ace.chosen', function (e, event_name, event_val) {
            if (event_name != 'sidebar_collapsed')
                return;
            $('.chosen-select').each(function () {
                var $this = $(this);
                $this.next().css({'width': $this.parent().width()});
            })
        });


        $('#chosen-multiple-style .btn').on('click', function (e) {
            var target = $(this).find('input[type=radio]');
            var which = parseInt(target.val());
            if (which == 2)
                $('#form-field-select-4').addClass('tag-input-style');
            else
                $('#form-field-select-4').removeClass('tag-input-style');
        });
    }



    /*datepicker plugin*/
    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
    });




    /*chosen plugin inside a modal will have a zero width because the select element is originally hidden
     and its width cannot be determined.
     so we set the width after modal is show*/
    $('#modal-form').on('shown.bs.modal', function () {
        if (!ace.vars['touch']) {
            $(this).find('.chosen-container').each(function () {
                $(this).find('a:first-child').css('width', '210px');
                $(this).find('.chosen-drop').css('width', '210px');
                $(this).find('.chosen-search input').css('width', '200px');
            });
        }
    })

    $(document).one('ajaxloadstart.page', function (e) {
        $('textarea[class*=autosize]').trigger('autosize.destroy');
        $('.limiterBox,.autosizejs').remove();
        $('.daterangepicker.dropdown-menu,.colorpicker.dropdown-menu,.bootstrap-datetimepicker-widget.dropdown-menu').remove();
    });

});