<?= $this->extend('layouts/main_print'); ?>

<?= $this->section('title'); ?>
    <?= esc($pageTitle ?? 'Fingerprint Report for Employee'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/fingerPrint_styles.css'); ?>">
<div class="book">
    <?php if (!empty($finger_print) && is_array($finger_print) && count($finger_print) > 0): ?>
        <?php
            // These variables are common for the entire report, assuming employee details are consistent
            // $emp_data is assumed to be passed directly from the controller as a top-level variable.
            // If not available, it will default to an empty object.
            $emp_data = $emp_data ?? (object)[];

            // Ensure common variables are defined from the controller or set defaults
            $company_name = $company_name ?? 'M/S. AL-GHANIM INTERNATIONAL GENERAL TRADING CONTRACTING CO.';
            $document_reference = $document_reference ?? 'MEW / MC / 6062 / 2024 - 2025';
            $from_month_overall = $from_month ?? 'N/A'; // Overall period start month
            $from_year_overall = $from_year ?? 'N/A';   // Overall period start year
            $to_month_overall = $to_month ?? 'N/A';     // Overall period end month
            $to_year_overall = $to_year ?? 'N/A';       // Overall period end year
        ?>

        <?php foreach ($finger_print as $month_report_data): ?>
            <div class="page">
                <div class="header-info" style="font-size: 10pt; line-height: 1.2; margin-bottom: 10px;">
                    M/S. AL-GHANIM INTERNATIONAL GENERAL TRADING CONTRACTING CO.
                    (<?= esc($document_reference); ?>) <br />
                    <strong style="font-size: 12pt;">Fingerprint Report For Employee</strong>
                    <?= esc($myFuns->getMonthName($from_month_overall, 'en') . ' - ' . $from_year_overall); ?> to <?= esc($myFuns->getMonthName($to_month_overall, 'en') . ' - ' . $to_year_overall); ?>
                </div>

                <table class="employee-details-table" style="margin-bottom: 5px;">
                    <tr>
                        <td style="padding: 3px 5px; font-size: 9pt;">File No:</td>
                        <td style="padding: 3px 5px; font-size: 9pt;"><?= esc($emp_data->file_no ?? 'N/A'); ?> - <?= esc($emp_data->civil_id ?? 'N/A'); ?></td>
                        <td style="padding: 3px 5px; font-size: 9pt;">Name:</td>
                        <td style="padding: 3px 5px; font-size: 9pt;"><?= esc($emp_data->name_english ?? 'N/A'); ?></td>
                        <td style="padding: 3px 5px; font-size: 9pt;">Designation:</td>
                        <td style="padding: 3px 5px; font-size: 9pt;"><?= esc($emp_data->design_name ?? 'N/A'); ?></td>
                    </tr>
                </table>

                <?php
                    $month_name = $month_report_data->month;
                    $year = $month_report_data->year;
                    $summary_details = [
                        'total_working_days' => $month_report_data->calculated_working_days ?? '0',
                        'attendance_days' => $month_report_data->present_days ?? '0',
                        'leaves_count' => $month_report_data->leaves_days ?? '0',
                        'medical_leaves_count' => $month_report_data->meds_days_without_fridays ?? '0',
                        'holidays_count' => $month_report_data->holidays_present ?? '0',
                        'pure_absent_days' => $month_report_data->real_absent_days ?? '0',
                    ];
                    $ot_data = [
                        'normal_ot' => $month_report_data->attend['normal_ot'] ?? '00:00',
                        'friday_ot' => $month_report_data->attend['friday_ot'] ?? '00:00',
                        'holiday_ot' => $month_report_data->attend['holiday_ot'] ?? '00:00',
                    ];
                    $finger_prints_for_month = $month_report_data->fingerPrint ?? [];
                ?>
                <h6 style="text-align: center;">
                    Attendance for <?= esc($myFuns->getMonthName($month_name, 'en')); ?> - <?= esc($year); ?>
                </h6>
                <div class="table-container" style="margin-bottom: 0px;">
                    <table class="fp_table table table-striped" style="width: 100%; border-collapse: collapse; margin-bottom: 0px;">
                        <thead>
                            <tr>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; background-color: #f2f2f2;">Day</th>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; background-color: #f2f2f2;">Date</th>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; background-color: #f2f2f2;">Clock In</th>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; background-color: #f2f2f2;">Clock Out</th>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; background-color: #f2f2f2;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($finger_prints_for_month)): ?>
                                <?php foreach ($finger_prints_for_month as $fp): ?>
                                    <tr>
                                        <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;"><?= esc($fp->week ?? ''); ?></td>
                                        <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;"><?= esc($myFuns->decorateDate($fp->date ?? '')); ?></td>
                                        <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;">
                                            <?php if (isset($fp->clock_in) && $myFuns->decorateTime($fp->clock_in) != "00:00"): ?>
                                                <?= esc($myFuns->decorateTime($fp->clock_in)); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;">
                                            <?php if (isset($fp->clock_out) && $myFuns->decorateTime($fp->clock_out) != "00:00"): ?>
                                                <?= esc($myFuns->decorateTime($fp->clock_out)); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;">
                                            <?php if (isset($fp->absent) && $fp->absent === "TRUE"): ?>
                                                <?= esc($fp->remarks); ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 3px; border: 1px solid #ddd; font-size: 11pt;">No fingerprint data for this month.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="summary-container" style="margin-top: 0px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td style="padding: 3px; border: 1px solid #ddd; font-weight: bold; font-size: 11pt;">Working Days:</td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;"><?= esc($summary_details['total_working_days']); ?></td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-weight: bold; font-size: 11pt;">Medical Leaves:</td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;"><?= esc($summary_details['medical_leaves_count']); ?></td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-weight: bold; font-size: 11pt;">Absent Days:</td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;"><?= esc($summary_details['pure_absent_days']); ?></td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-weight: bold; font-size: 11pt;">Leaves:</td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;"><?= esc($summary_details['leaves_count']); ?></td>
                            </tr>
                            <tr>
                                <td style="padding: 3px; border: 1px solid #ddd; font-weight: bold; font-size: 11pt;">Normal OT:</td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;"><?= esc($ot_data['normal_ot']); ?></td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-weight: bold; font-size: 11pt;">Friday OT:</td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;"><?= esc($ot_data['friday_ot']); ?></td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-weight: bold; font-size: 11pt;">Holiday OT:</td>
                                <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt;"><?= esc($ot_data['holiday_ot']); ?></td>
                                <td style="padding: 3px; border: 1px solid #ddd;" colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> <?php endforeach; ?>

    <?php else: ?>
        <div style="font-size: 16pt; color: #000; text-align: center; width: 100%; padding: 5%;">
            <?= esc($error ?? 'No attendance records found for the selected criteria.'); ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection(); ?>