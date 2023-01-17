<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="<?php echo base_url('assets/admin/css/bootstrap.min.css');  ?>" />
<style>
  .page-header h2 {
        font-size:18px;
        text-align: center;
        color: #333 !important;
    }

    table
    {
        text-align:left;
        border: 1px solid #000 !important;

    }

    thead th
    {
        font-weight:500;
        text-align: center; font-size:14px;
        padding: 5px;
        background-color: #cccccc;
        border: 1px solid #666 !important;
        border-collapse: collapse;

    }


    td{text-align: center;padding: 5px;font-size:13px}

</style>
<div class="page-header">
    <h2>
        Listing Featured Payment Report

    </h2>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

        <table id="" class="table table-striped table-bordered table-hover">

            <thead>
                <tr>
                    <th>Publisher</th>
                    <th>Product Type</th>
                    <th>Listing Name</th>
                    <th>Category</th>

                    <th>Date</th>
                    <th>Commission</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($result->result() as $row) {

                    ?>
                    <tr>
                            <td><?php echo $row->full_name; ?></td>
                            <td class=""><?php echo $row->product_type; ?></td>
                            <td><?php echo $row->product_name; ?></td>
                            <td><?php echo $row->category_name; ?></td>

                            <td><?php echo date('d M, Y', $row->created); ?></td>
                            <td>$<?php echo number_format($row->commission,2); ?></td>

                        </tr>
                    <?php
                }
                ?>



            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6" style="text-align:center;">&nbsp;</th>

                </tr>
                <tr>
                    <th colspan="5" style="text-align:center;">Total Records</th>
                    <th  style="text-align:center;"><?php echo count($result->result()) ?></th>

                </tr>

            </tfoot>

        </table>
    </div>
</div>

<!-- page specific plugin scripts -->
