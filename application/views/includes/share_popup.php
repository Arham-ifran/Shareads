<style>
    
    .tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 140px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
</style>
<div class="panel-body">
    <div class="modal fade" id="copyLinkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Copy Link</h4>
                </div>
                <form id="formLoginPopup" name="formLoginPopup" action="" class="form-horizontal" role="form" method="post" accept-charset="utf-8">

                    <div class="alert" id="formErrorMsgPopupCopy" style="display: none;"></div>

                    <div class="modal-body">
                        <div class="form-group animated fadeInUp">
                            <div class="clearfix"></div>
                            <div class="col-md-12">
                                <textarea readonly="readonly" id="sharedLink" class=" alert alert-success form-control" onclick="this.select();"></textarea>

                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="clearfix"></div>

                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" onclick="myFunction()" class="btn">Copy Link To Clipboard</button>
                    <button  id="submitLogin" type="button" class="btn" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("sharedLink");

  /* Select the text field */
  copyText.select();

  /* Copy the text inside the text field */
  document.execCommand("copy");

}
</script>