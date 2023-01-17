<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/site/css/support.min.css'); ?>"/>

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
        width: 80%;
        display: inline;
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
    .support .comment-heading h2 a{
        color: #2a4761;
    }
    .more-btn{
        margin-top: 20px;
        display: block;
        font-size: 15px;
        font-weight: 600;
        color: #8ac229;
    }

</style>
<style>
    .autocomplete {
        position: relative;
        display: block;
    }
    .autocomplete-items {
        position: absolute;
        border: none;
        z-index: 9;
        top: 100%;
        left: 0;
        right: 0;
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        max-height: 275px;
        overflow: auto;
    }
    /* width */
    .autocomplete-items::-webkit-scrollbar {
        width: 8px;
    }

    /* Track */
    .autocomplete-items::-webkit-scrollbar-track {
        background: #2a4761; 
    }

    /* Handle */
    .autocomplete-items::-webkit-scrollbar-thumb {
        background: #8ac229; 
    }

    /* Handle on hover */
    .autocomplete-items::-webkit-scrollbar-thumb:hover {
        background: #8ac229; 
    }
    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        font-weight: 100;
        font-size: 12px;
        background-color: #fff;
        border-bottom: 1px solid #eee;
        color: #2a4761;
    }
    .autocomplete-items div:hover {
        background: #8ac229;
        font-weight: 100;
        font-size: 12px;
        color: #fff;
        transition: 0.3s ease-in-out;
    }
    .autocomplete-active {
        background-color: DodgerBlue !important; 
        color: #ffffff; 
    }
</style>

<section class="pages-content">
    <div class="container">
        <div class="row"> 
            <div class="col-xs-5"><div class="green_line"></div></div>  
            <div class="col-xs-2"><h1 class="generic_heading decrease_mg">Support</h1></div>
            <div class="col-xs-5"><div class="green_line_2"></div></div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="support-search">
                    <form id="support_form" autocomplete="off" method="post" action="<?php echo base_url('support'); ?>">
                        <div class="autocomplete">
                            <input type="search" id="myInput" name="search_support" class="form-control" placeholder="Search here..." value="<?php echo $search_support; ?>" autocomplete="">
                            <button type="submit" class="search-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="content-wrapper">
                <div class="col-md-12">
                    <div class="about-content support-content">
                        <div class="support">
                            <?php
                            if (count($all_posts) > 0)
                            {
                                foreach ($all_posts as $post)
                                {
                                    ?>
                                    <div class="comment-heading">
                                        <h2><a href="<?php echo base_url() . 'support/posts/' . $this->common->encode($post['id']).'/' . $this->common->encode($post['id']).'/'.$mobile_link; ?>"><?php echo $post['title']; ?></a></h2>
                                        <div class="clearfix"></div>
                                        <div class="clearfix space-4"></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <p class="">
                                                    <?php
                                                    $dot = '';
                                                    if (count(explode(' ', $post['description'])) > 25)
                                                    {
                                                        $dot = '...';
                                                    }
                                                    echo implode(' ', array_slice(explode(' ', strip_tags($post['description'])), 0, 25)) . $dot;
                                                    ?>
                                                    <a class="blue_btn animation animated-item-3 more-btn" href="<?php echo base_url() . 'support/posts/' . $this->common->encode($post['id']).'/' . $this->common->encode($post['id']).'/'.$mobile_link; ?>">Read More</a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="clearfix space-2"></div>
                                    </div>
                                <?php }
                                ?>

                                <ul class="pagination  pagi pull-right"> <?php echo $pagination; ?>  </ul>

                                <?php
                            }
                            else
                            {
                                ?>
                                <div class="alert alert-info">
                                    <?php echo (trim($search_support) <> '')? '<strong>'.$search_support.'</strong>' : ''; ?> No Result found related to your query.
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var titles = [<?php
                            foreach ($all_posts_titles as $key => $value)
                            {
                                if ($key > 0)
                                {
                                    echo ',';
                                } echo '"' . ucfirst($value['support_title']) . '"';
                            }
                            ?>];
    function autocomplete(inp, arr) {
        /*the autocomplete function takes two arguments,
         the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function (e) {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) {
                return false;
            }
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/



                if (arr[i].toUpperCase().indexOf(val.toUpperCase()) >= 0) {
                    b = document.createElement("DIV");
                    b.innerHTML = "<strong>" + arr[i] + "</strong>";
                    b.innerHTML += arr[i];
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    b.addEventListener("click", function (e) {
                        inp.value = this.getElementsByTagName("input")[0].value;
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        inp.addEventListener("keydown", function (e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x)
                x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                 increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                 decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    if (x)
                        x[currentFocus].click();
                }
                $('#support_form').submit();
            }
        });
        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x)
                return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length)
                currentFocus = 0;
            if (currentFocus < 0)
                currentFocus = (x.length - 1);
            x[currentFocus].classList.add("autocomplete-active");

        }
        function removeActive(x) {
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
             except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

</script>
<script>
    $(document).ready(function () {
        autocomplete(document.getElementById("myInput"), titles);
    });
</script>
