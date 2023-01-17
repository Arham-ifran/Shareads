<style>
    .collapse_arrow:hover{
        background: transparent;
    }
    .collapse_arrow {
    padding: 7px 16px;
}
</style>
<div class="hidden-sm hidden-xs col-md-3 col-sm-3">
    <div class="white_box" style="padding-bottom: 0px">
        <div class="filter_heading" style="margin-bottom: 0px">
            
            <h5><i class="fa fa-tags fa-fw"></i>Categories</h5>
        </div>
        <div class=" in" id="">
            <ul class="left_filters left_collapse" style="min-height: auto">
            <?php
            foreach ($categories[0] as $cat) {
                if (array_key_exists($cat->category_id, $categories)) {
                    ?>
                    <li>
                        <a data-toggle="collapse" class="collapsed" href="#cats<?php echo $cat->category_id; ?>" aria-controls="cats<?php echo $cat->category_id; ?>">
                            <h5 class="fields_title">
                                <?php echo $cat->category_name; ?> <i class="fa fa-caret-down fa-fw"></i>
                            </h5>
                        </a>

                        <div class="collapse " id="cats<?php echo $cat->category_id; ?>">
                            <?php
                            foreach ($categories[$cat->category_id] as $c) {
                                echo '<a href="' . base_url('marketing/' . $c->category_slug) . '"><i class="fa fa-angle-right pull-left"></i>&nbsp; ' . $c->category_name . '</a>';
                            }
                            ?>
                        </div>

                    </li>
                    <?php
                } else {
                    ?>

                    <li>
                        <a href="<?php echo base_url('marketing/' . $cat->category_slug); ?>">
                            <h5 class="fields_title"><?php echo $cat->category_name; ?></h5>
                        </a>

                    </li>

                <?php
                }
            }
            ?>

        </ul>
        </div>
    </div>
    
</div>
<div class="hidden-md hidden-lg  col-md-3 col-sm-3">
    <div class="white_box" style="padding-bottom: 0px">
        <div class="filter_heading" style="margin-bottom: 0px">
            <a  href="#linksShared" class="collapse_arrow" data-toggle="collapse"></a>
            
            <h5><i class="fa fa-tags fa-fw"></i>Categories</h5>
        </div>
        <div class="collapse" id="linksShared">
            <ul class="left_filters left_collapse" style="min-height: auto">
            <?php
            foreach ($categories[0] as $cat) {
                if (array_key_exists($cat->category_id, $categories)) {
                    ?>
                    <li>
                        <a data-toggle="collapse" class="collapsed" href="#catss<?php echo $cat->category_id; ?>" aria-controls="catss<?php echo $cat->category_id; ?>" >
                            <h5 class="fields_title">
                                <?php echo $cat->category_name; ?> <i class="fa fa-caret-down fa-fw"></i>
                            </h5>
                        </a>

                        <div class="collapse" id="catss<?php echo $cat->category_id; ?>">
                            <?php
                            foreach ($categories[$cat->category_id] as $c) {
                                echo '<a href="' . base_url('marketing/' . $c->category_slug) . '"><i class="fa fa-angle-right pull-left"></i>&nbsp; ' . $c->category_name . '</a>';
                            }
                            ?>
                        </div>

                    </li>
                    <?php
                } else {
                    ?>

                    <li>
                        <a href="<?php echo base_url('marketing/' . $cat->category_slug); ?>">
                            <h5 class="fields_title"><?php echo $cat->category_name; ?></h5>
                        </a>

                    </li>

                <?php
                }
            }
            ?>

        </ul>
        </div>
    </div>
    
</div>

