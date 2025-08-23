<?= $this->extend('layouts/main_print'); ?>

<?= $this->section('title'); ?>
    <?= $pageTitle ?? 'لوحة التحكم - كشف التغطية (الاضافي قبل التخزين)'; ?>
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
        <form action="<?= base_url('attendance/monthlyCoverList_ot_save?'.$query); ?>" method="post">
            <?= csrf_field(); ?>

            <!-- Hidden Inputs - Ensure month is passed as numeric for saving -->
            <input type="hidden" name="no_of_rows" value="<?= count($attends ?? []); ?>" />
            <input type="hidden" name="month" value="<?= esc($month ?? ''); ?>" />
            <input type="hidden" name="year" value="<?= esc($year ?? ''); ?>" />
            <input type="hidden" name="sec_id" value="<?= esc($sec_id ?? ''); ?>" />
            <input type="hidden" name="sub_sec_id" value="<?= esc($sub_sec_id ?? ''); ?>" />
            <input type="hidden" name="payroll_category" value="<?= esc($payroll_category ?? 'mmd'); ?>" />
            <input type="hidden" name="db_table" value="<?= esc($db_table ?? ''); ?>" />
            <input type="hidden" name="fp_type" value="<?= esc($fp_type ?? ''); ?>" />
            <input type="hidden" name="query" value="<?= esc($query ?? ''); ?>" />

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

                <table class="table table-bordered table-striped text-center main-attendance-table w-100" dir="ltr">
                    <thead>
                        <tr class="table-header-row table-header-custom"> <!-- Added table-header-custom for styling -->
                            <th class="text-center" style="width: 3%;">No.</th>
                            <th class="text-center" style="width: 15%;">Name</th>
                            <th class="text-center" style="width: 10%;">Civil ID</th>
                            <th class="text-center" style="width: 10%;">Category Craft</th>
                            <th class="text-center" style="width: 10%;">Normal OT<br>(Manual)</th>
                            <th class="text-center" style="width: 10%;">Normal OT<br>(System)</th>
                            <th class="text-center" style="width: 10%;">Friday OT<br>(Manual)</th>
                            <th class="text-center" style="width: 10%;">Friday OT<br>(System)</th>
                            <th class="text-center" style="width: 10%;">Holiday OT<br>(Manual)</th>
                            <th class="text-center" style="width: 10%;">Holiday OT<br>(System)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($attends) && count($attends) >= 1): // Changed $attends to $emps for consistency ?>
                            <?php
                                $i = 1;
                                foreach ($attends as $emp):

                                    $saved_ot_data = $coverlistOtModel->getDataByEmpMonthYear($emp->id, $month, $year);

                                    $display_normal_ot_manual = $saved_ot_data->normal_ot ?? ($emp->attend['normal_ot'] === "00:00" ? '' : $emp->attend['normal_ot']);
                                    $display_friday_ot_manual = $saved_ot_data->friday_ot ?? ($emp->attend['friday_ot'] === "00:00" ? '' : $emp->attend['friday_ot']);
                                    $display_holiday_ot_manual = $saved_ot_data->holiday_ot ?? ($emp->attend['holiday_ot'] === "00:00" ? '' : $emp->attend['holiday_ot']);
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
                                    <!-- Normal OT Manual Input -->
                                    <td class="text-center">
                                        <input type="text" name="manual_not_<?= esc($i); ?>" value="<?= esc($display_normal_ot_manual); ?>" class="form-control form-control-sm text-center" />
                                    </td>
                                    <!-- Normal OT System Calculated -->
                                    <td class="text-center">
                                        <?= esc($emp->attend['normal_ot'] ?? "00:00"); ?>
                                    </td>
                                    <!-- Friday OT Manual Input -->
                                    <td class="text-center">
                                        <input type="text" name="manual_fot_<?= esc($i); ?>" value="<?= esc($display_friday_ot_manual); ?>" class="form-control form-control-sm text-center" />
                                    </td>
                                    <!-- Friday OT System Calculated -->
                                    <td class="text-center">
                                        <?= esc($emp->attend['friday_ot'] ?? "00:00"); ?>
                                    </td>
                                    <!-- Holiday OT Manual Input -->
                                    <td class="text-center">
                                        <input type="text" name="manual_hot_<?= esc($i); ?>" value="<?= esc($display_holiday_ot_manual); ?>" class="form-control form-control-sm text-center" />
                                    </td>
                                    <!-- Holiday OT System Calculated -->
                                    <td class="text-center">
                                        <?= esc($emp->attend['holiday_ot'] ?? "00:00"); ?>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <th colspan="10" class="text-center text-danger fs-5 py-4">
                                    No Employee in this section
                                </th>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if(!empty($attends)): ?>
                    <tfoot>
                        <tr>
                            <td colspan="10" class="py-3 text-center">
                                <button type="submit" class="btn btn-primary">
                                    حفــــظ
                                </button>
                                <!-- Back button - ensure correct query string for monthlyCoverList_form -->
                                <a href="<?= site_url('attendance/monthlyCoverList_form?' . $query) ?>" class="btn btn-secondary ms-2">
                                    رجــوع
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </form>
    </div>
<?= $this->endSection(); ?>
