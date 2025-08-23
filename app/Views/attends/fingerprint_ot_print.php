<?php
use App\Libraries\DateUtils;
use App\Libraries\MyFuns;
?>
<?= $this->extend('layouts/main_print'); ?>

<?= $this->section('title'); ?>
    <?= $pageTitle ?? 'Printing Overtime Report'; ?>
<?= $this->endSection(); ?>

<?= $this->section('extra_css'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/fingerPrint_styles.css'); ?>">
<div class="book">
    <?php if (!empty($finger_print)): ?>
        <?php $i = 1; ?>
        <?php foreach ($finger_print as $row): // $row is an employee object with fingerPrint (daily records) and attend (monthly totals) ?>
            <?php
            // Extract monthly aggregated OT values for cleaner display logic
            $monthly_normal_ot = $row->attend['normal_ot'] ?? '00:00';
            $monthly_friday_ot = $row->attend['friday_ot'] ?? '00:00';
            $monthly_holiday_ot = $row->attend['holiday_ot'] ?? '00:00';

            // Check if this employee has any aggregated overtime for the month
            $found_ot_monthly = (
                (isset($monthly_normal_ot) && DateUtils::hmsToSeconds($monthly_normal_ot) > 0) ||
                (isset($monthly_friday_ot) && DateUtils::hmsToSeconds($monthly_friday_ot) > 0) ||
                (isset($monthly_holiday_ot) && DateUtils::hmsToSeconds($monthly_holiday_ot) > 0)
            );

            // Only render employee's daily OT table if they have any monthly aggregated OT
            if ($found_ot_monthly):
            ?>
                <div class="page"> <!-- Keep .page for individual A4 page per employee -->
                    <!-- Header information section -->
                    <div class="header-info">
                        M/S. AL-GHANIM INTERNATIONAL GENERAL TRADING CONTRACTING CO. <br />
                        (MEW / MC / 6062 / 2024 - 2025) <br />
                        <strong>
                            <?php
                            $currentMonthNameEnglish = $myFuns->getMonthName(date('n', strtotime("1 {$month} {$year}")), 'en');
                            echo esc($currentMonthNameEnglish . ' ' . $year);
                            ?>
                        </strong> &nbsp; <span class="badge badge-primary">Overtime Report</span>
                    </div>

                    <!-- Employee Details Table -->
                    <table class="employee-details-table">
                        <tr>
                            <td style="padding: 3px 5px; font-size: 12pt;">File No:</td>
                            <td style="padding: 3px 5px; font-size: 12pt;"><?= esc($row->file_no ?? 'N/A'); ?> - <?= esc($row->civil_id ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 5px; font-size: 12pt;">Name:</td>
                            <td style="padding: 3px 5px; font-size: 12pt;"><?= esc($row->name_english ?? $row->name_arabic ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 5px; font-size: 12pt;">Designation:</td>
                            <td style="padding: 3px 5px; font-size: 12pt;"><?= esc($row->design_name ?? 'N/A'); ?></td>
                        </tr>
                    </table>

                    <!-- Daily Overtime Table -->
                    <table class="fp_table table table-striped">
                        <thead>
                            <tr>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; text-align: center;">Day</th>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; text-align: center;">Date</th>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; text-align: center;">Clock In</th>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; text-align: center;">Clock Out</th>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; text-align: center;">N OT</th>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; text-align: center;">Fri. OT</th>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; text-align: center;">Holiday OT</th>
                                <th style="padding: 4px 8px; border: 1px solid #ddd; text-align: center;">OT Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through the daily fingerprint records which now include calculated OT
                            if (isset($row->fingerPrint) && is_array($row->fingerPrint)):
                                foreach ($row->fingerPrint as $fp): // $fp now has daily_normal_ot, daily_friday_ot, daily_holiday_ot, found_ot_daily, ot_type
                                    // Only display rows where actual daily OT was found
                                    if ($fp->found_ot_daily):
                                ?>
                                        <tr>
                                            <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt; text-align: center;"><?= esc($fp->week ?? 'N/A'); ?></td>
                                            <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt; text-align: center;"><?= esc($myFuns->decorateDate($fp->date ?? '')); ?></td>
                                            <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt; text-align: center;">
                                                <?php if (isset($fp->clock_in) && $myFuns->decorateTime($fp->clock_in) != "00:00"): ?>
                                                    <?= esc($myFuns->decorateTime($fp->clock_in)); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt; text-align: center;">
                                                <?php if (isset($fp->clock_out) && $myFuns->decorateTime($fp->clock_out) != "00:00"): ?>
                                                    <?= esc($myFuns->decorateTime($fp->clock_out)); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt; text-align: center;">
                                                <?= ($fp->daily_normal_ot === "00:00:00") ? '-' : esc(substr($fp->daily_normal_ot, 0, 5)); ?>
                                            </td>
                                            <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt; text-align: center;">
                                                <?= ($fp->daily_friday_ot === "00:00:00") ? '-' : esc(substr($fp->daily_friday_ot, 0, 5)); ?>
                                            </td>
                                            <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt; text-align: center;">
                                                <?= ($fp->daily_holiday_ot === "00:00:00") ? '-' : esc(substr($fp->daily_holiday_ot, 0, 5)); ?>
                                            </td>
                                            <td style="padding: 3px; border: 1px solid #ddd; font-size: 11pt; text-align: center;"><?= esc($fp->ot_type ?? ''); ?></td>
                                        </tr>
                                    <?php endif; // End if found_ot_daily ?>
                                <?php endforeach; // End foreach for daily records ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; color: #777;">No daily overtime records for this employee in this month.</td>
                                </tr>
                            <?php endif; // End if $row->fingerPrint is set ?>
                        </tbody>
                        <!-- Add a row for monthly totals at the end of each employee's daily report -->
                        <tfoot>
                            <tr>
                                <td colspan="4" style="padding: 4px 8px; border: 1px solid #ddd; text-align: right; font-weight: bold; border-top: 2px solid #ddd;">Monthly Aggregated OT Total:</td>
                                <td style="padding: 4px 8px; border: 1px solid #ddd; text-align: center; font-weight: bold; border-top: 2px solid #ddd;">
                                    <?= ($monthly_normal_ot === "00:00" || empty($monthly_normal_ot)) ? '-' : esc(substr($monthly_normal_ot, 0, 5)); ?>
                                </td>
                                <td style="padding: 4px 8px; border: 1px solid #ddd; text-align: center; font-weight: bold; border-top: 2px solid #ddd;">
                                    <?= ($monthly_friday_ot === "00:00" || empty($monthly_friday_ot)) ? '-' : esc(substr($monthly_friday_ot, 0, 5)); ?>
                                </td>
                                <td style="padding: 4px 8px; border: 1px solid #ddd; text-align: center; font-weight: bold; border-top: 2px solid #ddd;">
                                    <?= ($monthly_holiday_ot === "00:00" || empty($monthly_holiday_ot)) ? '-' : esc(substr($monthly_holiday_ot, 0, 5)); ?>
                                </td>
                                <td style="padding: 4px 8px; border: 1px solid #ddd; text-align: center; border-top: 2px solid #ddd;"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php $i++; ?>
            <?php endif; // End if found_ot_monthly ?>
        <?php endforeach; // End foreach for employees ?>
    <?php else: ?>
        <div style="font-size: 16pt; color: #000; text-align: center; width: 100%; padding: 5%;">
            <?= esc($error ?? 'No Employee with Overtime in this section'); ?>
        </div>
    <?php endif; ?>

    <!-- Back and Print Buttons outside individual employee pages -->
    <?php if(!empty($finger_print)): ?>
    <div class="hide_print mt-3 text-center">
        <!-- Ensure $query is passed from the controller, using null coalesce for safety -->
        <a href="<?= site_url('attendance/monthlyCoverList_form?' . ($query ?? '')) ?>" class="btn btn-secondary me-2">
            رجــوع
        </a>
        <button type="button" class="btn btn-success" onclick="window.print()">
            طبــاعة
        </button>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection(); ?>
