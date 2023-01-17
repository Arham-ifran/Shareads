<?php $this->load->view('includes/profile_info'); ?>
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

<section class="container" style="padding: 45px">
    <div class="heading_links clearfix">
        <h3 class="main_heading">Product Details</h3>
        <p></p>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
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
                    <h3 class="product-title"><p><?php echo ucfirst($row['product_name']); ?> </p>
                        <div class="edit-product"><a href="<?php echo base_url('products/edit/' . $this->common->encode($row['product_id'])) ?>"><i class="fa fa-pencil-square-o"></i></a></div>
                    </h3>
                    <h4 class="product-category"><?php echo ucfirst($this->db->where('category_id', $row['category_id'])->get('categories')->row()->category_name); ?></h4>
                    <p class="product-description"><?php echo $row['short_description']; ?></p>
                    <h4 class="price">
                        <ul>
<!--                            <li><span>Price</span><?php echo getSiteCurrencySymbol('', $row['currency']) . number_format($row['price'], 2); ?></li>-->
                            <li><span>Commission</span><?php echo getSiteCurrencySymbol('', $row['currency']) . number_format($row['orignal_commision'], 2); ?></li>
                        </ul>
                    </h4>
                    <ul class="links">
                        <li><i class="fa fa-link"></i><a href="<?php echo $row['url']; ?>"><?php echo $row['url']; ?></a></li>
                        <li><p><?php echo ucfirst($row['title']); ?></p></li>
                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php //$this->load->view('includes/right_bar') ?>
        <?php //$this->load->view('includes/share_popup') ?>
    </div>
</section>
