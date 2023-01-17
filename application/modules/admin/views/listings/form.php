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
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>
        <li>
            <a href="<?php echo base_url('admin/listings') ?>">Listings</a>
        </li>
        <li class="active">Add Listing</li>
    </ul><!-- /.breadcrumb -->
</div>
<div class="page-header">
    <h1>
        Listings
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <?php
        if ($this->session->flashdata('success_message'))
        {
            echo '<div class="alert alert-success alertMessage">' . $this->session->flashdata('success_message') . '</div>';
        };
        ?>
        <div class="clearfix"></div>
        <!-- Notification -->
        <?php
        if ($this->session->flashdata('error_message'))
        {
            echo '<div class="alert alert-danger">' . $this->session->flashdata('error_message') . '</div>';
        };
        ?>
        <div class="clearfix"></div>
        <!-- /Notification -->
        <div class="space-8"></div>
        <div class="space-8"></div>
        <form id="form_listings" name="form_listings" action="<?php echo base_url('admin/listings/add') ?>" class="form-horizontal" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
            <input type="hidden" name="product_type" value="3">
            <input type="hidden" name="currency" value="2">
            <input type="hidden" name="of_admin" value="<?php echo $row['of_admin'] ? $row['of_admin'] : 0; ?>">
            <input type="hidden"  id="product_id" name="product_id" value="<?php echo $row['product_id']; ?>"  >
            <input type="hidden"  id="user_id" name="user_id" value="<?php echo isset($row) ? $row['user_id'] : $this->session->userdata('user_id'); ?>"  >
            <input type="hidden" name="price" value="<?php echo "Hello"; ?>" >
            <?php
            if (!isset($_GET['uid']))
            {
                /* ?>
                  <h3 class="header smaller lighter blue">Product Publisher</h3>
                  <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 no-padding-right">Publisher *</label>
                  <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                  <select id="user_id_publisher" name="user_id_publisher" class="col-xs-12 col-sm-5" required >
                  <option value="1"><?php echo SITE_NAME; ?> Administrator</option>
                  <?php
                  $publisher = get_users_by_type(2);
                  ?>
                  <optgroup label="Publishers">
                  <?php
                  foreach ($publisher as $key => $value)
                  {
                  echo '<option value="' . $value['user_id'] . '">' . ucfirst($value['full_name']) . '</option>';
                  }
                  ?>
                  </optgroup>
                  </select>
                  </div>
                  </div>
                  </div>
                  <script>$(function ()
                  {
                  $('#user_id_publisher').val(<?php echo isset($row) ? $row['user_id'] : $this->session->userdata('user_id'); ?>);
                  });</script>
                  <?php
                 */
                if ($action == 'add')
                {
                    ?>
                    <input type="hidden" name="user_id_publisher" value="1" />
                    <?php
                }
                else
                {
                    ?>
                    <input type="hidden" name="user_id_publisher" value="<?php echo $row['user_id']; ?>" />
                    <?php
                }
                ?>


    <?php }
else
{
    ?>
                <input type="hidden" name="redirect" value="1" />
                <input type="hidden" name="user_id_publisher" value="<?php echo $this->common->decode($_GET['uid']); ?>" />
<?php } ?>
            <h3 class="header smaller lighter blue">Category & Product Type</h3>
            <div class="form-group subCat_1">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Category *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <select id="category_1" name="category_id[]" class="col-xs-12 col-sm-5 catClass" onchange="parent_category1('category_1', 1)" required >
                            <option value="">Select Category</option>
