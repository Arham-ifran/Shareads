
/**Hide Success Messages*/
$(function () {
    setTimeout(function () {
        $('.alertMessages, .alertMessage, #formErrorMsg, #formErrorMsgContact').stop(true, true).hide(500);
    }, 5000);
});
/* VALIDATION*/
$(function () {
    $('#contactus_form, #signupForm, #loginForm, #ForgotPasswordForm, #users_form, #form_listings').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: true,
        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },
        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error');
            $(e).remove();
        },
        errorPlacement: function (error, element) {
            if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if (controls.find(':checkbox,:radio').length > 1)
                    controls.append(error);
                else
                    error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            } else if (element.is('.select2')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
            } else if (element.is('.chosen-select')) {
                error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
            } else
                error.insertAfter(element.parent());
        },
        invalidHandler: function (form) {
        }
    });
    $("#password_form").validate({
        rules: {
            password: {
                required: function (element) {
                    if ($("#new_password").val().length > 0) {
                        return true;
                    } else {
                        return false;
                    }
                },
                minlength: 6,
                pwcheck: true
            },
            new_password: {required: false,
                pwcheck: true,
                minlength: 6

            },
            confirm_password: {
                required: function (element) {
                    if ($("#new_password").val().length > 0) {
                        return true;
                    } else {
                        return false;
                    }
                },
                minlength: 6,
                equalTo: "#new_password"
            },
        },
        messages: {
            password: {required: "Password is required",
                minlength: "Password must be 6 characters minimum",
                pwcheck: "Password must be strong and at least 6 characters including special characters"
            },
            new_password: {minlength: "New Password must be 6 characters minimum",
                pwcheck: "New Password must be strong and at least 6 characters including special characters"
            },
            confirm_password: {minlength: "Confirm Password must be 6 characters minimum",
                equalTo: "Please enter same password as enter above"
            }

        },
        highlight: function (element) {
            $(element).parent().css({"color": "red", "font-size": "0.9em", "font-weight": "normal"});
        }
    });
});


/**
 @Method: Check email
 @Retrun: listing/HTML
 **/
function checkEmail() {
    $('#emailExist_error').hide();

    var msg = ''
    if (!isValidEmailAddress($("#email").val())) {
        msg = 'The email address contains illegal characters. Please enter correct email.';
        showMessage('alert-danger', 'formErrorMsg', msg);
        $("#email").focus();
        $("#email").val('');
        return false;
    }
    if ($('#email').valid())
    {
        var URL = BASE_URL + 'register/checkEmail';
        //Start AJax Call
        $.ajax({
            type: "POST",
            url: URL,
            dataType: "html",
            data: {
                'email': $('#email').val()
            },
            success: function (response) {

                if (response == 1)
                {
                    $('#emailExist_error').html('Email already exists... Try another one.');
                    $('#emailExist_error').show();
                    $('#email').val('');
                    return false;

                } else {
                    return true;
                }
            },
            error: function () {
                alert(ajax_alert);
            }

        });
    }

}

/**
 @Method: loginUserVarify
 @Retrun: True/False
 **/
function loginUserVarify() {

    if ($('#formLoginPopup').valid()) {
        $('#submitLogin').prop('disabled', true);
        var URL = BASE_URL + 'login/ajaxLoginPopUp';
        var data = $('#formLoginPopup').serialize();
        $.ajax({
            type: "POST",
            url: URL,
            data: data,
            dataType: "json",
            success: function (data) {
                $('#submitLogin').prop('disabled', false);
                if (data.action == 'error') {
                    showMessage('alert-danger', 'formErrorMsgPopupLogin', data.message);
                } else {
                    showMessage('alert-success', 'formErrorMsgPopupLogin', data.message);
                    clear_form_elements('#formLoginPopup');
                    setTimeout(function () {
                        window.location.href = window.location;
                    }, 1000);
                }
            },
            error: function () {
                alert(ajax_alert);
            }
        });
    }


}



