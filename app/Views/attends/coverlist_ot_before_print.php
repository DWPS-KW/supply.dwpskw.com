<?php
use App\Libraries\DateUtils;
use App\Libraries\MyFuns;
?>
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

            <table class="table table-striped text-center main-attendance-table w-100" dir="ltr">
                <thead>
                    <tr class="table-header-row table-header-custom"> <!-- Added table-header-custom for styling -->
                        <th class="text-center" style="width: 3%;">No.</th>
                        <th class="text-center" style="width: 15%;">Name</th>
                        <th class="text-center" style="width: 10%;">Civil ID</th>
                        <th class="text-center" style="width: 10%;">Category Craft</th>
                        <th class="text-center" style="width: 10%;">Normal OT<br>(System)</th>
                        <th class="text-center" style="width: 10%;">Friday OT<br>(System)</th>
                        <th class="text-center" style="width: 10%;">Holiday OT<br>(System)</th>
                        <th class="text-center" style="width: 8%;">File No.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $has_overtime_employees = false; // Flag to check if any employee with OT is found
                    if (!empty($attends)): ?>
                        <?php
                            $i = 1;
                            foreach ($attends as $emp):
                                // Check if the employee has any overtime (Normal, Friday, or Holiday)
                                $has_normal_ot = isset($emp->attend['normal_ot']) && DateUtils::hmsToSeconds($emp->attend['normal_ot']) > 0;
                                $has_friday_ot = isset($emp->attend['friday_ot']) && DateUtils::hmsToSeconds($emp->attend['friday_ot']) > 0;
                                $has_holiday_ot = isset($emp->attend['holiday_ot']) && DateUtils::hmsToSeconds($emp->attend['holiday_ot']) > 0;

                                // Only display this employee if they have any type of overtime
                                if ($has_normal_ot || $has_friday_ot || $has_holiday_ot):
                                    $has_overtime_employees = true; // Set flag to true
                        ?>
                                <tr class="data-row">
                                    <td class="text-center">
                                        <?= esc($i); ?>
                                        <input type="hidden" name="emp_id_<?= esc($i); ?>" value="<?= esc($emp->id); ?>" /> <!-- Using emp_id_$i for consistency -->
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
                                    <!-- Normal OT System Calculated -->
                                    <td class="text-center">
                                        <?= (!empty($emp->attend['normal_ot']) && $emp->attend['normal_ot'] !== "00:00") ? esc($emp->attend['normal_ot']) : ""; ?>
                                    </td>
                                    <!-- Friday OT System Calculated -->
                                    <td class="text-center">
                                        <?= (!empty($emp->attend['friday_ot']) && $emp->attend['friday_ot'] !== "00:00") ? esc($emp->attend['friday_ot']) : ""; ?>
                                    </td>
                                    <!-- Holiday OT System Calculated -->
                                    <td class="text-center">
                                        <?= (!empty($emp->attend['holiday_ot']) && $emp->attend['holiday_ot'] !== "00:00") ? esc($emp->attend['holiday_ot']) : ""; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= esc($emp->file_no); ?>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                        <?php 
                                endif; // End if ($has_normal_ot || $has_friday_ot || $has_holiday_ot)
                            endforeach; // End foreach ($attends as $emp)
                        ?>
                    <?php endif; ?>

                    <?php if (!$has_overtime_employees): // Display "No Employee" message only if no one with overtime was found ?>
                        <tr>
                            <th colspan="8" class="text-center text-danger fs-5 py-4">
                                No Employee with Overtime in this section
                            </th>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if($has_overtime_employees): // Only show footer if there are employees with OT ?>
                <tfoot>
                    <tr>
                        <td colspan="8" class="pt-4" style="border: none;">
                            <table class="w-100 signature-table" dir="rtl">
                                <tr>
                                    <td class="signature-line text-center">
                                        <?php if(($sec_id != "0") && ($sec_id != null) && ($sec_id != "all")): ?>
                                            <?= esc("توقيع المسئول"); ?>
                                        <?php else: ?>
                                            مراقب الصيانة الميكانيكية
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-bottom fs-5">
                                        <span class="section-heading fw-normal">
                                            كشف بيان أسماء العمالة الموردة من شركة الغانم انترناشونال للتجارة العامة والمقاولات - عن شهر <?= esc($month_year_ar) ; ?>
                                        </span>
                                    </td>
                                    <td class="signature-line text-center">
                                        <?php if(($sec_id != "0") && ($sec_id != null) && ($sec_id != "all")): ?>
                                            <?php // This section was empty in original, keeping it that way ?>
                                        <?php else: ?>
                                            مدير محطة الدوحة الغربية
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="8" class="py-3 text-center" style="border: none;">
                            <div class="hide_print mt-3 text-center">
                                <button type="button" class="btn btn-success" onclick="window.print()">
                                    طبــاعة
                                </button>
                                <button class="btn btn-success me-2" id="exportExcel">
                                    <i class="fa fa-file-excel"></i> Export to Excel
                                </button>
                            </div>         
                        </td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>

        </div>

    </div>
    <script>
        // Make sure DateUtils.hmsToSeconds is available if needed in JS, though it's a PHP function.
        // For client-side logic, you'd need a JS equivalent if performing client-side filtering.
        // Assuming the filtering is handled by PHP now.
        document.addEventListener('DOMContentLoaded', function() {
            const currentQueryParams = new URLSearchParams(window.location.search);

            document.getElementById('exportExcel').addEventListener('click', function() {
                // Using a custom modal/message box instead of alert()
                const messageBox = document.createElement('div');
                messageBox.style.cssText = 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; z-index: 1000;';
                messageBox.textContent = "Generating Excel file. Please wait...";
                document.body.appendChild(messageBox);

                // Redirect after a short delay to allow the message to be seen
                setTimeout(() => {
                    let excelUrl = '<?= base_url('attendance/exportToExcel'); ?>'; 
                    excelUrl += '?' + currentQueryParams.toString();
                    window.location.href = excelUrl;
                    messageBox.remove(); // Remove message box after redirect
                }, 500); // 0.5 second delay
            });
        });
    </script>

<?= $this->endSection(); ?>
