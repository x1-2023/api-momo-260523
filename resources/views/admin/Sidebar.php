<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php require_once(__DIR__.'/Navbar.php');?>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="<?=BASE_URL('admin');?>" class="brand-link">
                <center>
                    <h4>Admin Panel</h4>
                </center>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-header">Admin</li>
                        <li class="nav-item has-treeview">
                            <a href="<?=BASE_URL('admin');?>" class="nav-link ">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="<?=BASE_URL('admin/ListUsers');?>" class="nav-link ">
                            <i class="nav-icon fas fa-user-alt"></i>
                                <p>Thành viên</p>
                            </a>
                        </li>
                       
                        <li class="nav-item">
                            <a href="<?=BASE_URL('admin/ListBank');?>" class="nav-link">
                                <i class="nav-icon fas fa-university"></i>
                                <p>
                                    Ngân Hàng
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=BASE_URL('admin/SettingAPI');?>" class="nav-link ">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Cấu hình api
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=BASE_URL('admin/notification');?>" class="nav-link">
                                <i class="nav-icon fas fa-bell"></i>
                                <p>
                                    Thông Báo
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=BASE_URL('admin/Setting');?>" class="nav-link ">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Cài Đặt
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>