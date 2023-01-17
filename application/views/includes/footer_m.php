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