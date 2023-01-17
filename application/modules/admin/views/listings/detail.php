<link rel="stylesheet" href="<?php echo base_url('assets/site/js/dropzone/dropzone.css'); ?>">

<style>

    .dropzone a.dz-remove, .dropzone-previews a.dz-remove
    {
        margin-top: 5px;
    }
    .dropzone {
        min-height: 300px;
    }
</style>
<style>
     .card {
        margin-top: 50px;
        background: #fff;
    }
    .preview-pic {
        display: block;
        background: #ffffff;
        border: 1px solid #f4f4f4;
        border-radius: 6px;
        margin: auto;
        padding: 10px;
        margin-bottom: 30px;
        max-height: 400px;
        overflow: hidden;
        cursor: pointer;
    }
    .preview-pic img{
        object-fit: cover;
/*        width: 100%;*/
/*        height: 100%;*/
     max-height: 380px;  
        transition: all 0.4s ease-in-out;
    }
    .preview-pic:hover img{
        transform: scale(1.2);
    }
    .details .product-title p{
        font-size: 30px;
        color: #8ac229;
        padding-bottom: 3%;
        font-weight: 600;
        margin-bottom: 10px;
        text-transform: capitalize;
        max-width: 90%;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        -webkit-line-clamp: 1;
        position: relative;
    }
    .details .product-category{
        font-size: 17px;
        color: #2b4761;
        font-weight: 500;
        text-transform: capitalize;
        margin-bottom: 10px;
    }
    .details .product-description{
        color: #2b4761;
        font-size: 18px;
        border-top: 1px solid #f4f4f4;
        padding: 10px 0;
        margin-top: 20px;
    }
    .details .price{
        border-top: 1px solid #f4f4f4;
        border-bottom: 1px solid #f4f4f4;
        padding: 10px 0;
        margin: 15px 0;
    }
    .details .price ul{
        margin: 0;
        padding: 0 0;
    }
    .details .price ul li{
        list-style: none;
        display: inline-block;
        margin: 0 60px 0 0;
        padding: 0 0;
        font-size: 26px;
        color: #2b4761;
        font-weight: 700;
    }
    .details .price li span{
        font-size: 12px;
        font-weight: 600;
        color: #606a73;
        text-transform: uppercase;
        display: block;
    }




    .details ul.links{
        margin: 0;
        padding: 0 ;
    }
    .details ul.links li{
        list-style: none;
        display: block;
        margin: 10px 0;
        font-size: 16px;
        color: #2b4761;
        font-weight: 500;
    }
    .details ul.links li a{
        font-size: 16px;
        color: #2b4761;
        font-weight: 500;
        padding: 0 0 0 10px;
        margin: 0px 0;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        -webkit-line-clamp: 1;
        display: inline-block;
        max-width: 94%;
    }
    .details ul.links li:hover a{
        color: #8ac229;
    }
    .details ul.links li p{font-size: 16px;}
    .details ul.links li i{display: inline-block;}
    .edit-product{
        float: right;
        background: #2b4761;
        font-size: 14px;
        color: #FFFFFF;
        border-radius: 30px;
        width: 30px;
        height: 30px;
        padding: 5px 9px;
        text-align: center;
        position: absolute;
        top: 5px;
        right: 10px;
        transition: all 0.3s ease-in-out;
    }
    .edit-product:hover{
        background: #8ac229;
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
        <li class="active">Detail</li>
    </ul><!-- /.breadcrumb -->


</div>

<div class="page-header">
    <h1>
        <?php echo getVal('product_name', 'c_products', 'product_id', $product_id); ?>
    </h1>
</div><!-- /.page-header -->



<div class="row">
    <div class="col-xs-12">


        <div class="clearfix"></div>
        <!-- /Notification -->

        <div class="space-8"></div>
        <div class="space-8"></div>
        <?php /* ?>
        <b>1 Pixel Code for <?php echo $row['product_type'] == 3 ? 'Site Product' : 'Lead Generation' ?>:</b>
        <div class="alert alert-success alertMessage">
            <?php
   
            echo '<textarea style="width: 100%; height: 36px; resize:none;"><img  height="1" width="1" style="border-style:none;display:none;"  src="' . base_url('tracking?prd=' . $this->common->encode($product_id) . '&affid=') . '" /></textarea>';
            /*
            if ($row['product_type'] == 3)
            {
                echo '<textarea style="width: 100%; height: 36px; resize:none;"><img  height="1" width="1" style="border-style:none;display:none;"  src="' . base_url('tracking?prd=' . $this->common->encode($product_id) . '&affid=') . '" /></textarea>';
            }

            if ($row['product_type'] == 2)
            {
                echo '<textarea style="width: 100%;; height: 36px; resize:none;"><img  height="1" width="1" style="border-style:none;display:none;"  src="' . base_url('checkout/lead_generation?prd=' . $this->common->encode($product_id) . '&affid=') . '" /></textarea>';
            }
            
        </div>
        */ ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="wrapper row">
            <div class="preview col-md-6 col-xs-12">

                <div class="preview-pic">
                    <?php
                
                    if (!empty($products_images))
                    {
                        foreach ($products_images as $key => $pic)
                        {
                            $count = $key + 1;
                            ?>
                            <img src="<?php echo base_url('uploads/products/pic/' . $pic['image']); ?>" class="img-responsive center-block" alt=""/>
                            <?php
                        }
                    }
                    else
                    {
                        ?>
                        <img src="https://www.leehealthwellbeing.com.au/wp-content/uploads/2016/02/graphic_product_tangible.png" class="img-responsive center-block" alt=""/>
                    <?php } ?>

                </div>

            </div>
            <div class="details col-md-6 col-xs-12">
                <h3 class="product-title"><p><?php echo ucfirst($row['product_name']); if ($row['script_verified'] == 1)
                                {
                                echo '&nbsp;<span class="label label-sm label-success">Live</span>';
                                } ?> </p>
                   
                </h3>
                <h4 class="product-category"><?php echo ucfirst($this->db->where('category_id', $row['category_id'])->get('categories')->row()->category_name); ?></h4>
                <p class="product-description"><?php echo $row['short_description']; ?></p>
                <h4 class="price">
                    <ul>
<!--                        <li><span>Price</span><?php echo getSiteCurrencySymbol('', $row['currency']) . number_format($row['price'], 2); ?></li>-->
                        <li><span>Commission</span><?php echo getSiteCurrencySymbol('', $row['currency']); ?><?php echo number_format(getVal('commission', 'c_products_commission', 'product_id', $row['product_id']), 2); ?></li>
                    </ul>
                </h4>
                <ul class="links">
                    <li><i class="fa fa-link"></i><a href="<?php echo $row['url']; ?>"><?php echo $row['url']; ?></a></li>
                    <li><p><?php echo ucfirst($row['title']); ?></p></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<br><br>
<div class="row">
    <div class="col-xs-12">
        <div class="space-4"></div>
        <?php
        
        $facebook    = $twitter     = $email       = $linkedin    = $direct_link = 0;
        foreach ($product_chart as $key => $row_w)
        {
            if (strpos($row_w['referer_page'], 'facebook') !== false)
            {
                $facebook = $facebook + 1;
            }
            else if (strpos($row_w['referer_page'], 'twitter') !== false)
            {
                $twitter = $twitter + 1;
            }
            else if ($row_w['referer_page'] == 'email')
            {
                $email = $email + 1;
            }
            else if ($row_w['referer_page'] == 'linkedin')
            {
                $linkedin = $linkedin + 1;
            }
            else
            {
                $direct_link = $direct_link + 1;
            }
        }
        ?>

        <div class="space-6"></div> 
        <div class="infobox infobox-grey">
            <div class="infobox-icon">
                <i class="ace-icon fa fa-share-square"></i>
            </div>
            <div class="infobox-data">
                <span class="infobox-data-number"><?php echo $facebook ?></span>
                <div class="infobox-content"> Facebook </div>
            </div>
        </div>
        <div class="infobox infobox-grey">
            <div class="infobox-icon">
                <i class="ace-icon fa fa-share-square"></i>
            </div>
            <div class="infobox-data">
                <span class="infobox-data-number"><?php echo $twitter ?></span>
                <div class="infobox-content"> Twitter</div>
            </div>
        </div>
        <div class="infobox infobox-grey">
            <div class="infobox-icon">
                <i class="ace-icon fa fa-share-square"></i>
            </div>
            <div class="infobox-data">
                <span class="infobox-data-number"><?php echo $email ?></span>
                <div class="infobox-content"> Email</div>
            </div>
        </div>
        <div class="infobox infobox-grey">
            <div class="infobox-icon">
                <i class="ace-icon fa fa-share-square"></i>
            </div>
            <div class="infobox-data">
                <span class="infobox-data-number"><?php echo $linkedin ?></span>
                <div class="infobox-content"> Linkedin</div>
            </div>
        </div>
        <div class="infobox infobox-grey">
            <div class="infobox-icon">
                <i class="ace-icon fa fa-share-square"></i>
            </div>
            <div class="infobox-data">
                <span class="infobox-data-number"><?php echo $direct_link ?></span>
                <div class="infobox-content"> Direct Link</div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.bootstrap.min.js') ?>"></script>
<script>
    jQuery(function ()
    {
        $('#dynamic-table').dataTable({
            bAutoWidth: false, scrollX: true
        });


    });
</script>

