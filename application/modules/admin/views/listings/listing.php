<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
<div class="page-header">
    <h1>
        Listings
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Manage Listings
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">

        <div class="col-sm-8">

        </div>
        <?php
        // Check rights
        if (rights(22) == true)
        {
            ?>

            <div class="col-sm-4">
                <a href="<?php echo base_url('admin/listings/add') ?>"><button type="button" class="btn btn-primary"   style="float: right; margin-right: 15px;">Add New</button></a>
            </div>
        <?php } ?>

    </div>

    <div class="col-xs-12">
        <form class="form-inline" role="form" id="searchListing" method="get" action="<?php echo base_url('admin/listings') ?>">
            <input type="text" class="form-control" name="f_product_name" id="f_product_name" placeholder="Search Product Name" value="<?php echo $_GET['f_product_name'] ?>">
            <select id="category_1" name="f_category_id" class="form-control subCat_1" onchange="parent_category1333('category_1', 1)" >
                <option value="">Select Category</option>
                <?php echo $this->listings_model->loadCategories(0, 0, $row['type_id']); ?>
            </select>

            <select id="status" name="status" class="form-control" >
                <option value="">Select status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
                <option value="2">Live/Active</option>
            </select>


            <button type="submit" class="btn btn-danger" style="padding: 2px 12px;">Search</button>
            <button type="button" class="btn btn-info" style="padding: 2px 12px;" onclick="$('#f_product_name').val('');$('#category_2').val('');$('#status').val('');$('#category_1').val('');$('form').submit();">Clear Filters</button>
        </form>

    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <?php
        if ($this->session->flashdata('success_message'))
        {
            echo '<div class="alert alert-success alertMessage">' . $this->session->flashdata('success_message') . '</div>';
        };
        ?>
        <div class="clearfix"></div>
        <!-- Notification -->
        <?php
        if ($this->session->flashdata('error_message'))
        {
            echo '<div class="alert alert-danger">' . $this->session->flashdata('error_message') . '</div>';
        };
        ?>
        <div class="clearfix"></div>
        <!-- /Notification -->

        <div class="alert" id="formErrorMsg" style="display: none;">
        </div>
        <div class="space-4"></div>
        <div class="table-header"> Results for "All listings" </div>
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="">Publisher</th>
<!--                    <th>Product Type</th>-->
                    <th>Listing Name</th>
<!--                    <th>Commission</th>-->
                    <th>Commission</th>
                    <th class="">Category</th>
                    <th class="">No of Sales</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>View Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
