<style>
    .custom-tab ul.nav-tabs>li>a{
        background: transparent !important;
    }
    .active{
        background: #d6d6d6 !important;
    }
</style>
<div class="col-sm-3">
    <div class="grey-bar">
        <h2>Categories</h2>
    </div>
    <div class="white-bg custom-tab">
        <ul class="nav nav-tabs tabs-left tabs-leftBlog">
            <?php
           // echo $cat_id;
     //  echo '<pre>';            print_r($categories); die();
            foreach ($categories as $category) {
                ?>
            <li <?php if($category['id'] == $cat_id) echo 'class = "active"'; ?>><a href="<?php echo base_url() . 'blog/category/' . $this->common->encode($category['id']); ?>"><?php echo $category['category']; ?> <span>(<?php echo $category['nPosts']; ?>)</span></a></li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>