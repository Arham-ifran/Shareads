<section class="container">
<h2 class="main_heading">Checkout</h2>
    <div class="row">
        <div class="col-md-6 col-xs-9"><h4>Checkout Information</h4></div>
    </div>
            <?php
            if ($error == '') {
                ?>
                <form id="checkout_form" method="post" action="<?php echo base_url('checkout/paynow') ?>">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-striped"  style="border:solid 1px #ccc;">
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>SubTotal</th>




                                    <tr class=" prd_count" >

                                        <td>

                                            <?php
                                            $dot = '';
                                            if (count(explode(' ', $product_data['product_name'])) > 5) {
                                                $dot = '...';
                                            }
                                            echo implode(' ', array_slice(explode(' ', $product_data['product_name']), 0, 5)) . $dot;
                                            ?>

                                        </td>

                                        <td >

                                          &pound;<?php echo number_format(($product_data['price']), 2); ?>


                                        </td>



                                        <!--Sub total starts here -->
                                        <td >

                                            &pound;<?php echo number_format(($product_data['price']), 2); ?>
                                        </td>




                                    </tr>



                                </table>
                            </div>

                        </div>

                        <div class="col-md-4">


                            <div class="table-responsive">
                                <table class="table table-striped"  style="border:solid 1px #ccc;">
                                    <tbody>
                                        <tr>
                                            <th style="background-color:#333; color:#FFF;" colspan="2"><h4>Checkout</h4></th>
                                    </tr>
                                    <tr>
                                        <td>Subtotal</td>
                                        <td>&pound;<?php echo number_format($product_data['price'], 2); ?></td>
                                    </tr>

                                    <tr>
                                        <td>Tax</td>
                                        <td>&pound;<?php echo number_format($product_data['tax'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Grand Total</td>
                                        <td><strong>&pound;<?php echo number_format(($product_data['price'] + $product_data['tax']), 2); ?></strong></td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <button type="submit" class="pull-right btn btn-primary btn-block"><i class="fa fa-edit fa-fw"></i> PAY NOW</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <input type="hidden" name="product_id" value="<?php echo $product_data['product_id']; ?>" />
                        </div>
                    </div>
                </form>

            <?php } ?>

</section>