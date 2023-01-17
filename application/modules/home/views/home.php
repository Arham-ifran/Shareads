<section id="get" class="get_section text-center">
    <div class="container wow animated fadeInDown">
        <h1>Get paid for sharing adverts, it's that simple</h1>
        <p>Becoming a member of ShareAds is completely free. Once you’ve joined our network,
            we’ll provide you with advertising links to share across your social media. We will then pay
            you a small fee for activity resulting from the links that you share.<br />
            <br />
            The more you share, the more money you make. We operate a strict code of conduct, and
            we never allow third parties or our advertisers to contact you direct.  Why not sign up today? <br />
            <strong>The sooner you start sharing, the sooner you’ll start earning.</strong></p>
        <?php
        if ($this->session->userdata('user_id')) {
            ?>
            <a href="<?php echo base_url('dashboard') ?>" class="btn btn-primary btn-lg button-start">START SHARING & EARNING</a>
        <?php } else {
            ?>
            <a href="<?php echo base_url('register') ?>" class="btn btn-primary btn-lg button-start">START SHARING & EARNING</a>
        <?php } ?>
    </div>

</section>
<section class="howitworks text-center">
    <div class="container">
        <h1 class="wow animated fadeInDown">How it works, it’s not rocket science</h1>
        <div class="col-sm-12 ad_main wow fadeInLeft">
            <div class="ad"><img src="<?php echo base_url('assets/site/images/ad.png') ?>" alt="" /></div>
            <p><span>The advertiser provides shareads with an advert to share</span></p>
        </div>
        <div class="ad_main_arrow wow fadeInLeft"></div>
        <div class="col-sm-12 ad_main wow animated fadeInDown">
            <div class="ad"><img src="<?php echo base_url('assets/site/images/ad2.png') ?>" alt="" /></div>
            <p><span>ShareAds passes on the link to this advert to members</span></p>
        </div>
        <div class="ad_main_arrow wow fadeInRight"></div>
        <div class="col-sm-12 ad_main wow fadeInRight">
            <div class="ad"><img src="<?php echo base_url('assets/site/images/ad3.png') ?>" alt="" /></div>
            <p><span>ShareAds members share the link via their social channels</span></p>
        </div>

        <div class="col-sm-12 wow fadeInRight animated " style="visibility: visible;">
            <video style="width:100% !important;" preload="none" controls="" poster="<?php echo base_url('assets/admin/img/poster.PNG'); ?>" class="img-thumbnail" >
                <source src="<?php echo base_url('assets/admin/img/shareads.mp4'); ?>" type="video/mp4">
            </video>
        </div>
        <div class="clearfix"></div>
        <div class="divider " style="margin-top: 20px"></div>
        <p class="wow animated fadeInDown">Advertisers have been paying “affiliates? with an online presence for many years.  Suppose you’re a florist. You decide to pay various other businesses who have an online presence to carry your advert. You then pay these “affiliates? according to the activity that adverts on their sites generate.<br />
            <br />
            What’s new about the ShareAds idea is that we are the affiliate, but an affiliate with a difference.
            We pass on each link to our members, and the advert is displayed on their social media feeds. Currently, we support sharing via email, Facebook, Twitter, Google+ and Linkedin.</p>
        <?php
        if ($this->session->userdata('user_id')) {
            ?>
            <a href="<?php echo base_url('dashboard') ?>" class="btn btn-primary btn-lg button-start wow animated fadeInDown">START SHARING & EARNING</a>

        <?php } else {
            ?>
            <a href="<?php echo base_url('register') ?>" class="btn btn-primary btn-lg button-start wow animated fadeInDown">START SHARING & EARNING</a>
        <?php } ?>
    </div>
</section>
<section class="account_section text-center">
    <div class="container">
        <h1 class="wow animated fadeInDown">Keeping tabs on your accout</h1>
        <p class="wow animated fadeInDown">As a ShareAds member, you’ll automatically get a ShareAds Account. You can monitor this account via a simple dashboard, which displays all the information that you need to keep track of how things are going.<br />
            <br />
            On your ShareAds Dashboard, you can see:<br />
            What links you have shared<br />
            What activity these links have generated<br />
            What you have been paid by ShareAds so far<br />
            What ShareAds has yet to pay you<br />
            <br />
            We’ll then pay you, once a month, via your PayPal account. </p>
        <?php
        if ($this->session->userdata('user_id')) {
            ?>
            <a href="<?php echo base_url('dashboard') ?>" class="btn btn-primary btn-lg button-start vwow animated fadeInDown">START SHARING & EARNING</a>

        <?php } else {
            ?>
            <a href="<?php echo base_url('register') ?>" class="btn btn-primary btn-lg button-start vwow animated fadeInDown">START SHARING & EARNING</a>

        <?php } ?>
    </div>
</section>
<?php if (isset($_GET) && $_GET['affid'] <> '') { ?>

<?php } ?>
<script src="<?php echo base_url('assets/site/js/shareads.min.js') ?>"></script>
