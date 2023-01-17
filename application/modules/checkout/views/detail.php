<script src="<?php // echo base_url('assets/site/js/shareads.min.js')    ?>"></script>
<link rel="stylesheet"  href="<?php echo base_url('assets/site/js/lightslider/src/css/lightslider.min.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/js/fancybox/jquery.fancybox.min.css?v=2.1.5') ?>" media="screen" />

<?php
$uri = explode("/", $_SERVER['REQUEST_URI']);
$urlType = @$uri[1];
$urlType1 = @$uri[2];
$strings = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$url = trim(strtok($strings, '?'));
$currentPage = $this->uri->segment(1);
$currentPage1 = $this->uri->segment(2);

if ($urlType == 'products') {
    foreach ($product_images as $img) {
        $image = base_url("uploads/products/pic/" . $img['image'] . "?" . time());
        echo '<meta property="og:image" content="' . $image . '"/> ';
        break;
    }
}
?>
<meta property="og:title" content="<?php echo (isset($title) && $title <> '') ? $title : SITE_TITLE; ?>" />
<meta property="og:description" content="<?php echo (isset($meta_description) && $meta_description <> '') ? $meta_description : SITE_DESCRIPTION; ?>" />
<?php
$pageURL = 'http';
if ($_SERVER["HTTPS"] == "on") {
    $pageURL .= "s";
}
$pageURL .= "://";
if ($_SERVER["SERVER_PORT"] != "80") {
    $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
} else {
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
}
?>
<meta property="og:url" content="<?php echo $pageURL ?>" />
<!-- USED FOR SHARE PURPOSE END-->

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@shareads">
<meta name="twitter:title" content="<?php echo (isset($title) && $title <> '') ? $title : SITE_TITLE; ?>">
<meta name="twitter:description" content="<?php echo (isset($meta_description) && $meta_description <> '') ? $meta_description : SITE_DESCRIPTION; ?>">
<?php
if ($urlType == 'products') {
    foreach ($product_images as $img) {
        $image = base_url("uploads/products/pic/" . $img['image']);
        echo '<meta name="twitter:image:src" content="' . $image . '">';
        break;
    }
}
?>


<meta name="twitter:image:alt" content="<?php echo (isset($title) && $title <> '') ? $title : SITE_TITLE; ?>" />
<meta name="twitter:domain" content="<?php echo base_url(); ?>">



<section class="container">

    <div class=" clearfix">
        <h3 class="main_heading"><?php echo $row['product_name']; ?></h3>
    </div>

    <div class="row">
        <div class="col-sm-4">

            <div class="similar-slider-side">

                <ul id="image-gallery" class="gallery list-unstyled cS-hidden">

                    <?php
                    foreach ($product_images as $imgs) {
                        if ($imgs['image'] == '') {
                            $imgs['image'] = 'abc.png';
                        }
                        $image = $this->common->check_image(base_url("uploads/products/large/" . $imgs['image']), 'no_image.jpg');
                        $thumb = $this->common->check_image(base_url("uploads/products/small/" . $imgs['image']), 'no_image_small.jpg');
                        ?>
                        <li data-thumb="<?php echo $thumb; ?>">
                            <a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo $image; ?>">
                                <img src="<?php echo $image; ?>" />
                            </a>
                        </li>
<?php } ?>
                </ul>



            </div>
        </div>

        <div class="col-md-6 data-formating">

            <h2 class="main_heading"><?php echo $row['product_name']; ?></h2>
            <p><?php echo $row['short_description']; ?></p>

        </div>

    </div>



</section>
<!-- Add fancyBox main JS and CSS files -->
<script src="<?php echo base_url('assets/site/js/fancybox/jquery.fancybox.min.js?v=2.1.5') ?>"></script>
<script src="<?php echo base_url('assets/site/js/lightslider/src/js/lightslider.min.js') ?>"></script>

<script>

    $(document).ready(function () {
        $('#image-gallery').lightSlider({
            gallery: true,
            galleryMargin: 5,
            autoWidth: false,
            item: 1,
            thumbItem: 3,
            slideMargin: 0,
            speed: 500,
            auto: false,
            loop: true,
            onSliderLoad: function () {
                $('#image-gallery').removeClass('cS-hidden');
            }
        });

        $('.fancybox-buttons').fancybox({
            openEffect: 'none',
            closeEffect: 'none',
            prevEffect: 'none',
            nextEffect: 'none',
            closeBtn: true,
            afterLoad: function () {
                this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
            }
        });


    });


    $(function () {

<?php
if (count($product_images) > 1) {
    ?>
            $('ul#image-gallery li:first-child > a').removeAttr('data-fancybox-group').removeClass('fancybox-buttons');
            $('ul#image-gallery li:last-child > a').addClass('data-fancybox-group').removeClass('fancybox-buttons');
<?php } ?>

    });
</script>