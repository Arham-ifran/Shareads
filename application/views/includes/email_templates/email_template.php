<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!-- NAME: 1:2 COLUMN -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title><?php echo SITE_NAME; ?></title>
                <style>
                    body{
                        width:100% !important;
                        min-width: 100%;
                        -webkit-text-size-adjust:100%;
                        -ms-text-size-adjust:100%;
                        margin:0;
                        padding:0;
                        background: #fff;
                        font-family: "Helvetica", "Arial", sans-serif;
                    }
                    .blue_btn {
                        background: #2fa1e6; /* Old browsers */
                        background: -moz-linear-gradient(top,  #2fa1e6 0%, #087ac0 11%, #31a2e7 100%); /* FF3.6+ */
                        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#2fa1e6), color-stop(11%,#087ac0), color-stop(100%,#31a2e7)); /* Chrome,Safari4+ */
                        background: -webkit-linear-gradient(top,  #2fa1e6 0%,#087ac0 11%,#31a2e7 100%); /* Chrome10+,Safari5.1+ */
                        background: -o-linear-gradient(top,  #2fa1e6 0%,#087ac0 11%,#31a2e7 100%); /* Opera 11.10+ */
                        background: -ms-linear-gradient(top,  #2fa1e6 0%,#087ac0 11%,#31a2e7 100%); /* IE10+ */
                        background: linear-gradient(to bottom,  #2fa1e6 0%,#087ac0 11%,#31a2e7 100%); /* W3C */
                        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2fa1e6', endColorstr='#31a2e7',GradientType=0 ); /* IE6-9 */
                        padding: 6px 15px;
                        color: #fff;
                        display: inline-block;
                        font-size:15px;
                        font-weight:bold;
                        width: auto;
                        text-align:center;
                        border-radius:4px;
                        border-bottom:solid 1px #00598e;
                        margin-bottom:10px;
                        box-shadow: 0 2px 2px #ccc;
                        min-width: 100px;cursor: pointer;
                    }
                    td .heading{
                        /*background: #444 none repeat scroll 0 0;*/
                        color: #0088cc;
                        font-size: 20px;
                        padding: 7px 5px;
                        text-align: left;}
                    th.head{
                        background:#444; color:#fff; padding:10px; text-align:left; font-size:20px;}
                    td p.time	{
                        font-size:13px; color:#999; margin:5px;}
                    td p.txt	{
                        font-size:14px; color:#555; margin:5px;}
                    td p.price	{
                        color:#555; margin:5px; font-size: 16px;
    font-weight: bold;}
                    td p.txt .read-more	{
                        font-size:14px; color:#07b0e6; margin-left:5px;}
                    .td{ border-bottom: 1px solid #ccc;
                         padding: 7px;}
                    .new_user{ padding:5px;}
                    .new_user p{
                        font-size:12px;
                        color:#666;
                        margin:5px;}
                    .img-circle {
                        border: 2px solid #bababa;
                        float:left;
                        border-radius: 50%;
                        height: 72px;
                        width: 72px;
                    }
                    .images{
                     border: 2px solid #bababa;
                        float:left;margin-right:10px; vertical-align:initial;
                        height: 72px;
                        width: 72px;
                    }
                    .connect{
                        background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #ffa912 0%, #eb8500 100%) repeat scroll 0 0;
                        border-color: #eb8500;
                        border-radius: 2px;
                        color: #fff;
                        font-family: arial;
                        font-size: 11px !important;
                        font-weight: bold;
                        margin-top: 6px;
                        padding: 6px 10px;
                        text-transform: uppercase;text-decoration: none;
                    }
                    .label {
background-color: #3a87ad;
    border-radius: 5px;
    color: #ffffff;
    float: right;
    font-size: 11px;
    font-weight: bold;
    padding: 5px;
}
                </style>
                </head>
                <body>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" align="center">
                                <table width="80%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="text-align:center;"><a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/site/img/logo.png" alt="<?php echo SITE_NAME; ?>" /></a></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <table width="80%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
<!--                                    <tr>
                                        <td><h1 style="color: #222; font-size: 30px; font-weight: normal; margin:3px 0px;"><?php echo $title; ?></h1></td>
                                    </tr>-->
                                    <?php if($receiver_name <> 'no_hi'){ ?>
                                    <tr>
                                        <td><h2 style="color: #222; font-size: 30px; font-weight: normal; margin:3px 0px;">Hi&nbsp;<?php echo $receiver_name; ?>!</h2></td>
                                    </tr>
                                    <?php } ?>
                                    <?php if ($welcome_content <> '' && $welcome_content <> 0) { ?>
                                        <tr>
                                            <td><p style="font-size: 18px; color: #777; margin:3px 0px;line-height:24px;"><?php echo $welcome_content; ?></p></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" style="clear:both;">
                                <?php echo $content ?>
                            </td>
                        </tr>
                        <tr><td valign="top" align="center">&nbsp;</td></tr>
                        <tr>
                            <td valign="top" align="center" >
                                <table width="80%" border="0" cellspacing="0" cellpadding="0"  style="background-color:#f2ffd8; padding:10px 20px">
                                    <tr>
                                        <td><p style="margin: 10px;"><?php echo $email_content; ?></p></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                <tr><td valign="top" align="center">&nbsp;</td></tr>
                        <tr>
                            <td valign="top" align="center">
                                <?php echo $footer ?>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>
