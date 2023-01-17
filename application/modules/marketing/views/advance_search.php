<?php $this->load->view('includes/profile_info'); ?>

<section class="container">
    <div class="heading_links clearfix">
        <h3 class="main_heading">Marketing</h3>
        <p></p>
    </div>

    <div class="row">

        <div class="col-md-8 col-sm-8">
            <div class="white_box clearfix">
                <form action="<?php echo base_url('marketing/search'); ?>" method="get">
                    <h5 class="filter_heading"><i class="fa fa-search fa-fw"></i> Advanced Search</h5>
                    <div class="form-group clearfix">
                        <div class="col-md-4"><label>Enter Keywords</label></div>
                        <div class="col-md-8"><input type="text"name="query" class="textbox" value="<?php echo $search['query'] ?>"></div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-md-4"><label>In this Category</label></div>
                        <div class="col-md-8">
                            <select class="textbox" name="category_id" id="category_id">
                                <option value="all" <?php echo $search['category_id'] == 'all'?'selected':'';?>>All Categories</option>
                                <?php echo $this->marketing_model->getCategories(0, 0, $search['category_id']); ?>

                            </select>
                        </div>
                    </div>
                    <div class="clearfix">
                        <div class="col-md-4"><label>Result per page</label></div>
                        <div class="col-md-8">
                            <select class="textbox" name="limit" id="limit" style="width:20%;">
                                <option value="10" <?php echo $search['limit'] == '10' ? 'selected' : '' ?>>10</option>
                                <option value="25" <?php echo $search['limit'] == '25' ? 'selected' : '' ?>>25</option>
                                <option value="50" <?php echo $search['limit'] == '50' ? 'selected' : '' ?>>50</option>
                                <option value="100" <?php echo $search['limit'] == '100' ? 'selected' : '' ?>>100</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Search</button>
                        &nbsp;
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                    <h5 class="sub_heading">Stats</h5>
                    <div class="form-group clearfix">
                        <div class="col-xs-12 control-label">Avg <?php echo getSiteCurrencySymbol(); ?>/sale</div>
                        <div class="col-md-4"><label><input name="avg_price_sale" <?php echo ($search['avg_price_sale'] == 1) ? 'checked' : '' ?> value="1" type="checkbox"/> Show items with avg <?php echo getSiteCurrencySymbol(); ?>/sale</label></div>
                        <div class="col-md-5">
                            <select class="textbox">
                                <option>Higher than</option>
                            </select>
                        </div>
                        <div class="col-sm-3"><input type="text" name="avg_sale" value="<?php echo $search['avg_sale']?>" class="textbox"></div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-xs-12 control-label">Avg %/sale</div>
                        <div class="col-md-4"><label><input name="avg_percent_sale" value="1" <?php echo ($search['avg_percent_sale'] == 1) ? 'checked' : '' ?> type="checkbox"/> Show items with avg %/sale</label></div>
                        <div class="col-md-5">
                            <select class="textbox">
                                <option>Higher than</option>
                            </select>
                        </div>
                        <div class="col-sm-3"><input type="text" name="avg_percentage" value="<?php echo $search['avg_sale']?>" class="textbox"></div>
                    </div>
                    <?php /* ?>
                    <br>
                    <h5 class="sub_heading">Product Type</h5>
                    <div class="form-group clearfix">
                        <?php $product_type  = explode(',', $search['product_type']);?>
                        <div class="col-sm-4"><label><input value="1" <?php echo (in_array(1, $product_type)) ? 'checked' : '' ?> name="product_type[]" type="checkbox"/> Products</label></div>
                        <div class="col-sm-4"><label><input value="2" <?php echo (in_array(2, $product_type)) ? 'checked' : '' ?> name="product_type[]" type="checkbox"/> Lead Generation</label></div>
                        <div class="col-sm-4"><label><input value="3" <?php echo (in_array(3, $product_type)) ? 'checked' : '' ?> name="product_type[]" type="checkbox"/> Site Products</label></div>
                    </div>
                    <?php */ ?>
                    <br>

                    <div class="text-right panel-footer">
                        <button type="submit" class="btn btn-primary">Search</button>
                        &nbsp;
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        <?php $this->load->view('includes/right_bar') ?>

    </div>
</section>
