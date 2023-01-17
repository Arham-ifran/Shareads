<?php $this->load->view('includes/login_popup'); ?>
<footer>
    <div class="container wow animated fadeInDown">
        <div class="col-sm-4 col-xs-12 col-md-4">
            <h3><?php echo SITE_NAME ?></h3>
            <ul>
                <?php
                $pages_data = get_pages_footer(7);
                foreach ($pages_data as $pages) {
                    ?>
                    <li><a href="<?php echo base_url($pages['slug']) ?>"><?php echo $pages['title']; ?></a></li>
                <?php } ?>
                <li><a href="<?php echo base_url('blog') ?>">Blog</a></li>
            </ul>
        </div>

        <div class="col-sm-4 help col-xs-12 col-md-4">
            <h3>HELP</h3>
            <p><a href="<?php echo base_url('support'); ?>"><i class="fa fa-headphones" aria-hidden="true"></i>&nbsp;Support</a></p>
            <p><?php echo nl2br(ADMIN_ADDRESS);?>
                <br/><a href="mailto:<?php echo ADMIN_EMAIL;?>"><?php echo ADMIN_EMAIL;?></a></p>
        </div>
        <div class="col-sm-4 connect col-xs-12 col-md-4">
            <h3>CONNECT</h3>
            <ul>
                <li><a target="_blank" class="facebook" href="<?php echo FACEBOOK; ?>"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a></li>
                <li><a target="_blank" class="twitter" href="<?php echo TWITTER; ?>"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</a></li>
                <li><a target="_blank" class="linkedin" href="<?php echo LINKEDIN; ?>"><i class="fa fa-linkedin" aria-hidden="true"></i> Linkedin</a></li>
                <!--<li><a target="_blank" class="google" href="<?php // echo GOOGLE; ?>"><i class="fa fa-google-plus" aria-hidden="true"></i> Google+</a></li>-->
            </ul>
        </div>
    </div>
    <div class="clearfix"></div>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo base_url('assets/site/js/bootstrap.min.js') ?>"></script>
<script>
    function openNav() {
        document.getElementById("myNav").style.width = "100%";
    }
    function closeNav() {
        document.getElementById("myNav").style.width = "0%";
    }
    $('.arrow-section').click(function () {
        $('html, body').animate({
            scrollTop: $($(this).attr('href')).offset().top
        }, 500);
        return false;
    });
</script>
<script src="<?php echo base_url('assets/site/js/wow.min.js') ?>"></script>
<script>
    wow = new WOW(
            {
                animateClass: 'animated',
                offset: 100,
                callback: function (box) {
                    console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
                }
            }
    );
    wow.init();
</script>
<script src="<?php echo base_url('assets/site/js/main.min.js') ?>"></script>
<script src="<?php echo base_url('assets/admin/js/validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/admin/js/validation/additional-methods.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/site/js/progress/jqprogress.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/admin/js/custom/global.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/site/js/custom/custom_functions.min.js') ?>"></script>
<?php echo OTHER_CODES; ?>
<?php echo GOOGLE_ANALYTICS_CODE; ?>
<?php echo SUPPORT_CHAT_CODE; ?>
</body>
</html>