<?php echo $this->listings_model->loadCategories(0, 0, $row['type_id']); ?>
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
                    $getParentCats = $this->listings_model->getParentCats($row['category_id']);
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
                            $p_catagories      = $this->listings_model->getSubCategories($data);
                            ?>
                            <div class="form-group subCat_<?php echo $i + 1 ?>">
                                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Sub Category</label>
                                <div class="col-xs-12 col-sm-9">
                                    <div class="clearfix">
                                        <select id="category_<?php echo $i + 1 ?>" name="category_id[]" class="col-xs-12 col-sm-5 catClass"  onchange="parent_category1('category_<?php echo $i ?>',<?php echo $i ?>)">
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
                ?>
            </div>
            <?php /*
              <div class="space-2"></div>
              <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right">Product Type *</label>
              <div class="col-xs-12 col-sm-9">
              <div class="clearfix">
              <select id="product_type" name="product_type" class="col-xs-12 col-sm-5" required >
              <option value="">Select Product Type</option>
              <?php echo $this->listings_model->loadProductTypes($row['product_type']); ?>
              </select>
              </div>
              </div>
              </div>
             */ ?>
            <div class="space-2"></div>
            <h3 class="header smaller lighter blue">Title & Listing Details</h3>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Title *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" minlength="2" class="col-xs-12 col-sm-5" id="product_name" name="product_name"  placeholder="Title" value="<?php echo $row['product_name']; ?>" required>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group animated fadeInDown">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Note:</label>
                <div class="col-xs-12 col-sm-5">
                    <div class="clearfix">
                        <p class="alert alert-success">Follow the <a href="<?php echo base_url() ?>uploads/adding_listing.pdf" target="_blank"><b>Link</b></a> for creating referral URL.</p>
                    </div>
                </div>
            </div>
            <div class="form-group animated fadeInDown">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Referral URL *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="url" placeholder="Referral URL" name="url" id="url" class="col-xs-12 col-sm-5 "  <?php echo ((!empty($row)) ? '' : 'onblur="return referal_url_check()"') ?> value="<?php echo $row['url'] ?>" <?php echo ((!empty($row)) ? 'readonly' : 'required') ?>>
                        <label class="alert-danger" id="referal_url_error" style="display: none;"></label>
                    </div>
                </div>
            </div>
            <div id="type-2" style="display: <?php echo $row['product_type'] == 1 ? 'block' : 'none'; ?>">
                <div class="form-group animated fadeInDown">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">Price *</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <input type="number" placeholder="Price"  min="0" name="price" id="price" class="col-xs-12 col-sm-5 pull-left" value="<?php $price = $row['price'] ? $row['price']: 0; echo $price; ?>" required>
                            <strong class="pull-left margin-top-5"><?php echo getSiteCurrencySymbol(); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Description *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <textarea class="col-xs-12 col-sm-5 limited" id="short_description" name="short_description"  placeholder="Short Description (1000 Characters only)" maxlength="1000" rows="10" required><?php echo $row['short_description'] ?></textarea>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
           <?php /* <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Product Currency *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <select id="currency" name="currency" class="col-xs-12 col-sm-5" <?php echo ((!empty($row)) ? 'readonly disabled' : 'required') ?> >
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
            </div> */ ?>
            <div class="space-2"></div>
<?php
if (isset($row))
{
    ?>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">Publisher Commission</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <input onkeyup="calculateVal()" onchange="calculateVal()" type="number" class="col-xs-12 col-sm-5 pull-left" min="0" id="commission" name="commission"  placeholder="Commission" value="<?php echo number_format(getVal('commission', 'c_products_commission', 'product_id', $row['product_id']), 2); ?>"  />
    <!--                            <strong class="control-label col-xs-12 col-sm-3 margin-top-5">
    <?php echo number_format(getVal('commission', 'c_products_commission', 'product_id', $row['product_id']), 2); ?>
                            </strong>-->
                        </div>
                    </div>
                </div>
                <div class="space-2"></div>
                        <?php } ?>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Advertiser Commission *</label>
                <div class="col-xs-12 col-sm-5">
                    <div class="clearfix">
<?php
if (isset($row))
{
    ?>
                            <strong  style="padding: 0px;text-align: left" class="control-label col-xs-12 col-sm-3 margin-top-5">
                                <span id="advertiser_comission"> <?php echo $row['commission']; ?></span>
                            </strong>
                            <?php
                        }
                        else
                        {
                            ?>
                            <strong class="pull-left margin-top-5"></strong>
                            <input type="number" class="col-xs-12 col-sm-5 pull-left" min="0" id="commission" name="commission"  placeholder="Commission" value="<?php echo $row['commission']; ?>" required />
            <?php } ?>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <?php /* ?>
              <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right">Product Price *</label>
              <div class="col-xs-12 col-sm-5">
              <div class="clearfix">
              <strong class="pull-left margin-top-5"></strong>
              <input type="number" class="col-xs-12 col-sm-5 pull-left" min="0" id="price" name="price"  placeholder="Product Price" value="<?php echo $row['price']; ?>" required />
              </div>
              </div>
              </div>
              <?php */ ?>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Offer Text *</label>
                <div class="col-xs-12 col-sm-5">
                    <div class="clearfix">
                        <input type="text" class="form-control pull-left" id="title" name="title"  placeholder="Offer Text" maxlength="200" value="<?php echo $row['title']; ?>" required />
