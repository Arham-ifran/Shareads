<?php
$display1 = 'block';
$display2 = 'none';
if ($row['is_banner'] == 1) {
    $display1 = 'block';
    $display2 = 'none';
} else if ($row['is_banner'] == 2) {
    $display2 = 'block';
    $display1 = 'none';
}
?>
<div class="breadcrumbs ace-save-state" id="breadcrumbs">


    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>

        <li>
            <a href="<?php echo base_url('admin/announcements') ?>">Site Ads</a>
        </li>
        <li class="active">Add Site Ad</li>
    </ul><!-- /.breadcrumb -->


</div>


<div class="page-header">
    <h1>
        Add Site Ads

    </h1>
</div><!-- /.page-header -->



<div class="row">
    <div class="col-xs-12">

        <?php
        if ($this->session->flashdata('success_message')) {
            echo '<div class="alert alert-success alertMessage">' . $this->session->flashdata('success_message') . '</div>';
        };
        ?>
        <div class="clearfix"></div>
        <!-- Notification -->
        <?php
        if ($this->session->flashdata('error_message')) {
            echo '<div class="alert alert-danger">' . $this->session->flashdata('error_message') . '</div>';
        };
        ?>
        <div class="clearfix"></div>
        <!-- /Notification -->

        <div class="space-8"></div>
        <div class="space-8"></div>


        <form id="formannouncements" name="formannouncements" action="<?php echo base_url('admin/announcements/add') ?>" class="form-horizontal" role="form" method="post"  accept-charset="utf-8"  enctype="multipart/form-data">

            <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
            <input type="hidden"  id="ads_id" name="ads_id" value="<?php echo $row['ads_id']; ?>"  >
            <!--<input type="hidden"  id="user_id" name="user_id" value="<?php echo $row['user_id']; ?>"  >-->
            <input type="hidden"  id="" value="3" name="is_home">

            <?php /* ?>
              <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right">Show On home / Other Pages</label>
              <div class="col-xs-12 col-sm-9">

              <label class="blue">
              <input type="radio" id="" class="ace " <?php echo ( isset($row) && $row['is_home'] == 1) ? 'checked' : '' ?> <?php echo (!isset($row)) ? 'checked' : '' ?> onclick="showCathide(1)" value="1" name="is_home" required>
              <span class="lbl">&nbsp;Home Page</span>
              </label>

              <?php /*?>                       <label class="blue">
              <input type="radio" class="ace" onclick="showCathide(2)" <?php echo ( isset($row) && $row['is_home'] == 2) ? 'checked' : '' ?> id="" value="2" name="is_home" required>
              <span class="lbl">&nbsp;Categories</span>
              </label><?php *-/?>

              <label class="blue">
              <input type="radio" class="ace" onclick="showCathide(3)" <?php echo ( isset($row) && $row['is_home'] == 3) ? 'checked' : '' ?> id="" value="3" name="is_home" required>
              <span class="lbl">&nbsp;Other Pages</span>
              </label>

              </div>
              </div><?php */ ?>
            <div class="space-2"></div>


            <div class="space-2"></div>


            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Is Image / Code *</label>
                <div class="col-xs-12 col-sm-9">
                    <div>
                        <label class="blue">
                            <input type="radio" id="is_banner" class="ace is_banners" <?php echo ( isset($row) && $row['is_banner'] == 1) ? 'checked' : '' ?> <?php echo (!isset($row)) ? 'checked' : '' ?> value="1" name="is_banner" required>
                            <span class="lbl">&nbsp;Is Banner</span>
                        </label>
                    </div>
                    <div>
                        <label class="blue">
                            <input type="radio" class="ace is_banners" <?php echo ( isset($row) && $row['is_banner'] == 2) ? 'checked' : '' ?> id="is_banner" value="2" name="is_banner" required>
                            <span class="lbl">&nbsp;Is Code</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>




            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Ads Location *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <select class="col-xs-12 col-sm-5" id="announcements_destination_id" name="announcements_destination_id" required>
                            <option value="">Select Location</option>
                            <?php
                            foreach ($all_locations as $loc) {
                                echo '<option value="' . $loc['announcements_destination_id'] . '">' . $loc['page_area'] . '</option>';
                            }
                            ?>
                        </select>
                        <script>$('#announcements_destination_id').val('<?php echo $row['announcements_destination_id']; ?>');</script>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>

            <div id="banner_1" class="banSH" style="display:<?php echo $display1 ?>">
                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">Image<?php echo (!isset($row) ? ' *' : '') ?></label>
                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <strong>
                                <div id="banLoc_1" class="bImgSize" style="display:none;">Header Ad image should be 670 x 90 OR 670 x 250 size.</div>
                                <div id="banLoc_2" class="bImgSize" style="display:none;">Footer area Ad image (square) should be 336 x 280 size</div>
                                <div id="banLoc_3" class="bImgSize" style="display:none;">Left  area Ad image should be 232 x 600 size or width is 232 x any height.</div>
                                <div id="banLoc_4" class="bImgSize" style="display:none;">Right area Ad image should be 150 x 150 size or width is 150 x any height.</div>
                                <div id="banLoc_5" class="bImgSize" style="display:none;">Centered area Ad image (square) should be 336 x 280 size</div>
                            </strong>
                            <div class="space-2"></div>
                            <input type="file" class="upload" id="b_images" name="images" <?php echo (!isset($row) ? 'required' : '') ?> accept="image/*" />
                            <input type="hidden" name="old_image"  id="old_image" value="<?php echo $row['images']; ?>">
                            <div class="clear5"></div>
                            <?php
                            if ($row['images'] == '') {
                                $row['images'] = 'abc.png';
                            }
                            $image = $this->common->check_image(base_url("uploads/announcements/pic/" . $row['images']), 'no_image.jpg');
                            ?>
                            <img id="bannImg" src="<?php echo $image; ?>" width="75" />
                            <div id="imgShow"></div>
                        </div></div>
                </div>



            </div>

            <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">URL</label>
                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="url" class="col-xs-12 col-sm-5" id="url" name="url"  placeholder="URL" value="<?php echo $row['url']; ?>" >
                        </div>
                    </div>
                </div>

            <div class="space-2"></div>

            <div class="form-group banSH" id="banner_2" style="display:<?php echo $display2 ?>;">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Banner Text *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <textarea class="col-xs-12 col-sm-5 limited" maxlength="250" rows="7" id="bannerCode" name="bannerCode"  placeholder="Banner Text (250 characters only)"  required><?php echo $row['bannerCode'] ?></textarea>
                    </div>
                </div>
            </div>

            <div class="space-2"></div>




            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Expire After *</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <?php
                        if(isset($row) && $row['end_date'] <> '')
                        $date = date('Y-m-d',  $row['end_date']);
                        else
                        $date = date('Y-m-d',  strtotime('+180 Days'));
                        ?>
                <input type="text" class="col-xs-12 col-sm-5 datepicker" id="end_date" name="end_date"  placeholder="Date" value="<?php echo $date; ?>" >
                    </div>
                </div>
            </div>
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


            <div class="space-2"></div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button id="submit_btn" class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick="clear_form_elements('#formannouncements');">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Reset
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

