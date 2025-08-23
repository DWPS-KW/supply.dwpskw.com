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
    body {
        margin: 0;
        padding: 0;
        background-color: #FFFFFF;
        font: 12pt "Calibri";
        color: #000;
    }
    
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page {
        width: 21cm;
        min-height: 29.7cm;
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
    .fp_table th{
        font-family: "candara";
        font-weight: 600;
        background: #bcf1f5;
        border: thin solid grey;
        padding: 7px 0px;
    }
    .fp_table td{
        border: thin solid #333;
        color: #000;
        padding: 4px 0px;
        font-size: 12pt;
    }
    @page {
        size: A4;
        margin: 0;
    }
    @media print {
        body{
            background: none;
        }
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }
   </style>
  </head>
  <body onunload="">


		 
  <div class="book">
    <?php
        if($finger_print != null){

            $i = 1;

        foreach($finger_print as $row){
    ?>
        <div class="page">
            
            <div style="width: 100%; margin: 0px 10px; text-align: center;">
                (MEW / MC / 6062 / 2024 - 2025) <br />
                M/S. AL-GHANIM INTERNATIONAL GENERAL TRADING CONTRACTING CO. <br />
                <div class="alert alert-dark" role="alert" style="font-weight: bold;">Finger Print <?php echo "from (".$from_month."-".$from_year.") to (".$to_month."-".$to_year.")"; ?></div>
            </div>

            <table style="width: 100%;" class="fp_table">
				<tr>
					<td style="width: 20%; text-align: center;">
                        File No.
					</td>
					<td style="width: 80%; text-align: center; border-bottom: solid gray thin; background: #eee;">
                        <?php echo $row['emp_data']['en_name']; ?>
					</td>
                </tr>
                <tr>
					<td style="width: 20%; text-align: center; border-bottom: solid gray thin; background: #eee;">
                        <?php echo $row['emp_data']['file_no']; ?>
					</td>
					<td style="width: 80%; text-align: center; border-bottom: solid gray thin;">
                        <?php echo $this->design_model->getNameById($row['emp_data']['design_id']); ?>
					</td>
				</tr>
			</table>

            <hr />

            <table style="width: 100%;" class="fp_table table-striped">
				<tr>
					<th style="text-align: center;">
                        Day
					</th>
					<th style="text-align: center; ">
                        Date
					</th>
					<th style="text-align: center;">
                        Clock In
					</th>
					<th style="text-align: center;">
                        Clock Out
					</th>
					<th style="text-align: center;">
                        Remarks
					</th>
				</tr>
                <?php
                    foreach($row['emp_attend_data'] as $fp){
                ?>
				<tr>
					<td style="width: 20%; text-align: center;">
                        <?php echo $fp->week; ?>
					</td>
					<td style="width: 20%; text-align: center; ">
                        <?php echo $this->myFuns->decorateDate($fp->date); ?>
					</td>
					<td style="width: 20%; text-align: center;">
                        <?php
                            if(($this->myFuns->decorateTime($fp->clock_in) != "00:00") && ($fp->clock_in != NULL) ){
                                echo $this->myFuns->decorateTime($fp->clock_in);
                            }
                        ?>
					</td>
					<td style="width: 20%; text-align: center;">
                        <?php
                            if(($this->myFuns->decorateTime($fp->clock_out) != "00:00") && ($fp->clock_out != NULL)){
                                echo $this->myFuns->decorateTime($fp->clock_out);
                            }
                        ?>
					</td>
					<td style="width: 20%; text-align: center;">
                        <?php
                            $status = "";

                            if($fp->absent == 'True' || $fp->absent == 'true' || $fp->absent == 'TRUE'){
                                
                                $status = "Absent";

                                if($this->attendModel->isLeave($row['emp_data']['id'], $fp->date)){

                                    $status = "Leave";
                                }else{

                                    if($this->attendModel->isFriday($fp->date)){

                                        $status = "Friday" ;
                                        
                                    }
                                    else{

                                        if($this->attendModel->isHoliday($fp->date)){
        
                                            $status = "Holiday";
                                        }else{

                                            if($this->attendModel->isMedical($row['emp_data']['id'], $fp->date)){
                                                
                                                $status = "Medical";
                                                
                                            }else{
            
                                                if($this->attendModel->isFday($row['emp_data']['id'], $fp->date)){
            
                                                    $status = "Full Day Permission";
                                                }
                                            }
                                        }
                                    }

                                }
                            }
                            echo $status;

                        ?>
					</td>
				</tr>
                <?php
                    }
                ?>
            </table>
            <table style="width: 100%;" class="fp_table table-striped mt-3 mb-3">
                <tr>
                    <td>
                        working_days
                    </td>
                    <td>
                        Medical
                    </td>
                    <td>
                        Leave
                    </td>
                    <td>
                        full day Perm.
                    </td>
                    <td>
                        Absent
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                            if($db_table == "before") {
                                $saved_wd = $this->attendModel->getAttendCoverListForEmp($row['emp_data']['id'], $row['month'], $row['year']);
                                
                                if ($saved_wd !== false) {
                                    echo $saved_wd->working_days;
                                } else {
                                    echo "--"; // or a default value
                                }
                            }

                            if($db_table == "after") {
                                $working_days = isset($row['emp_attend_details']['working_days']) ? $row['emp_attend_details']['working_days'] : null;
                                echo ($working_days >= 1) ? $working_days : "--";
                            }                            

                        ?>
                    </td>
                    <td>
                        <?php
                            $no_of_med = count($row['emp_attend_details']['pure_medical_days']);
                            if($no_of_med >= 1){

                                echo $no_of_med;
                            }else{
                                echo "--";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            $pure_leaves_days =  $row['emp_attend_details']['pure_leaves_days'];
                            if($pure_leaves_days >=1){

                                echo $pure_leaves_days;
                            }else{
                                echo "--";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            $fday_days =  count($row['emp_attend_details']['fday_days']);
                            if($fday_days >=1){

                                echo $fday_days;
                            }else{
                                echo "--";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            $absent_days =  count($row['emp_attend_details']['pure_absent_days']);
                            if($absent_days >=1){

                                echo $absent_days;
                            }else{
                                echo "--";
                            }
                        ?>
                    </td>
                </tr>
            </table>
            <table style="width: 100%;" class="fp_table table-striped">
                <tr>
                    <td>
                        Normal Ot
                    </td>
                    <td>
                        Friday Ot
                    </td>
                    <td>
                        Holiday Ot
                    </td>
                </tr>
                <?php
                    $not_arr = explode(":", $row['not']);
                    $fot_arr = explode(":", $row['fot']);
                    $hot_arr = explode(":", $row['hot']);
                ?>
                <tr>
                    <td>
                        <?php if($not_arr["0"] >=1){ echo $this->attendModel->displayHours($row['not']); }else{ echo "--";} ?>
                    </td>
                    <td>
                        <?php if($fot_arr["0"] >=1){ echo $this->attendModel->displayHours($row['fot']); }else{ echo "--";} ?>
                    </td>
                    <td>
                        <?php if($hot_arr["0"] >=1){ echo $this->attendModel->displayHours($row['hot']); }else{ echo "--";} ?>
                    </td>
                </tr>
            </table>

        </div>
        <?php
                $i++;
            }
        }else{
            ?>
            <div style="font-size: 16pt; color: white; text-align: center; width: 100%; padding: 5%;">
                No Employee in this section
            </div>            
            <?php
        }
        ?>			
		<!-- Container DIV Ends -->

    </div>


  </body>
</html>