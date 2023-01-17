<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/js/progress/jqprogress.min.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/css/blog.min.css'); ?>"/>

<style>
.support-search{
    background: #2b4761;
    color: #fff;
    padding: 10px 10px;
    border-radius: 10px;
    margin-bottom: 30px;
}
.support-search form{
/*    background: #8ac229;*/
    border-radius: 25px;
}
.support-search .form-control{
    color: #FFFfff;
    background: none;
    border: none;
    box-shadow: none;
    padding: 10px 30px;
    height: 50px;
    font-size: 16px;
    width: 95%;
    display: inline-block;
}
.support-search button.search-btn{
    background: none;
    border: none;
    padding: 18px;
    float: right;
}
.support-content{
    padding: 0 0;
}
.support .comment-heading{
    border: 1px solid #eee;
    display: block;
    padding: 15px 20px;
    border-radius: 8px;
    margin: 20px 0;
}
.support .comment-heading:hover .blog-heading h2{
    color: #8ac229;
    transition: 0.3s ease-in-out;
}
.support .comment-heading h2{
    color: #2a4761;
    font-weight: 600;
    padding: 10px 0 20px 0px;
    border-bottom: solid 1px #eee;
}
.support .comment-heading p{
    margin: 0 0;
}
.back-btn{
    font-family: 'Avenir Next';
    font-weight: 600;
    background-color: rgba(138,194,41,1);
    -webkit-border-radius: 35px;
    -moz-border-radius: 35px;
    border-radius: 35px;
    font-size: 18px;
    padding: 0px 29px;
    border: none 0;
    line-height: 50px;
    margin: 10px 0;
    letter-spacing: 0.5px;
}

</style>

<section class="pages-content">
    <div class="container">
        <!--        <div class="row" > 
                    <div class="col-xs-5"><div class="green_line"></div></div>  
                    <div class="col-xs-2"><h1 class="generic_heading decrease_mg">Support</h1></div>
                    <div class="col-xs-5"><div class="green_line_2"></div></div>  
                </div>-->
        <div class="row">
            <div class="content-wrapper">
                <input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id; ?>" />
                <div class="col-xs-12">
                    <div class="about-content support-content support">
                        <div class="blogPosts">
                            <div class="comment-heading">
                                <div class="blog-heading">
                                    <h2><?php echo $posts['title']; ?></h2>
                                </div>
                                <div class="clearfix"></div>
                                <div class="clearfix space-4"></div>
                                <div>
                                    <p class=""><?php echo $posts['description']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-primary back-btn" href="<?php echo base_url('support'); ?>">Go back</a>
                </div>
                
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url('assets/site/js/progress/jqprogress.min.js'); ?>"></script>