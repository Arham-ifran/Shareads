
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 heading-bg">
            <h1><?php echo $pages['title']; ?></h1>
        </div>
    </div>
</div>

<section id="contact-info">
    <div class="container">
        <div class="center wow fadeInDown heading-area">
            <div class="col-md-10 col-sm-12">

            <p class="lead">
                <?php echo $pages['description']; ?>

            </p>


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



            </div>
    </div>
    <?php
                if ($pages['is_contactus'] == 1) {
                    ?>
         <div class="gmap-area">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <div class="gmap">
              <iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=<?php echo htmlentities(ADMIN_ADDRESS);?>&sspn=16.220119,33.596191&t=h&ie=UTF8&z=14&output=embed"  frameborder="0" style="border:0">
            </iframe>


          </div>
        </div>
      </div>
    </div>
  </div>
                <?php }?>



</section>



