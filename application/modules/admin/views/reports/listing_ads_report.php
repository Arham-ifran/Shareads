<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Listing Featured Payment Report
<!--        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>

        </small>-->
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

        <form class="form-inline" role="form" id="searchListing" method="post" action="<?php echo base_url('admin/reports/ads_list_report') ?>">
            <input type="text" class="form-control date-picker" name="date_from" id="date_from" placeholder="Search Date From" value="<?php echo $date_from ?>">
            <input type="text" class="form-control date-picker" name="date_to" id="date_to" placeholder="Search Date To" value="<?php echo $date_to ?>">
            <input type="text" class="form-control " name="product_name" id="product_name" placeholder="Search Title" value="<?php echo $product_name ?>">
            <button type="submit" class="btn btn-danger">Search</button>
            <button type="button" class="btn btn-info" onclick="$('#date_from').val('');$('#date_to').val('');$('#product_name').val('');$('form').submit();">Clear Filters</button>
        </form>


    </div>
    <div class="clearfix space-8"></div>
    <div class="col-sm-8">
        <?php
        if (count($result->result()) > 0)
        {
            ?>
            <a  class="btn btn-info"  target="_blank" href="<?php echo base_url('admin/reports/print_ads_report') ?>"> Print Report</a>
<?php } ?>

    </div>
    <div class="col-sm-4">
    </div>

</div>
<div class="clearfix space-8"></div>

<div class="row">
    <div class="col-xs-12">
        <div class="space-4"></div>
        <div class="table-header"> Results for "Latest Ads"</div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">

            <thead>
                <tr>
                    <th>Publisher</th>
                    <th>Listing Name</th>
                    <th>Category</th>
                    <th>No of Sales</th>
                    <th>Date</th>
                    <th>Commission</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i      = 0;
                $total  = 0;
                $gtotal = 0;
                foreach ($result->result() as $row)
                {
                    $pro_currecny = getVal('currency', 'c_products', array('product_id' => $row->product_id));
                    if ($row->product_name == null)
                    {
                        continue;
                    }
                    $where = ' ';
                    if (trim($date_from) != '' && trim($date_to) == '')
                    {
                        $where .=' AND c_orders.created  >=UNIX_TIMESTAMP(STR_TO_DATE("' . $date_from . '","%m/%d/%Y"))';
                    }
                    if (trim($date_from) == '' && trim($date_to) != '')
                    {
                        $where .=' AND c_orders.created  <=UNIX_TIMESTAMP(STR_TO_DATE("' . $date_to . '","%m/%d/%Y")))';
                    }
                    if (trim($date_from) != '' && trim($date_to) != '')
                    {
                        $where .=' AND c_orders.created  >=  UNIX_TIMESTAMP(STR_TO_DATE("' . $date_from . '","%m/%d/%Y")) ';
                        $where .=' AND c_orders.created  <= UNIX_TIMESTAMP(STR_TO_DATE("' . $date_to . '","%m/%d/%Y")) ';
                    }
                    $sql__             = 'SELECT c_orders.* FROM c_orders INNER join c_products as pro on pro.product_id = c_orders.product_id where c_orders.product_id = ' . $row->product_id . ' and c_orders.order_status = 2 ' . $where;
                    // echo $sql__;die();
                    $query_no_of_sales = $this->db->query($sql__);
                    $no_of_sales       = $query_no_of_sales->num_rows();
                    ?>
                    <tr>
                        <td><?php echo $row->full_name; ?></td>
                        <td><?php echo $row->product_name; ?></td>
                        <td><?php echo $row->category_name; ?></td>
                        <td><?php echo $no_of_sales; ?></td>

                        <td><?php echo date('d M, Y', $row->created); ?></td>
                        <td><?php echo getSiteCurrencySymbol(); ?><?php echo number_format(get_currency_rate($row->commission, $pro_currecny, CURRENCY), 2); ?></td>

                    </tr>
                    <?php
                }
                ?>

            </tbody>

        </table>
    </div>
</div>

<!-- page specific plugin scripts -->

<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/admin/js/jquery.dataTables.bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script>
                jQuery(function () {
                    try {
                        $('.table').dataTable({
                            dom: '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
                            buttons: [
                                {
                                    title: 'Listing Featured Payment Report | ShareAds Admin Panel',
                                    text: 'Export to excel', extend: 'excel', footer: false,
                                    exportOptions: {columns: [0, 1, 2, 3, 4, 5]}
                                }
                            ],
                            bAutoWidth: false, scrollX: true
                        });
                    } catch (e)
                    {
                        console.log(e);
                    }
                });
</script>