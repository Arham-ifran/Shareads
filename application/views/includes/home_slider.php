  <section id="main-slider" class="no-margin">
    <div class="carousel slide">
      <ol class="carousel-indicators">
        <li data-target="#main-slider" data-slide-to="0" class="active"></li>
      </ol>
      <div class="carousel-inner">
        <div class="item active" style="background-image:url(<?php echo base_url('assets/site/images/slider-bg-img.jpg'); ?>); background-position:center;">
          <div class="container">
            <div class="row slide-margin">
              <div class="col-sm-6">
                <div class="carousel-content">
                  <h2 class="animation animated-item-1">Share . Monitor . Earn</h2>
                  <p class="animation animated-item-2">Earn Money by simply sharing our links on your website or social networks. ShareAds are trusted experts providing the performance marketing industry a superior service and most importantly. <br><span>RESULTS!</span> </p>
                  <?php
                  if($this->session->userdata('user_id'))
                  {
                  }else{?>
                  <a class="btn btn-lg" href="<?php echo base_url('register')?>">Get Sign Up Today !</a>
                  <?php }?>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="carousel-content">

                  <div class="animation animated-item-2"><img src="<?php echo base_url('assets/site/images/slider-img.png'); ?>"/></div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

    <!--<a class="prev hidden-xs" href="#main-slider" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>-->

    <!--<a class="next hidden-xs" href="#main-slider" data-slide="next"> <i class="fa fa-chevron-right"></i> </a>-->

  </section>