function formPostsComments() {
    var msg = ''
    if ($("#comment").val() == '') {

        msg = 'Please enter comment';

        showMessage('alert-danger', 'formErrorMsg', msg);
        $("#comment").focus();
        return false;

    }
    if ($('#commentsCounter').val() == 0)
    {
        $('#newComnt').html('');
    }

    $('#newComnt').prepend('<div class="us-op">' + '<img src="' + $("#member_photo").val() + '" alt="' + $("#login_user_name").val() + '" class="" /><div class="pull-left comentDiv">' + '<span>' + $("#login_user_name").val() + '</span>' + '<small class="pull-right">few second ago</small>' + '<p class="cmnt-head-area-cntnt">' + $("#comment").val() + '</p></div><div class="clearfix"></div></div>');

    var URL = BASE_URL + 'blog/ajaxSave';
    var data = $('#formPostsComments').serialize() + '&post_id=' + $('#post_id').val();

    /*Start AJax Call*/
    $.ajax({
        type: "POST",
        url: URL,
        data: data,
        dataType: "html",
        success: function (response) {
            showMessage('alert-success', 'formErrorMsg', response);
            clear_form_elements('#formPostsComments');
        },
        error: function () {

            alert('Error occured during Ajax request...');

        }

    });
}

/**
 * Method: like
 * Return: boolean
 * */
function like(id) {
    if ($('#login_user_id').val() == '' || LOGIN_USER_ID == 0) {
        $('#loginBtnForm').click();
        return false;
    } else {

        var text = '';
        if ($("#like_countr_" + id).text() == '') {
            text = 0;
        } else {
            text = $("#like_countr_" + id).text();
        }

        $("#like_countr_" + id).text((parseInt(text) + 1));
        $("#" + id + "_like").removeAttr("onclick");

        $("#" + id + "_like").addClass("already_liked");

        $("#" + id + "_like").attr("onclick", "dislike('" + id + "','" + id + "')");

        $.ajax({
            type: "POST",
            global: false,
            url: BASE_URL + 'blog/like',
            data: 'id=' + id,
            success: function (msg) {

            }
        });
    }

}
/**
 * Method: dislike
 * Return: boolean
 * */
function dislike(id) {
    if ($('#login_user_id').val() == '' || LOGIN_USER_ID == 0) {
        $('#loginBtnForm').click();
        return false;
    } else {

        var text = '';
        if ($("#like_countr_" + id).text() == '') {
            text = 0;
        } else {
            text = $("#like_countr_" + id).text();
        }

        $("#like_countr_" + id).text((parseInt(text) - 1));

        $("#" + id + "_like").removeAttr("onclick");
        $("#" + id + "_like").removeClass("already_liked");

        $("#" + id + "_like").attr("onclick", "like('" + id + "','" + id + "')");

        $.ajax({
            type: "POST",
            global: false,
            url: BASE_URL + 'blog/dislike',
            data: 'id=' + id,
            success: function (msg) {

            }
        });
    }

}

/**
 * Method: shareLinkCopy
 * Return: boolean
 * */
function shareLinkCopy(id, type)
{
    $('#sharedLink').html($('#proLink' + id).val());
    if (id)
    {
        $.ajax({
            type: "POST",
            global: false,
            url: BASE_URL + 'marketing/shareLinkCopy',
            data: {id: id, link: $('#proLink' + id).val(), type: type},
            success: function (msg) {

            }
        });
    }
}
/**
 @Method: varfyPaypalEmail
 @Retrun: listing/HTML
 **/
function varfyPaypalEmail() {
    $('#paypal_error').hide();

    var msg = '';
    if (!isValidEmailAddress($("#paypal_email").val())) {
        msg = 'The email address contains illegal characters. Please enter correct email.';
        showMessage('alert-danger', 'paypal_error', msg);
        $("#paypal_email").focus();
        $("#paypal_email").val('');
        return false;
    }
    if ($('#paypal_email').valid())
    {
        var URL = BASE_URL + 'dashboard/varfyPaypalEmail';
        /*Start AJax Call*/
        $.ajax({
            type: "POST",
            url: URL,
            dataType: "html",
            data: {
                'email': $('#paypal_email').val()
            },
            success: function (response) {

                if (response == 0)
                {
                    $('#paypal_error').html('PayPal email not verified. Please enter correct email.');
                    $('#paypal_error').show();
                    $('#paypal_email').val('');
                    return false;

                } else {
                    $('#paypal_error').html('PayPal email verified successfully.');
                    $('#paypal_error').show();
                    return true;
                }
            },
            error: function () {
                alert(ajax_alert);
            }

        });
    }
}
/**
 
 * Method: parent_category1
 
 * Return: boolean
 
 * */
