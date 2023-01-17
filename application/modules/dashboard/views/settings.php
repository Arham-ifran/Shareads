<?php $this->load->view('includes/profile_info'); ?>
<link rel="stylesheet" href="<?php echo base_url('assets/site/slim/slim.min.css') ?>">
<style>
    .slim-label{
        margin-top: 250px !important;
        margin-bottom: 35px !important;;
    }
    .slim-label p{
        font-size: 12px;
    }
    @media (max-width: 500px) {

    .slim-label{
        margin-bottom: 10px !important;
    }
}
</style>
<section class="container" style="padding: 45px">
    <div class="heading_links clearfix"><h3 class="main_heading">Settings</h3></div>
    <div class="row">
        <div class="col-md-9 col-sm-12 settings_table">
            <h4 style="margin:0 0 25px;">Account Information</h4>
            <div class="row">

                <div class="col-md-3 text-center">
                    <div class="pic profile_pic">
                        <div class="profile_avatar">
                            <?php
                            $src = "";

                            if ($userdata['photo'] != '') {
                                $src = base_url("uploads/users/medium/" . $userdata['photo'] . "?" . time());
                            } else {
                                if ($userdata['gender'] == 'male')
                                    $src = base_url('assets/site/img/unknown_male.jpg');
                                else
                                    $src = base_url('assets/site/img/unknown_female.jpg');;
                            }
                            ?>

                            <div class="change_profile">
                                <div class="slim"
                                     data-label=""
                                     data-fetcher="fetch.php"
                                     data-size="400,400"
                                     data-max-file-size="0.5"
                                     data-ratio="1:1" style="border-radius: 100%; background-image:url('<?php
                                     echo $src;
                                     ?>'); background-size: cover; background-repeat: no-repeat; ">
                                    <input id="images" name="slim[]" type="file" value="">

                                </div>
                            </div>
                            <a id="saveBTN" onclick="saveImage()" href="javascript:void(0)" class="btn btn-edit" style="margin-bottom:25px;display: none">
                                <i class="fa fa-edit fa-fw"></i> Save
                            </a>
                        </div>
                    </div>

                    <a  href="<?php echo base_url('settings/edit') ?>" class="btn btn-primary" style="margin-top: 10px;margin-bottom:25px;">
                        <i class="fa fa-edit fa-fw"></i> Edit Profile
                    </a>
                </div>
                <div class="col-md-5 col-sm-6">
                    <ul class="profile_list">
                        <li>
                            <label>User Unique Key</label>
                            <p><?php echo $userdata['user_key'] ?></p>
                        </li>
                        <li>
                            <label>First Name</label>
                            <p><?php echo $userdata['first_name'] ?></p>
                        </li>
                        <li>
                            <label>Last Name</label>
                            <p><?php echo $userdata['last_name'] ?></p>
                        </li>
                        <li>
                            <label>Name</label>
                            <p><?php echo $userdata['full_name'] ?></p>
                        </li>
                        <li>
                            <label>Email</label>
                            <p><?php echo $userdata['email'] ?></p>
                        </li>
                        <li>
                            <label>Password</label>
                            <p><a href="<?php echo base_url('settings/changepassword') ?>">Change Password</a></p>
                        </li>

                    </ul>
                </div>
                <div class="col-md-4 col-sm-6">
                    <ul class="profile_list">
                        <li>
                            <label>Phone / Fax</label>
                            <p><?php echo $userdata['phone'] <> '' ? $userdata['phone'] : ''; ?><?php echo $userdata['fax'] <> '' ? ' / ' . $userdata['fax'] : ''; ?></p>
                        </li>
                        <li>
                            <label>Address</label>
                            <p>
                                <?php echo $userdata['address']; ?>
                                <?php echo $userdata['additional_address'] <> '' ? '<br>' . $userdata['additional_address'] : ''; ?>
                            </p>
                        </li>
                        <li>
                            <label>City / State</label>
                            <p><?php echo $userdata['city'] <> '' ? $userdata['city'] : ''; ?><?php echo $userdata['state'] <> '' ? ' / ' . $userdata['state'] : ''; ?></p>
                        </li>
                        <li>
                            <label>Country</label>
                            <p><?php echo $userdata['country'] ?></p>
                        </li>
                        <li>
                            <label>Post Code</label>
                            <p><?php echo $userdata['zip_code'] ?></p>
                        </li>
                        <li>
                            <label>About Me</label>
                            <p><?php echo nl2br($userdata['about_me']) ?></p>
                        </li>
                        <li>
                            <label>Paypal Email</label>
                            <p><?php echo $userdata['paypal_email'] ?></p>
                        </li>
                        <?php if ($this->session->userdata('account_type') == 2) { ?>
                        <li>
                            <label>Payment Schedule</label>
                            <p><?php echo ($userdata['payment_schedule'] == 1)? 'Biweekly <small>(15 days)</small>' : 'Monthly' ?></p>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php $this->load->view('includes/right_bar') ?>
    </div>
</section>

<script src="<?php echo base_url('assets/site/slim/slim.kickstart.min.js') ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('assets/site/slim/slim.jquery.min.js') ?>"></script>
<script>
                                $('#images').on("change", function () {
                                    //alert('changed');
                                    var formData = new FormData();
                                    var file = this.files[0];
                                    formData.append('file', file);
                                    console.log(file);
                                    saveImage(formData);
                                    $(".slim-btn-remove").remove();
                                    $('.slim-btn-edit').on('click', function () {
                                        $('.slim-btn-confirm').on('click', function () {
                                            // alert('changed confirm');
                                            var formData = new FormData();
                                            formData.append('img', $('img.in').attr('src'));
                                            console.log(file);
                                            saveCropImage(formData);
                                        });
                                    });


                                });



                                function saveImage(formData) {
                                    $.ajax({
                                        url: BASE_URL + 'dashboard/saveImage',
                                        type: 'POST',
                                        dataType: "json",
                                        data: formData,
                                        async: false,
                                        success: function (data) {

                                            if (data.flag == 1)
                                                $("#p_img").attr("src", data.img);
                                        },
                                        cache: false,
                                        contentType: false,
                                        processData: false
                                    });
                                }
                                function saveCropImage(formData) {
                                    $.ajax({
                                        url: BASE_URL + 'dashboard/saveCropImage',
                                        type: 'POST',
                                        dataType: "json",
                                        data: formData,
                                        async: false,
                                        success: function (data) {
                                            $("#p_img").attr("src", data.img);
                                        },
                                        cache: false,
                                        contentType: false,
                                        processData: false
                                    });
                                }



</script>