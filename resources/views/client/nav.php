	<!-- [ navigation menu ] start -->
	<nav class="pcoded-navbar  ">
		<div class="navbar-wrapper  ">
			<div class="navbar-content scroll-div ">
				<div class="">
					<div class="main-menu-header">
						<img class="img-radius" src="<?=BASE_URL('')?>public/assets/images/logo.png" alt="User-Profile-Image">
						<div class="user-details">
							<span>Số dư: <?=format_cash($getUser['money'])?></span>
							<div id="more-details">Tài khoản: <?=$getUser['username']?></div>
						</div>
					</div>
				</div>
				<ul class="nav pcoded-inner-navbar ">
					<li class="nav-item pcoded-menu-caption">
						<label>Menu</label>
					</li>
					<li class="nav-item">
						<a href="<?=BASE_URL('')?>" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Trang Chủ</span></a>
					</li>
					<li class="nav-item pcoded-hasmenu">
						<a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Cổng Thanh Toán</span></a>
						<ul class="pcoded-submenu">
							<li><a href="<?=BASE_URL('client/listaccount')?>">Momo</a></li>
						</ul>
					</li>
					<li class="nav-item pcoded-menu-caption">
						<label>Nạp Tiền</label>
					</li>
					<li class="nav-item">
						<a href="<?=BASE_URL('client/recharge')?>" class="nav-link "><span class="pcoded-micon"><i class="feather icon-file"></i></span><span class="pcoded-mtext">Nạp Tiền</span></a>
					</li>
					<li class="nav-item">
						<a href="<?=BASE_URL('client/upgrade')?>" class="nav-link "><span class="pcoded-micon"><i class="feather icon-map"></i></span><span class="pcoded-mtext">Gia Hạn API</span></a>
					</li>
					<li class="nav-item pcoded-menu-caption">
						<label>Khác</label>
					</li>
					<li class="nav-item">
						<a href="<?=BASE_URL('client/document')?>" class="nav-link "><span class="pcoded-micon"><i class="feather icon-file-text"></i></span><span class="pcoded-mtext">Tài Liệu API</span></a>
					</li>
				</ul>
				<div class="card text-center">
					<div class="card-block">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<i class="feather icon-sunset f-40"></i>
						<h6 class="mt-3">DAILYSIEURE.COM</h6>

						<a href="https://dailysieure.com/" target="_blank" class="btn btn-primary btn-sm text-white m-0">XEM NGAY</a>
					</div>
				</div>
			</div>
		</div>
	</nav>
	<!-- [ navigation menu ] end -->
	<!-- [ Header ] start -->
	<header class="navbar pcoded-header navbar-expand-lg navbar-light header-dark">
		<div class="m-header">
			<a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
			<a href="#!" class="b-brand">
				<!-- ========   change your logo hear   ============ -->
				<img src="https://dailysieure.com/hinhanh/logo2019.png" width="150px" alt="" class="logo">
				<img src="https://dailysieure.com/hinhanh/logo2019.png" alt="" class="logo-thumb">
			</a>
			<a href="#!" class="mob-toggler">
				<i class="feather icon-more-vertical"></i>
			</a>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a href="#!" class="pop-search"><i class="feather icon-search"></i></a>
					<div class="search-bar">
						<input type="text" class="form-control border-0 shadow-none" placeholder="Tìm kiếm...">
						<button type="button" class="close" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</li>
				<li class="nav-item">
					<div class="dropdown">
						<a class="dropdown-toggle h-drop" href="#" data-toggle="dropdown">
							Liên Hệ
						</a>
						<div class="dropdown-menu profile-notification ">
							<ul class="pro-body">
								<li><a href="<?=$NNL->site('link_facebook')?>" target="_blank" class="dropdown-item"><i class="fas fa-circle"></i> Facebook</a></li>
								<li><a href="<?=$NNL->site('link_zalo')?>" target="_blank" class="dropdown-item"><i class="fas fa-circle"></i> Zalo</a></li>
							</ul>
						</div>
					</div>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li>
					<div class="dropdown">
						<a class="dropdown-toggle" href="#" data-toggle="dropdown">
							<i class="icon feather icon-bell"></i>
							<span class="badge badge-pill badge-danger"><?= format_cash($NNL->num_rows("SELECT * FROM `notifications` where `status`='1'")) ?></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right notification">
							<div class="noti-head">
								<h6 class="d-inline-block m-b-0">Thông báo hệ thống</h6>
							</div>
							<ul class="noti-body">
							<?php foreach ($NNL->get_list("SELECT * FROM `notifications` ORDER BY `id` DESC") as $row): ?>
								<li class="notification">
									<div class="media">
										<img class="img-radius" src="<?=BASE_URL('')?>public/assets/images/bell.png" alt="Generic placeholder image">
										<div class="media-body">
											<p><strong><?=$row['title']?></strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i><?=$row['create_date']?></span></p>
											<?=$row['content']?>
										</div>
									</div>
								</li>
								<?php endforeach?>
							</ul>
						</div>
					</div>
				</li>
				<li>
					<div class="dropdown drp-user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="feather icon-user"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right profile-notification">
							<div class="pro-head">
								<img src="<?=BASE_URL('')?>public/assets/images/logo.png" class="img-radius" alt="User-Profile-Image">
								<span><?=$getUser['username']?></span>
								<a href="<?=BASE_URL('client/logout')?>" class="dud-logout" title="Logout">
									<i class="feather icon-log-out"></i>
								</a>
							</div>
							<ul class="pro-body">
								<?php if (isset($getUser) && $getUser['level'] == '1'): ?>
								<li><a href="<?=BASE_URL('admin')?>" class="dropdown-item"><i class="feather icon-settings m-r-5"></i>Quản trị website</a></li>
								<?php endif?>
								<li><a href="<?=BASE_URL('client/user-profile')?>" class="dropdown-item"><i class="feather icon-user"></i> Thông Tin Tài Khoản</a></li>
								<li><a href="<?=BASE_URL('client/logout')?>" class="dropdown-item"><i class="feather icon-log-out m-r-5"></i>Đăng Xuất</a></li>
							</ul>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</header>
	<!-- [ Header ] end -->