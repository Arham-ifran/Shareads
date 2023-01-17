
<!DOCTYPE html><html lang='en' class=''>
    <head>
    </script><meta charset='UTF-8'><meta name="robots" content="noindex">
    <link rel="shortcut icon" type="image/x-icon" href="//static.codepen.io/assets/favicon/favicon-8ea04875e70c4b0bb41da869e81236e54394d63638a1ef12fa558a4a835f1164.ico" />
    <link rel="mask-icon" type="" href="//static.codepen.io/assets/favicon/logo-pin-f2d2b6d2c61838f7e76325261b7195c27224080bc099486ddd6dccb469b8e8e6.svg" color="#111" />
    <link rel="canonical" href="https://codepen.io/JacobLett/pen/vyegPV" />
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css'>
    <style class="cp-pen-styles"></style>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js'></script>
    <script type="text/javascript"> window._pfq = window._pfq || [];(function ()
        {
            var pf = document.createElement("script");
            pf.type = "text/javascript";
            pf.async = true;
            pf.src = "//www.flopanda.com/v1/website/script/5cb43748ce94c03f324b03be";
            document.getElementsByTagName("head")[0].appendChild(pf);
        })();</script>
   <script type="text/javascript"> window._pfq = window._pfq || [];(function() {var pf = document.createElement("script");pf.type = "text/javascript"; pf.async = true;pf.src = "//www.flopanda.com/v1/website/payment/script";document.getElementsByTagName("head")[0].appendChild(pf);})();</script>
</head>
<body>
    <div class="jumbotron text-xs-center">
        <h1 class="display-3">Thank You!</h1>
        <p class="lead"><strong>Your Order is successfully completed</strong> for further instructions on how to complete your account setup.</p>
        <hr>
        <p>
            Check Your sale and Earning status on <a target="_blank" href="<?php echo base_url(); ?>">FloPanda </a>
        </p>
        <p class="lead">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('demoflopandasale'); ?>" role="button">Continue to Checkout</a>
        </p>
    </div>
</body>
<script>
<?php if (isset($_GET))
{ ?>
            $(document).ready(function ()
            {
                getCost('<?php  echo $_GET['price']; ?>');
            });
<?php } ?>
</script>
</html>