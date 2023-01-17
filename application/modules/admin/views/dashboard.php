<div class="breadcrumbs ace-save-state" id="breadcrumbs">


    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="#">Home</a>
        </li>
        <li class="active">Dashboard</li>
    </ul><!-- /.breadcrumb -->


</div>

<div class="page-header">
    <h1>
        Dashboard
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            overview &amp; stats
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert">
                <i class="ace-icon fa fa-times"></i>
            </button>

            <i class="ace-icon fa fa-check green"></i>

            Welcome to
            <strong class="green">
                <?php echo ucwords(SITE_NAME); ?> Admin Panel

            </strong>

        </div>

        <div class="row">
            <div class="space-6"></div>

            <div class="col-sm-12 infobox-container">


                <div class="infobox infobox-black">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-building-o"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $new_publishers; ?></span>
                        <div class="infobox-content">Publishers</div>
                    </div>

                </div>

                <div class="infobox infobox-pink">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-list"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $listing_counter ?></span>
                        <div class="infobox-content">Listings</div>
                    </div>

                </div>

    
    <div class="clearfix"></div>
                <div class="space-10"></div>


                <div class="infobox infobox-purple">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-users"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $new_advertiser_bankclaimfund ?></span>
                        <div class="infobox-content">Bank Refund Claims</div>
                    </div>

                </div>
                <div class="clearfix"></div>
                <div class="space-10"></div>


                <div class="infobox infobox-purple">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-users"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $new_advertiser ?></span>
                        <div class="infobox-content">Advertiser</div>
                    </div>

                </div>



                <div class="infobox infobox-blue2">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-newspaper-o"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $total_ads ?></span>
                        <div class="infobox-content">Announcements</div>
                    </div>

                </div>


                <div class="infobox infobox-blue">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-list-ol"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $categories ?></span>
                        <div class="infobox-content">Categories</div>
                    </div>

                </div>
                <div class="infobox infobox-blue">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-list-ol"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $subcategories; ?></span>
                        <div class="infobox-content">Sub Categories</div>
                    </div>

                </div>

                <div class="clearfix"></div>
                <div class="space-10"></div>

                <div class="infobox infobox-grey">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-pagelines"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $total_cms ?></span>
                        <div class="infobox-content">CMS Pages</div>
                    </div>

                </div>


                <div class="space-6"></div>

                <div class="infobox infobox-grey">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-share-square"></i>
                    </div>
                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $total_invitation_sent ?></span>
                        <div class="infobox-content">Total Invitations Sent</div>
                    </div>
                </div>
                <div class="infobox infobox-grey">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-share-square"></i>
                    </div>
                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $total_accepted_invitations ?></span>
                        <div class="infobox-content">Accepted Invitation</div>
                    </div>
                </div>
                <h2>Sales Statistics</h2>
                <?php
                $facebook    = $twitter     = $email       = $linkedin    = $direct_link = 0;
                $_sql        = " Select ut.* from c_usertracking as ut LEFT join c_orders as ordr ON ordr.user_tracking = ut.id where ordr.order_status = 2   ORDER BY `id` DESC ";
                $product_chart = $this->db->query($_sql)->result_array();
                foreach ($product_chart as $key => $row)
                {
                    if (strpos($row['referer_page'], 'facebook') !== false)
                    {
                        $facebook = $facebook + 1;
                    }
                    else if (strpos($row['referer_page'], 'twitter') !== false)
                    {
                        $twitter  = $twitter + 1;
                    }
                    else if ($row['referer_page'] == 'email')
                    {
                        $email = $email + 1;
                    }
                    else if ($row['referer_page'] == 'linkedin')
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
                        <i class="ace-icon fa fa-facebook"></i>
                    </div>
                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $facebook ?></span>
                        <div class="infobox-content"> Facebook </div>
                    </div>
                </div>
                <div class="infobox infobox-grey">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-twitter"></i>
                    </div>
                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $twitter ?></span>
                        <div class="infobox-content"> Twitter</div>
                    </div>
                </div>
                <div class="infobox infobox-grey">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-envelope"></i>
                    </div>
                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $email ?></span>
                        <div class="infobox-content"> Email</div>
                    </div>
                </div>
                <div class="infobox infobox-grey">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-linkedin"></i>
                    </div>
                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $linkedin ?></span>
                        <div class="infobox-content"> Linkedin</div>
                    </div>
                </div>
                <div class="infobox infobox-grey">
                    <div class="infobox-icon">
                        <i class="ace-icon fa fa-external-link"></i>
                    </div>
                    <div class="infobox-data">
                        <span class="infobox-data-number"><?php echo $direct_link ?></span>
                        <div class="infobox-content"> Direct Link</div>
                    </div>
                </div>



            </div>

            <div class="vspace-12-sm"></div>


        </div><!-- /.row -->

        <div class="hr hr32 hr-dotted"></div>

        <div class="row">



            <!-- /.col -->
        </div><!-- /.row -->

        <div class="hr hr32 hr-dotted"></div>


        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
<!-- /.page-content -->

