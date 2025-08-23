<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?><?= $pageTitle; ?><?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<style>
    /* Initially hide the mobile view */
    #srchRes_mobile {
        display: none;
    }

    /* On smaller screens (max-width: 767px), hide the desktop table and show the mobile list */
    @media (max-width: 767px) {
        #srchRes_desktop {
            display: none;
        }
        #srchRes_mobile {
            display: block;
        }
    }

    /* On larger screens (min-width: 768px), hide the mobile list and show the desktop table */
    @media (min-width: 768px) {
        #srchRes_desktop {
            display: table; /* Or your preferred table display value */
        }
        #srchRes_mobile {
            display: none;
        }
    }
</style>

<div class="container-fluid pt-6">

    <form action="<?= base_url('employees/searching'); ?>" method="get">

        <div id="repSelects" class="row no-print">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="permanent">نوع التوظيف</label>
                    <select class="form-control" id="permanent" name="permanent">
                        <option value="all" <?= ($filters['permanent'] ?? '' == 'all') ? "selected" : ""; ?>>الكل</option>
                        <option value="0" <?= ($filters['permanent'] ?? '' == '0') ? "selected" : ""; ?>>مؤقت</option>
                        <option value="1" <?= ($filters['permanent'] ?? '' == '1') ? "selected" : ""; ?>>دائم</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="sec_id">المراقبة</label>
                    <select class="form-control" id="sec_id" name="sec_id">
                        <option value="">غير محدد</option>
                        <?php if (session('type') == "admin"): ?>
                            <?php foreach($sections as $section): ?>
                                <option value="<?= $section->id; ?>" <?= ($section->id == ($filters['sec_id'] ?? null)) ? 'selected' : ''; ?>>
                                    <?= $section->name_arabic; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="sub_sec_id">القسم</label>
                    <select class="form-control" id="sub_sec_id" name="sub_sec_id">
                        <option value="">غير محدد</option>
                        <?php if (session('type') == "depart"): ?>
                            <?php
                                $sub_secs = $subSecModel->getAllSubSec(session('sec_sub'));
                                foreach ($sub_secs as $sub_section):
                            ?>
                                <option value="<?= $sub_section->id; ?>" <?= ($sub_section->id == ($filters['sub_sec_id'] ?? null)) ? 'selected' : ''; ?>>
                                    <?= $sub_section->name_arabic; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="edu_cert">المؤهل العلمي</label>
                    <input type="text" class="form-control" id="edu_cert" name="edu_cert" placeholder="غير محدد" value="<?= esc($filters['edu_cert'] ?? ''); ?>">
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="join_date_from">تاريخ التعيين من</label>
                    <input type="date" class="form-control" id="join_date_from" name="join_date_from" value="<?= esc($filters['join_date_from'] ?? ''); ?>">
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="join_date_to">تاريخ التعيين إلى</label>
                    <input type="date" class="form-control" id="join_date_to" name="join_date_to" value="<?= esc($filters['join_date_to'] ?? ''); ?>">
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="active">بالخدمة</label>
                    <select class="form-control" id="active" name="active">
                        <!-- Use loose comparison (==) for values coming from URL query strings -->
                        <option value="all" <?= ((isset($filters['active']) && $filters['active'] == 'all') || !isset($filters['active']) || $filters['active'] === '') ? 'selected' : ''; ?>>الكـــل</option>
                        <option value="1" <?= (isset($filters['active']) && $filters['active'] == '1') ? "selected" : ""; ?>>في الخدمة</option>
                        <option value="0" <?= (isset($filters['active']) && $filters['active'] == '0') ? "selected" : ""; ?>>خارج الخدمة</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="nation">الجنسية</label>
                    <input type="text" class="form-control" id="nation" name="nation" value="<?= esc($filters['nation'] ?? ''); ?>" placeholder="الجنسية">
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="has_overtime">الإضــافي</label>
                    <select class="form-control" id="has_overtime" name="has_overtime">
                        <option value="" <?= ($filters['has_overtime'] ?? '' === '') ? 'selected' : ''; ?>>الكل</option>
                        <option value="1" <?= ($filters['has_overtime'] === "1") ? "selected" : ""; ?>>مسمـــوح</option>
                        <option value="0" <?= ($filters['has_overtime'] === "0") ? "selected" : ""; ?>>غير مسمـــوح</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="design_id">الوظيفة</label>
                    <select class="form-control en" id="design_id" name="design_id">
                        <option value="">غير محدد</option>
                        <?php foreach($designations as $designation): ?>
                            <option value="<?= $designation->id; ?>" <?= (($filters['design_id'] ?? null) == $designation->id) ? "selected" : ""; ?>>
                                <?= $designation->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="view">طريقة عرض النتائج</label>
                    <select class="form-control" id="view" name="view">
                        <option value="list" <?= (($filters['view'] ?? 'list') === "list") ? 'selected' : ''; ?>>كشف</option>
                        <option value="grid" <?= (($filters['view'] ?? '') === "grid") ? 'selected' : ''; ?>>كروت</option>
                    </select>
                </div>
            </div>

            <div class="col no-print">
                <div class="row align-items-center mt-3">
                    <div class="col-auto mb-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-search"></i> بحــــث
                        </button>
                    </div>

                    <div class="col-auto mb-2">
                        <?php $query = $_SERVER['QUERY_STRING']; ?>
                        <a href="<?= base_url('printing/empsSearch?' . $query); ?>" target="_blank" class="btn btn-primary">
                            <i class="fa fa-print"></i> طبــــاعة
                        </a>
                    </div>

                    <div class="col-auto mb-2">
                        <a href="<?= base_url('employees/exportToExcel?' . http_build_query($filters)); ?>" class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i> تحميل Excel
                        </a>
                    </div>

                    
                </div>
            </div>

        </div>
    </form>
    <div class="row">
        <div class="col" style="text-align: right; font-size: 12pt; color: white;">
            عدد نتائج البحث : <?= count($search_results); ?>
        </div>
    </div>

    <?php if (($filters['view'] ?? 'list') == null || ($filters['view'] ?? 'list') == 'list'): ?>

    <div class="row-clear"></div>

    <div class="col-12" id="srchRes_mobile">

        <?php foreach ($search_results as $employee): ?>

        <div style="background: #eee; margin: 10px 0px; border-radius: 5px; text-align: right; padding: 10px;">
            <a href="<?= base_url('employees/show/' . $employee->id); ?>" class="a_" style="color: black;">
                <?= ucwords(strtolower($employee->name_english)); ?>
                <br />
                <?= $employee->design_name; ?>
                <br />
                الرقم المدني :
                <?= $employee->civil_id; ?>
                <br />
                رقم الملف :
                <?= $employee->file_no; ?>
                <br />
                <?= ($employee->sec_name_arabic ?? ''); ?>
                <br />
                <?= ($employee->sub_sec_name_arabic ?? ''); ?>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <table class="table table-striped table-hover display" id="srchRes_desktop" style="direction: rtl;">
        <thead>
            <tr>
                <th class="tbl_row" style="width: 5%;">#</th>
                <th class="tbl_row" style="width: 10%;">رقم الملف</th>
                <th class="tbl_row" style="width: 15%;">الاسم</th>
                <th class="tbl_row" style="width: 15%;">الرقم المدني</th>
                <th class="tbl_row" style="width: 15%;">المسمى</th>
                <th class="tbl_row" style="width: 10%;">الراتب</th>
                <th class="tbl_row" style="width: 10%;">المراقبة</th>
                <th class="tbl_row" style="width: 10%;">القسم</th>
                <th class="tbl_row" style="width: 20%;">ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($search_results as $employee): ?>
                <tr>
                    <td class="tbl_row"><?= $i++; ?></td>
                    <td class="tbl_row"><?= $employee->file_no; ?></td>
                    <td class="tbl_row en">
                        <a href="<?= base_url('employees/show/' . $employee->id); ?>" target="_new" style="text-decoration: none;">
                            <?= ucwords(strtolower($employee->name_english)); ?>
                        </a>
                    </td>
                    <td class="tbl_row"><?= $employee->civil_id; ?></td>
					<td class="tbl_row en"><?= !empty($employee->design_name) ? esc($employee->design_name) : '<span style="color:#888;">غير محدد</span>'; ?></td>
					<td class="tbl_row en">
						<?= isset($employee->total_salary) && $employee->total_salary !== '' ? number_format($employee->total_salary, 3) . ' د.ك' : '<span style="color:#888;">غير محدد</span>'; ?>
					</td>
					<td class="tbl_row">
						<?= !empty($employee->sec_name_arabic) ? esc($employee->sec_name_arabic) : '<span style="color:#888;">غير محدد</span>'; ?>
					</td>
					<td class="tbl_row">
						<?= !empty($employee->sub_sec_name_arabic) ? esc($employee->sub_sec_name_arabic) : '<span style="color:#888;">غير محدد</span>'; ?>
					</td>
                    <td class="tbl_row"><?= $employee->remarks; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>

    <?php if (($filters['view'] ?? '') == 'grid'): ?>
    <div class="row">
        <?php foreach ($search_results as $employee): ?>
        <div class="col-md-3 col-lg-2 mb-4">
            <a href="<?= base_url('employees/show/' . $employee->id); ?>" style="text-decoration:none;">
                <div class="card" style="color: #006699;">

                    <img src="<?= base_url($employee->photo); ?>" class="card-img" alt="<?= $employee->name_english; ?>">

                    <div class="card-body">
                        <h5 class="card-title"><?= $employee->name_english; ?></h5>
                        <p class="card-text">
                            <h6 class="card-subtitle mb-2 text-muted">
                                <?= ($employee->design_name) ? $employee->design_name : ''; ?>
                                <br />
                                <?= ($employee->sec_name_arabic) ? $employee->sec_name_arabic : ''; ?>
                                <?= ($employee->sub_sec_name_arabic) ? "(" . $employee->sub_sec_name_arabic . ") <br />" : ""; ?>
                                <br />
                                <?= $employee->file_no; ?>
                            </h6>
                        </p>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        console.log("Document ready!");

        <?php if (session('type') == "admin" || session('type') == "depart"): ?>
            console.log("User is admin or depart.");

            var selected_sub_sec_id = "<?= $filters['sub_sec_id'] ?? '' ?>"; // Get pre-selected value
            console.log("Initial selected_sub_sec_id:", selected_sub_sec_id);

            function loadSubSections(sec_id_param, initial_sub_sec_id = '') {
                console.log("loadSubSections called with sec_id:", sec_id_param, "and initial_sub_sec_id:", initial_sub_sec_id);

                if (!sec_id_param) {
                    console.log("sec_id_param is empty. Setting default sub-section option.");
                    $('#sub_sec_id').html('<option value="">غير محدد</option>');
                    return;
                }

                $.ajax({
                    url: "<?= base_url('stnSubSec/browseLoadSubSec'); ?>",
                    method: "GET",
                    data: { sec_id: sec_id_param },
                    dataType: "html",
                    beforeSend: function() {
                        console.log("AJAX request started for sec_id:", sec_id_param);
                        $('#sub_sec_id').html('<option>جاري التحميل...</option>');
                    },
                    success: function(data) {
                        console.log("AJAX request successful. Data:", data);
                        $('#sub_sec_id').html(data);

                        if (initial_sub_sec_id) {
                            console.log("Setting selected sub_sec_id:", initial_sub_sec_id);
                            $('#sub_sec_id').val(initial_sub_sec_id);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed. Status:", status, "Error:", error, "Response:", xhr.responseText);
                        $('#sub_sec_id').html('<option value="">تعذر تحميل الأقسام</option>');
                    }
                });
            }

            // Initial load - to populate sub-sections based on potentially pre-selected section
            var initial_sec_id = $('#sec_id').val();
            console.log("Initial sec_id value:", initial_sec_id);
            loadSubSections(initial_sec_id, selected_sub_sec_id);

            // When section changes, reload sub sections
            $('#sec_id').on('change', function() {
                var changed_sec_id = $(this).val();
                console.log("sec_id changed to:", changed_sec_id);
                loadSubSections(changed_sec_id); // Load without initial sub_sec_id on change
            });

        <?php else: ?>
            console.log("User is not admin or depart. Sub-section loading will not be active.");
        <?php endif; ?>
    });
</script>

<?= $this->endSection(); ?>