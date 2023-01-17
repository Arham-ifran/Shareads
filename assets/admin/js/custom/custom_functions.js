$(function(){

   $("#submit_btn").click(function () {
       if($('form').valid())
       {
         $("#submit_btn").prop("disabled", true);
         $('form').submit();
       }

});
});

/**General functions**/

function delete_confirm() {
    return confirm("Are you sure you want to delete this record?") ? !0 : !1
}

function delete_cancel() {
    return confirm("Are you sure you want to cancel this?") ? !0 : !1
}

/**Hide Success Messages*/
setTimeout(function () {
    $('.alertMessages').toggle("slide")
}, 1000);

/**
 * Method: updateStatus
 * params: itemId,status
 * */

function updateStatus(controller, itemId, status) {

    if (status == 1)
    {
        $('.status_label' + itemId).removeClass('label-info').addClass('label-danger').html(lbl_inactive);
    } else {
        $('.status_label' + itemId).removeClass('label-danger').addClass('label-info').html(lbl_active);
    }
    var URL = ADMIN_URL + controller + '/ajaxChangeStatus';
    $.ajax({
        type: "POST",
        url: URL,
        data: {
            'itemId': itemId,
            'status': status
        },
        dataType: "html",
        success: function (response) {

            showMessage('alert-success', 'formErrorMsg', response);
            if (status == 1)
            {
                $('.status_button' + itemId).removeAttr('onclick').attr('onclick', 'updateStatus("' + controller + '","' + itemId + '","0")');
                $('.status_button' + itemId + ' > i').removeClass('fa-plus').addClass('fa-minus');
                $('.status_button' + itemId).removeClass('blue').addClass('red');

                $('.status_sm_button' + itemId).removeAttr('onclick').attr('onclick', 'updateStatus("' + controller + '","' + itemId + '","0")');
                $('.status_sm_button' + itemId + ' > span').removeClass('blue').addClass('red');
                $('.status_sm_button' + itemId + ' > span > i').removeClass('fa-plus').addClass('fa-minus');

            } else {
                $('.status_button' + itemId).removeAttr('onclick').attr('onclick', 'updateStatus("' + controller + '","' + itemId + '","1")');
                $('.status_button' + itemId + ' > i').removeClass('fa-minus').addClass('fa-plus');
                $('.status_button' + itemId).removeClass('red').addClass('blue');

                $('.status_sm_button' + itemId).removeAttr('onclick').attr('onclick', 'updateStatus("' + controller + '","' + itemId + '","1")');
                $('.status_sm_button' + itemId + ' > span').removeClass('red').addClass('blue');
                $('.status_sm_button' + itemId + ' > span > i').removeClass('fa-minus').addClass('fa-plus');

            }

            setTimeout(function () {
                $('#formErrorMsg').hide('slow');
            }, 3000);

        },
        error: function () {

            alert(ajax_alert);

        }

    });

}

/**

 @Method: deleteListItem

 @Param: controller, itemId

 @Return: boolean (True,false)

 */

function deleteListItem(controller, itemId,id) {

    var ans = confirm('Are you sure! You want to delete.');

    if (ans) {

        if (id > 0) {

            var URL = ADMIN_URL + controller + '/deleteItem/' + itemId;

            /*Start AJax Call*/
            $.ajax({
                type: "POST",
                url: URL,
                dataType: "html",
                success: function (response) {

                    if (response == 1) {
                        var rowId = controller + '_' + id;
                        removeRow(rowId);
                        var msg = 'Record deleted successfully.';

                        showMessage('alert-success', 'formErrorMsg', msg);
                        setTimeout(function () {
                            $('#formErrorMsg').slideUp('slow');
                        }, 2000);
                        /*setTimeout(function(){
                         window.location.href = ADMIN_URL+controller;
                         }, 3000);*/
                    } else {


                        var msg = 'Record not deleted! try again.';
                        showMessage('alert-danger', 'formErrorMsg', msg);
                    }
                },
                error: function () {
                    alert(ajax_alert);
                }

            });

        }

    }

}

/**

 @Method: Check & create Page Slug

 @Retrun: listing/HTML

 **/

function creatPageSlug() {
    $('#pageExist_error').hide();
    var URL = ADMIN_URL + 'pages/checkPage';
    /*Start AJax Call*/
    $.ajax({
        type: "POST",
        url: URL,
        dataType: "html",
        data: {
            'slug': $('#pageTitle').val()
        },
        success: function (response) {

            if (response == 1) {
                if ($('#old_slug').val() == $('#pageTitle').val()) {
                    $('#slug').val($('#old_slug').val());
                    return false;
                } else {
                    $('#pageExist_error').show();
                    $('#slug').val('');
                    return false;
                }

            } else {
                $('#slug').val(response.trim());
            }
        },
        error: function () {

            alert(ajax_alert);

        }

    });

}

/**
 @Method: Check email
 @Retrun: listing/HTML
 **/
