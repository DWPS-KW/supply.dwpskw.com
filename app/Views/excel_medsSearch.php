<!doctype html>
<html lang="en">
	<head>
		<title>Browsing Permissions Data</title>
		<link rel="icon" href="<?php echo base_url(); ?>assets/images/logo_icon.png">
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="robots" content="noindex">
		<!-- Datatable css -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/DataTables/datatables.min.css"/>
		<?php include("bootstrap4.php"); ?>
		<script src="<?php echo base_url();?>assets/js/google-ajax.js"></script>

		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/fonts.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/general.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/browse_style.css" />

		<!-- Datatable js -->
		<script type="text/javascript" src="<?php echo base_url();?>assets/DataTables/datatables.min.js"></script>

		<script>

			$(document).ready(function(){

				$('#srchRes_desktop').DataTable( {
					dom: 'Bt',
					buttons: [
						'excel'
					],
					paging: false,
					"ordering": false
				} );

			});
		</script>
		<style>
			
			#srchRes_desktop th{
				text-align: center;	
			}

			.container-fluid{
				background: none;
			}
		</style>
	</head>
	<body dir="rtl" onunload="">
		
		<div class="container-fluid">
				
				<div class="row">
					<div class="col" style="text-align: right; font-size: 12pt; color: white;">	
							عدد نتائج البحث : <?php echo count($result); ?>
					</div>
				</div>

			<div class="row">
				<div class="col pdata" style="width: 100%; color: white; text-align: center; font-size: 16pt; margin-top: 10px; marigin-bottom: 10px;">
					كشف مرضيات العاملين في مراقبة الصيانة الميكانيكية
				</div>
			</div>
			<div class="row-clear"></div>

			<table class="table table-striped table-hover display" id="srchRes_desktop" style="direction: rtl;">
				<thead>
					<tr>						
						<th scope="col" style="width: 5%; text-align: center">#</th>
						<th scope="col" style="width: 25%; text-align: center">الأسم</th>
						<th scope="col" style="width: 5%; text-align: center">المدة</th>
						<th scope="col" style="width: 10%; text-align: center">من</th>
						<th scope="col" style="width: 10%; text-align: center">إلى</th>
						<th scope="col" style="width: 20%; text-align: center">المراقبة</th>
						<th scope="col" style="width: 15%; text-align: center;">القسم</th>
					</tr>
				</thead>
				<tbody>
				<?php

				$i = 1;
				foreach($result as $row) {
					$emp = $empModel->find($row->emp_id);
					
					$med_begin = $row->date;
					$dur = ($row->duration - 1);
					$med_end = date('Y-m-d', strtotime((string)$row->date. " + $dur days"));
	
					?>

					<tr>
						<td scope="col"><?php echo $i; ?></td>
						<td scope="col">
							<a href="<?php echo base_url().'employees/display/'.$row->emp_id; ?>" style="text-decoration: none;">
								<?php echo $emp->en_name; ?>
							</a>
						</td>
						<td scope="col"><?php echo $row->duration; ?></td>
						<td scope="col"><?php echo $med_begin; ?></td>
						<td scope="col"><?php echo $med_end; ?></td>
						<td scope="col"><?php if($secModel->find($emp->sec_id)){echo $secModel->find($emp->sec_id)->ar_name;} ?></td>
						<td scope="col">
							<?php if (!empty($emp->sub_sec_id)): ?>
								<?php $subSec = $subSecModel->find($emp->sub_sec_id); ?>
								<?php if (is_object($subSec) && isset($subSec->ar_name)): ?>
									<?= esc($subSec->ar_name); ?>
								<?php else: ?>
									<?= ''; // Output empty string if not an object or 'ar_name' is missing ?>
								<?php endif; ?>
							<?php else: ?>
								<?= ''; // Output empty string if $row->sub_sec_id is empty (null, 0, '') ?>
							<?php endif; ?>
						</td>
					</tr>

				<?php $i++;}?>
				</tbody>
			</table>


		</div>
  </body>
</html>