//				 dd($result->result_array());
                if ($result->num_rows() > 0)
                {
                    foreach ($result->result() as $row)
                    {

                        $url_found         = '';
                        $sql               = 'SELECT `url` FROM `c_usertracking` WHERE REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX("' . $row->url . '", "/", 3), "://", -1), "/", 1), "?", 1),"www.","") = REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(c_usertracking.url, "/", 3), "://", -1), "/", 1), "?", 1),"www.","") limit 1';
                        $url_found         = $this->db->query($sql)->row()->url;
                        $query_no_of_sales = $this->db->query('SELECT * FROM c_orders where product_id = ' . $row->product_id . ' and order_status = 2');
                        $no_of_sales       = $query_no_of_sales->num_rows();

                        if ($row->status == 1)
                        {
                            $status = '<span class="label label-sm label-info status_label' . $row->product_id . '">Active</span>';
                        }
                        else
                        {
                            $status = '<span class="label label-sm label-danger status_label' . $row->product_id . '">Inactive</span>';
                        }
                        ?>
                        <tr id="listings_<?php echo $row->product_id; ?>">

                            <td class=""><?php echo ucwords($row->full_name);
                        ?></td>
        <!--                            <td class=""><label class="label label-primary"><?php echo $row->pro_type; ?></label></td>-->

                            <td><?php
                                echo ucwords($row->product_name);
                                if ($row->script_verified == 1 && $row->publisher_status == 1 && $row->status == 1)
                                {
                                    echo '&nbsp;<span class="label label-sm label-success">Live</span>';
                                }
                                if ($row->publisher_status == 0)
                                {
                                    echo '&nbsp;<span class="label label-sm label-danger">Publisher Deactivated</span>';
                                }
                                ?>
                                <?php
                                /*
                                  if ($row->script_verified == 0)
                                  {
                                  ?>
                                  <br><small><a style="font-weight: bold;text-decoration: underline;" href="<?php echo base_url('admin/listings/activate_product_manually/' . $this->common->encode($row->product_id)); ?>">&nbsp;Activate product manually</a></small>
                                  <?php }
                                 */
                                ?>
                            </td>
        <!--                            <td><?php echo getSiteCurrencySymbol('', $row->currency); ?><?php echo number_format($row->commission, 2); ?></td>-->
                            <td><?php echo getSiteCurrencySymbol('', $row->currency); ?><?php echo number_format(getVal('commission', 'c_products_commission', 'product_id', $row->product_id), 2); ?></td>

                            <td class=""><?php echo $row->category_name ?></td>

                            <td><?php echo $no_of_sales; ?></td>
                            <td><?php echo $status; ?></td>


                            <td>

                                <div class="hidden-sm hidden-xs btn-group">
                                    <?php
                                    if ($row->status == 1)
                                    {
                                        echo '<a title="Status" href="javascript:void(0);" class="blue status_button' . $row->product_id . '" onclick=updateStatus("listings",' . $row->product_id . ',1)><i class="ace-icon fa fa-plus bigger-130"></i></a>';
                                    }
                                    else
                                    {
                                        echo '<a title="Status" href="javascript:void(0);" class="red status_button' . $row->product_id . '" onclick=updateStatus("listings",' . $row->product_id . ',0)><i class="ace-icon fa fa-minus bigger-130"></i></a>';
                                    }
                                    ?>
                                    <?php
                                    // Check rights
                                    if (rights(23) == true)
                                    {
                                        ?>
                                        <a title="Edit" class="green" href="<?php echo base_url('admin/listings/edit/' . $this->common->encode($row->product_id)); ?>">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <?php
                                    }
                                    // Check rights
                                    if (rights(24) == true && $no_of_sales == 0)
                                    {
                                        ?>

                                        <a  title="Delete" class="red" onclick="return delete_confirm();" href="<?php echo base_url('admin/listings/delete/' . $this->common->encode($row->product_id)); ?>" onclick="return delete_confirm();">
                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                        </a>
                                    <?php } ?>

                                </div>

                                <div class="hidden-md hidden-lg">
                                    <div class="inline pos-rel">
                                        <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                            <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">

                                            <?php
                                            if ($row->status == 1)
                                            {
                                                ?>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="updateStatus('listings', '<?php echo $row->product_id; ?>', 1)" class="tooltip-info status_sm_button<?php echo $row->product_id; ?>" data-rel="tooltip" title="View">
                                                        <span class="blue">
                                                            <i class="ace-icon fa fa-plus bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>

                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="updateStatus('listings', '<?php echo $row->product_id; ?>', 1)" class="tooltip-info status_sm_button<?php echo $row->product_id; ?>" data-rel="tooltip" title="View">
                                                        <span class="red">
                                                            <i class="ace-icon fa fa-minus bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php }
                                            ?>

                                            <?php
                                            // Check rights
                                            if (rights(23) == true)
                                            {
                                                ?>
                                                <li>
                                                    <a  href="<?php echo base_url('admin/listings/edit/' . $this->common->encode($row->product_id)) ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
                                                        <span class="green">
                                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>

                                                <?php
                                            }
                                            // Check rights
                                            if (rights(24) == true)
                                            {
                                                ?>

                                                <li>
                                                    <a  href="<?php echo base_url('admin/listings/delete/' . $this->common->encode($row->product_id)); ?>" onclick="return delete_confirm();" class="tooltip-error" data-rel="tooltip" title="Delete">
                                                        <span class="red">
                                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>

                            <td>
                                <?php
                                if ($row->product_type == 3 || $row->product_type == 2)
                                {
                                    ?>
                                    <a class="green" href="<?php echo base_url('admin/listings/detail/' . $this->common->encode($row->product_id)); ?>"><i class="ace-icon fa fa-eye bigger-130"></i> View Detail</a>

                                <?php } ?>

                            </td>

                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                <?php }
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

                                                        $(function ()
                                                        {
                                                            try
                                                            {
                                                                $('.table').dataTable({
                                                                    dom: '<"top"B<"clear">f>rt<"bottom"lp><"clear">',
                                                                    buttons: [
                                                                        {
                                                                            title: 'Product Listing | ShareAds Admin Panel',
                                                                            text: 'Export to excel', extend: 'excel', footer: false,
                                                                            exportOptions: {columns: [0, 1, 2, 3, 4, 5]}
                                                                        }
                                                                    ],
                                                                    "aoColumns": [null, null, null, null, null, null, {"bSortable": false}, {"bSortable": false}],
                                                                    "aaSorting": []
                                                                });
                                                            }
                                                            catch (e)
                                                            {
                                                                console.log(e);
                                                            }
                                                        });


                                                        function parent_category1333(idd, inc)
                                                        {

                                                            var incc;
                                                            incc = inc;
                                                            incc = incc + 1;
                                                            var allselect = ($('#form_listings .catClass').size() + 2);
                                                            for (i = incc; i <= allselect; i++)
                                                            {
                                                                $('.subCat_' + i).remove();
                                                                $('.catClass').remove();
                                                            }

                                                            if ($('#' + idd).val() == 0 || $('#' + idd).val() == "")
                                                            {
                                                                return false;
                                                            }
                                                            else
                                                            {

                                                                var URL = ADMIN_URL + 'listings/getSubCategory';
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: URL,
                                                                    data: 'category_id=' + $('#' + idd).val() + '&level=' + inc,
                                                                    dataType: 'json',
                                                                    success: function (data1)
                                                                    {

                                                                        inc++;

                                                                        var subcat = "";
                                                                        if (data1.result_counter == 0)
                                                                        {
                                                                            $('.subCat_' + (inc++)).remove();
                                                                            $('.catClass').remove();
                                                                        }

                                                                        else
                                                                        {
                                                                            $('.subCat_' + inc).remove();

                                                                            subcat = '&nbsp;<select id="category_'
                                                                                    + inc
                                                                                    + '" name="f_sub_category_id"  class="form-control  catClass" onchange="parent_category1(\'category_'
                                                                                    + inc + '\',' + inc
                                                                                    + ')"><option value="">Select</option>';
                                                                            $.each(data1.result, function ()
                                                                            {

                                                                                subcat = subcat + '<option value="'
                                                                                        + this.category_id + '">'
                                                                                        + this.category_name + '</option>';

                                                                            });

                                                                            subcat = subcat + '</select>';



                                                                        }
                                                                        $('.subCat_' + (--inc)).after(subcat);
                                                                        $('#inc').val($('#form_listings > .catClass').size());



                                                                        var option_all = $(".catClass option:selected").map(function ()
                                                                        {
                                                                            if ($(this).val() == 0)
                                                                            {
                                                                            }
                                                                            else
                                                                            {
                                                                                return $(this).val();
                                                                            }

                                                                        }).get().join(',');


                                                                        $('#sub_parent').val(option_all);

                                                                    }
                                                                });
                                                            }
                                                        }

</script>
<?php
if (isset($_GET) && !empty($_GET))
{
    ?>
    <script>
        
        $(document).ready(function ()
        {
            $('#category_1').val('<?php echo $_GET['f_category_id']; ?>');
            parent_category1333('category_1',<?php echo $_GET['f_category_id']; ?>);
            $('#status').val('<?php echo $_GET['status']; ?>');
            setTimeout(function ()
            {
                $('.catClass').val('<?php echo $_GET['f_sub_category_id']; ?>');
            }, 1000);
        });
    </script>
<?php } ?>