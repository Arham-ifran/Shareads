
<?php $this->load->view('includes/profile_info'); ?>
<style>
    .share a.btn-share {
        min-width: 90px !important;
    }
    .btn-group-sm>.btn, .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }

</style>
<section class="container" style="padding: 45px">
    <div class="heading_links clearfix">
        <h3 class="main_heading">Marketing</h3>
    </div>
    <div class="row">
        <div class="col-md-9" >
            <p style="font-size: 15px;margin-bottom: 5px;">Simply find a product you are interested in and share it using the social media icons or copy your unique link, if you get a sale check the reporting tab and it will show how much you have made.</p>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <?php $this->load->view('categories'); ?>
            <div class="col-md-6 col-sm-6">
                <form id="listingsSearch" name="listingsSearch" method="get" action="<?php echo base_url('marketing/search'); ?>">
                    <div class="white_box clearfix">
                        <h5 class="filter_heading"><i class="fa fa-search fa-fw"></i> Find Products</h5>
                        <div class="form-group">
                            <div class="col-xs-6">
                                <input type="text" name="query" class="textbox" value="<?php echo $query; ?>">
                            </div>
                            <div class="col-xs-6">
                                <button style="padding:2px 8px;" class="btn btn-primary" >GO</button>
                                &nbsp;&nbsp;<a href="<?php echo base_url('marketing/advance_search') ?>"><i class="fa fa-search fa-fw"></i> Advanced Search</a></div> </div>
                                <div class="col-xs-12">
                                    <label>
                                        <input <?php echo ($type == 'all' || $type == '') ? 'checked' : '' ?> name="type" value="all"  type="radio"/>
                                    All Categories</label>
                                    <label>
                                        <input <?php echo $type == 'popular' ? 'checked' : '' ?> name="type" value="popular"  type="radio"/>
                                    Most Popular</label>
                                    <label>
                                        <input <?php echo $type == 'highest_paying' ? 'checked' : '' ?> name="type" value="highest_paying"  type="radio"/>
                                    Highest Paying</label>
                                    <label>
                                        <input <?php echo $type == 'lowest_paying' ? 'checked' : '' ?> name="type" value="lowest_paying" type="radio"/>
                                    Lowest Paying</label>
                                </div>
                            </div>
                        </form>

                        <div class="row clearfix">
                            <div class="col-md-7 col-sm-7">
                                <h4>Results</h4>
                            </div>
                            <div class="col-md-5 col-sm-5" style=" margin:8px 0; font-size:12px; text-align:right;">Displaying results 1-<?php echo $num_rows > 10 ? '10' : $num_rows ?> out of <?php echo $num_rows ?></div>
                        </div>
                        <?php

                        if ($num_rows > 0)
                        {
                            ?>
                            <ul class="marketResults_list">
                                <?php
                                $i = 0;
                                 foreach ($results as $row)
                                {
                                    ?>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-6">
                                                <div class="title"><?php echo $row->product_name ?></div>
                                                <p class="para"><?php echo $row->title; ?> <span class="price"><?php echo $row->commission; ?></span> </p>
                                                        <p class="para" style="word-wrap: break-word;">
                                                            <?php
                                                            $img = getVal('image', 'c_product_images', 'product_id', $row->product_id);
                                                            if ($img <> '')
                                                            {
                                                                $image = $this->common->check_image(base_url("uploads/products/small/" . $img), 'no_image.jpg');
                                                                echo '<img src="' . $image . '" style="width: 120px; float: left; margin: 0px 5px 4px 0px;" />';
                                                            }
                                                            echo $row->short_description;
                                                            ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="share clearfix">
                                                            <input type="hidden" id="i-<?php echo $row->product_id; ?>" value="<?php echo $i; ?>"/>
                                                            <?php
                                                            $id        = $this->common->encode($row->product_id);
                                                            $share_url = $row->url . '?prd=' . $id . '&affid=' . $this->session->userdata('user_key');
                                                            ?>
                                                            
                                                            <a onclick="fbShare('<?php echo $share_url . '&type=fb' ?>', '<?php echo $row->product_id; ?>','<?php echo $share_url; ?>')" class="pull-left btn btn-primary btn-sm btn-share "><i class="fa fa-facebook"></i> Facebook</a>
                                                            <?php /* <div onclick="shareLinkCopy('<?php echo $row->product_id; ?>', '2',,'<?php echo $share_url; ?>');" class="<?php echo $row->product_id ?>" style="display: none;"></div> */?>
                                                            <a style="background: #1b95e0; color: #fff;"  onclick="twShare('<?php echo $share_url . '&type=tw'; ?>','<?php echo $row->product_id; ?>','<?php echo $share_url; ?>')"  class=" pull-left btn btn-sm"><i class="fa fa-twitter"></i>Tweet&nbsp; </a>
                                                            <?php
                                                            $emailUrl = '?subject=' . $row->product_name . '&body=';
                                                            ?>
                                                            <a onclick="eShare('<?php echo base_url('detail') . '?prd=' . $id . '&affid=' . $this->session->userdata('user_key') . '&type=em'; ?>','<?php echo $row->product_id; ?>','<?php echo $row->product_name;  ?>','<?php echo $share_url; ?>');" style="margin-right:3px;" class="btn btn-danger btn-sm btn-share "><i class="fa fa-envelope"></i> Email</a>
                                                            <a  onclick="shareLinkCopy('<?php echo $row->product_id; ?>', '4','<?php echo $share_url; ?>');" class="btn btn-success btn-sm btn-share " ><i class="fa fa-link"></i> Copy Link</a>
                                                            <a onclick="liShare('<?php echo $share_url . '&type=ln'; ?>','<?php echo $row->product_id; ?>','<?php echo $row->product_name ?>','<?php echo $share_url; ?>')" class="btn btn-info btn-sm btn-share "><i class="fa fa-linkedin"></i> LinkedIn</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                    </ul>
                                    <div class="row clearfix">
                                        <ul class="pagination">
                                            <?php echo $pagination; ?>
                                        </ul>
                                    </div>
                                    <div class="alert alert-warning"> We strongly recommend you share once in the morning and once in the evening 7 days per week for best results (Highest Payouts). </div>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <div class="alert ">No record found.</div>
                                <?php } 
                                ?>
                            </div>
                            <?php $this->load->view('includes/right_bar') ?>
                            <?php $this->load->view('includes/share_popup') ?>
                        </div>
                    </section>

                    <script>!function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = p + '://platform.twitter.com/widgets.js';
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, 'script', 'twitter-wjs');

                    window.twttr = (t = {_e: [], ready: function (f) {
                        t._e.push(f);
                    }});
                    window.twttr.ready(function (twttr) {
                        twttr.events.bind('click', function (event) {
                        });

                        twttr.events.bind(
                            'tweet',
                            function (event) {
                                $('#' + event.target.id).prev().trigger('click');

                            });
                    });
                    // $(document).ready(function () {

                        $.ajaxSetup({cache: true});
                        $.getScript('//connect.facebook.net/en_US/sdk.js', function () {
                            FB.init({
                                appId: '<?php echo FACEBOOK_APPID ?>',
                                version: 'v2.5'
                            });
                        });
                    // });
                    function fbShare(_url, id,share_url) {
                        
                            $.post(BASE_URL+"marketing/get_bitly_url", {
                                url: _url
                            },
                            function(response) {
                               var url = 'https://www.facebook.com/sharer/sharer.php?u='+response;
                            window.open(url,id, 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                                shareLinkCopy(id, '1',share_url,false);
                            });
                    }
                    function liShare(_url,id,name,share_url) {
                           
                            $.post(BASE_URL+"marketing/get_bitly_url", {
                                url: _url
                            },
                            function(response) {
                               var url = 'http://www.linkedin.com/shareArticle?url='+response+'&title='+name;
                                window.open(url,id, 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                                shareLinkCopy(id, '5',share_url,false);
                            });
                    }
                    function twShare(_url,id,share_url) {
                            
                            $.post(BASE_URL+"marketing/get_bitly_url", {
                                url: _url
                            },
                            function(response) {
                             
                            var url = 'https://twitter.com/intent/tweet?hashtags=ShareAds&amp;original_referer='+BASE_URL+'marketing'+';ref_src=twsrc%5Etfw&amp;text=ShareAds&amp;tw_p=tweetbutton&amp;url='+response;
                            window.open(url,id, 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                                 shareLinkCopy(id, '2',share_url,false);
                            });
                    }
                    
                    function eShare(_url,id,name,share_url){
                        
                         $.post(BASE_URL+"marketing/get_bitly_url", {
                                url: _url
                            },
                            function(response) {
                            var url = 'mailto:'+'?subject='+name+'&body='+response; 
                            window.open(url,'_self');
                            shareLinkCopy(id, '3',share_url,false);  
                            });    
                       
                    }
                </script>
                <style>
                    .share .btn-sm{     margin: 6px 0px 0 5px; float:left;}
                    .twitter-share-button{ float:left !important; margin:6px 6px 0;}
                    .IN-widget span {
                        margin: 0 !important;
                    }
                    .btn{
                        margin-right: 5px !important;
                    }
                </style>
                <script>
                    $(document).ready(function () {
                        $('input[name=type]').click(function () {
                            $('#listingsSearch').submit();
                        });
                        if (window.location.hash === "#_=_"){
    history.replaceState 
        ? history.replaceState(null, null, window.location.href.split("#")[0])
        : window.location.hash = "";
}
                    });
                </script>