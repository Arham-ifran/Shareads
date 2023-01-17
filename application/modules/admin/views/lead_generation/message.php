<div class="page-header">
    <h1>
        Commission <?php echo $type; ?>

    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">


    </div>
    <div class="clearfix space-8"></div>
    <div class="col-sm-8">

    </div>
    <div class="col-sm-4">
    </div>

</div>

<div class="clearfix space-8"></div>

<div class="row">
    <div class="col-xs-12">


        <?php
        if ($type == 'error') {
            ?>
            <div class="alert alert-danger" ><?php echo $msg; ?></div>
        <?php } else { ?>
            <div class="alert alert-success" ><?php echo $msg; ?></div>
        <?php } ?>

        <input class="btn btn-primary pull-right" type="button" value="Go Back" onclick="window.history.back()" />

    </div>
</div>