<script src="<?php echo base_url('assets/admin/js/bootstrap-datepicker.min.js');?>"></script>
<script>
    $(function () {

        $('.datepicker').datepicker({
            autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
        });

        $('#formannouncements').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
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
                }
                else if (element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                }
                else if (element.is('.chosen-select')) {
                    error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                }
                else
                    error.insertAfter(element.parent());
            },
            invalidHandler: function (form) {
            }
        });


        $('#b_images').change(function () {

            var ext = $('#b_images').val().split('.').pop()
                    .toLowerCase();
            if ($.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) {
                $('#b_images').val('');
                alert('Invalid extension,Please select only image file');
                return false;
            }

            var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
            var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
            if (isSafari) {
                var nAgt = navigator.userAgent;
                var fullVersion = '' + parseFloat(navigator.appVersion);
                var verOffset;
                if ((verOffset = nAgt.indexOf("Safari")) != -1) {
                    fullVersion = parseFloat(nAgt.substring(verOffset + 7));
                    if ((verOffset = nAgt.indexOf("Version")) != -1)
                        fullVersion = parseFloat(nAgt.substring(verOffset + 8));
                }
                if (fullVersion >= 7)
                {
                    readURL_ads(this);
                } else {
                    alert('Broswer not supported. Please update your browser.');
                    $('#b_images').val('');
                }
            } else {
                readURL_ads(this);
            }
        });


        function readURL_ads(input) {

            var type = $('#announcements_destination_id').val();
            if (type == '' || type == 0)
            {
                alert('Please select Ad location first.');
                $('#b_images').val('');
                return false;
            }
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                var file = input.files[0];
                var image = new Image();
                reader.readAsDataURL(file);
                reader.onload = function (_file) {
                    image.src = _file.target.result;
                    image.onload = function () {
                        var w = this.width,
                                h = this.height,
                                t = file.type,
                                n = file.name,
                                s = ~~(file.size / 1024) + 'KB';

                        $('#bannImg').show();
                        $('#bannImg').attr('src', image.src);
                        if (type == 1) {
                            if ((w == '670' && h == '90') || (w == '670' && h == '250')) {
                                $('#bannImg').attr('src', image.src);

                            } else {
                                $('#b_images').val('');
                                $('#bannImg').attr('src', '').hide();
                                alert('Invalid image size (' + w + 'x' + h + ').' + $('#banLoc_1').html());
                                return false;
                            }
                        } else if (type == 2) {
                            if (w == '670' && h == '90') {
                                $('#bannImg').attr('src', image.src);
                            } else {
                                $('#b_images').val('');
                                $('#bannImg').attr('src', '').hide();
                                alert('Invalid image size (' + w + 'x' + h + ').' + $('#banLoc_2').html());
                                return false;
                            }
                        } else if (type == 3) {
                            if ((w == '232' && h == '600') || w == '232') {
                                $('#bannImg').attr('src', image.src);
                            } else {
                                $('#b_images').val('');
                                $('#bannImg').attr('src', '').hide();
                                alert('Invalid image size (' + w + 'x' + h + ').' + $('#banLoc_3').html());
                                return false;
                            }
                        }
                        else if (type == 4) {
                            if (w == '150' || w == '160') {
                                $('#bannImg').attr('src', image.src);
                            } else {
                                $('#b_images').val('');
                                $('#bannImg').attr('src', '').hide();
                                alert('Invalid image size (' + w + 'x' + h + ').' + $('#banLoc_4').html());
                                return false;
                            }
                        }
                        else if (type == 5) {
                            if (w == '336' && h == '280') {
                                $('#bannImg').attr('src', image.src);
                            } else {
                                $('#b_images').val('');
                                $('#bannImg').attr('src', '').hide();
                                alert('Invalid image size (' + w + 'x' + h + ').' + $('#banLoc_5').html());
                                return false;
                            }
                        }

                    };

                };

            }
        }


    });
    function showCathide(id)
    {
        if (id == 2)
            $("#catDiv").show();
        else
            $("#catDiv").hide();
    }


</script>