<button id="loginBtnForm" style="display:none;" data-toggle="modal" data-target="#myLoginModal"></button>

<div class="panel-body" style="padding:0 !important;">
    <div class="modal fade" id="myLoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Login</h4>
                </div>
                <form id="formLoginPopup" name="formLoginPopup" action="" class="form-horizontal" role="form" method="post" accept-charset="utf-8">

                    <div class="alert" id="formErrorMsgPopupLogin" style="display: none;"></div>

                    <div class="modal-body">
                        <div class="form-group animated fadeInUp">
                            <label class="col-md-2 control-label">Email *</label>
                            <div class="col-md-8">
                                <div class="clearfix">
                                    <input type="email" class="form-control input-md" id="login_email" name="login_email"  placeholder="Email"  required>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="clearfix"></div>
                        <div class="form-group animated fadeInUp">
                            <label class="col-md-2 control-label">Password *</label>
                            <div class="col-md-8">
                                <div class="clearfix">
                                    <input type="password" class="form-control input-md" id="login_password" name="login_password"  placeholder="Password"  required>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="clearfix"></div>
                        <div class="form-group animated fadeInUp">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="login_rememberme" value="1" class="pull-left">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="space-2"></div>
                        <div class="clearfix"></div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" onclick="return loginUserVarify();" class="btn btn-primary btn-block">Login</button>

                    <button  id="submitLogin" type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
</div>