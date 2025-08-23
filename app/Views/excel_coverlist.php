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

		<!-- Datatable js -->
		<script type="text/javascript" src="<?php echo base_url();?>assets/DataTables/datatables.min.js"></script>

		<script>

			$(document).ready(function(){

				$('#coverlist').DataTable( {
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

        <table style="width: 100%;" id="coverlist">
            <thead>
                <tr style="border-bottom: thin solid #333;">
                    <th class="tbl_head" style="width: 3%;">
                        S.N
                    </th>
                    <th class="tbl_head" style="width: 10%;">
                        Name
                    </th>
                    <th class="tbl_head" style="width: 8%;">
                        Civil ID
                    </th>
                    <th class="tbl_head" style="width: 9%;">
                        Category Craft
                    </th>
                    <th class="tbl_head" style="width: 6%;">
                        Working Days
                    </th>
                    <th class="tbl_head" style="width: 6%;">
                        Medical Days
                    </th>
                    <th class="tbl_head" style="width: 6%;">
                        Absent Days
                    </th>
                    <th class="tbl_head" style="width: 5%;">
                        Leave Days
                    </th>
                    <th class="tbl_head" style="width: 6%;">
                        Leave From
                    </th>
                    <th class="tbl_head" style="width: 6%;">
                        Leave To
                    </th>
                    <th class="tbl_head" style="width: 6%;">
                        File No
                    </th>
                </tr>
            </thead>
            <tbody>
                    <?php
                            if(($emps != null) && (count($emps) >= 1)){

                        $i = 1;
                        $total_records = count($emps);
                        $no_of_pages = ($total_records / 17);
                        foreach($emps as $emp){
                            
                            $emp_attend_details = $this->attendModel->calEmpAttend($emp->id, $month, $year, $db_table);
                    ?>
                        <tr style="border-bottom: thin solid #333;">
                            <td class="tbl_row">
                                <?php echo $i; ?>
                            </td>
                            <td class="tbl_row" style="text-align: left; padding: 5px;">
                                <?php
                                    echo ucwords(strtolower($emp->en_name));
                                ?>
                            </td>
                            <td class="tbl_row">
                                <?php 
                                    echo $emp->civil_id;
                                ?>
                            </td>
                            <td class="tbl_row">
                                <?php
                                    echo ucwords(strtolower($this->design_model->getNameById($emp->design_id)));
                                ?>
                            </td>
                            <td class="tbl_row">
                                <?php
                                    $saved_wd = null;
                                    if($this->attendModel->getAttendCoverListId($emp->id, $month, $year)){
                                        $saved_wd = $this->attendModel->getAttendCoverListForEmp($emp->id, $month, $year)->working_days;
                                    }
                                    echo $saved_wd;
                                ?>
                            </td>
                            <td class="tbl_row">
                                <?php
                                    $no_of_med = count($emp_attend_details['pure_medical_days']);
                                    if($no_of_med >= 1){

                                        // echo $no_of_med;
                                    }
                                ?>
                            </td>
                            <td class="tbl_row">
                                <!-- Absent Days Not Printed -->
                            </td>
                            <td class="tbl_row">
                                <!-- Leave Days The Company is Calculated by it Self -->
                            </td>
                            <td class="tbl_row">
                                <?php
                                    if($emp_attend_details['has_leave'] == TRUE){
                                        foreach($emp_attend_details['leaves_list'] as $leave){
                                            echo $this->myFuns->decorateDateNo($leave->begin);
                                            if(count($emp_attend_details['leaves_list'])>1){echo"<br />";}
                                        }
                                    }
                                ?>
                            </td>
                            <td class="tbl_row" style="border-right: thin solid #333;">
                                <?php
                                    if($emp_attend_details['has_leave'] == TRUE){
                                        foreach($emp_attend_details['leaves_list'] as $leave){
                                            echo $this->myFuns->decorateDateNo($leave->end);
                                            if(count($emp_attend_details['leaves_list'])>1){echo"<br />";}
                                        }
                                    }
                                ?>
                            </td>
                            <td>
                                <?php echo $emp->file_no; ?>
                            </td>
                        </tr>
                <?php
                    $i++;
                    }
                }else{
                    ?>
                        <tr>
                            <th colspan="10" style="text-align: center; font-size: 18pt;">
                                No Employee in this section
                            </th>
                        </tr>

                    <?php
                }

                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


  </body>
</html>