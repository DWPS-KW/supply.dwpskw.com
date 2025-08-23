<?= $this->extend('layouts/main_print'); ?>

<?= $this->section('title'); ?>
    <?= $pageTitle ?? 'لوحة التحكم - الحضور'; ?>
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
                    <img src="<?= base_url('assets/images/new_kuwait_logo.png'); ?>" class="kuwait-logo" alt="New Kuwait Logo">
                </td>
            </tr>
        </table>

        <table class="table table-striped main-attendance-table text-black" dir="ltr">
            <thead class="table-header-group">
                <tr>
                    <th colspan="11" class="text-center pb-2 arabic-section">
                        <span class="section-heading fw-normal">
                            كشف بيان أسماء العمالة الموردة من شركة الغانم انترناشونال للتجارة العامة والمقاولات - عن شهر <?= esc($month_year_ar) ; ?>
                            <br />
                            <?php
                                if(($sec_id != null) && ($sec_id != "all")){
                                    echo "( ";
                                    echo esc($secModel->find($sec_id)->name_arabic ?? '');
                                    if(($sub_sec_id != null) && ($sub_sec_id != "all")){
                                        echo " - " . esc($subSecModel->find($sub_sec_id)->name_arabic ?? '');
                                    }
                                    echo " )";
                                }
                            ?>
                        </span>
                        <hr class="header-separator">
                    </th>
                </tr>
                <tr class="table-header-custom header-lower">
                    <th scope="col" class="table-cell-custom">S.N</th>
                    <th scope="col" class="table-cell-custom">Name</th>
                    <th scope="col" class="table-cell-custom">Civil ID</th>
                    <th scope="col" class="table-cell-custom">Category Craft</th>
                    <th scope="col" class="table-cell-custom">Working Days</th>
                    <th scope="col" class="table-cell-custom">Medical Days</th>
                    <th scope="col" class="table-cell-custom">Absent Days</th>
                    <th scope="col" class="table-cell-custom">Leave Days</th>
                    <th scope="col" class="table-cell-custom">Leave From</th>
                    <th scope="col" class="table-cell-custom">Leave To</th>
                    <th scope="col" class="table-cell-custom">File No.</th>
                </tr>
            </thead>
            <tbody class="tbody" dir="ltr">
                <?php if(($attends != null) && (count($attends) >= 1)): ?>
                <?php
                $i = 1;
                foreach($attends as $emp): ?>
                <tr>
                    <td class="table-cell-custom text-center"><?= esc($i); ?></td>
                    <td class="table-cell-custom table-data-cell-left"><?= esc(ucwords(strtolower($emp->name_english))); ?></td>
                    <td class="table-cell-custom text-center"><?= esc($emp->civil_id); ?></td>
                    <td class="table-cell-custom text-center"><?= esc(ucwords(strtolower($emp->design_name ?? ''))); ?></td>

                    <td class="table-cell-custom text-center">
                        <?php
                        $working_days = $emp->attend['calculated_working_days'] ?? null;
                        if ($working_days === 0 || strtolower($working_days) === 'n/a' || $working_days === '0') {
                            echo '';
                        } else {
                            echo esc($working_days);
                        }
                        ?>
                    </td>

                    <td class="table-cell-custom text-center">
                        <?php
                        $no_of_med = isset($emp->attend['meds_days_without_fridays_list']) ? count($emp->attend['meds_days_without_fridays_list']) : null;
                        if ($no_of_med === 0 || strtolower($no_of_med) === 'n/a' || $no_of_med === '0') {
                            echo '';
                        } else {
                            echo esc($no_of_med);
                        }
                        ?>
                    </td>

                    <td class="table-cell-custom text-center">
                        <?php
                        $no_of_abs = isset($emp->attend['real_absent_days_list']) ? count($emp->attend['real_absent_days_list']) : null;
                        if ($no_of_abs === 0 || strtolower($no_of_abs) === 'n/a' || $no_of_abs === '0') {
                            echo '';
                        } else {
                            echo esc($no_of_abs);
                        }
                        ?>
                    </td>

                    <td class="table-cell-custom text-center">
                        <?php
                        $leaves_days_without_fridays = $emp->attend['leaves_days_without_fridays'] ?? null;
                        if ($leaves_days_without_fridays === 0 || strtolower($leaves_days_without_fridays) === 'n/a' || $leaves_days_without_fridays === '0') {
                            echo '';
                        } else {
                            echo esc($leaves_days_without_fridays);
                        }
                        ?>
                    </td>

                    <td class="table-cell-custom text-center">
                        <?php
                        if (isset($emp->attend['has_leave']) && $emp->attend['has_leave']) {
                            
                            $first_day_of_month_ts = strtotime(date('Y-m-01', strtotime($emp->attend['month'])));

                            foreach ($emp->attend['leaves_days_list'] as $leave) {
                                $leave_begin_ts = strtotime($leave->begin);
                                $display_begin_date_ts = ($leave_begin_ts < $first_day_of_month_ts) ? $first_day_of_month_ts : $leave_begin_ts;
                                echo esc(date('d-m-Y', $display_begin_date_ts)) . '<br>';
                            }
                        } else {
                            echo '';
                        }
                        ?>
                    </td>

                    <td class="table-cell-custom text-center">
                        <?php
                        if (isset($emp->attend['has_leave']) && $emp->attend['has_leave']) {

                            $last_day_of_month_ts = strtotime(date('Y-m-t', strtotime($emp->attend['month'])));

                            foreach ($emp->attend['leaves_days_list'] as $leave) {
                                $leave_end_ts = strtotime($leave->end);
                                $display_end_date_ts = ($leave_end_ts > $last_day_of_month_ts) ? $last_day_of_month_ts : $leave_end_ts;
                                echo esc(date('d-m-Y', $display_end_date_ts)) . '<br>';
                            }
                        } else {
                            echo '';
                        }
                        ?>
                    </td>
                    
                    <td class="table-cell-custom text-center"><?= esc($emp->file_no); ?></td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center fs-3 p-3 table-cell-custom">
                            No Employee in this section
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="table-footer-group" dir="rtl">
                <tr>
                    <td colspan="11" class="pt-4" stye="border: none;">
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
                    <td colspan="11" class="text-center fs-6" stye="border: none;">
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
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentQueryParams = new URLSearchParams(window.location.search);

            document.getElementById('exportExcel').addEventListener('click', function() {
                alert("Generating Excel file. Please wait...");
                let excelUrl = '<?= base_url('attendance/exportToExcel'); ?>'; 
                excelUrl += '?' + currentQueryParams.toString();
                window.location.href = excelUrl;
            });

        });
    </script>

<?= $this->endSection(); ?>