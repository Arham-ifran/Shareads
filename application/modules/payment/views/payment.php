<section class="container">
    <h2 class="main_heading"><?php echo $type;?></h2>


<?php
if($type== 'error')
{
?>
    <div class="alert alert-danger" ><?php echo $msg;?></div>
<?php }else{?>
    <div class="alert alert-success" ><?php echo $msg;?></div>
<?php }?>


</section>