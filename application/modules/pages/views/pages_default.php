
<section id="contact-info">
    <div class="container">

        <div class="row"> 
            <div class="col-xs-4"><div class="green_line"></div></div>  
            <div class="col-xs-4"><h1 class="generic_heading decrease_mg"><?php echo $pages['title']; ?></h1></div>
            <div class="col-xs-4"><div class="green_line_2"></div></div>  
        </div> 
        <div class="center wow fadeInDown heading-area  animated" style="visibility: visible; animation-name: fadeInDown;"">
            <div class="col-md-9 col-sm-12">

                <?php echo $pages['description']; ?>

                <?php
                $get_sbmenu = get_subMenu_left($pages['cmId']);

                if (count($get_sbmenu) > 0) {
                    ?>

                    <div class="white-bg custom-tab">
                        <ul class="nav nav-tabs tabs-left">
                            <?php
                            $i = 1;
                            foreach ($get_sbmenu as $dropDown) {
                                ?>
                                <li class="<?php echo($i == 1) ? "active" : "" ?>"><a href="#<?php echo $dropDown['slug'] ?>" data-toggle="tab"><b><?php echo $dropDown['title'] ?></b></a></li>
                                <?php
                                $i++;
                            }
                            ?>

                        </ul>
                    </div>

                    <div class="about-content">
                        <div class="tab-content">
                            <?php
                            $i = 1;
                            foreach ($get_sbmenu as $pg) {
                                ?>
                                <div class="tab-pane <?php echo($i == 1) ? "active" : "" ?>" id="<?php echo $pg['slug'] ?>">
                                    <div class="space-6"></div>
                                    <h4><?php echo $pg['title'] ?></h4>
                                    <div class="space-6"></div>

                                    <?php echo $pg['description']; ?>
                                </div>
                                <?php
                                $i++;
                            }
                            ?>

                        </div>
                    </div>


                <?php } ?>



                <p>
                    <?php
                    if ($pages['is_contactus'] == 1) {
                        $this->load->view('pages/contactus');
                    }
                    ?>

                </p>

            </div>


            <?php $this->load->view('includes/right_bar') ?>
        </div>
    </div>
    <?php
    if ($pages['is_contactus'] == 1) {
        ?>
        <div class="gmap-area">

            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="gmap">
                        
                        <img src="<?php echo base_url('assets/address.png');?>"/>
                        <!--<img id="loader" src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif" />-->
                        <!--<iframe id="iframe" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src=""  frameborder="0" style="border:0">-->
                        <!--</iframe>-->
                    </div>
                </div>
            </div>
            <span style="display: none" id="address1"><?php echo htmlentities(trim(ADMIN_ADDRESS)); ?></span>
        </div>
    <?php } ?>
    
    
    
    
    <script>
        $(document).ready(function () {
//            var iframe = document.getElementById("iframe"); // create or get iframe, make sure no src parameter at first
//            var unload = function () {
//                $("#loader").show();
//                // show loading gif here
//            };
//
//            var load = function () {
//                // hide loading gif here
//                $("#loader").hide();
//                iframe.contentWindow.onbeforeunload = unload;
//                iframe.onload = load;
//            };
//            var address = $("#address1").text();
//            $("#address1").remove();
//            unload();
//            iframe.onload = load;
            
//            "https://maps.googleapis.com/maps/api/staticmap?center="+address+"&zoom=13&size=600x300&maptype=roadmap&markers=color:blue%7Clabel:S%7C40.702147,-74.015794&markers=color:green%7Clabel:G%7C40.711614,-74.012318&markers=color:red%7Clabel:C%7C40.718217,-73.998284&key=YOUR_API_KEY";
//            iframe.src = 'https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=' + address +'&sspn=51.293008,-1.069256&t=h&maptype=roadmap&ie=UTF8&z=14&output=embed';

        });
    </script>


</section>



