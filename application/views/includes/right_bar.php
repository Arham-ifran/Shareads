<div class="col-md-3 col-xs-12">
<h4 class="filter_heading">Our Partners</h4>
    <div class="add_sec">

        <!--    / Ads Area/-->
        <?php
        $adv = get_ad_code(4);
        //echo '<pre>';        print_r($adv); exit;
        if (count($adv) > 0) {
            $ad_url = 'javascript:void(0);';
            if ($adv['url'] <> '') {
                $ad_url = $adv['url'];
            }
            if ($adv['is_banner'] == 1) {
                ?>
                <div class="adds">
                    <a href="<?php echo $ad_url; ?>" target="_blank"><img src="<?php
                        if ($adv['images'] == '') {
                            $adv['images'] = 'abc.png';
                        }
                        echo $this->common->check_image(base_url('uploads/announcements/pic/' . $adv['images']), 'no_image.jpg');
                        ?>"></a>
                </div>
            <?php } else { ?>
                <div class="adds">
                    <a href="<?php echo $ad_url; ?>" target="_blank">
                        <?php
                        echo $adv['bannerCode'];
                        ?></a>
                </div>
                <?php
            }
        }
        ?>
        <!--    / Ads Area/-->

        <div class="space-4"></div>

        <!--    / Ads Area/-->
        <?php
        $adv2 = get_ad_code(4, $adv['ads_id']);
        if (count($adv2) > 0) {
            $ad_url = 'javascript:void(0);';
            if ($adv2['url'] <> '') {
                $ad_url = $adv2['url'];
            }
            if ($adv2['is_banner'] == 1) {
                ?>
                <div class="adds">
                    <a href="<?php echo $adv2['url']; ?>"><img src="<?php
                        if ($adv2['images'] == '') {
                            $adv2['images'] = 'abc.png';
                        }
                        echo $this->common->check_image(base_url('uploads/announcements/pic/' . $adv2['images']), 'no_image.jpg');
                        ?>"></a>
                </div>
            <?php } else { ?>
                <div class="adds">
                    <a href="<?php echo $ad_url; ?>" target="_blank">
                        <?php
                        echo $adv2['bannerCode'];
                        ?></a>
                </div>
                <?php
            }
        }
        ?>
        <!--    / Ads Area/-->
        <!--<div class="space-4"></div>
        <div class="adds">
        Case Studies<br />
        See how access, assessment, and analytics drive business performance and roi. <br />
        <a target="_blank" href="<?php echo base_url(); ?>case studies/CS_01_driving business performance and roi.pdf">Download case study</a>
        <div class="space-4"></div>
        Virtual training can elevate the customer experience by delivering consistent content  that drives engagement and effectiveness.<br />
        <a target="_blank" href="<?php echo base_url(); ?>case studies/CS_02_xerox_article04.pdf">Download case study</a>
        <div class="space-4"></div>
        Given the time and money invested in sales training each year, showing an ROI requires driving adoption at an individual, team, and organizational level. This white paper outlines six steps used by the most effective sales organizations to manage change. <br />
        <a target="_blank" href="<?php echo base_url(); ?>case studies/CS_03_Six Steps to drive adoption of your sales methodology.pdf">Download case study</a>
        </div>-->
    </div>
</div>