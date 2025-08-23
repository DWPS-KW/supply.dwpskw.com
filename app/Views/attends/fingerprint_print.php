<?= $this->extend('layouts/main_print'); ?>

<?= $this->section('title'); ?>
    <?= $pageTitle ?? 'Printing Fingerprint Report'; ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/fingerPrint_styles.css'); ?>">
<div class="book">
    <?php if (!empty($finger_print)): ?>
        <?php $i = 1; ?>
        <?php foreach ($finger_print as $row): ?>

            <div class="page">
                <div class="header-info">
                    M/S. AL-GHANIM INTERNATIONAL GENERAL TRADING CONTRACTING CO. <br />
                    (MEW / MC / 6062 / 2024 - 2025) <br />
                    <strong><?= esc($month); ?></strong>
                </div>

                <table class="employee-details-table">
                    <tr>
                        <td style="padding: 3px 5px; font-size: 12pt;">File No:</td>
                        <td style="padding: 3px 5px; font-size: 12pt;"><?= esc($row->file_no ?? 'N/A'); ?> - <?= esc($row->civil_id ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 5px; font-size: 12pt;">Name:</td>
                        <td style="padding: 3px 5px; font-size: 12pt;"><?= esc($row->name_english ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 5px; font-size: 12pt;">Designation:</td>
                        <td style="padding: 3px 5px; font-size: 12pt;"><?= esc($row->design_name ?? 'N/A'); ?></td>
                    </tr>
                </table>

                <hr class="separator" />

                <table class="fp_table table table-striped">
                    <thead>
                        <tr>
                            <th style="padding: 4px 8px; border: 1px solid #ddd;">Day</th>
                            <th style="padding: 4px 8px; border: 1px solid #ddd;">Date</th>
                            <th style="padding: 4px 8px; border: 1px solid #ddd;">Clock In</th>
                            <th style="padding: 4px 8px; border: 1px solid #ddd;">Clock Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($row->fingerPrint as $fp): ?>
                            <?php 
                                // Use the preloaded holidays_by_date array passed from the controller
                                // Ensure $holidays_by_date is available in $data from the controller
                                $isHolidayForThisDate = isset($holidays_by_date[$fp->date]);
                            ?>

                            <?php if (isset($fp->week) && $fp->week !== "Fri" && isset($fp->date) && !$isHolidayForThisDate): ?>
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
                                    </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php $i++; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="font-size: 16pt; color: #000; text-align: center; width: 100%; padding: 5%;">
            <?= esc($error ?? 'No Employee in this section'); ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection(); ?>
