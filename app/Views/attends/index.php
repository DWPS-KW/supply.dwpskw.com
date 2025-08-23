<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?>
    <?= $pageTitle ?? 'لوحة التحكم - الحضور'; ?>
<?= $this->endSection(); ?>

<?= $this->section('head_links'); ?>
    <style>
        label{
            color: #006699; !important;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }
        .sidebar-column .card {
            background-color: #f8f9fa;
            border-left: 5px solid #007bff;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .sidebar-column .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            border-bottom: 1px solid #0056b3;
            border-radius: 0.5rem 0.5rem 0 0;
            padding: 0.75rem 1.25rem;
            text-align: right;
        }
        .sidebar-column .card-body {
            padding: 1.5rem;
        }
        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            text-align: right;
            color: #495057;
        }
        .form-control {
            border-radius: 0.25rem;
            border-color: #ced4da;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        @media (max-width: 767.98px) {
            .sidebar-column {
                margin-bottom: 1.5rem;
            }
        }
        option{
             font-size: 1.25rem;
        }
        /* Custom styles to make forms smaller */
        .form-card .card-body {
            padding: 1rem; /* Reduced padding */
        }
        .form-card .form-group {
            margin-bottom: 0.75rem !important; /* Reduced margin between form groups */
        }
        .form-card .form-control {
            font-size: 0.875rem; /* Slightly smaller font size for inputs */
            padding: 0.5rem 0.75rem; /* Smaller padding for inputs */
        }
        .form-card .btn {
            padding: 0.5rem 1rem; /* Smaller padding for button */
            font-size: 0.9rem; /* Slightly smaller font size for button */
        }
        .label_style{
            border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;
        }
    </style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container-fluid mt-4 pt-6">
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 main-content-column">
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">نظرة عامة على لوحة التحكم</h5>
                </div>
                <div class="card-body">
                    <p class="lead">
                        مرحباً بك في لوحة التحكم! من هنا يمكنك الوصول إلى الإحصائيات والتقارير المختلفة.
                    </p>
                    <div class="row justify-content-center">
						<div class="col-md-5 col-lg-4 mb-4">
							<div class="card form-card h-100" style="font-size: 1.25rem;">
								<div class="card-header">
                                بصمة الحضور
                            </div>
                            <div class="card-body">
                                <form action="<?= base_url('attendance/fingerPrint'); ?>" method="GET" target="_blank">
                                    <div class="form-group mb-3">
                                        <label for="month_fp" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">اختر الشهر</label>
                                        <select name="month" id="month_fp" class="form-control" style="font-size: 1.25rem;">
                                            <?php $currentMonth = date('F'); ?>
                                            <option value="January" <?= ($currentMonth == 'January') ? 'selected' : ''; ?>>يناير</option>
                                            <option value="February" <?= ($currentMonth == 'February') ? 'selected' : ''; ?>>فبراير</option>
                                            <option value="March" <?= ($currentMonth == 'March') ? 'selected' : ''; ?>>مارس</option>
                                            <option value="April" <?= ($currentMonth == 'April') ? 'selected' : ''; ?>>أبريل</option>
                                            <option value="May" <?= ($currentMonth == 'May') ? 'selected' : ''; ?>>مايو</option>
                                            <option value="June" <?= ($currentMonth == 'June') ? 'selected' : ''; ?>>يونيو</option>
                                            <option value="July" <?= ($currentMonth == 'July') ? 'selected' : ''; ?>>يوليو</option>
                                            <option value="August" <?= ($currentMonth == 'August') ? 'selected' : ''; ?>>أغسطس</option>
                                            <option value="September" <?= ($currentMonth == 'September') ? 'selected' : ''; ?>>سبتمبر</option>
                                            <option value="October" <?= ($currentMonth == 'October') ? 'selected' : ''; ?>>أكتوبر</option>
                                            <option value="November" <?= ($currentMonth == 'November') ? 'selected' : ''; ?>>نوفمبر</option>
                                            <option value="December" <?= ($currentMonth == 'December') ? 'selected' : ''; ?>>ديسمبر</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="year_fp" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">اختر السنة</label>
                                        <select name="year" id="year_fp" class="form-control" style="font-size: 1.25rem;">
                                            <option value="">اختر السنة</option>
                                            <?php for ($year = 2021; $year <= 2026; $year++): ?>
                                                <option value="<?= $year; ?>" <?= date('Y') == $year ? 'selected' : ''; ?>><?= $year; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="sec_id_fp" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">اختر المراقبة</label>
                                        <select name="sec_id" class="form-control" id="sec_id_fp" style="font-size: 1.25rem;">
                                            <option value="all">كل المراقبات</option>
                                            <?php foreach ($secs as $sec): ?>
                                                <option value="<?= $sec->id; ?>"><?= $sec->name_arabic; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="sub_sec_id_fp" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">اختر القسم</label>
                                        <select name="sub_sec_id" class="form-control" id="sub_sec_id_fp" style="font-size: 1.25rem;">
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="payroll_category_fp" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">اختر الفاتورة</label>
                                        <select name="payroll_category" id="payroll_category_fp" class="form-control" style="font-size: 1.25rem;">
                                            <option value="mmd">الميكانيكا</option>
                                            </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="db_table_fp" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">البصمة (قبل - بعد)</label>
                                        <select name="db_table" id="db_table_fp" class="form-control" style="font-size: 1.25rem;">
                                            <option value="before">البصمة (قبل)</option>
                                            <option value="after">البصمة (بعد)</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="fp_type_fp" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">البصمة (أساسي - إضافي)</label>
                                        <select name="fp_type" id="fp_type_fp" class="form-control" style="font-size: 1.25rem;">
                                            <option value="basic">أساسي</option>
                                            <option value="overtime">إضافي</option>
                                        </select>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-block">عــرض</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4 mb-4">
						<div class="card form-card h-100" style="font-size: 1.25rem;">
							<div class="card-header">
                                كشف الحضور
                            </div>
                            <div class="card-body" style="font-size: 1.25rem;">
                                <form action="<?= base_url('attendance/monthlyCoverList_form'); ?>" method="GET" target="_blank">
                                    <div class="form-group mb-3">
                                        <label for="month_cl" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">اختر الشهر</label>
                                        <select name="month" id="month_cl" class="form-control" style="font-size: 1.25rem;">
                                            <?php $currentMonth = date('F'); ?>
                                            <option value="January" <?= ($currentMonth == 'January') ? 'selected' : ''; ?>>يناير</option>
                                            <option value="February" <?= ($currentMonth == 'February') ? 'selected' : ''; ?>>فبراير</option>
                                            <option value="March" <?= ($currentMonth == 'March') ? 'selected' : ''; ?>>مارس</option>
                                            <option value="April" <?= ($currentMonth == 'April') ? 'selected' : ''; ?>>أبريل</option>
                                            <option value="May" <?= ($currentMonth == 'May') ? 'selected' : ''; ?>>مايو</option>
                                            <option value="June" <?= ($currentMonth == 'June') ? 'selected' : ''; ?>>يونيو</option>
                                            <option value="July" <?= ($currentMonth == 'July') ? 'selected' : ''; ?>>يوليو</option>
                                            <option value="August" <?= ($currentMonth == 'August') ? 'selected' : ''; ?>>أغسطس</option>
                                            <option value="September" <?= ($currentMonth == 'September') ? 'selected' : ''; ?>>سبتمبر</option>
                                            <option value="October" <?= ($currentMonth == 'October') ? 'selected' : ''; ?>>أكتوبر</option>
                                            <option value="November" <?= ($currentMonth == 'November') ? 'selected' : ''; ?>>نوفمبر</option>
                                            <option value="December" <?= ($currentMonth == 'December') ? 'selected' : ''; ?>>ديسمبر</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="year_cl" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">اختر السنة</label>
                                        <select name="year" id="year_cl" class="form-control" style="font-size: 1.25rem;">
                                            <option value="">اختر السنة</option>
                                            <?php for ($year = 2021; $year <= 2026; $year++): ?>
                                                <option value="<?= $year; ?>" <?= date('Y') == $year ? 'selected' : ''; ?>><?= $year; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="sec_id_cl" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">اختر المراقبة</label>
                                        <select name="sec_id" class="form-control" id="sec_id_cl" style="font-size: 1.25rem;">
                                            <option value="all">كل المراقبات</option>
                                            <?php foreach ($secs as $sec): ?>
                                                <option value="<?= $sec->id; ?>"><?= $sec->name_arabic; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="sub_sec_id_cl" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">اختر القسم</label>
                                        <select class="form-control" id="sub_sec_id_cl" name="sub_sec_id" style="font-size: 1.25rem;">
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="payroll_category_cl" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">اختر الفاتورة</label>
                                        <select name="payroll_category" id="payroll_category_cl" class="form-control" style="font-size: 1.25rem;">
                                            <option value="mmd">الميكانيكا</option>
                                            </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="db_table_cl" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">البصمة (قبل - بعد)</label>
                                        <select name="db_table" id="db_table_cl" class="form-control" style="font-size: 1.25rem;">
                                            <option value="before">البصمة (قبل)</option>
                                            <option value="after">البصمة (بعد)</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="fp_type_cl" style="border-radius: 5px; padding: 5px 10px; color: white; !important; background: #0d6efd; width: 100%;">البصمة (أساسي - إضافي)</label>
                                        <select name="fp_type" id="fp_type_cl" class="form-control" style="font-size: 1.25rem;">
                                            <option value="basic">أساسي</option>
                                            <option value="overtime">إضافي</option>
                                        </select>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-block">عــرض</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- <div class="row g-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="card text-white bg-info mb-3 h-100">
                                <div class="card-body">
                                    <h5 class="card-title">إجمالي الموظفين</h5>
                                    <p class="card-text fs-2 fw-bold">250</p>
                                    <small>آخر تحديث: اليوم</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card text-white bg-success mb-3 h-100">
                                <div class="card-body">
                                    <h5 class="card-title">حضور اليوم</h5>
                                    <p class="card-text fs-2 fw-bold">235</p>
                                    <small>نسبة الحضور: 94%</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card text-white bg-warning mb-3 h-100">
                                <div class="card-body">
                                    <h5 class="card-title">أقسام النشاط</h5>
                                    <p class="card-text fs-2 fw-bold">12</p>
                                    <small>مراقبات نشطة: 5</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">الرسوم البيانية والإحصائيات الرئيسية</h5>
                    <div class="alert alert-light border text-center py-5" role="alert">
                        <i class="bi bi-graph-up display-4 text-muted"></i>
                        <p class="mt-3 mb-0 text-muted">
                            يمكنك هنا تضمين رسوم بيانية تفاعلية (مثل Chart.js أو D3.js) لعرض بيانات الحضور، الغياب، وتوزيع الموظفين.
                        </p>
                    </div>

                    <h5 class="mb-3">أحدث الأنشطة</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped text-right">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>الحدث</th>
                                    <th>التاريخ</th>
                                    <th>الموظف/القسم</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>تسجيل دخول: بصمة صباحية</td>
                                    <td><?= date('Y-m-d H:i'); ?></td>
                                    <td>أحمد محمود (قسم الصيانة)</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>طلب إجازة: يوم واحد</td>
                                    <td><?= date('Y-m-d H:i', strtotime('-1 hour')); ?></td>
                                    <td>فاطمة علي (الموارد البشرية)</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>تحديث بيانات: قسم جديد</td>
                                    <td><?= date('Y-m-d H:i', strtotime('-2 hours')); ?></td>
                                    <td>الإدارة (قسم الإحصاء)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div> -->

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        console.log("Document ready!");

        <?php if(session('type') == "admin"){ ?>
            console.log("User is admin.");

            var selected_sub_sec_id_from_old = "<?= old('sub_sec_id') ?>";
            console.log("Initial selected_sub_sec_id (from old):", selected_sub_sec_id_from_old);

            function loadSubSections(secId, subSecIdToSelect, targetElementId) {
                console.log(`loadSubSections called for ${targetElementId} with sec_id: ${secId}, sub_sec_id_to_select: ${subSecIdToSelect}`);
                if (!secId || secId === 'all') { // Handle 'all' case for sec_id, also for empty
                    console.log("sec_id_param is empty or 'all'. Setting default sub-section option.");
                    $('#' + targetElementId).html('<option value="all">كل الأقسام</option>'); // Added 'all' option
                    return;
                }
                $.ajax({
                    url: "<?= base_url('stnSubSec/browseLoadSubSec'); ?>",
                    method: "GET",
                    data: {sec_id: secId, sub_sec_id: subSecIdToSelect}, // Pass subSecIdToSelect
                    dataType: "html",
                    beforeSend: function() {
                        console.log("AJAX request started for sec_id:", secId);
                        $('#' + targetElementId).html('<option>جاري التحميل...</option>');
                    },
                    success: function(data){
                        console.log("AJAX request successful. Data:", data);
                        $('#' + targetElementId).html(data);
                        if (subSecIdToSelect) {
                            console.log("Setting selected sub_sec_id:", subSecIdToSelect);
                            $('#' + targetElementId).val(subSecIdToSelect);
                        } else if (selected_sub_sec_id_from_old) { // For initial load from old()
                            console.log("Setting selected sub_sec_id from old():", selected_sub_sec_id_from_old);
                            $('#' + targetElementId).val(selected_sub_sec_id_from_old);
                            selected_sub_sec_id_from_old = ""; // Clear after use
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading subsections:", status, error, "Response:", xhr.responseText);
                        $('#' + targetElementId).html('<option value="">تعذر تحميل الأقسام</option>');
                    }
                });
            }

            // --- Fingerprint Section (fp) ---
            var initial_sec_id_fp = $('#sec_id_fp').val();
            var initial_sub_sec_id_fp = $('#sub_sec_id_fp option:selected').val(); // This will get the current selected value, if any
            if (initial_sec_id_fp !== undefined && initial_sec_id_fp !== null && initial_sec_id_fp !== '') {
                loadSubSections(initial_sec_id_fp, initial_sub_sec_id_fp, 'sub_sec_id_fp');
            } else {
                 $('#sub_sec_id_fp').html('<option value="all">كل الأقسام</option>');
            }

            $('#sec_id_fp').change(function(){
                var selected_sec_id_fp = $(this).val();
                var current_sub_sec_id_fp = 'all'; // Reset to 'all' or empty on section change
                loadSubSections(selected_sec_id_fp, current_sub_sec_id_fp, 'sub_sec_id_fp');
            });

            // --- Cover List Section (cl) ---
            var initial_sec_id_cl = $('#sec_id_cl').val();
            var initial_sub_sec_id_cl = $('#sub_sec_id_cl option:selected').val(); // This will get the current selected value, if any
            if (initial_sec_id_cl !== undefined && initial_sec_id_cl !== null && initial_sec_id_cl !== '') {
                loadSubSections(initial_sec_id_cl, initial_sub_sec_id_cl, 'sub_sec_id_cl');
            } else {
                $('#sub_sec_id_cl').html('<option value="all">كل الأقسام</option>');
            }

            $('#sec_id_cl').change(function(){
                var selected_sec_id_cl = $(this).val();
                var current_sub_sec_id_cl = 'all'; // Reset to 'all' or empty on section change
                loadSubSections(selected_sec_id_cl, current_sub_sec_id_cl, 'sub_sec_id_cl');
            });

        <?php } else { ?>
            console.log("User is not admin. Sub-section loading will not be active.");
        <?php } ?>
    });
</script>
<?= $this->endSection(); ?>
