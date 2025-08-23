<!doctype html>
<html lang="en">
  <head>
        <title>Printing</title>
        <link rel="icon" href="<?php echo base_url(); ?>assets/images/logo_icon.png">
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="robots" content="noindex">
		<?php include("bootstrap4.php"); ?>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/fonts.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/general.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/browse_style.css" />
		<script src="<?php echo base_url();?>assets/js/google-ajax.js"></script>

  		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/printing.css" />

   <style>
    .tbl_head{
        font-family: "calibri";
        font-size: 12pt;
        font-weight: bold;
        vertical-align: top;
        text-align: center;
    }
    .tbl_row{
        font-family: "calibri";
        font-size: 12pt;
        vertical-align: middle;
        text-align: center;
        border-left: thin solid #333;
    }
    body {
        margin: 0;
        padding: 0;
        background: #fff;
        font: 12pt "Abdo";
        color: #000;
    }
    
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page {
        min-width: 21cm;
        height: 29.7cm;
        padding: 1cm;
        margin: 1cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    
    .subpage {
        width:100%;
        text-align: right;
    }
    .mew_logo{
        width: 40px;
    }
    .first_line{
        font-weight: bold;
        font: 16pt "Abdo";
    }
    .second_line{
        font-weight: 400;
        font: 14pt "Abdo";
    }
    .third_line{
        font: 13pt;
    }
    .fourth_line{
        font-weight: 400;
        font: 12pt "Abdo";
    }
    .fifth_line{
        font: 12pt "Abdo";
        font-weight: 400;
    }
    .kuwait_logo{
        width: 130px;
        height: auto;
    }
    .flag_logo{
        width: 90px;
        height: auto;
    }
    @page {
        size: landscape;
        margin: 30px 0px 0px 0px;
        
    }
    @media print {
        .hide_print{
            display: none;
        }
        .tbl_head{
            font-family: "calibri";
            font-size: 10pt;
            font-weight: bold;
            vertical-align: top;
            text-align: center;
        }
        .tbl_row{
            font-family: "calibri";
            font-size: 9pt;
            vertical-align: middle;
            text-align: center;
            border-left: thin solid #333;
        }
        .page {
            margin: 0;
            padding: 0px 20px;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
            size: landscape;
        }
        .first_line{
            font-weight: bold;
            font: 14pt "Abdo";
        }
        .second_line{
            font-weight: bold;
            font: 12pt "Abdo";
        }
        .third_line{
            font: 11pt;
        }
        .fourth_line{
            font-weight: bold;
            font: 10pt "Abdo";
        }
        .fifth_line{
            font: 10pt "Abdo";
            font-weight: 400;
        }
        .mew_logo{
            width: 30px;
        }
        .kuwait_logo{
            width: 98px;
            height: auto;
        }
        .flag_logo{
            width: 68px;
            height: auto;
        }
    }
    .cover_table th td{
        text-align: right;
        border-right: thin solid #333;
    }
    .cover_table tr{
        border: thin solid #333;
    }
    .sign_footer{
        font-weight: bold;
        font: 14pt "Abdo";
    }

   </style>
  </head>
  <body onunload="">
		
  <div class="book">
      <div class="page">
        <table style="width: 100%;">
            <tr>
                <td style="width: 15%; text-align: right; vertical-align: middle;">
                    <img src="<?php echo base_url(); ?>assets/images/state_of_kuwait.png" class="flag_logo"  alt="">
                </td>
                <td style="width: 70%; text-align: center;">

                    <img src="<?php echo base_url(); ?>assets/images/mew_logo.png" class="center-block mew_logo" alt="">

                    <br />

                    <span class="first_line">
                        وزارة الكـهـربـاء والمــاء والطاقة المتجددة
                    </span>
                    <br />

                    <span class="second_line">
                        محطة الدوحة الغربية
                    </span>
                    <br />

                    <span class="third_line">
                        و ك م / ع ص / 6062 / 2024 - 2025
                    </span>
                    <br />

                    <span class="fourth_line">
                        أعمال الصيانة السنوية للمعدات الميكانيكية في محطات القوى الكهربائية وتقطير المياه
                    </span>
                    
                </td>
                <td style="width: 15%; text-align: left; vertical-align: middle;">
                    <img src="<?php echo base_url(); ?>assets/images/new_kuwait_logo.png" class="kuwait_logo" alt="">
                </td>
            </tr>
        </table>
        <hr />
            <button type="button" class="btn btn-success hide_print" onclick="window.print()">
				طبــاعة
			</button>
			<table class="table table-striped table-hover display" style="direction: rtl;">
				<thead>
					<tr>
						<th scope="col" style="width: 5%; text-align: center;">#</th>
						<th scope="col" style="width: 25%; text-align: center;">الأسم</th>
						<th scope="col" style="width: 10%; text-align: center;">رقم الملف</th>
						<th scope="col" style="width: 15%; text-align: center;">التاريخ</th>
						<th scope="col" style="width: 20%; text-align: center;">المراقبة</th>
						<th scope="col" style="width: 15%; text-align: center;">القسم</th>
						<th scope="col" style="width: 10%; text-align: center;">ملاحظات</th>
					</tr>
				</thead>
				<tbody>
				<?php

				$i = 1;
				foreach($result as $row) {
					$emp = $this->emp_model->getById($row->emp_id);
					?>

					<tr>
						<td scope="col"><?php echo $i; ?></td>
						<td scope="col" class="en_text">
							<a href="<?php echo base_url().'employees/display/'.$row->emp_id; ?>" style="text-decoration: none;">
								<?php echo $emp->en_name; ?>
							</a>
						</td>
						<td scope="col"><?php echo $emp->file_no; ?></td>
						<td scope="col"><?php echo $row->date; ?></td>
						<td scope="col"><?php if($this->sec_model->getSecById($emp->sec_id)){echo $this->sec_model->getSecById($emp->sec_id)->ar_name;} ?></td>
						<td scope="col"><?php if($this->sec_model->getSubSecById($emp->sub_sec_id)){ echo $this->sec_model->getSubSecById($emp->sub_sec_id)->ar_name;} ?></td>
						<td scope="col"><?php echo $row->remarks; ?></td>
					</tr>

				<?php $i++;}?>
				</tbody>
			</table>

        </div>
    </div>


  </body>
</html>