<!--                        <textarea style="height: 70px" class="col-xs-12 col-sm-5 limited" id="title" name="title"  placeholder="Short Description..." maxlength="200" rows="10" required><?php echo $row['title'] ?></textarea>-->
                    </div>
                    <small style="background-color: #caf9fb; text: black">For example: On every unique sign up of Shareads you will receive</small>
                </div>
            </div>
            <div class="space-2"></div>
            <div id="type-3" style="">
                <h3 class="header smaller lighter blue"> Product Image</h3>
                <div class="space-2"></div>
                <div class="form-group animated fadeInUp">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="Images">Image</label>
                    <div  class="col-xs-12 col-sm-5">
                        
                        <div class="dropzone">
                            <div class="fallback">
                                <input type="file" accept="image/*" class="" id="images" name="images[]"  >
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
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Publisher Status *</label>
                <div class="col-xs-12 col-sm-9">
                    <div>
                        <label class="blue">
                            <input type="radio" id="publisher_status" class="ace" <?php echo ( isset($row) && $row['publisher_status'] == 1) ? 'checked' : '' ?> value="1" name="publisher_status" required>
                            <span class="lbl">&nbsp;Active</span>
                        </label>
                    </div>
                    <div>
                        <label class="blue">
                            <input type="radio" class="ace" <?php echo ( isset($row) && $row['publisher_status'] == 0) ? 'checked' : '' ?> id="publisher_status" value="0" name="publisher_status" required>
                            <span class="lbl">&nbsp;Inactive</span>
                        </label>
                    </div>
                </div>
            </div>
            <?php */ ?>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Status *</label>
                <div class="col-xs-12 col-sm-9">
                    <div>
                        <label class="blue">
                            <input type="radio" id="status" class="ace" <?php echo ( isset($row) && $row['status'] == 1) ? 'checked' : '' ?> value="1" name="status" required>
                            <span class="lbl">&nbsp;Active</span>
                        </label>
                    </div>
                    <div>
                        <label class="blue">
                            <input type="radio" class="ace" <?php echo ( isset($row) && $row['status'] == 0) ? 'checked' : '' ?> id="status" value="0" name="status" required>
                            <span class="lbl">&nbsp;Inactive</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Pin to top *</label>
                <div class="col-xs-12 col-sm-9">
                    <div>
                        <label class="blue">
                            <input type="radio" id="sequence" class="ace" <?php echo ( isset($row) && $row['sequence'] == 1) ? 'checked' : '' ?> value="1" name="sequence" required>
                            <span class="lbl">&nbsp;Yes</span>
                        </label>
                    </div>
                    <div>
                        <label class="blue">
                            <input type="radio" class="ace" <?php echo ( isset($row) && $row['sequence'] == 0) ? 'checked' : '' ?> id="sequence" value="0" name="sequence" required>
                            <span class="lbl">&nbsp;No</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="space-2"></div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button id="submit_btn" class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>
                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick="clear_form_elements('#form_listings');">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Reset
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?php echo base_url('assets/site/js/dropzone/dropzone.js'); ?>"></script>
<script src="<?php echo base_url('assets/site/js/dropzone/exif.js'); ?>"></script>
<script>
                        function isUrlValid(url)
                        {
                            return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
                        }
                        function referal_url_check()
                        {
                            $('#referal_url_error').hide();
                            var URL = BASE_URL + 'admin/listings/verifyReferalURL';
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

                                    if (isUrlValid($('#url').val()) == false)
                                    {

                                    }
                                    else if (response == 0)
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
                        $(function ()
                        {
                            $('#product_type').change(function ()
                            {
                                if ($(this).val() == 3)
                                {
                                    $('#type-2').hide();
                                    //  $('#type-3').show();
                                }
                                else if ($(this).val() == 2)
                                {
                                    $('#type-2').hide();
                                    //   $('#type-3').hide();
                                }
                                else
                                {
                                    //$('#type-3').hide();
                                    $('#type-2').show();
                                }
                            });
                            $('#form_listings').validate({
                                errorElement: 'div',
                                errorClass: 'help-block',
                                focusInvalid: true,
                                highlight: function (e)
                                {
                                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                                },
                                success: function (e)
                                {
                                    $(e).closest('.form-group').removeClass('has-error');
                                    $(e).remove();
                                },
                                errorPlacement: function (error, element)
                                {
                                    if (element.is('input[type=checkbox]') || element.is('input[type=radio]'))
                                    {
                                        var controls = element.closest('div[class*="col-"]');
                                        if (controls.find(':checkbox,:radio').length > 1)
                                            controls.append(error);
                                        else
                                            error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                                    }
                                    else if (element.is('.select2'))
                                    {
                                        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                                    }
                                    else if (element.is('.chosen-select'))
                                    {
                                        error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                                    }
                                    else
                                        error.insertAfter(element.parent());
                                },
                                invalidHandler: function (form)
                                {
                                }
                            });
                        });
                        function calculateVal()
                        {
                            var amount = $("#commission").val();
                            var ninty_five_percent_amount = amount * 0.95;
                            $("#advertiser_comission").text(ninty_five_percent_amount.toFixed(2));
                        }
</script>
