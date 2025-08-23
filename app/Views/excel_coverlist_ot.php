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
      $(document).ready(function() {
        $('#coverlist').DataTable({
          dom: 'Bt',
          buttons: ['excel'],
          paging: false,
          ordering: false
        });
      });
    </script>

    <style>
      body {
        margin: 0;
        padding: 0;
        background: #fff;
        font: 12pt "Abdo";
        color: #000;
      }
      .tbl_head, .tbl_row {
        font-family: "calibri";
        font-size: 12pt;
        vertical-align: middle;
        text-align: center;
        border-left: thin solid #333;
      }
      .tbl_head {
        font-weight: bold;
        text-align: center;
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
        width: 100%;
        text-align: right;
      }
      .mew_logo {
        width: 40px;
      }
      .first_line {
        font-weight: bold;
        font: 16pt "Abdo";
      }
      .second_line {
        font-weight: 400;
        font: 14pt "Abdo";
      }
      .third_line {
        font: 13pt;
      }
      .fourth_line {
        font-weight: 400;
        font: 12pt "Abdo";
      }
      .fifth_line {
        font: 12pt "Abdo";
        font-weight: 400;
      }
      .kuwait_logo {
        width: 130px;
        height: auto;
      }
      .flag_logo {
        width: 90px;
        height: auto;
      }
      @page {
        size: landscape;
        margin: 30px 0px 0px 0px;
      }
      @media print {
        .footer_tr {
          border: none;
        }
        .tbl_head {
          font-family: "calibri";
          font-size: 10pt;
          font-weight: bold;
          text-align: center;
        }
        .tbl_row {
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
        .first_line {
          font-weight: bold;
          font: 14pt "Abdo";
        }
        .second_line {
          font-weight: bold;
          font: 12pt "Abdo";
        }
        .third_line {
          font: 11pt;
        }
        .fourth_line {
          font-weight: bold;
          font: 10pt "Abdo";
        }
        .fifth_line {
          font: 10pt "Abdo";
          font-weight: 400;
        }
        .mew_logo {
          width: 30px;
        }
        .kuwait_logo {
          width: 98px;
          height: auto;
        }
        .flag_logo {
          width: 68px;
          height: auto;
        }
      }
      .cover_table th td {
        text-align: right;
        border-right: thin solid #333;
      }
      .cover_table tr {
        border: thin solid #333;
      }
      .sign_footer {
        font-weight: bold;
        font: 14pt "Abdo";
      }
    </style>
  </head>
  <body>
    <div class="book">
      <div class="page">
        <table style="width: 100%;" id="coverlist">
          <thead>
            <tr>
              <th colspan="7" style="text-align: center;">
                <span class="fifth_line">
                  كشف بيان أسماء العمالة الموردة من شركة الغانم انترناشيونال للتجارة العامة والمقاولات - عن شهر <?php echo $month_year_ar; ?>
                  <br />
                  مراقبة الصيانة الميكانيكية
                  <?php
                    if(($sec_id != null) && ($sec_id != "all")) {
                      echo "( ";
                      echo $this->sec_model->getSecById($sec_id)->ar_name;
                      if(($sub_sec_id != null) && ($sub_sec_id != "all")) {
                        echo " - " . $this->sec_model->getSubSecById($sub_sec_id)->ar_name;
                      }
                      echo " )";
                    }
                  ?>
                </span>
                <hr />
              </th>
            </tr>
            <tr style="border-bottom: thin solid #333;">
              <th class="tbl_head" style="width: 5%;">S.N</th>
              <th class="tbl_head" style="width: 15%;">Name</th>
              <th class="tbl_head" style="width: 15%;">Civil ID</th>
              <th class="tbl_head" style="width: 20%;">Craft</th>
              <th class="tbl_head" style="width: 15%;">Normal OT</th>
              <th class="tbl_head" style="width: 15%;">Friday OT</th>
              <th class="tbl_head" style="width: 15%;">Holiday OT</th>
              <th class="tbl_head" style="width: 15%;">File No</th>
            </tr>
          </thead>
          <tbody>
            <?php
              if (($emps != null) && (count($emps) >= 1)) {
                $i = 1;
                if ($result) {
                  foreach ($result as $row) {
                    // Fetch employee data
                    $emp = $this->emp_model->getById($row->emp_id);

                    // Handle 'normal_ot', 'friday_ot', and 'holiday_ot'
                    $not_arr = (!empty($row->normal_ot) && strpos($row->normal_ot, ':') !== false) ? explode(":", $row->normal_ot) : [0, 0];
                    $fot_arr = (!empty($row->friday_ot) && strpos($row->friday_ot, ':') !== false) ? explode(":", $row->friday_ot) : [0, 0];
                    $hot_arr = (!empty($row->holiday_ot) && strpos($row->holiday_ot, ':') !== false) ? explode(":", $row->holiday_ot) : [0, 0];
            ?>
            <tr style="border-bottom: thin solid #333;">
              <td class="tbl_row">
                <?php echo $i; ?>
                <input type="hidden" name="<?php echo $i; ?>" value="<?php echo $emp->id; ?>" />
              </td>
              <td class="tbl_row" style="text-align: left; padding: 5px;">
                <?php echo ucwords(strtolower($emp->en_name)); ?>
              </td>
              <td class="tbl_row"><?php echo $emp->civil_id; ?></td>
              <td class="tbl_row"><?php echo ucwords(strtolower($this->design_model->getNameById($emp->design_id))); ?></td>
              <td class="tbl_row"><?php if($not_arr[0] >= 1) { if($not_arr[1] >= 30) { $not_arr[0]++; } echo $not_arr[0]; } ?></td>
              <td class="tbl_row"><?php if($fot_arr[0] >= 1) { if($fot_arr[1] >= 30) { $fot_arr[0]++; } echo $fot_arr[0]; } ?></td>
              <td class="tbl_row"><?php if($hot_arr[0] >= 1) { if($hot_arr[1] >= 30) { $hot_arr[0]++; } echo $hot_arr[0]; } ?></td>
              <td class="tbl_row"><?php echo $emp->file_no; ?></td>
            </tr>
            <?php
                    $i++;
                  }
                }
              } else {
            ?>
            <tr>
              <th colspan="7" style="text-align: center; font-size: 18pt;">No Employee in this section</th>
            </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr><td colspan="7"></td></tr>
          </tfoot>
        </table>
      </div>
    </div>
  </body>
</html>