function parent_category1(idd, inc) {

    var incc;
    incc = inc;
    incc = incc + 1;
    var allselect = ($('#form_listings .catClass').size() + 2);
    for (i = incc; i <= allselect; i++)
    {
        $('.subCat_' + i).remove();
    }

    if ($('#' + idd).val() == 0 || $('#' + idd).val() == "") {
        return false;
    } else {

        var URL = BASE_URL + 'products/getSubCategory';
        $
                .ajax({
                    type: "POST",
                    url: URL,
                    data: 'category_id=' + $('#' + idd).val() + '&level=' + inc,
                    dataType: 'json',
                    success: function (data1) {

                        inc++;

                        var subcat = "";
                        if (data1.result_counter == 0) {
                            $('.subCat_' + (inc++)).remove();
                        } else {
                            $('.subCat_' + inc).remove();

                            subcat = '<div class="form-group subCat_'
                                    + inc
                                    + '"><label class="col-md-3 control-label">' + data1.label + '</label><div class="col-xs-12 col-sm-6"><div class="clearfix"><select id="category_'
                                    + inc
                                    + '" name="category_id[]"  class="form-control catClass" onchange="parent_category1(\'category_'
                                    + inc + '\',' + inc
                                    + ')"><option value="">Select</option>';
                            $.each(data1.result, function () {

                                subcat = subcat + '<option value="'
                                        + this.category_id + '">'
                                        + this.category_name + '</option>';

                            });

                            subcat = subcat + '</select></div></div></div>';



                        }
                        $('.subCat_' + (--inc)).after(subcat);
                        $('#inc').val($('#form_listings > .catClass').size());



                        var option_all = $(".catClass option:selected").map(function () {
                            if ($(this).val() == 0)
                            {
                            } else {
                                return $(this).val();
                            }

                        }).get().join(',');


                        $('#sub_parent').val(option_all);

                    }
                });
    }
}

/**
 @Method: checkUsersEmail
 @Retrun: listing/HTML
 **/
function checkUsersEmail() {
    $('#emailExist_error').hide();

    var msg = ''
    if (!isValidEmailAddress($("#email").val())) {
        msg = 'The email address contains illegal characters. Please enter correct email.';
        showMessage1('alert-danger', 'emailExist_error', msg);
        $("#email").focus();
        $("#email").val('');
        return false;
    }
    if ($('#email').valid())
    {
        var URL = BASE_URL + 'dashboard/checkEmail';
        /*Start AJax Call*/
        $.ajax({
            type: "POST",
            url: URL,
            dataType: "html",
            data: {
                'email': $('#email').val()
            },
            success: function (response) {

                if (response == 1)
                {
                    msg = 'Email already exists... Try another one.';
                    showMessage1('alert-danger', 'emailExist_error', msg);
                    $("#email").focus();
                    $('#email').val('');
                    return false;

                } else {
                    return true;
                }
            },
            error: function () {
                alert(ajax_alert);
            }

        });
    }

}

/**
 
 @Method: Delete listings Images
 
 @Retrun: listing/HTML
 
 **/
function delete_image(filename, image_id)
{
    URL = BASE_URL + 'products/delete_image';
    var ans = confirm("Are you sure you want to delete this image?") ? !0 : !1;
    if (ans)
    {
        $.ajax({
            type: "POST",
            url: URL,
            data: 'image_id=' + image_id + '&image=' + filename,
            success: function (msg) {
                $('#imagesCount').val($('#imagesCount').val() - 1);
                $('#image_' + image_id).remove();
            }
        });
    }
}

function remove_uploaded_file(image_name, id)
{
    URL = BASE_URL + 'products/remove_uploaded_file';
    $.ajax({
        type: "POST",
        url: URL,
        data: 'image_name=' + image_name + '&id=' + id,
        success: function (msg) {
            $('#img_' + id).remove();
            var option_all = $(".image_ids").map(function () {
                return $(this).val();
            }).get().join(',');
            $('#image_ids').val(option_all);
        }
    });
}