function checkEmail() {
    $('#emailExist_error').hide();

    var msg=''
    if (!isValidEmailAddress($("#email").val())){
        msg='The email address contains illegal characters. Please enter correct email.';
        showMessage('alert-danger','formErrorMsg',msg);
        $("#email").focus();
         $("#email").val('');
        return false;
    }
    if ($('#email').valid())
    {
        var URL = ADMIN_URL + 'admin_users/checkEmail';
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
 @Method: checkUsersEmail
 @Retrun: listing/HTML
 **/
function checkUsersEmail() {
    $('#emailExist_error').hide();

    var msg=''
    if (!isValidEmailAddress($("#email").val())){
        msg='The email address contains illegal characters. Please enter correct email.';
        showMessage('alert-danger','emailExist_error',msg);
        $("#email").focus();
         $("#email").val('');
        return false;
    }
    if ($('#email').valid())
    {
        var URL = ADMIN_URL + 'users/checkEmail';
        /*Start AJax Call*/
        $.ajax({
            type: "POST",
            url: URL,
            dataType: "html",
            data: {
                'email': $('#email').val(),
                  'id': $('#user_id').val()
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

function checkInvitationsEmail() {
    $('#emailExist_error').hide();

    var msg=''
    if (!isValidEmailAddress($("#email").val())){
        msg='The email address contains illegal characters. Please enter correct email.';
        showMessage('alert-danger','emailExist_error',msg);
        $("#email").focus();
         $("#email").val('');
        return false;
    }
    if ($('#email').valid())
    {
        var URL = ADMIN_URL + 'users/checkInvitationsEmail';
        /*Start AJax Call*/
        $.ajax({
            type: "POST",
            url: URL,
            dataType: "html",
            data: {
                'email': $('#email').val(),
                  'id': $('#user_id').val()
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
 @Method: varfyPaypalEmail
 @Retrun: listing/HTML
 **/
function varfyPaypalEmail() {
    $('#paypal_error').hide();

    var msg='';
    if (!isValidEmailAddress($("#paypal_email").val())){
        msg='The email address contains illegal characters. Please enter correct email.';
        showMessage('alert-danger','paypal_error',msg);
        $("#paypal_email").focus();
         $("#paypal_email").val('');
        return false;
    }
    if ($('#paypal_email').valid())
    {
        var URL = ADMIN_URL + 'users/varfyPaypalEmail';
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


function creatCategorySlug() {
    $('#CatExist_error').hide();
    var URL = ADMIN_URL + 'categories/checkCatSlug';
    /*Start AJax Call*/
    $.ajax({
        type: "POST",
        url: URL,
        dataType: "html",
        data: {
            'slug': $('#catTitle').val()
        },
        success: function (response) {

            if (response == 1) {
                if ($('#old_category_slug').val() == $('#catTitle').val()) {
                    $('#category_slug').val($('#old_category_slug').val());
                    return false;
                } else {
                    $('#CatExist_error').show();
                    $('#category_slug').val('');
                    return false;
                }

            } else {
                $('#category_slug').val(response.trim());
            }
        },
        error: function () {

            alert(ajax_alert);

        }

    });

}



/**

 * Method: parent_category1

  * Return: boolean

 * */
function parent_category1(idd, inc) {

    var incc;
    incc=inc;
    incc=incc+1;
    var allselect=($('#form_listings .catClass').size()+2);
    for (i=incc ;i<=allselect;i++)
    {
        $('.subCat_'+i).remove();
    }

    if ($('#'+idd).val() == 0 || $('#' + idd).val() == "") {
        return false;
    } else {

        var URL = ADMIN_URL+'listings/getSubCategory';
        $
        .ajax({

            type : "POST",
            url : URL,
            data : 'category_id=' + $('#' + idd).val()+'&level='+inc,
            dataType : 'json',
            success : function(data1) {

                inc++;

                var subcat = "";
                if (data1.result_counter == 0) {
                    $('.subCat_' + (inc++)).remove();
                }

                else {
                    $('.subCat_' + inc).remove();

                    subcat = '<div class="form-group subCat_'
                    + inc
                    + '"><label class="control-label col-xs-12 col-sm-3 no-padding-right">'+data1.label+'</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><select id="category_'
                    + inc
                    + '" name="category_id[]"  class="col-xs-12 col-sm-5  catClass" onchange="parent_category1(\'category_'
                    + inc + '\',' + inc
                    + ')"><option value="">Select</option>';
                    $.each(data1.result, function() {

                        subcat = subcat + '<option value="'
                        + this.category_id + '">'
                        + this.category_name + '</option>';

                    });

                    subcat = subcat + '</select></div></div></div>';



                }
                $('.subCat_' + (--inc)).after(subcat);
                $('#inc').val($('#form_listings > .catClass').size());



                var option_all = $(".catClass option:selected").map(function () {
                    if($(this).val() == 0)
                    {}else{
                        return $(this).val();
                    }

                }).get().join(',');


                $('#sub_parent').val(option_all);

            }
        });
    }
}



/**

@Method: Delete listings Images

@Retrun: listing/HTML

**/
function delete_image(filename,image_id)
{
    URL = ADMIN_URL+'listings/delete_image';
    var ans  = confirm("Are you sure you want to delete this image?") ? !0 : !1;
    if(ans)
    {
        $.ajax({
            type : "POST",
            url : URL,
            data : 'image_id=' + image_id +'&image=' + filename,
            success : function(msg) {
                $('#imagesCount').val($('#imagesCount').val()-1);
                $('#image_'+image_id).remove();
            }
        });
    }
}

function remove_uploaded_file(image_name, id)
{
    URL = ADMIN_URL + 'listings/remove_uploaded_file';
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


/**

 @Method: Hide / Show banner Code or image/URL

 **/
$(document).ready(function () {
    $(".is_banners").click(function () {
        var val = $(this).val();
        $("div.banSH").hide();
        $("#banner_" + val).show();
        $("#url").val('');
        $("#b_images").val('');
        $("#bannerCode").val('');
    });


    /**
     @Method: Hide / Show banner Image Sizes
     **/
    $("#announcements_destination_id").change(function () {
        var val = $(this).val();
        $("div.bImgSize").hide();
        $("#banLoc_" + val).show();
    });
});