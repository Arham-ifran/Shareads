<?php $this->load->view('includes/profile_info'); ?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<style>
    .product_activate small,.product_activate code{white-space: normal;word-break: break-all;}
</style>
<section class="container" style="padding: 45px">
    <div class="heading_links clearfix">
        <h3 class="main_heading">Products</h3>
        <p></p>
    </div>
    
    <?php
    $paypal_email = getVal('paypal_email', 'c_users', 'user_id', $this->session->userdata('user_id'));
    ?>

    <div class="row">
        <div class="col-md-9 col-sm-12">

            <div class="pull-right" ><a href="<?php echo base_url('products/add') ?>" class="btn btn-primary">Add New</a>
                <div class="clearfix space-10"></div>
            </div>
            <div class="clearfix"></div>

            <div class="table-responsive">



                <?php
                if (count($results->result()) > 0)
                {
                    ?>

                    <table class="table table-striped"  style="border:solid 1px #ccc;">

                        <thead>
                            <tr>
                                <th>Product Name</th>
    <!--                                <th>Description</th>-->
                                <th>Share Counter</th>
                                <th>Commission Payment</th>
                                <th width="15%">Detail</th>
                                <?php /* <th width="10%">1Pixal Code</th> */ ?>
                                <th width="10%">Commission info</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $i = 0;
                            foreach ($results->result() as $row)
                            {

                                $query_no_of_sales = $this->db->query('SELECT * FROM c_orders where product_id = ' . $row->product_id . ' and order_status = 2');
                                $no_of_sales       = $query_no_of_sales->num_rows();
                                ?>
                                <tr>
                                    <td><?php
                                        echo ucfirst($row->product_name);
                                    if($row->status == 0)
                                    {
                                        echo '&nbsp;<span class="label label-danger">Inactive</span>';
                                    }
//                                     if($row->status == 0)
//                                    {
//                                        echo '&nbsp;<span class="label label-danger">Admin Deactivates</span>';
//                                    }
                                        ?>

                                        <?php
                                        /*
                                          if (trim($url_found) == '')
                                          { ?>
                                          <br><small><a target="_blank" style="font-weight: bold;" href="<?php echo $row->url . '?affid=' . $this->common->encode($this->session->userdata('user_id')); ?>">&nbsp;Activate Product</a></small>
                                          <?php } */
                                        if ($row->status == 1)
                                        {
                                            if($paypal_email == '')
                                            {
                                                echo '<br><small><button type="button" class="paypal_warning" style="font-weight: bold;background: none;border: none;color: blue;">&nbsp;Activate Product</button></small>';
                                            }
                                            else if ($row->script_verified == 0 && $paypal_email <> '')
                                            {
                                                echo '<br><small><button type="button" style="font-weight: bold;background: none;border: none;color: blue;" data-toggle="modal" data-target="#activator_' . $this->common->encode($row->product_id) . '">&nbsp;Activate Product</button></small>';
                                                ?>
                                                <div id="activator_<?php echo $this->common->encode($row->product_id); ?>" class="modal fade" role="dialog" data-controls-modal="activator_<?php echo $this->common->encode($row->product_id); ?>" data-backdrop="static" data-keyboard="false">
                                                    <div class="modal-dialog" style="width: 55%;">
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="font-weight: 300;text-align: center;">
                                                                <h4 class="modal-title" style="text-transform: uppercase;font-weight: lighter;"><?php echo ucfirst($row->product_name); ?> <small>Script Activation</small></h4>
                                                            </div>
                                                            <div class="modal-body product_activate" id="step_<?php echo $this->common->encode($row->product_id); ?>_1">
                                                                <p style="text-align: justify;font-size: 13px;">
                                                                    <small>To activate the Product this is Mandatory to add the following script at the header of the Referral Website. If you will not add the script listed below the product site will not be activated and verified.  </small>
                                                                    <br/>
                                                                    <code style="font-size: 12px;">&lt;script src="https://shareads.com/assets/site/js/shareads.min.js"&gt;&lt;/script&gt;</code>
                                                                </p>
                                                                <p style="text-align: justify;font-size: 13px;">
                                                                    <small>The following script must be added at the header of success page where your sales get completed. Ensure to add this script before the activation of your product to make it verifiable.  </small>
                                                                    <br/>
                                                                    <code style="font-size: 12px;">&lt;script src="https://shareads.com/assets/site/js/shareads_catcher.min.js"&gt;&lt;/script&gt;</code>
                                                                    <!--                                                                <br/>
                                                                                                                                    And provide your order Id and transaction ID<br/>
                                                                                                                                    <code>$(function(){---shareads->params('order_id','transaction_id')orderCatcherForm.init('order_id_556','transaction_id_12312');</code>-->
                                                                </p>
                                                                <p style="text-align: justify;font-size: 13px;">
                                                                    <small>Pass the Transaction ID and Order ID parameters in the Script through function at the Success page to verify the sales. If you feel any difficulty adding the Script must consult your Developer otherwise your Product will not be verified.   </small>
                                                                    <br/>
                                                                    <code>$(function(){orderCatcherForm.init('{YOUR_UNIQUE_ORDER_ID}','{YOUR_UNIQUE_TRANSACTION_ID}');});</code>
                                                                </p>
                                                                <p style="display: none;">
                                                                    <small>Please enter your success page URL</small><br/>
                                                                    <input type="url" class="form-control" placeholder="Sales Success page URL *" id="sale_url_<?php echo $this->common->encode($row->product_id); ?>" onkeyup="if (this.value != '')
                                                                            {
                                                                                $('#error_sales_message_<?php echo $this->common->encode($row->product_id); ?>').hide();
                                                                            }
                                                                            else
                                                                            {
                                                                                $('#error_sales_message_<?php echo $this->common->encode($row->product_id); ?>').show();
                                                                            }"/>
                                                                    <span style="color: red;font-size: 12px;" id="error_sales_message_<?php echo $this->common->encode($row->product_id); ?>" style="display:none;">Please provide a valid sales success page URL</span>
                                                                </p>
                                                            </div>
                                                            <div class="modal-body" id="step_<?php echo $this->common->encode($row->product_id); ?>_2" style="display:none;">
                                                                <p style="text-align: center;">
                                                                    <img src="<?php echo base_url('assets/site/img/load_activate.gif'); ?>" style="width: 50%;" />
                                                                </p>
                                                            </div>
                                                            <div class="modal-body" id="step_<?php echo $this->common->encode($row->product_id); ?>_3" style="display:none;">
                                                                <p id="message_<?php echo $this->common->encode($row->product_id); ?>"></p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <a id="test_sale_btn_<?php echo $this->common->encode($row->product_id); ?>" href="<?php echo $row->url . '?prd=' . $this->common->encode($row->product_id) . '&affid=' . $this->common->encode($this->session->userdata('user_id')); ?>" target="_blank" class="btn btn-success"  style="float: left;border: none; background: #78a300; box-shadow: 0px 0px 17px #ccc;">Make a Test sale</a>

                                                                <button style="box-shadow: 0px 0px 5px #ccc; display: none;" type="button" class="btn btn-success" onclick="activate_product(this, '<?php echo $this->common->encode($row->product_id); ?>')">Verify my site</button>
                                                                <button style="box-shadow: 0px 0px 5px #ccc; display: none;" type="button" class="btn btn-danger" id="go_back_<?php echo $this->common->encode($row->product_id); ?>" style="display:none;" onclick="$(this).hide();
                                                                        $('#step_<?php echo $this->common->encode($row->product_id); ?>_2').hide();
                                                                        $('#step_<?php echo $this->common->encode($row->product_id); ?>_3').hide();
                                                                        $('#message_<?php echo $this->common->encode($row->product_id); ?>').hide();
                                                                        $('#step_<?php echo $this->common->encode($row->product_id); ?>_1').fadeIn();">Go back</button>
                                                                <button style="box-shadow: 0px 0px 5px #ccc;float: right;" type="button" class="btn btn-default" onclick="$('#go_back_<?php echo $this->common->encode($row->product_id); ?>').hide();
                                                                        $('#step_<?php echo $this->common->encode($row->product_id); ?>_2').hide();
                                                                        $('#step_<?php echo $this->common->encode($row->product_id); ?>_3').hide();
                                                                        $('#message_<?php echo $this->common->encode($row->product_id); ?>').hide();
                                                                        $('#step_<?php echo $this->common->encode($row->product_id); ?>_1').fadeIn();" data-dismiss="modal">Close</button>
                                                                <a href="javascript:void(0)" target="_blank" class="btn btn-success" onclick="window.location = '<?php echo base_url('products'); ?>'" style="float: right;border: none;background: #044f80;box-shadow: 0px 0px 17px #ccc;">I have made the test sale</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            else
                                            {
                                                echo '<br><small><a href="javascript:void(0)" data-href="' . base_url('products/inactive/' . $this->common->encode($row->product_id)) . '" onclick="return inactive_confirm(this);" style="font-weight: bold;background: none;border: none;color: #d9524e;">&nbsp;Deactivate Product</a></small>';
                                            }
                                        }
                                        ?>
                                    </td>
        <!--                                     <td><?php
                                    $dot = '';
                                    if (count(explode(' ', $row->short_description)) > 20)
                                    {
                                        $dot = '...';
                                    }
                                    echo implode(' ', array_slice(explode(' ', $row->short_description), 0, 20)) . $dot;
                                    ?></td>-->
                                    <td><?php echo number_format($row->counter); ?></td>
                                    <td><?php echo ($row->currency_symbol <> getSiteCurrencySymbol()) ? $row->currency_symbol : getSiteCurrencySymbol(); ?><?php echo number_format($row->orignal_commision, 2); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a title="View" href="<?php echo base_url('products/detials/' . $this->common->encode($row->product_id)) ?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a><a title="Edit" href="<?php echo base_url('products/edit/' . $this->common->encode($row->product_id)) ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                                            <?php
                                            if ($no_of_sales == 0)
                                            {
                                                ?> <a title="Delete"  onclick="return delete_confirm();" href="<?php echo base_url('products/delete/' . $this->common->encode($row->product_id)) ?>" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i></a> <?php } ?>
                                        </div>
                                    </td>
                                    <?php /* ?>
                                      <td>
                                      <?php
                                      if ($row->product_type == 3)
                                      {
                                      ?>
                                      <a data-toggle="modal"  onclick="copyLink('<?php echo base_url('tracking?prd=' . $this->common->encode($row->product_id) . '&affid='); ?>');" data-target="#copyLinkModal" class="btn btn-success btn-sm"><i class="fa fa-link"></i> Copy Link</a>
                                      <?php } ?>
                                      </td>
                                     */ ?>

                                    <td>
                                        <?php
                                        if ($row->product_type == 3 || true)
                                        {
                                            ?>
                                            <a href="<?php echo base_url('products/view_commisions/' . $this->common->encode($row->product_id)) ?>" class="btn btn-success btn-sm"><i class="fa fa-external-link"></i> View</a>
                                        <?php } ?>
                                    </td>

                                </tr>
                                <?php
                                $i++;
                            }
                            ?>

                        </tbody>
                    </table>
                    <?php
                }
                else
                {
                    ?>
                    <div class="alert alert-warning">No product found.</div>
                <?php } ?>

            </div>

            <div class="row clearfix pull-left">
                <ul class="pagination">
                    <?php echo $pagination; ?>
                </ul>
            </div>

<!--            <div class="pull-right" style="margin-top:10px;"><a href="<?php echo base_url('products/add') ?>" class="btn btn-primary">Add New</a></div>-->

        </div>

        <?php $this->load->view('includes/right_bar') ?>
        <?php $this->load->view('includes/share_popup') ?>
    </div>


</section>
<script>
    function inactive_confirm(_context)
    {
        swal({
        title: "Are you sure you want to deactivate this product?",
                text: "Once Deactiveted, you need to make the demo sale again to make active!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
        })
                .then((willDelete) => {
                if (willDelete)
                {
                window.location.href = $(_context).attr('data-href');
                }
                else
                {

                }
                }
                );
    }
    function copyLink(txt)
    {
        $('#sharedLink').html('<img  height="1" width="1" style="border-style:none;display:none;"  src="' + txt + '" />');
    }
    function ValidURL(str)
    {
        var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
        if (!regex.test(str))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    function activate_product(context, pid)
    {
        var sales_url = $('#sale_url_' + pid).val();
        if (sales_url == '' || sales_url == 'undefined' || ValidURL(sales_url) == false)
        {
            $('#error_sales_message_' + pid).show();
            return false;
        }
        $('#step_' + pid + '_1').hide();
        $('#step_' + pid + '_2').fadeIn();
        $(context).hide();
        $('#go_back_' + pid).hide();
        var URL = '<?php echo base_url('products/activate_product'); ?>';
        $('#message_' + pid).html('');
        $.ajax({
            url: URL,
            type: "post",
            data: {
                pid: pid,
                sale_url: sales_url
            },
            success: function (response)
            {
                response = JSON.parse(response);
                $('#step_' + pid + '_2').hide();
                $('#message_' + pid).show();
                $('#step_' + pid + '_3').fadeIn();
                if (response.status == 1)
                {
                    $('#message_' + pid).html(response.message);
                    setTimeout(function ()
                    {
                        location.reload();
                    }, 5000);
                }
                else
                {
                    $('#message_' + pid).html(response.message);
                    $(context).show();
                    $('#go_back_' + pid).show();
                    $('#test_sale_btn_' + pid).show();
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                document.getElementById("submit").disabled = false;
                console.log(textStatus, errorThrown);
            }
        });
    }
    
    $(document).ready(function(){
       $('.paypal_warning').click(function(){
//           swal("Paypal ID required", "Please update you paypal id in profile settings", "error");
           
           
           swal({
        title: "Paypal ID required",
                text: "To keep advertising your products with ShareAds, please provide your PayPal information for the advertisers to get their share on time. ",
                icon: "warning",
                buttons: true,
                dangerMode: true,
        })
                .then((_is) => {
                if (_is)
                {
                window.location.href = '<?php echo base_url(); ?>settings/edit';
                }
                else
                {

                }
                }
                );
           
       }); 
    });
</script>
