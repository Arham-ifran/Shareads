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
        Advertiser Report
    </h2>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

        <table id="" class="table table-striped table-bordered table-hover">

                <thead>
                <tr>
                    <th>User Name</th>
                    <th>Account Type</th>
                    <th class="">Email</th>
                    <th class="">Phone</th>
                    <th class="">Country</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;

                foreach ($result->result() as $row) {
                    if ($row->status == 1) {
                            $status = '<span class="label label-sm label-info status_label' . $row->user_id . '">Active</span>';
                        } else {
                            $status = '<span class="label label-sm label-danger status_label' . $row->user_id . '">Inactive</span>';
                        }
                    ?>
                    <tr>
                        <td><?php echo ucwords($row->full_name); ?></td>
                            <td><?php echo ucwords($row->user_type); ?></td>
                            <td  class=""><?php echo $row->email ?></td>
                            <td  class=""><?php echo $row->phone ?></td>
                            <td  class=""><?php echo $row->country ?></td>
                            <td><?php echo $status; ?></td>

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
