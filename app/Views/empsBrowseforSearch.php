<!doctype html>
<html lang="en">
<head>
	<title>عرض الموظفين</title>
	<link rel="icon" href="<?= base_url('assets/images/logo_icon.png'); ?>">
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex">
	<?= view('bootstrap4'); ?>
	<link rel="stylesheet" href="<?= base_url('assets/css/fonts.css'); ?>" />
	<link rel="stylesheet" href="<?= base_url('assets/css/general.css'); ?>" />
	<link rel="stylesheet" href="<?= base_url('assets/css/browse_style.css'); ?>" />
	<script src="<?= base_url('assets/js/google-ajax.js'); ?>"></script>

	<style>
		.card {
			text-align: right;
		}
		#empData {
			padding: 50px;
		}
		.card-img-top {
			width: 100%;
			height: 15vw;
		}
	</style>
</head>
<body dir="rtl">

	<?= view('header'); ?>

	<div class="row input-group-md mx-auto" style="margin-top:70px; direction: ltr;">
		<div class="col-4 col-md-2 col-lg-2 col-xl-3"></div>
		<div class="col-12 col-md-8 col-lg-8 col-xl-6">
			<form action="<?= base_url('employees/nextSearch'); ?>" method="GET">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<button type="submit" class="btn btn-outline-primary fav">بـحـــث</button>
						<?php if (session('type') === "ADMIN"): ?>
						<select class="custom-select" id="search_at" name="search_at" style="direction: rtl;">
							<option value="undefined" <?= ($search_at === 'undefined' || $search_at === null) ? 'selected' : ''; ?>>جميع الأقسام</option>
							<?php foreach ($secs as $sec): ?>
							<option value="<?= $sec->id; ?>" <?= ($search_at == $sec->id) ? 'selected' : ''; ?>>
								<?= esc($secModel->getSecById($sec->id)->ar_name); ?>
							</option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</div>
					<select class="custom-select" id="search_in" name="search_in" style="direction: rtl;">
						<option value="undefined" <?= ($search_in === 'undefined' || $search_in === null) ? 'selected' : ''; ?>>تحديداً</option>
						<option value="name" <?= ($search_in === 'name') ? 'selected' : ''; ?>>الاسم</option>
						<option value="civil_id" <?= ($search_in === 'civil_id') ? 'selected' : ''; ?>>الرقم المدني</option>
						<option value="file_no" <?= ($search_in === 'file_no') ? 'selected' : ''; ?>>رقم الملف</option>
						<option value="mobile" <?= ($search_in === 'mobile') ? 'selected' : ''; ?>>التيليفون</option>
					</select>
					<input type="text" id="search_text" name="search_text" class="form-control" style="direction: rtl;" value="<?= isset($search_text) && $search_text !== null ? esc($search_text) : ''; ?>">
				</div>
			</form>
		</div>
		<div class="col-4 col-md-2 col-lg-2 col-xl-3"></div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col" style="text-align: right; font-size: 12pt; color: white;">
				عدد نتائج البحث : <?= count($result); ?>
			</div>
		</div>

		<div id="empsData" class="row row-cols-xs-6 row-cols-md-4 row-cols-lg-3 row-cols-xl-3" style="padding: 30px;">
			<?php foreach ($result as $row): ?>
			<div class="col-md-3 col-lg-2 col-xl-2 mb-4">
				<a href="<?= base_url('employees/display/' . $row->id); ?>" style="text-decoration: none;">
					<div class="card" style="color: #006699; text-align: left; font-family: calibri;">
						<?php
						$photo = 'assets/images/male_avatar.jpg';
						if (!empty($row->photo) && file_exists(FCPATH . $row->photo)) {
							$photo = $row->photo;
						} elseif ($row->gender === "female") {
							$photo = 'assets/images/female_avatar.jpg';
						}
						?>
						<img src="<?= base_url($photo); ?>" class="card-img" alt="<?= esc($row->en_name); ?>">

						<div class="card-body">
							<h5 class="card-title"><?= esc($row->en_name); ?></h5>
							<p class="card-text">
								<h6 class="card-subtitle mb-2 text-muted">
									<?= ($design = $designModel->find($row->design_id)) && is_object($design) && isset($design->name) ? $design->name . '<br />' : ''; ?>
									<?= ($subSec = $subSecModel->find($row->sub_sec_id)) && is_object($subSec) && isset($subSec->en_name) ? '(' . esc($subSec->en_name) . ') <br />' : ''; ?>
									<?= ($sec = $secModel->find($row->sec_id)) && is_object($sec) && isset($sec->en_name) ? esc($sec->en_name) . '<br />' : ''; ?>
									<br />
									<?= esc($row->file_no); ?>
								</h6>
							</p>
						</div>
					</div>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</body>
</html>
