<?php
$currentPage  = $this->uri->segment(2);
$currentPage1 = $this->uri->segment(3);




$openSite   = $openSite1  = $openDash   = $openPages  = $openPages1 = $openUsers  = $openUsers1 = $openUsers2 = '';
$openRoles  = $openRoles1 = $openCat    = $openCat1   = $openCat2   = $openPro    = $openPro1   = '';
$opentest   = $opentest1  = $openblog   = $openblog1  = $openblog2  = '';
$openfeed   = $openfeed1  = $openCom    = $openCom1   = $openCom2   = $openCom3   = $openCom4   = '';
$helptopoic = $helptopoic1 = '';
$openRep    = $openRep1   = $openRep2   = $openRep3   = $openRep4   = $openRep5   = $openRep6   = '';
$openPay    = $openPay1   = $openAdv1   = $openAdv1   = $openEmail  = $openEmail1 = '';
$openPages2 = $openPages3 = $openPages4  = $openPages5 = $openPay2   = $openLead   = $openLead1  = '';

if ($currentPage == 'site_settings')
{
    $openSite  = 'active open';
    $openSite1 = 'active';
}
elseif ($currentPage == 'dashboard')
{
    $openDash = 'active';
}
elseif ($currentPage == 'admin_users')
{
    $openAdmin  = 'active open';
    $openAdmin1 = 'active';
}
elseif ($currentPage == 'roles')
{
    $openRoles  = 'active open';
    $openRoles1 = 'active';
}
elseif ($currentPage == 'categories' && $currentPage1 == '')
{
    $openCat  = 'active open';
    $openCat1 = 'active';
}
elseif ($currentPage == 'categories')
{
    $openCat  = 'active open';
    $openCat2 = 'active';
}
elseif ($currentPage == 'listings')
{
    $openPro  = 'active open';
    $openPro1 = 'active';
}
elseif ($currentPage == 'helptopics')
{
    $helptopoic  = 'active open';
    $helptopoic1 = 'active';
}
elseif ($currentPage == 'users')
{
    $openUsers = 'active open';
    if ($currentPage1 == '' || $currentPage1 == 'add_advertiser' || $currentPage1 == 'edit_advertiser' || $currentPage1 == 'delete_advertiser')
    {
        $openUsers1 = 'active';
    }
    if ($currentPage1 == 'publisher' || $currentPage1 == 'add_publisher' || $currentPage1 == 'edit_publisher' || $currentPage1 == 'delete_publisher')
    {
        $openUsers2 = 'active';
    }
    if ($currentPage1 == 'publisher_invitations' || $currentPage1 == 'add_publisher_invitations' || $currentPage1 == 'edit_publisher_invitations' || $currentPage1 == 'delete_publisher_invitations')
    {
        $openUsers3 = 'active';
    }
}
elseif ($currentPage == 'payment_integration' || $currentPage == 'social_integration')
{
    $openPay = 'active open';
    if ($currentPage == 'payment_integration')
    {
        $openPay1 = 'active';
    }
    if ($currentPage == 'social_integration')
    {
        $openPay2 = 'active';
    }
}
elseif ($currentPage == 'announcements')
{
    $openAdv  = 'active open';
    $openAdv1 = 'active';
}
elseif ($currentPage == 'email_templates')
{
    $openEmail  = 'active open';
    $openEmail1 = 'active';
}
elseif ($currentPage == 'newsletter')
{
    $opennews  = 'active open';
    $opennews1 = 'active';
}
elseif ($currentPage == 'feedback')
{
    $openfeed  = 'active open';
    $openfeed1 = 'active';
}
elseif ($currentPage == 'boat_types')
{
    $openBoatType  = 'active open';
    $openBoatType1 = 'active';
}
elseif ($currentPage == 'blog' || $currentPage == 'blog_categories')
{
    $openblog = 'active open';
    if ($currentPage == 'blog_categories')
    {
        $openblog1 = 'active';
    }
    if ($currentPage == 'blog')
    {
        $openblog2 = 'active';
    }
}
elseif ($currentPage == 'commission' && $currentPage1 == '')
{
    $openCom  = 'active open';
    $openCom1 = 'active';
}
elseif ($currentPage == 'commission' && $currentPage1 == 'lead_generation')
{
    $openCom  = 'active open';
    $openCom2 = 'active';
}
elseif ($currentPage == 'commission' && $currentPage1 == 'manage_withdraw')
{
    $openCom  = 'active open';
    $openCom3 = 'active';
}
elseif ($currentPage == 'commission' && $currentPage1 == 'manage_invoices')
{
    $openCom  = 'active open';
    $openCom4 = 'active';
}
elseif ($currentPage == 'lead_generation')
{
    $openLead  = 'active open';
    $openLead1 = 'active';
}
elseif ($currentPage == 'reports')
{
    $openRep = 'active open';
    if ($currentPage1 == 'advertiser')
    {
        $openRep1 = 'active';
    }
    if ($currentPage1 == 'publisher')
    {
        $openRep2 = 'active';
    }
    if ($currentPage1 == 'ads_list_report')
    {
        $openRep3 = 'active';
    }
    if ($currentPage1 == 'advertiser_commissions')
    {
        $openRep4 = 'active';
    }
    if ($currentPage1 == 'publisher_commissions')
    {
        $openRep5 = 'active';
    }
    if ($currentPage1 == 'admin_commissions')
    {
        $openRep6 = 'active';
    }
}
elseif ($currentPage == 'pages')
{
    $openPages = 'active open';
    if ($currentPage1 == 'add' || $currentPage1 == 'edit' || $currentPage1 == '')
    {
        $openPages1 = 'active';
    }
    if ($currentPage1 == 'home')
    {
        $openPages2 = 'active';
    }
    if ($currentPage1 == 'login_signup_content')
    {
        $openPages3 = 'active';
    }
    if ($currentPage1 == 'welcome_content' && $this->uri->segment(4) == 2)
    {
        $openPages4 = 'active';
    }
    if ($currentPage1 == 'welcome_content' && $this->uri->segment(4) == 3)
    {
        $openPages5 = 'active';
    }
}
$role_id = $this->engineinit->get_session_super_admin();
?>
<!-- sidebar menu -->
<div id="sidebar" class="sidebar                  responsive ace-save-state">
    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">

            <a title="Dashboard" class="btn btn-success" href="<?php
            echo base_url('admin/dashboard');
            ?>">
                <i class="ace-icon fa fa-signal"></i>
            </a>
            <?php
            if (rights(2) == true)
            {
                ?>
                <a  title="Profile" class="btn btn-info" href="<?php
                echo base_url('admin/admin_users/edit/' . $this->common->encode($this->session->userdata('user_id')));
                ?>">
                    <i class="ace-icon fa fa-pencil"></i>
                </a>

                <a  title="Admin's" class="btn btn-warning" href="<?php
                echo base_url('admin/admin_users');
                ?>">
                    <i class="ace-icon fa fa-users"></i>
                </a>
                <?php
            }
            ?>
            <?php
            if (rights(1) == true)
            {
                ?>
                <a  title="Site Settings" class="btn btn-danger" href="<?php
                echo base_url('admin/site_settings');
                ?>">
                    <i class="ace-icon fa fa-cogs"></i>
                </a>
                <?php
            }
            ?>
        </div>

        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <span class="btn btn-success"></span>

            <span class="btn btn-info"></span>

            <span class="btn btn-warning"></span>

            <span class="btn btn-danger"></span>
        </div>
    </div><!-- /.sidebar-shortcuts -->

    <ul class="nav nav-list">
        <li class="<?php
        echo $openDash;
        ?>">
            <a href="<?php
            echo base_url('admin/dashboard');
            ?>">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text"> Dashboard </span>
            </a>

            <b class="arrow"></b>
        </li>


        <li class="<?php
        echo $openSite;
        ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-desktop"></i>
                <span class="menu-text">
                    Site
                </span>

                <b class="arrow fa fa-angle-down"></b>
            </a>

            <b class="arrow"></b>

            <ul class="submenu">

                <?php
                if (rights(1) == true)
                {
                    ?>
                    <li class="<?php
                    echo $openSite1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/site_settings');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Site settings
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <?php
                }
                ?>

                <li class="">
                    <a target="_blank" href="<?php
                    echo base_url();
                    ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        View Site
                    </a>

                    <b class="arrow"></b>
                </li>

                <li class="">
                    <a href="<?php
                    echo base_url('admin/login/logout');
                    ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Logout
                    </a>

                    <b class="arrow"></b>
                </li>


            </ul>
        </li>

        <?php
        if ($this->session->userdata('role_id') == 0)
        {
            ?>
            <li class="<?php
            echo $openRoles;
            ?><?php
            echo $openAdmin;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-cog"></i>
                    <span class="menu-text">
                        Admin Users
                    </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">

                    <li class="<?php
                    echo $openRoles1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/roles');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Admin Roles
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <?php
                    if (rights(2) == true)
                    {
                        ?>
                        <li class="<?php
                        echo $openAdmin1;
                        ?>">
                            <a href="<?php
                            echo base_url('admin/admin_users');
                            ?>">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Manage Admin's
                            </a>

                            <b class="arrow"></b>
                        </li>
                        <?php
                    }
                    ?>

                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(64) == true)
        {
            ?>
            <li class="<?php
            echo $openPay;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-share"></i>
                    <span class="menu-text">
                        Manage Integrations
                    </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">

                    <li class="<?php
                    echo $openPay1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/payment_integration');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Payment Integration
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="<?php
                    echo $openPay2;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/social_integration');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Social Integration
                        </a>

                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(68) == true)
        {
            ?>
            <li class="<?php
            echo $openAdv;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-newspaper-o"></i>
                    <span class="menu-text">
                        Advertisements
                    </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">

                    <li class="<?php
                    echo $openAdv1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/announcements');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Advertisement
                        </a>

                        <b class="arrow"></b>
                    </li>

                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(82) == true)
        {
            ?>
            <li class="<?php
            echo $openUsers;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-users"></i>
                    <span class="menu-text">
                        Users
                    </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">

                    <li class="<?php
                    echo $openUsers1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/users');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Advertiser
                        </a>

                        <b class="arrow"></b>
                    </li>

                    <li class="<?php
                    echo $openUsers2;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/users/publisher');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Publisher
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="<?php
                    echo $openUsers3;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/users/publisher_invitations');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Publisher Invitations
                        </a>

                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(90) == true)
        {
            ?>
            <li class="<?php
            echo $openCat;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-list-alt"></i>
                    <span class="menu-text"> Categories </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php
                    echo $openCat1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/categories');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Categories
                        </a>

                        <b class="arrow"></b>
                    </li>



                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(21) == true)
        {
            ?>
            <li class="<?php
            echo $openPro;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-list"></i>
                    <span class="menu-text"> Listings </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php
                    echo $openPro1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/listings');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Listings
                        </a>

                        <b class="arrow"></b>
                    </li>


                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(101) == true)
        {
            ?>
            <li class="<?php
            echo $openCom;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-money"></i>
                    <span class="menu-text"> Commission </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php
                    echo $openCom1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/commission');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Total Commission
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <?php
                    /* ?>
                      <li class="<?php echo $openCom2; ?>">
                      <a href="<?php echo base_url('admin/commission/lead_generation'); ?>">
                      <i class="menu-icon fa fa-caret-right"></i>
                      Lead Generation Commission
                      </a>

                      <b class="arrow"></b>
                      </li>
                      <?php */
                    ?>
                    <li class="<?php
                    echo $openCom3;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/commission/manage_withdraw');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Withdraw
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo $openCom4; ?>">
                        <a href="<?php echo base_url('admin/commission/manage_invoices'); ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Invoice Management
                        </a>
                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(103) == true)
        {
            /*
              ?>
              <li class="<?php echo $openLead; ?>">
              <a href="#" class="dropdown-toggle">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text"> Lead Generation </span>

              <b class="arrow fa fa-angle-down"></b>
              </a>

              <b class="arrow"></b>

              <ul class="submenu">
              <li class="<?php echo $openLead; ?>">
              <a href="<?php echo base_url('admin/lead_generation'); ?>">
              <i class="menu-icon fa fa-caret-right"></i>
              Manage Lead Generation
              </a>

              <b class="arrow"></b>
              </li>


              </ul>
              </li>
              <?php */
        }
        ?>

        <?php
        if (rights(102) == true)
        {
            ?>
            <li class="<?php
            echo $openRep;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-list"></i>
                    <span class="menu-text"> Reports </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php
                    echo $openRep1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/reports/advertiser');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Advertiser list
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php
                    echo $openRep2;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/reports/publisher');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Publisher list
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php
                    echo $openRep3;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/reports/ads_list_report');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Listings
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php
                    echo $openRep4;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/reports/advertiser_commissions');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Advertiser Commissions
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php
                    echo $openRep5;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/reports/publisher_commissions');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Publisher Commissions
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php
                    echo $openRep6;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/reports/admin_commissions');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Admin Commissions
                        </a>
                        <b class="arrow"></b>
                    </li>


                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(25) == true)
        {
            ?>
            <li class="<?php
            echo $openPages;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-file-text-o"></i>
                    <span class="menu-text"> CMS Pages </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php
                    echo $openPages1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/pages');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage CMS Pages
                        </a>

                        <b class="arrow"></b>
                    </li>

                    <li class="<?php
                    echo $openPages2;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/pages/home');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Edit Home Page
                        </a>

                        <b class="arrow"></b>
                    </li>

                    <li class="<?php
                    echo $openPages3;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/pages/login_signup_content');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Edit SignIn/SignUp Content
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="<?php
                    echo $openPages4;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/pages/welcome_content/2');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Edit Welcome Content
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="<?php
                    echo $openPages5;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/pages/welcome_content/3');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Edit Welcome Content Other
                        </a>

                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(97) == true)
        {
            ?>
            <li class="<?php
            echo $openblog;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-share-square-o"></i>
                    <span class="menu-text"> Blog </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php
                    echo $openblog1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/blog_categories');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Blog Categories
                        </a>

                        <b class="arrow"></b>
                    </li>

                    <li class="<?php
                    echo $openblog2;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/blog');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Blog
                        </a>

                        <b class="arrow"></b>
                    </li>

                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(29) == true)
        {
            ?>
            <li class="<?php
            echo $opennews;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-bullhorn"></i>
                    <span class="menu-text"> NewsLetters </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php
                    echo $opennews1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/newsletter');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage NewsLetters
                        </a>

                        <b class="arrow"></b>
                    </li>

                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(48) == true)
        {
            ?>
            <li class="<?php
            echo $openfeed;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-envelope-o"></i>
                    <span class="menu-text">Contact Us Feedback </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php
                    echo $openfeed;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/feedback');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Contact Us Feedback
                        </a>

                        <b class="arrow"></b>
                    </li>

                </ul>
            </li>
            <?php
        }
        ?>

        <?php
        if (rights(76) == true)
        {
            ?>
            <li class="<?php
            echo $openEmail;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-envelope-o"></i>
                    <span class="menu-text"> Email Templates </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">

                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'signup_email_templete')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/signup_email_templete');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>Signup Email
                        </a>
                        <b class="arrow"></b>
                    </li>

                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'account_activation_email')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/account_activation_email');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>Account Activation Email
                        </a>
                        <b class="arrow"></b>
                    </li>

                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'forgot_password_email_templete')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/forgot_password_email_templete');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>Forgot Password Email
                        </a>
                        <b class="arrow"></b>
                    </li>

                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'feedback_email_templete')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/feedback_email_templete');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>Feedback Email
                        </a>
                        <b class="arrow"></b>
                    </li>

                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'newsletter_email_templete')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/newsletter_email_templete');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>Newsletter Email
                        </a>
                        <b class="arrow"></b>
                    </li>

                    <!--                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'product_email_templete')? 'active' : ''); ?>"><a href="<?php
                    echo base_url('admin/email_templates/product_email_templete');
                    ?>">
                                                <i class="menu-icon fa fa-caret-right"></i>Product Save Template
                                            </a>
                                            <b class="arrow"></b>
                                        </li>-->

                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'contact_us_email')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/contact_us_email');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>Contact Us Template
                        </a>
                        <b class="arrow"></b>
                    </li>

                    <!--                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'order_email_templete')? 'active' : ''); ?>"><a href="<?php
                    echo base_url('admin/email_templates/order_email_templete');
                    ?>">
                                                <i class="menu-icon fa fa-caret-right"></i>Order Email Template
                                            </a>
                                            <b class="arrow"></b>
                                        </li>-->

                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'payment_email_templete')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/payment_email_templete');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>Payment Email Template
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'publisher_invitation')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/publisher_invitation');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>Publisher Invitation
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'new_product')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/new_product');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>New Product <small>(admin)</small>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'on_sale')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/on_sale');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>On Sale <small>Advertiser</small>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'on_sale_publisher')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/on_sale_publisher');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>On Sale <small>Publisher</small>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'on_invoice_sending')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/on_invoice_sending');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>On Invoice <small>sending</small>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'on_withdraw_request')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/on_withdraw_request');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>On Withdraw Request
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'on_withdrawal')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/on_withdrawal');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>On Withdrawal 
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'on_first_invitation_email')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/on_first_invitation_email');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>First Invitation Email
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'on_welcome_message_email')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/on_welcome_message_email');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>Welcome Message Email
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'on_product_activation_email')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/on_product_activation_email');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>Product Activation
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="<?php echo (($currentPage == 'email_templates' && $currentPage1 == 'on_product_deactivation_email')? 'active' : ''); ?>"><a href="<?php
                        echo base_url('admin/email_templates/on_product_deactivation_email');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>Product Deactivation
                        </a>
                        <b class="arrow"></b>
                    </li>

                </ul>
            </li>
            <?php
        }
        ?>
        <?php
        if (true)
        {
            ?>
            <li class="<?php
            echo $helptopoic;
            ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-question"></i>
                    <span class="menu-text">Help Topics </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php
                    echo $helptopoic1;
                    ?>">
                        <a href="<?php
                        echo base_url('admin/helptopics');
                        ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Manage Help Topics
                        </a>

                        <b class="arrow"></b>
                    </li>

                </ul>
            </li>
            <?php
        }
        ?>

    </ul><!-- /.nav-list -->

    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left  ace-save-state" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>

</div>
<!-- /sidebar menu -->