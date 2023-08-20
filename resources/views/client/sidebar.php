<nav id="sidebar" class="sidebar">
    <a class="sidebar-brand" href="index.html">
        <svg>
            <use xlink:href="#ion-ios-pulse-strong"></use>
        </svg>
        API SYSTEM
    </a>
    <div class="sidebar-content">
        <div class="sidebar-user">
            <img src="<?=BASE_URL('')?>public/assets/images/avatar.gif" class="img-fluid rounded-circle mb-2" alt="Linda Miller" />
            <div class="fw-bold">Xin chào: <?=$getUser['username']?></div>
            <small>Hạn sử dụng: <?=date('h:i:s d/m/Y',$getUser['time_momo'])?></small><br>
            <small>Số dư tài khoản: <?=format_cash($getUser['money'])?>đ</small>
        </div>

        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a class="sidebar-link" href="<?=BASE_URL('')?>">
                    <i class="align-middle me-2 fas fa-fw fa-home"></i> <span class="align-middle">Trang chủ</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="<?=BASE_URL('client/listaccount')?>">
                    <i class="align-middle me-2 fas fa-fw fa-list"></i> <span class="align-middle">Tài khoản Momo</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="<?=BASE_URL('client/recharge')?>">
                    <i class="align-middle me-2 fas fa-fw fa-money-bill-wave-alt"></i> <span class="align-middle">Nạp Xiền</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="<?=BASE_URL('client/upgrade')?>">
                    <i class="align-middle me-2 fas fa-fw fa-repeat"></i> <span class="align-middle">Gia hạn API</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="<?=BASE_URL('client/document')?>">
                    <i class="align-middle me-2 fas fa-fw fa-code-branch"></i> <span class="align-middle">Tài liệu
                        API</span>
                </a>
            </li>
            <?php if(isset($getUser['level']) && $getUser['level'] == '1'){?>
            <li class="sidebar-item">
                <a class="sidebar-link" href="<?=BASE_URL('admin')?>">
                    <i class="align-middle me-2 fas fa-fw fa-cogs"></i> <span class="align-middle">Trang quản trị viên</span>
                </a>
            </li>
            <?php }?>
            <li class="sidebar-item">
                <a class="sidebar-link" href="<?=BASE_URL('client/logout')?>">
                    <i class="align-middle me-2 fas fa-fw fa-sign-in-alt"></i> <span class="align-middle">Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>
</nav>