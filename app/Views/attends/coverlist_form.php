<?= $this->extend('layouts/main_print'); ?>

<?= $this->section('title'); ?>
    <?= $pageTitle ?? 'لوحة التحكم - كشف التغطية'; ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

    <div class="page-container bg-white text-black">

        <table class="w-100 mb-4 header-table" dir="ltr">
            <tr>
                <td class="header-logo-container text-start">
                    <img src="<?= base_url('assets/images/state_of_kuwait.png'); ?>" class="flag-logo" alt="State of Kuwait Flag">
                </td>
                <td class="header-text-center">
                    <img src="<?= base_url('assets/images/mew_logo.png'); ?>" class="mew-logo d-block mx-auto" alt="MEW Logo">
                    <span class="main-header-text bold-text">
                        وزارة الكـهـربـاء والمــاء والطاقة المتجددة
                    </span>
                    <span class="main-header-text fs-4-custom fw-normal">
                        محطة الدوحة الغربية
                    </span>
                    <span class="main-header-text fs-5-custom">
                        و ك م / ع ص / 6062 / 2024 - 2025
                    </span>
                    <span class="main-header-text fs-6-custom fw-normal">
                        أعمال الصيانة السنوية للمعدات الميكانيكية في محطات القوى الكهربائية وتقطير المياه
                    </span>
                </td>
                <td class="header-logo-container text-end">
                    <img src="<?= base_url('assets/images/new_kuwait_logo.png'); ?>" class="kuwait-logo" alt="Kuwait Vision 2035">
                </td>
            </tr>
        </table>

        <!-- The main form starts here -->
        <form action="<?= base_url('attendance/monthlyCoverList_save?'.$query); ?>" method="post">
            <?= csrf_field(); ?>

            <!-- Hidden Inputs -->
            <input type="hidden" name="no_of_rows" value="<?= count($attends ?? []); ?>" />
            <input type="hidden" name="month" value="<?= esc($month ?? ''); ?>" />
            <input type="hidden" name="year" value="<?= esc($year ?? ''); ?>" />
            <input type="hidden" name="sec_id" value="<?= esc($sec_id ?? ''); ?>" />
            <input type="hidden" name="sub_sec_id" value="<?= esc($sub_sec_id ?? ''); ?>" />
            <input type="hidden" name="payroll_category" value="<?= esc($payroll_category ?? 'mmd'); ?>" />
            <input type="hidden" name="db_table" value="<?= esc($db_table ?? ''); ?>" />
            <input type="hidden" name="fp_type" value="<?= esc($fp_type ?? ''); ?>" />
            <input type="hidden" name="query" value="<?= esc($_SERVER['QUERY_STRING'] ?? ''); ?>" />


            <div class="main-attendance-section">
                <p class="section-heading text-center bold-text mb-2">
                    كشف بيان أسماء العمالة الموردة لعمالة شركة الغانم انترناشيونال - عن شهر <?= esc($month_year_ar); ?><br>
                    مراقبة الصيانة الميكانيكية
                    <?php if (!empty($sec_id) && $sec_id !== "all"): ?>
                        ( <?= esc($secModel->find($sec_id)->name_arabic ?? ''); ?>
                        <?php if (!empty($sub_sec_id) && $sub_sec_id !== "all"): ?>
                            - <?= esc($subSecModel->find($sub_sec_id)->name_arabic ?? ''); ?>
                        <?php endif; ?>
                        )
                    <?php endif; ?>
                </p>

                <table class="table table-bordered table-striped text-center main-attendance-table  w-100" dir="ltr">
                    <thead>
                        <tr class="table-header-row english-section">
                            <th class="text-center" style="width: 3%;">No.</th>
                            <th class="text-center" style="width: 10%;">Name</th>
                            <th class="text-center" style="width: 8%;">Civil ID</th>
                            <th class="text-center" style="width: 7%;">Technical Category</th>
                            <!-- Modified Headers for Manual and Calculated Values -->
                            <th class="text-center" style="width: 6%;">Working Days<br>(Manual)</th>
                            <th class="text-center" style="width: 6%;">Working Days<br>(System)</th>
                            <th class="text-center" style="width: 6%;">Medical Days<br>(Manual)</th>
                            <th class="text-center" style="width: 6%;">Medical Days<br>(System)</th>
                            <th class="text-center" style="width: 6%;">Absent Days<br>(Manual)</th>
                            <th class="text-center" style="width: 6%;">Absent Days<br>(System)</th>
                            <th class="text-center" style="width: 6%;">Leave Days<br>(Manual)</th>
                            <th class="text-center" style="width: 6%;">Leave Days<br>(System)</th>
                            <th class="text-center" style="width: 6%;">Leave From</th>
                            <th class="text-center" style="width: 6%;">Leave To</th>
                            <th class="text-center" style="width: 6%;">File No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($attends) && count($attends) >= 1): ?>
                            <?php
                                $i = 1;
                                foreach ($attends as $emp):
                                    $saved_data = $coverlistModel->getDataByEmpMonthYear($emp->id, $month ?? date('m'), $year ?? date('Y'));

                                    // Determine values for input fields (manual or calculated)
                                    $display_wd = $saved_data ? ($saved_data->working_days ?? $emp->attend['calculated_working_days']) : ($emp->attend['calculated_working_days'] ?? 0);
                                    $display_meds = $saved_data ? ($saved_data->med_days ?? $emp->attend['meds_days_without_fridays']) : ($emp->attend['meds_days_without_fridays'] ?? 0);
                                    $display_absent = $saved_data ? ($saved_data->absent_days ?? $emp->attend['absent_no_excusie_days']) : ($emp->attend['absent_no_excusie_days'] ?? 0);
                                    $display_leaves = $saved_data ? ($saved_data->leave_days ?? $emp->attend['leaves_days_without_fridays']) : ($emp->attend['leaves_days_without_fridays'] ?? 0);

                                    $current_month_start_date = date('Y-m-01', strtotime("{$year}-{$month}"));
                                    $current_month_end_date = date('Y-m-t', strtotime("{$year}-{$month}"));
                                    $leaves_list_for_display = $leaveModel->getAllforEmp($emp->id, $current_month_start_date, $current_month_end_date);
                            ?>
                                <tr class="data-row">
                                    <td class="text-center">
                                        <?= esc($i); ?>
                                        <input type="hidden" name="emp_id_<?= esc($i); ?>" value="<?= esc($emp->id); ?>" />
                                    </td>
                                    <td class="text-end pe-2">
                                        <?= esc(ucwords(strtolower($emp->name_english ?? $emp->name_arabic ?? ''))); ?>
                                    </td>
                                    <td class="text-center">
                                        <?= esc($emp->civil_id); ?>
                                    </td>
                                    <td class="text-center">
                                        <?= esc(ucwords(strtolower($emp->design_name ?? ''))); ?>
                                    </td>
                                    <!-- Working Days Manual Input - Changed to type="text" -->
                                    <td class="text-center">
                                        <input type="text" name="manual_wd_<?= esc($i); ?>" value="<?= esc($display_wd); ?>" class="form-control form-control-sm text-center" />
                                    </td>
                                    <!-- Working Days Program Calculated - Added "Full Month" logic -->
                                    <td class="text-center">
                                        <?php
                                        echo $emp->attend['calculated_working_days'];
                                            // $calculated_wd = $emp->attend['calculated_working_days'] ?? 0;
                                            // if ($calculated_wd == 26) {
                                            //     echo 'Full Month'; // "Full Month" in Arabic
                                            // } else {
                                            //     echo esc($calculated_wd);
                                            // }
                                        ?>
                                    </td>
                                    <!-- Medical Days Manual Input - Changed to type="text" -->
                                    <td class="text-center">
                                        <input type="text" name="manual_meds_<?= esc($i); ?>" value="<?= esc($display_meds); ?>" class="form-control form-control-sm text-center" />
                                    </td>
                                    <!-- Medical Days Program Calculated -->
                                    <td class="text-center">
                                        <?= esc($emp->attend['meds_days_without_fridays'] ?? 0); ?>
                                    </td>
                                    <!-- Absent Days Manual Input - Changed to type="text" -->
                                    <td class="text-center">
                                        <input type="text" name="manual_absent_<?= esc($i); ?>" value="<?= esc($display_absent); ?>" class="form-control form-control-sm text-center" />
                                    </td>
                                    <!-- Absent Days Program Calculated -->
                                    <td class="text-center">
                                        <?= esc($emp->attend['absent_no_excusie_days'] ?? 0); ?>
                                    </td>
                                    <!-- Leave Days Manual Input - Changed to type="text" -->
                                    <td class="text-center">
                                        <input type="text" name="manual_leaves_<?= esc($i); ?>"
                                            value="<?php
                                                if ($display_leaves >= 26) {
                                                    echo 'Full Leave';
                                                } else {
                                                    echo esc($display_leaves);
                                                }
                                            ?>"
                                            class="form-control form-control-sm text-center" />
                                    </td>
                                    <!-- Leave Days Program Calculated -->
                                    <td class="text-center">
                                        <?php
                                            $calculated_leaves = $emp->attend['leaves_days_without_fridays'] ?? 0;

                                            if ($calculated_leaves >= 26) {
                                                echo 'Full Leave';
                                            } else {
                                                echo esc($calculated_leaves);
                                            }
                                        ?>
                                    </td>
                                    <!-- Leave From -->
                                    <td class="text-center">
                                        <?php if (!empty($emp->attend['has_leave']) && $emp->attend['has_leave']): ?>
                                            <?php
                                            // Get the timestamp for the first day of the current report month
                                            $first_day_of_month_ts = strtotime(date('Y-m-01', strtotime($emp->attend['month'])));
                                            ?>
                                            <?php foreach ($emp->attend['leaves_days_list'] as $leave): ?>
                                                <?php
                                                $leave_begin_ts = strtotime($leave->begin);
                                                // If the leave starts before the first day of the month, display the first day of the month
                                                $display_begin_date_ts = ($leave_begin_ts < $first_day_of_month_ts) ? $first_day_of_month_ts : $leave_begin_ts;
                                                ?>
                                                <?= esc(date('d-m-Y', $display_begin_date_ts)); ?>
                                                <?php if (count($leaves_list_for_display) > 1): ?><br /><?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </td>
                                    <!-- Leave To -->
                                    <td class="text-center">
                                        <?php if (!empty($emp->attend['has_leave']) && $emp->attend['has_leave']): ?>
                                            <?php
                                            $last_day_of_month_ts = strtotime(date('Y-m-t', strtotime($emp->attend['month'])));
                                            ?>
                                            <?php foreach ($emp->attend['leaves_days_list'] as $leave): ?>
                                                <?php
                                                $leave_end_ts = strtotime($leave->end);
                                                $display_end_date_ts = ($leave_end_ts > $last_day_of_month_ts) ? $last_day_of_month_ts : $leave_end_ts;
                                                ?>
                                                <?= esc(date('d-m-Y', $display_end_date_ts)); ?>
                                                <?php if (count($leaves_list_for_display) > 1): ?><br /><?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= esc($emp->file_no); ?>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <th colspan="15" class="text-center text-danger fs-5 py-4">
                                    No Employee in this section
                                </th>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if(!empty($attends)): ?>
                    <tfoot>
                        <tr>
                            <td colspan="15" class="py-3 text-center">
                                <button type="submit" class="btn btn-primary">
                                    حفــــظ
                                </button>
                                <!-- Reset button if needed here -->
                                <!-- <button type="reset" class="btn btn-warning me-2">إعادة تعيين</button> -->
                            </td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </form>
    </div>
<?= $this->endSection(); ?>
