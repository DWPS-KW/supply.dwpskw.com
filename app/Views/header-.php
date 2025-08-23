<nav class="navbar fixed-top navbar-expand-sm navbar-light">

	<a class="navbar-brand" href="#">
		<img src="<?= base_url('assets/images/logo_h.png'); ?>" class="d-inline-block navbar-logo" alt="" loading="lazy" style="width: 180px; height: auto;">
	</a>

	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarNavDropdown" style="text-align: center;">
		<ul class="navbar-nav">
			<li class="nav-item header-search" style="padding: 7px 0px 0px 0px;">
				<form action="<?= base_url('employees/nextSearch'); ?>" method="GET">
					<div class="input-group mb-3" style="direction: ltr;">
						&nbsp;
						<div class="input-group-prepend">
							<button class="btn-sm btn-outline-secondary fav dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">ابحـــث</button>
							<div class="dropdown-menu">
								<button class="dropdown-item" name="search_in" value="undefined" type="submit">غير محدد</button>
								<button class="dropdown-item" name="search_in" value="name" type="submit">بالاسم</button>
								<button class="dropdown-item" name="search_in" value="civil_id" type="submit">بالرقم المدني</button>
								<button class="dropdown-item" name="search_in" value="file_no" type="submit">برقم الملف</button>
								<button class="dropdown-item" name="search_in" value="mobile" type="submit">بالتيليفون</button>
							</div>
						</div>
						<input type="text" id="search_text" name="search_text" value="<?= isset($search_text) && $search_text != null ? esc($search_text) : ''; ?>" placeholder="ابحث هنا" class="form-control form-control-sm" aria-label="" aria-describedby="basic-addon1" style="direction: rtl;" autofocus>
					</div>
				</form>
			</li>

			<li class="nav-item active">
				<a class="nav-link" href="<?= base_url(); ?>">الرئيسية</a>
			</li>
			<?php if (session('type') === 'ADMIN' || (session('type') === 'DEPART' && session('sec') == 2)) : ?>
				<li class="nav-item">
					<a class="nav-link" href="<?= base_url('employees/create'); ?>">إضافة موظف</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= base_url('employees/searching'); ?>">بحث مفصل</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReps" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						احصائيات
					</a>
					<div class="dropdown-menu right" aria-labelledby="navbarDropdownReps">
						<a class="dropdown-item" href="<?= base_url('employees/searching'); ?>">الموظفين</a>
						<a class="dropdown-item" href="<?= base_url('fdays/'); ?>">الأذونات</a>
						<?php if (session('type') === 'ADMIN' || (session('type') === 'DEPART' && session('allow_paper') === 'all')) : ?>
							<a class="dropdown-item" href="<?= base_url('leaves'); ?>">الإجازات</a>
							<a class="dropdown-item" href="<?= base_url('medicals'); ?>">المرضيات</a>
						<?php endif; ?>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= base_url('cpanel'); ?>">الحضور</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= base_url('holidays'); ?>">العطــلات</a>
				</li>
			<?php endif; ?>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('auth/logoutAct'); ?>" style="text-decoration: none;">
					<button type="button" id="signoutBtn" class="btn fav btn-sm">تسجيل خروج</button>
				</a>
			</li>
		</ul>
	</div>

</nav>

<br />
