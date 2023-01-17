<?php /*<div class="ace-settings-container" id="ace-settings-container">
    <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
        <i class="ace-icon fa fa-cog bigger-130"></i>
    </div>

    <script type="text/javascript">
function SetCookie(cookieName,cookieValue,nDays) {
 var today = new Date();
 var expire = new Date();
 if (nDays==null || nDays==0) nDays=1;
 expire.setTime(today.getTime() + 3600000*24*nDays);
 document.cookie = cookieName+"="+escape(cookieValue)
                 + ";expires="+expire.toGMTString();
}

    </script>
<?php  $skins = $_COOKIE['skin_s'];?>
    <div class="ace-settings-box clearfix " id="ace-settings-box">
        <div class="pull-left width-50">
            <div class="ace-settings-item">
                <div class="pull-left">
                    <select id="skin-colorpicker" class="hide">
                        <option <?php echo $skins == 'no-skin'?'selected':'';?> data-skin="no-skin" value="#438EB9">#438EB9</option>
                        <option <?php echo $skins == 'skin-1'?'selected':'';?> data-skin="skin-1" value="#222A2D">#222A2D</option>
                        <option <?php echo $skins == 'skin-2'?'selected':'';?> data-skin="skin-2" value="#C6487E">#C6487E</option>
                        <option <?php echo $skins == 'skin-3'?'selected':'';?> data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>

                    </select>
                </div>
                <span>&nbsp; Choose Skin</span>
            </div>

            <div class="ace-settings-item">
                <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-navbar" />
                <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
            </div>

            <div class="ace-settings-item">
                <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-sidebar" />
                <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
            </div>

            <div class="ace-settings-item">
                <input type="checkbox" class="ace ace-checkbox-2  ace-save-state" id="ace-settings-breadcrumbs" />
                <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
            </div>

            <div class="ace-settings-item">
                <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-add-container" />
                <label class="lbl" for="ace-settings-add-container">
                    Inside
                    <b>.container</b>
                </label>
            </div>
        </div><!-- /.pull-left -->

        <div class="pull-left width-50">

        </div><!-- /.pull-left -->
    </div><!-- /.ace-settings-box -->
</div><!-- /.ace-settings-container -->
 *
 */?>