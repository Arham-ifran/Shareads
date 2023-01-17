
<link rel="stylesheet" href="<?php echo base_url('assets/site/js/dropzone/dropzone.css'); ?>">

<style>

    .dropzone a.dz-remove, .dropzone-previews a.dz-remove
    {
        margin-top: 5px;
    }
    .dropzone {
            min-height: 88px;
    border: none;
    padding: 0;
    }
</style>
<?php $this->load->view('includes/profile_info'); ?>

<section class="container">
    <div class="heading_links clearfix">
        <h3 class="main_heading">Products</h3>
        <p></p>
    </div>
    <div class="row">
        <div class="col-md-6 col-xs-9"><h4><?php echo (isset($row) ? 'Edit' : 'Add New ') ?> Product</h4></div>
    </div>

    <div class="row">
        <div style="margin-bottom: 20px;" class="col-md-9 col-sm-12">




            <?php
            $paypal_email = getVal('paypal_email', 'c_users', 'user_id', $this->session->userdata('user_id'));
            if ($paypal_email <> '' || true)
            {
                ?>

                <form id="form_listings" name="form_listings" action="<?php echo base_url('products/add') ?>" class="form-horizontal" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <?php
                    if (!empty($row))
                    {
                        ?>
                        <input type="hidden" name="currency" value="<?php echo $row['currency']; ?>">
    <?php } ?>
                    <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
                    <input type="hidden" name="product_type" value="3">
                    <input type="hidden" name="currency" value="2">
    
                    <input type="hidden" name="price" value="0" >

                    <input type="hidden"  id="product_id" name="product_id" value="<?php echo $row['product_id']; ?>"  >
                    <input type="hidden"  id="user_id" name="user_id" value="<?php echo isset($row) ? $row['user_id'] : $this->session->userdata('user_id'); ?>"  >

                    <h4 class="header smaller lighter blue">Categories & Product Type</h4><br />


                    <div class="form-group subCat_1">
                        <label class="col-sm-3">Category *</label>
                        <div class="col-xs-12 col-sm-6">
                            <div class="clearfix">

                                <select id="category_1" name="category_id[]" class="form-control catClass" onchange="parent_category1('category_1', 1)" required >

                                    <option value="">Select Category</option>

    <?php echo $this->products_model->loadCategories(0, 0, $row['type_id']); ?>

                                </select>

                            </div>
                        </div>
                    </div>


                    <div class="space-2"></div>


                    <input type="hidden" name="sub_parent" id="sub_parent" >
                    <div id="allSubCategories">
                        <?php
                        $i = 0;
                        if (isset($row))
                        {
                            $getParentCats = $this->products_model->getParentCats($row['category_id']);
                            if (count($getParentCats) > 0)
                            {
                                foreach ($getParentCats as $childs)
                                {
                                    $i++;
                                    ?>
                                    <script>$(function ()
                                        {
                                            $("#category_<?php echo $i; ?>").val('<?php echo $childs['category_id'] ?>');
                                        });

                                    </script>
                                    <?php
                                    if ((count($getParentCats)) == $i)
                                        continue;
                                    $data              = array();
                                    $data['parent_id'] = $childs['category_id'];
                                    $p_catagories      = $this->products_model->getSubCategories($data);
                                    ?>
                                    <div class="form-group subCat_<?php echo $i + 1 ?>">
                                        <label class="col-sm-3">Sub Category</label>
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="clearfix">
                                                <select id="category_<?php echo $i + 1 ?>" name="category_id[]" class="form-control catClass"  onchange="parent_category1('category_<?php echo $i ?>',<?php echo $i ?>)">
                                                    <option value="">Select Category</option>
                                                    <?php
                                                    foreach ($p_catagories as $catagory)
                                                    {
                                                        ?>
                                                        <option value="<?php echo $catagory['category_id'] ?>"><?php echo $catagory['category_name'] ?></option>
                <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-2"></div>

                                    <?php
                                }
                            }
                        }

                        /*
                          ?>
                          <div class="space-2"></div>

                          <div class="form-group">
                          <label class="col-sm-3">Product Type *</label>
                          <div class="col-xs-12 col-sm-6">
                          <div class="clearfix">

                          <select id="product_type" name="product_type" class="form-control" required >

                          <option value="">Select Product Type</option>

                          <?php echo $this->products_model->loadProductTypes($row['product_type']); ?>

                          </select>

                          </div>
                          </div>
                          </div>
                          <?php */
                        ?>
                    </div>

                    <h4 class="header smaller lighter blue">Title & Listing Details</h4>
                    <br />




                    <div class="form-group">
                        <label class="col-sm-3">Title *</label>
                        <div class="col-xs-12 col-sm-6">
                            <div class="clearfix">
                                <input type="text" minlength="2" class="form-control" id="product_name" name="product_name"  placeholder="Title" value="<?php echo $row['product_name']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="space-2"></div>


                    <div class="form-group animated fadeInDown">
                        <label class="col-sm-3">Note:</label>
                        <div class="col-xs-12 col-sm-6">


                            <div class="clearfix">
                                <p class="alert alert-success">Follow the <a href="<?php echo base_url() ?>uploads/adding_listing.pdf" target="_blank"><b>Link</b></a> for creating referral URL.</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group animated fadeInDown">
                        <label class="col-sm-3">Referral URL *</label>
                        <div class="col-xs-12 col-sm-6">


                            <div class="clearfix">
                                <input type="url" placeholder="Referral URL" name="url" id="url" class="form-control"  <?php echo ((!empty($row)) ? '' : 'onblur="return referal_url_check()"') ?>  value="<?php echo $row['url'] ?>" <?php echo ((!empty($row)) ? 'readonly' : 'required') ?>>
                                <label class="alert-danger" id="referal_url_error" style="display: none;"></label>
                            </div>
                        </div>
                    </div>

                    <?php /* ?>

                      <div id="type-2" style="display:<?php echo $row['product_type'] == 1 ? 'block' : 'none'; ?>;">
                      <div class="form-group animated fadeInDown">
                      <label class="col-sm-3">Price *</label>
                      <div class="col-xs-12 col-sm-3">
                      <div class="clearfix">
                      <input type="number" placeholder="Price" min="0" name="price" id="price" class="form-control pull-left"  style="width: 150px;" value="<?php echo $row['price'] ?>" required>
                      <!--  <strong class="pull-left margin-top-5 ml-6"><?php echo ($row->currency_symbol <> getSiteCurrencySymbol()) ? $row->currency_symbol : getSiteCurrencySymbol(); ?></strong> -->
                      </div>
                      </div>
                      </div>
                      </div>

                      <?php */ ?>

                    <div class="space-2"></div>
                    <h4 class="header smaller lighter blue">Description & Listing Details</h4>
                    <br />

                    <div class="form-group">
                        <label class="col-sm-3">Description *</label>
                        <div class="col-xs-12 col-sm-6">
                            <div class="clearfix">

                                <textarea class="form-control limited" id="short_description" name="short_description"  placeholder=" Description (1000 Characters only)" maxlength="1000" rows="10" required><?php echo $row['short_description'] ?></textarea>

                            </div>
                        </div>
                    </div>

                    <div class="space-2"></div>
                    <?php /* ?>
                      <div class="form-group">
                      <label class="col-sm-3">Price *</label>
                      <div class="col-xs-12 col-sm-6">
                      <div class="clearfix">
                      <input type="number" min="0" class="form-control" id="price" name="price"  placeholder="Price" value="<?php echo $row['price']; ?>" required>
                      </div>
                      </div>
                      </div>
                      <div class="space-2"></div>
                      <?php */ ?>
                    <div class="form-group">
                        <label class="col-sm-3">Commission *</label>
                        <div class="col-xs-12 col-sm-3">
                            <div class="clearfix">
                                <input type="text" class="form-control pull-left" id="commission" name="commission"  placeholder="Commission" value="<?php echo $row['orignal_commision']; ?>"  style="width: 150px;"  <?php echo ((!empty($row)) ? 'readonly disabled' : 'required') ?> />
                                <!-- <strong class="pull-left margin-top-5 ml-6"><?php echo getSiteCurrencySymbol(); ?></strong> -->


                            </div>
                        </div>
                    </div>
                    <div class="space-2"></div>
                    <?php /*<div class="form-group">
                        <label class="col-sm-3">Product Currency *</label>
                        <div class="col-xs-12 col-sm-6">
                            <div class="clearfix">
                                <select class="col-xs-12 col-sm-5 form-control" id="currency" name="currency" <?php echo ((!empty($row)) ? 'readonly disabled' : 'required') ?> >
                                    <option value="">Select Currency</option>
                                    <?php
                                    foreach (getCurrencies() as $currency)
                                    {
                                        echo '<option value="' . $currency['currency_id'] . '" ' . (($currency['currency_id'] == $row['currency']) ? "selected" : "" ) . ' >' . $currency['currency_symbol'] . '  ' . ucfirst($currency['currency']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div> */?>
                    <div class="space-2"></div>
                    <div class="form-group">
                        <label class="col-sm-3">Offer Text *</label>
                        <div class="col-xs-12 col-sm-6">
                            <div class="clearfix">
                                <input type="text" class="form-control pull-left" id="title" name="title"  placeholder="Offer Text" maxlength="200" value="<?php echo $row['title']; ?>" required />
    <!--                                <textarea class="form-control limited" id="title" name="title"  placeholder="Short Description" maxlength="200" rows="10" required><?php echo $row['title'] ?></textarea>-->
                            </div>
                            <small style="background-color: #caf9fb; text: black">For example: On every unique sign up of Shareads you will receive</small>

                        </div>
                    </div>

                    <div class="space-2"></div>


                    <div id="type-3" >
                        <h4 class="header smaller lighter blue"> Product Images</h4>


                        <div class="space-2"></div>


                        <div class="form-group animated fadeInUp">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="Images">Images</label>

                            <div  class="col-xs-10 col-sm-7">
                                
                                <div class="dropzone">
                                    <div class="fallback">
                                        <input type="file" accept="image/*" id="images" name="images[]"  >
                                    </div>

                                </div>
                                <div class="space-8"></div>



                                <div id="drop-zone"> </div>
                                <?php
                                if ($products_images <> '')
                                {
                                    $aryw = array();
                                    foreach ($products_images as $img)
                                    {
                                        $aryw[] = $img['image_id'];
                                    }
                                }
                                ?>
                                <input type="hidden" class="image_ids" id="image_ids" name="image_ids" value="<?php echo implode(',', $aryw) ?>">
                                <input type="hidden" id="imagesCount" value="<?php echo (count($products_images) == 0) ? '0' : count($products_images) ?>">
                            </div>
                        </div>

                        <div class="space-2"></div>
                        <?php
                        if ($products_images <> '')
                        {
                            ?>
                            <div class="form-group animated fadeInUp">
                                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="">Uploaded Image</label>

                                <div  class="col-xs-10 col-sm-9">
                                    <?php
                                    foreach ($products_images as $img)
                                    {
                                        if ($img['image'] == '')
                                        {
                                            $img['image'] = 'abc.png';
                                        } $image = $this->common->check_image(base_url() . "uploads/products/small/" . $img['image'], 'no_image.jpg');
                                        ?>
                                        <div class="pull-left" style="width: 80px;" id="image_<?php echo $img['image_id']; ?>">

                                            <img id="bannImg" class="" style="width:75px;height:75px;margin-right:5px"   src="<?php echo $image; ?>">

                                            <a href='javascript:void(0)' onclick="delete_image('<?php echo $img['image']; ?>', '<?php echo $img['image_id']; ?>')"><i class="ace-icon fa fa-trash-o bigger-130"></i> Delete</a>


                                            <div class='clearfix'></div>

                                        </div>


        <?php } ?>


                                </div>
                            </div>
    <?php } ?>
                    </div>

<?php /* ?>
                    <div class="space-2"></div>

                    <div class="form-group ">
                        <label class="col-sm-3">Status</label>
                        <div class="col-md-6">
                            <label class="col-sm-3">
                                <input type="radio" id="publisher_status" class="ace" <?php echo ( isset($row) && $row['publisher_status'] == '1') ? 'checked' : '' ?> value="1" name="publisher_status" >
                                <span class="lbl">&nbsp;Active</span>
                            </label>
                            <label class="col-sm-3">
                                <input type="radio" class="ace" <?php echo ( isset($row) && $row['publisher_status'] == '0') ? 'checked' : '' ?> <?php echo (!isset($row)) ? 'checked' : '' ?> id="publisher_status" value="0" name="publisher_status" >
                                <span class="lbl">&nbsp;Inactive</span>
                            </label>
                        </div>
                    </div>
<?php */ ?>




                    <div class="space-2"></div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button id="submit_btn" class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Submit
                            </button>

                            &nbsp; &nbsp; &nbsp;
                            <button class="btn btn-default" type="reset" onclick="clear_form_elements('#form_listings');">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Reset
                            </button>
                        </div>
                    </div>

                </form>

                <?php
            }
            else
            {
                ?>
                <div class="alert alert-danger">Please complete your profile and provide your <b>PayPal Email</b> before adding new product.</div>

                <a class=" btn btn-primary" href="<?php echo base_url('settings'); ?>">Complete Your Profile</a>

<?php } ?>
        </div>

<?php $this->load->view('includes/right_bar') ?>

    </div>


</section>
<script src="<?php echo base_url('assets/site/js/dropzone/dropzone.js'); ?>"></script>
<script src="<?php echo base_url('assets/site/js/dropzone/exif.js'); ?>"></script>
<script src="<?php echo base_url('assets/admin/js/jquery.inputlimiter.1.3.1.min.js'); ?>"></script>
<script>
                            function isUrlValid(url)
                            {
                                return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
                            }
                            function referal_url_check()
                            {
                                if ($('#url').val().trim() != '' && isUrlValid($('#url').val().trim()) != false)
                                {
                                    $('#referal_url_error').hide();
                                    var URL = BASE_URL + 'products/verifyReferalURL';
                                    /*Start AJax Call*/
                                    $.ajax({
                                        type: "POST",
                                        url: URL,
                                        dataType: "html",
                                        data: {
                                            referal_url: $('#url').val()
                                        },
                                        success: function (response)
                                        {

                                            if (response == 0)
                                            {
                                                $('#referal_url_error').removeClass('alert-success').removeClass('alert-danger').addClass('alert-danger');
                                                $('#referal_url_error').html('This referral URL is already added for sharead products');
                                                $('#referal_url_error').show();
                                                $('#url').val('');
                                                return false;

                                            }
                                            else
                                            {
                                                $('#referal_url_error').removeClass('alert-success').removeClass('alert-danger').addClass('alert-success');
                                                $('#referal_url_error').html('Referral URL Added Successfully');
                                                $('#referal_url_error').show();
                                                return true;
                                            }
                                        },
                                        error: function ()
                                        {
                                            alert(ajax_alert);
                                        }

                                    });
                                }
                            }
                            /*limiter.js*/
                            $('textarea.limited').inputlimiter({
                                remText: '%n character%s remaining...',
                                limitText: 'max allowed : %n.'
                            });

                            $('#product_type').change(function ()
                            {
                                if ($(this).val() == 3)
                                {
                                    $('#type-2').hide();
                                    // $('#type-3').show();
                                }
                                else if ($(this).val() == 2)
                                {
                                    $('#type-2').hide();
                                    // $('#type-3').hide();
                                }
                                else
                                {
                                    //  $('#type-3').hide();
                                    $('#type-2').show();
                                }
                            });


                            $(document).ready(function ()
                            {
                                $('#commission').on('input', function ()
                                {
                                    this.value = this.value
                                            .replace(/[^\d.]/g, '')             // numbers and decimals only
                                            .replace(/(^[\d]{2})[\d]/g, '$1')   // not more than 2 digits at the beginning
                                            .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
                                            .replace(/(\.[\d]{4})./g, '$1');    // not more than 4 digits after decimal
                                });

                            });
</script>