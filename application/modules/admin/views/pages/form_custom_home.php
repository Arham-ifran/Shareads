<style>
    .navbar {
    background: #438eb9 !important;
    padding: unset !important;
}

</style>
<div class="breadcrumbs ace-save-state" id="breadcrumbs">


    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="<?php echo base_url('admin/dashboard') ?>">Home</a>
        </li>

        <li class="active">Edit Home Page</li>
    </ul><!-- /.breadcrumb -->


</div>


<div class="page-header">
    <h1>
        Edit Home Page

    </h1>
</div><!-- /.page-header -->


<div class="row">
    <div class="col-xs-12">

        <?php
        if ($this->session->flashdata('success_message')) {
            echo '<div class="alert alert-success alertMessage">' . $this->session->flashdata('success_message') . '</div>';
        };
        ?>
        <div class="clearfix"></div>
        <!-- Notification -->
        <?php
        if ($this->session->flashdata('error_message')) {
            echo '<div class="alert alert-danger">' . $this->session->flashdata('error_message') . '</div>';
        };
        ?>
        <div class="alert alert-info" id="formErrorMsg" style='display:none'></div>
        <div class="clearfix"></div>
        <!-- /Notification -->
<lable class='label label-primary'><b>Editable Content</b> (Edit content in blue box after editing click on save button or click outside the box.)</lable>
        <div class="space-8"></div>
        <div class="space-8"></div>

<link href="<?php echo base_url('assets/site/css/main.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/site/css/custom.css') ?>" rel="stylesheet">

<div class="containerDiv">
                <?php $this->load->view('home/home')?>
            </div>



            <div class="space-2"></div>





            <div class="space-2"></div>
            <div class="clearfix form-actions">
                <div class="col-md-12">
                    <button  class="btn btn-info btn-block" type="button">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Save
                    </button>


                </div>
            </div>


    </div>
</div>
<script src="<?php echo base_url('assets/admin/js/ckeditor/ckeditor.js') ?>"></script>

<script>

         CKEDITOR.on( 'instanceCreated', function( event ) {
			var editor = event.editor,
				element = editor.element;

			/* Customize editors for headers and tag list.
			 These editors don't need features like smileys, templates, iframes etc.*/
			if ( element.is( 'h1', 'h2', 'h3' ) || element.getAttribute( 'id' ) == 'containerEditable' || element.getAttribute( 'id' ) == 'containerEditable1') {
				/* Customize the editor configurations on "configLoaded" event,
				 which is fired after the configuration file loading and
				 execution. This makes it possible to change the
				 configurations before the editor initialization takes place.*/
				editor.on( 'configLoaded', function() {
});
			}
		});
 $(document).ready(function() {

    CKEDITOR.disableAutoInline = true;

    $("div[contenteditable='true']" ).each(function( index ) {

        var content_id = $(this).attr('id');
        var tpl = $(this).attr('tpl');

        CKEDITOR.inline( content_id, {
            on: {
                blur: function( event ) {
                    var data = event.editor.getData();
                var abc =         confirm('Are you sure you want to change this page.');
                if(abc)
                {
                    var request = jQuery.ajax({
                        url: ADMIN_URL+"pages/custom_update_home",
                        type: "POST",
                        data: {
                            content : data,
                            content_id : content_id,
                            tpl : tpl

                        },
                        dataType: "html",
                        success : function(response){

                        showMessage('alert-success','formErrorMsg','File saved successfully');

                        /*setTimeout(function(){
                            window.location.href = window.location;
                        }, 3000);*/
                        }
                    });
                }

                }
            }
        } );

    });

});

         </script>