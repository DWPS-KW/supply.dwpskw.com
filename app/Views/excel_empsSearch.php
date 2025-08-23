<!doctype html>
<html lang="en">
<head>
	<title>Browsing Employees Data</title>
	<link rel="icon" href="<?= base_url('assets/images/logo_icon.png'); ?>">
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex">
	<!-- Datatable css -->
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/DataTables/datatables.min.css'); ?>"/>
	<?= view('bootstrap4'); ?>
	<script src="<?= base_url('assets/js/google-ajax.js'); ?>"></script>

	<link rel="stylesheet" href="<?= base_url('assets/css/fonts.css'); ?>" />
	<link rel="stylesheet" href="<?= base_url('assets/css/general.css'); ?>" />
	<link rel="stylesheet" href="<?= base_url('assets/css/browse_style.css'); ?>" />

	<!-- Datatable js -->
	<script type="text/javascript" src="<?= base_url('assets/DataTables/datatables.min.js'); ?>"></script>

	<script>
		$(document).ready(function () {
			$('#srchRes_desktop').DataTable({
				dom: 'Bt',
				buttons: [
					'excel'
				],
				paging: false,
				"ordering": false
			});
		});
	</script>
	<style>
		#srchRes_desktop th {
			text-align: center;
		}

		.container-fluid {
			background: none;
		}
	</style>
</head>
<body dir="rtl">

<div class="container-fluid">

	<div class="row">
		<div class="col" style="text-align: right; font-size: 12pt; color: white;">
			عدد نتائج البحث : <?= count($result); ?>
		</div>
	</div>

	<div class="row">
		<div class="col pdata" style="width: 100%; text-align: center; font-size: 16pt; margin-top: 10px; margin-bottom: 10px;">
			كشف بأسماء العاملين في مراقبة الصيانة الميكانيكية
		</div>
	</div>
	<div class="row-clear"></div>

	<table class="table table-striped table-hover display" id="srchRes_desktop" style="direction: rtl;">
		<thead>
		<tr>
			<th class="tbl_row">#</th>
			<th class="tbl_row">رقم الملف</th>
			<th class="tbl_row">الاسم بالعربي</th>
			<th class="tbl_row">الاسم بالانجليزي</th>
			<th class="tbl_row">الجنس</th>
			<th class="tbl_row">موبايل</th>
			<th class="tbl_row">الرقم المدني</th>
			<th class="tbl_row">تاريخ التعيين</th>
			<th class="tbl_row">المسمى</th>
			<th class="tbl_row">الراتب</th>
			<th class="tbl_row">المراقبة</th>
			<th class="tbl_row">القسم</th>
			<th class="tbl_row">الجنسية</th>
			<th class="tbl_row">الميلاد</th>
			<th class="tbl_row">المؤهل</th>
			<th class="tbl_row">نوع التوظيف</th>
			<th class="tbl_row">الخبرة</th>
			<th class="tbl_row">في الخدمة</th>
			<th class="tbl_row">الفاتورة</th>
			<th class="tbl_row">إضافي</th>
			<th class="tbl_row">ملاحظات</th>
		</tr>
		</thead>
		<tbody>
		<?php $i = 1; foreach ($result as $row): ?>
			<tr>
				<td class="tbl_row"><?= $i; ?></td>
				<td class="tbl_row"><?= esc($row->file_no); ?></td>
				<td class="tbl_row">
					<a href="<?= base_url('employees/display/' . $row->id); ?>" target="_new" style="text-decoration: none;">
						<?= esc($row->ar_name); ?>
					</a>
				</td>
				<td class="tbl_row en">
					<a href="<?= base_url('employees/display/' . $row->id); ?>" target="_new" style="text-decoration: none;">
						<?= ucwords(strtolower(esc($row->en_name))); ?>
					</a>
				</td>
				<td class="tbl_row en"><?= esc($row->gender); ?></td>
				<td class="tbl_row"><?= esc($row->mobile); ?></td>
				<td class="tbl_row"><?= esc($row->civil_id); ?></td>
				<td class="tbl_row"><?= $row->join_date ? $myFuns->decorateDate($row->join_date) : ''; ?></td>
				<td class="tbl_row en"><?= esc($designModel->find($row->design_id)->name); ?></td>
				<td class="tbl_row en"><?= esc($designModel->find($row->design_id)->total_salary); ?></td>
				<td class="tbl_row">
					<?= $secModel->find($row->sec_id) ? esc($secModel->find($row->sec_id)->ar_name) : ''; ?>
				</td>
				<td class="tbl_row">
					<?php if (!empty($row->sub_sec_id)): ?>
						<?php $subSec = $subSecModel->find($row->sub_sec_id); ?>
						<?php if (is_object($subSec) && isset($subSec->ar_name)): ?>
							<?= esc($subSec->ar_name); ?>
						<?php else: ?>
							<?= ''; // Output empty string if not an object or 'ar_name' is missing ?>
						<?php endif; ?>
					<?php else: ?>
						<?= ''; // Output empty string if $row->sub_sec_id is empty (null, 0, '') ?>
					<?php endif; ?>
				</td>
				<td class="tbl_row en"><?= esc($row->nation); ?></td>
				<td class="tbl_row"><?= $row->b_date ? $myFuns->decorateDate($row->b_date) : ''; ?></td>
				<td class="tbl_row"><?= esc($row->edu_cert); ?></td>
				<td class="tbl_row"><?= $row->permanent == 1 ? "دائم" : "مؤقت"; ?></td>
				<td class="tbl_row"><?= esc($row->experience); ?></td>
				<td class="tbl_row"><?= $row->active == 1 ? "في الخدمة" : "خارج الخدمة"; ?></td>
				<td class="tbl_row en"><?= esc($row->pay); ?></td>
				<td class="tbl_row"><?= $row->ot == 1 ? "مسموح" : "غير مسموح"; ?></td>
				<td class="tbl_row"><?= esc($row->remarks); ?></td>
			</tr>
		<?php $i++; endforeach; ?>
		</tbody>
	</table>

</div>
</body>
</html>
