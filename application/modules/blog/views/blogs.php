<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/css/blog.min.css'); ?>"/>

<section class="pages-content">
    <div class="container">
        <div class="row"> 
            <div class="col-xs-5"><div class="green_line"></div></div>  
            <div class="col-xs-2"><h1 class="generic_heading decrease_mg">Blog</h1></div>
            <div class="col-xs-5"><div class="green_line_2"></div></div>  
        </div>
        <div class="row">
            <div class="content-wrapper">
                <?php $this->load->view('blog/blog_left'); ?>

                <div class="col-md-9 col-sm-8">
                    <div class="blog-heading">
                        <h2>Latest Blog Posts</h2>
                    </div>
                    <div class="about-content">
                        <div class="blogPosts">
                            <?php
                            if (count($all_posts) > 0) {
                                foreach ($all_posts as $post) {
                                    ?>
                                    <div class="comment-heading">

                                        <h3><a href="<?php echo base_url() . 'blog/posts/' . $this->common->encode($post['post_id']); ?>"><?php echo $post['post_title']; ?></a></h3>
                                        <span class=""><?php echo date("F", $post['created']); ?></span><span class="postdate"><?php echo date(" d, Y", $post['created']); ?></span>
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="thumnail"><img src="<?php echo base_url('uploads/blogs/pic/' . $post['photo']); ?>" alt=""></div>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class=""><?php echo $post['short_description']; ?>
                                                    <a class="blue_btn animation animated-item-3" href="<?php echo base_url() . 'blog/posts/' . $this->common->encode($post['post_id']); ?>">Read More</a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <span class="likes"><i class="fa fa-thumbs-up"></i>&nbsp;Likes (<?php echo $post['like_counter']; ?>)</span> |

                                        <?php
                                        if ($post['nCounts'] > 0) {
                                            ?>
                                            <span class="comments"><i class="fa fa-comment"></i>&nbsp;comments (<?php echo $n = $this->blog_model->totalComentsCounter($post['post_id']); ?>)</span>
                                            <?php
                                        } else {
                                            ?>
                                            <span class="comments"><i class="fa fa-comment"></i>&nbsp;No comments</span>
                                            <?php
                                        }
                                        ?>
                                        <div class="clearfix space-4"></div>
                                    </div>
                                    <hr>
                                <?php }
                                ?>

                                <ul class="pagination  pagi pull-right"> <?php echo $pagination; ?>  </ul>

                            <?php } else {
                                ?>

                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>