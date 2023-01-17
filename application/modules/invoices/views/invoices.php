<?php $this->load->view('includes/profile_info'); ?>

<section class="container" style="padding: 45px">
    <div class="heading_links clearfix">
        <h3 class="main_heading">Invoices</h3>
        <p></p>
    </div>

    <div class="row">
        <div class="col-md-9 col-sm-12">

            <div class="clearfix"></div>

            <div class="table-responsive">

                <?php
                if (count($results->result()) > 0)
                {
                    ?>

                    <table class="table table-striped"  style="border:solid 1px #ccc;">

                        <thead>
                            <tr>
                                <th>Invoice Number</th>
                                <th>Amount</th>
                                <th>Due date</th>
                                <th>Status</th>
                                <th width="30%">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $i = 0;
                            foreach ($results->result() as $row)
                            {
//                                dd($row);
                                if ($row->status == 1)
                                {
                                    $status = '<span class="label label-sm label-info ' . $this->common->encode($row->invoice_id) . '">Paid</span>';
                                }
                                else
                                {
                                    $status = '<span class="label label-sm label-danger ' . $this->common->encode($row->invoice_id) . '">Pending</span>';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $row->invoice_number; ?></td>
                                    <td><?php echo (getSiteCurrencySymbol('currency_symbol',$row->invoice_currency)).' ' .number_format($row->invoice_amount, 2); ?></td>
                                    <td><?php echo date('d M, Y', strtotime('+7 day', $row->created)); ?></td>
                                    <td><?php echo $status; ?></td>
                                    <td>
                                        <a href="<?php echo base_url() . $row->file; ?>" class="btn btn-success btn-sm" download><i class="fa fa-download"></i> Download</a>
                                        <a href="<?php echo base_url('invoices/view/' . $this->common->encode($row->invoice_id)) ?>" class="btn btn-success btn-sm"><i class="fa fa-external-link"></i> View</a>
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
                    <div class="alert alert-warning">No invoice found.</div>
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
    function copyLink(txt)
    {
        $('#sharedLink').html('<img  height="1" width="1" style="border-style:none;display:none;"  src="' + txt + '" />');
    }
</script>
