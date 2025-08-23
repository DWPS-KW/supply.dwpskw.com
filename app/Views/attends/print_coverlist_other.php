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
        background-color: #FFFFFF;
        font: 12pt "Abdo";
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
      <table style="width: 100%;">
        <thead>
            <tr>
                <th colspan="12" style="text-align: center;">
                    <span class="fifth_line">
                    كشف بيان أسماء العمالة الموردة من شركة الغانم انترناشيونال للتجارة العامة والمقاولات - عن شهر <?php echo $month_year ; ?>
                    </span>
                    <hr />
                </th>
            </tr>
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
                <th class="tbl_head" style="width: 5%;">
                    Section
                </th>
                <th class="tbl_head" style="width: 5%;">
                    File No.
                </th>
            </tr>
        </thead>
        <tbody>
                <?php
                    $i = 1;
                    $total_records = count($emps);
                    $no_of_pages = ($total_records / 17);
                    foreach($emps as $emp){
                        
                        $emp_attend_details = $this->attendModel->calAll($emp->id, $month, $year, "after");

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

                                $leave_days = $this->attendModel->CountEmpLeaveDaysNoFri($emp->id, $from, $to, "after");
                                                                    
                                if($leave_days >= 1){

                                    $working_days =  26 - ( count ($emp_attend_details['pure_absent_days'])  + count ($emp_attend_details['leave_days']) );
                                    
                                }else{
                                    $working_days =  26 - ( count ($emp_attend_details['pure_absent_days']) );
                                }

                                if($leave_days == 26){
                                    echo "Leave";
                                }else{
                                    if($working_days == 26){
                                        echo "Full Month";
                                    }
                                    else{
                                        echo $working_days;
                                    }
                                }
                            ?>
                        </td>
                        <td class="tbl_row">
                            <?php
                                    $no_of_meds = $this->attendModel->countPureEmpMeds($emp->id, $from, $to, "after");
                                    if($no_of_meds > 0){
                                        echo $no_of_meds;
                                    }
                            ?>
                        </td>
                        <td class="tbl_row">
                            <!-- Absent Days Not Printed -->
                        </td>
                        <td class="tbl_row">
                            <!-- The Company is Calculated by it Self -->
                        </td>
                        <td class="tbl_row">
                            <?php

                                $emp_leave_data = $this->attendModel->getEmpLeaveData($emp->id, $from, $to);
                                if($emp_leave_data){
                                    foreach ($emp_leave_data as $leave_data) {
                                        echo $this->myFuns->decorateDateNo($leave_data->begin);
                                        if(count($emp_leave_data)>1){echo"<br />";}
                                    }
                                }

                            ?>
                        </td>
                        <td class="tbl_row" style="border-right: thin solid #333;">
                            <?php

                                $emp_leave_data = $this->attendModel->getEmpLeaveData($emp->id, $from, $to);
                                if($emp_leave_data){
                                    foreach ($emp_leave_data as $leave_data) {
                                    echo $this->myFuns->decorateDateNo($leave_data->end);
                                    if(count($emp_leave_data)>1){echo"<br />";}
                                    }
                                }
                            ?>
                        </td>
                        <td class="tbl_row" style="border-right: thin solid #333;">
                            <?php
                                echo ucwords(strtolower($this->sec_model->getSecNameById($emp->sec_id)));
                            ?>
                        </td>
                        <td class="tbl_row" style="border-right: thin solid #333;">
                            <?php
                                echo $emp->file_no;
                            ?>
                        </td>
                    </tr>
            <?php
                $i++;
                }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="12">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 20%; text-align: center; padding: 0px 0px 80px 0px;">مدير محطة الدوحة الغربية</td>
                                <td style="width: 60%; text-align: center;">

                                </td>
                                <td style="width: 20%; text-align: center; padding: 0px 0px 80px 0px;">مراقب الصيانة الميكانيكية</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>


  </body>
</html>