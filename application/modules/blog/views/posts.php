<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/js/progress/jqprogress.min.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/css/blog.min.css'); ?>"/>

<section class="pages-content">
    <div class="container">
        <div class="row" > 
            <div class="col-xs-5"><div class="green_line"></div></div>  
            <div class="col-xs-2"><h1 class="generic_heading decrease_mg">Blog</h1></div>
            <div class="col-xs-5"><div class="green_line_2"></div></div>  
        </div>
        <div class="row">
            <div class="content-wrapper">

                <?php $this->load->view('blog/blog_left'); ?>
                <input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id; ?>" />


                <div class="col-md-9 col-sm-8">

                    <div class="blog-heading">
                        <h2><?php echo $posts['post_title']; ?></h2>
                    </div>



                    <div class="about-content">
                        <div class="blogPosts">

                            <div class="comment-heading">


                                <div class="pull-left">

                                    <span><?php echo date("F", $posts['created']); ?></span><span class="postdate"><?php echo date(" d, Y", $posts['created']); ?></span>

                                    <div class="clearfix"></div>
<br />

                                    <span class="likes"><i class="fa fa-thumbs-up"></i>&nbsp;Likes (<?php echo $posts['like_counter']; ?>)</span> |


                                    <?php
                                    if ($posts['nCounts'] > 0) {
                                        ?>
                                        <span class="comments"><i class="fa fa-comment"></i>&nbsp;comments (<?php echo $this->blog_model->totalComentsCounter($posts['post_id']); ?>)</span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="comments"><i class="fa fa-comment"></i>&nbsp;No comments</span>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <div class="pull-right">

                                    <div class="likeB pull-left" style="padding-top: 12px;">
                                        <?php if ($isLike < 1) { ?>

                                            <a href="javascript:void(0)" id="<?php echo $posts['post_id'] ?>_like" onclick="like('<?php echo $posts['post_id'] ?>')"><i class="fa fa-thumbs-up fa-2x"></i></a>

                                        <?php } else { ?>

                                            <a  href="javascript:void(0)" class="already_liked" id="<?php echo $posts['post_id'] ?>_like" onclick="dislike('<?php echo $posts['post_id'] ?>')"><i class="fa fa-thumbs-up fa-2x"></i></a>

                                        <?php } ?>&nbsp;<span id="like_countr_<?php echo $posts['post_id'] ?>" class="counter_class"><?php echo $posts['like_counter'] ?></span>

                                    </div>

                                    <?php $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
                                    <!-- AddToAny BEGIN -->
                                    <!-- AddToAny BEGIN -->
                                    <div class="a2a_kit a2a_kit_size_32 a2a_default_style pull-right">
                                        <a class="a2a_button_facebook"></a>
                                        <a class="a2a_button_twitter"></a>
                                        <a class="a2a_button_linkedin"></a>
                                    </div>
                                    <script type="text/javascript" src="//static.addtoany.com/menu/page.js"></script>
                                    <!-- AddToAny END -->
                                </div>


                                <div class="clearfix"></div>
<br />
                                <div>

                                    <p class=""><?php echo $posts['short_description']; ?></p>

                                    <div class="space-6"></div>

                                    <p class=""><?php echo $posts['description']; ?></p>

                                </div>


                            </div>

                        </div>




                        <div class="postCommentsArea">
                            <?php
                            if ($this->session->userdata('user_id') != "") {
                                ?>
                                <h2>Add your Comments</h2><br />


                                <form id="formPostsComments"  role="form" method="post" accept-charset="utf-8">



                                    <div class="form_wrapper">

                                        <textarea class="form-control input-md" id="comment" name="comment"></textarea>
                                        <script type="text/javascript">/*<![CDATA[*/(function (a) {
                                                    a.fn.autoResize = function (j) {
                                                        var b = a.extend({onResize: function () {
                                                            }, animate: true, animateDuration: 150, animateCallback: function () {
                                                            }, extraSpace: 20, limit: 1000}, j);
                                                        this.filter('textarea').each(function () {
                                                            var c = a(this).css({resize: 'none', 'overflow-y': 'hidden'}), k = c.height(), f = (function () {
                                                                var l = ['height', 'width', 'lineHeight', 'textDecoration', 'letterSpacing'], h = {};
                                                                a.each(l, function (d, e) {
                                                                    h[e] = c.css(e)
                                                                });
                                                                return c.clone().removeAttr('id').removeAttr('name').css({position: 'absolute', top: 0, left: -9999}).css(h).insertBefore(c)
                                                            })(), i = null, g = function () {
                                                                f.height(0).val(a(this).val()).scrollTop(10000);
                                                                var d = Math.max(f.scrollTop(), k) + b.extraSpace, e = a(this).add(f);
                                                                if (i === d) {
                                                                    return
                                                                }
                                                                i = d;
                                                                if (d >= b.limit) {
                                                                    a(this).css('overflow-y', '');
                                                                    return
                                                                }
                                                                b.onResize.call(this);
                                                                b.animate && c.css('display') === 'block' ? e.stop().animate({height: d}, b.animateDuration, b.animateCallback) : e.height(d)
                                                            };
                                                            c.unbind('.dynSiz').bind('keyup.dynSiz', g).bind('keydown.dynSiz', g).bind('change.dynSiz', g)
                                                        });
                                                        return this
                                                    }
                                                })(jQuery);
                                                $('textarea#comment').autoResize(); /*]]>*/</script>
                                    </div>

                                    <div class="bottom-controls">

                                        <a class="btn btn-success pull-right animation animated-item-3" onclick="return formPostsComments();" style="cursor:pointer;">Submit</a>
                                    </div>

                                </form>

                                <input type="hidden" id="member_photo" name="member_photo" value="
                                <?php
                                if (trim($userdata['photo']) == "") {
                                    $userdata['photo'] = "abc.png";
                                }
                                echo $this->common->is_person_image_exist(base_url("uploads/users/medium/" . $userdata['photo']), $userdata['gender'])
                                ?>"/>

                                <?php
                            }
                            ?>


                            <div class="clearfix"></div>

                            <h2>Comments</h2>
                            <?php
                            if (count($comments) > 0) {
                                echo '<span id="newComnt">';
                                foreach ($comments as $comment) {
                                    ?>


                                    <div class="us-op">


                                        <img src="<?php
                                        if (trim($msg['photo']) == "") {
                                            $msg['photo'] = "abc.png";
                                        }
                                        echo $this->common->is_person_image_exist(base_url("uploads/users/medium/" . $comment['photo']), $comment['gender']);
                                        ?>" alt="<?php echo $comment['full_name'] ?>" class="" />

                                        <div class="pull-left comentDiv">

                                        <span>
                                            <?php echo $comment['user_name']; ?>
                                        </span>
                                        <small class="pull-right"><?php echo date("F d, Y g:i A", $comment['created']); ?></small>



                                        <p class="cmnt-head-area-cntnt"><?php echo $comment['comment']; ?>
                                        </p>

                                        </div>
                                        <div class="clearfix"></div>
                                    </div>


                                    <?php
                                }
                                echo '</span>';
                            } else {
                                ?><span id="newComnt">

                                <div class="space-4"></div>

                                <div class="alert alert-warning">Yes there is no comments</div>
                                </span>


                                <?php
                            }
                            ?>

                        </div>
                        <input type="hidden" id="commentsCounter" value="<?php echo count($comments)>0?count($comments):0?>" />

                        <ul class="pagination"> <?php echo $pagination; ?></ul>




                    </div>
                </div>


            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url('assets/site/js/progress/jqprogress.min.js'); ?>"></script>