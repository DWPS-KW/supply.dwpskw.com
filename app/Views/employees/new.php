<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?>إضافة موظف جديد<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<script>
$(document).ready(function() {
    console.log("Document ready!");
    <?php if (session('type') == "admin"): ?>
    console.log("User is admin.");

    // Store selected sub section id if available (for edit forms)
    var selected_sub_sec_id = "<?= old('sub_sec_id') ?>";
    console.log("Initial selected_sub_sec_id:", selected_sub_sec_id);

    function loadSubSections(sec_id_param) {
        console.log("loadSubSections called with sec_id:", sec_id_param);
        if (!sec_id_param) {
            console.log("sec_id_param is empty. Setting default sub-section option.");
            $('#sub_sec_id').html('<option value="">اختر القسم</option>');
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
                if (selected_sub_sec_id) {
                    console.log("Setting selected sub_sec_id:", selected_sub_sec_id);
                    $('#sub_sec_id').val(selected_sub_sec_id);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed. Status:", status, "Error:", error, "Response:", xhr.responseText);
                $('#sub_sec_id').html('<option value="">تعذر تحميل الأقسام</option>');
            }
        });
    }

    // On page load, if sec_id has a value, load sub sections
    var initial_sec_id = $('#sec_id').val();
    console.log("Initial sec_id value:", initial_sec_id);
    if (initial_sec_id) {
        loadSubSections(initial_sec_id);
    } else {
        console.log("Initial sec_id is empty. Setting default sub-section option.");
        $('#sub_sec_id').html('<option value="">اختر القسم</option>');
    }

    // When section changes, reload sub sections
    $('#sec_id').on('change', function() {
        var changed_sec_id = $(this).val();
        console.log("sec_id changed to:", changed_sec_id);
        selected_sub_sec_id = ""; // Reset on section change
        loadSubSections(changed_sec_id);
    });

    <?php else: ?>
    console.log("User is not admin. Sub-section loading will not be active.");
    <?php endif; ?>
});
</script>

<div class="container-fluid pt-6">
    <div class="h3" style="text-align: right; padding: 5px; border-bottom: thin solid #ccc;">إضافة موظف جديد</div>

    <?php if (session('error')): ?>
        <div class="alert alert-danger text-right">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('employees/create'); ?>" method="post" enctype="multipart/form-data" novalidate>

    <?php $errors = session('errors'); ?>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?= base_url('assets/images/male_avatar.jpg') ?>"
                     class="card-img-top" alt="صورة الموظف">
                <div class="card-body text-center">
                    <div class="form-group">
                        <label class="btn btn-sm btn-primary">
                            إضافة صورة <input type="file" name="file" style="display: none;">
                        </label>
                        <?php if (!empty($errors['file'])): ?>
                            <span class="text-danger d-block"><?= esc($errors['file']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row row-clear">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="name_arabic">الاسم - عربي</label>
                        <input type="text" class="form-control <?= isset($errors['name_arabic']) ? 'is-invalid' : '' ?>"
                               id="name_arabic" name="name_arabic" value="<?= old('name_arabic') ?>"
                               placeholder="اسم الموظف بالكامل عربي">
                        <?php if (!empty($errors['name_arabic'])): ?>
                            <span class="text-danger"><?= esc($errors['name_arabic']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="name_english">الاسم - انجليزي</label>
                        <input type="text" class="form-control en left <?= isset($errors['name_english']) ? 'is-invalid' : '' ?>"
                               id="name_english" name="name_english" value="<?= old('name_english') ?>"
                               placeholder="Employee Name in English">
                        <?php if (!empty($errors['name_english'])): ?>
                            <span class="text-danger"><?= esc($errors['name_english']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="file_no">رقم الملف</label>
                        <input type="text" class="form-control <?= isset($errors['file_no']) ? 'is-invalid' : '' ?>"
                               id="file_no" name="file_no" value="<?= old('file_no') ?>" placeholder="رقم الملف">
                        <?php if (!empty($errors['file_no'])): ?>
                            <span class="text-danger"><?= esc($errors['file_no']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="civil_id">الرقم المدني</label>
                        <input type="text" class="form-control <?= isset($errors['civil_id']) ? 'is-invalid' : '' ?>"
                               id="civil_id" name="civil_id" value="<?= old('civil_id') ?>"
                               placeholder="الرقم المدني">
                        <?php if (!empty($errors['civil_id'])): ?>
                            <span class="text-danger"><?= esc($errors['civil_id']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="mobile">الموبايل</label>
                        <input type="text" class="form-control <?= isset($errors['mobile']) ? 'is-invalid' : '' ?>"
                               id="mobile" name="mobile" value="<?= old('mobile') ?>" placeholder="الموبايل">
                        <?php if (!empty($errors['mobile'])): ?>
                            <span class="text-danger"><?= esc($errors['mobile']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="gender">الجنس</label>
                        <select class="form-control <?= isset($errors['gender']) ? 'is-invalid' : '' ?>" id="gender" name="gender">
                            <option value="male" <?= old('gender') == 'male' ? 'selected' : '' ?>>ذكر</option>
                            <option value="female" <?= old('gender') == 'female' ? 'selected' : '' ?>>أنثى</option>
                        </select>
                        <?php if (!empty($errors['gender'])): ?>
                            <span class="text-danger"><?= esc($errors['gender']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="nation">الجنسية</label>
                        <input type="text" class="form-control <?= isset($errors['nation']) ? 'is-invalid' : '' ?>"
                               id="nation" name="nation" value="<?= old('nation') ?>" placeholder="الجنسية">
                        <?php if (!empty($errors['nation'])): ?>
                            <span class="text-danger"><?= esc($errors['nation']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="birth_date">تاريخ الميلاد</label>
                        <input type="date" class="form-control <?= isset($errors['birth_date']) ? 'is-invalid' : '' ?>"
                               id="birth_date" name="birth_date" value="<?= old('birth_date') ?>" placeholder="تاريخ الميلاد">
                        <?php if (!empty($errors['birth_date'])): ?>
                            <span class="text-danger"><?= esc($errors['birth_date']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="edu_cert">المؤهل العلمي</label>
                        <input type="text" class="form-control en <?= isset($errors['edu_cert']) ? 'is-invalid' : '' ?>"
                               id="edu_cert" name="edu_cert" value="<?= old('edu_cert') ?>" placeholder="المؤهل الدراسي">
                        <?php if (!empty($errors['edu_cert'])): ?>
                            <span class="text-danger"><?= esc($errors['edu_cert']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="design_id">الوظيفة</label>
                        <select class="form-control en <?= isset($errors['design_id']) ? 'is-invalid' : '' ?>" id="design_id" name="design_id">
                            <?php foreach($designs as $design): ?>
                                <option value="<?= $design->id; ?>" <?= old('design_id') == $design->id ? 'selected' : '' ?>>
                                    <?= esc($design->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors['design_id'])): ?>
                            <span class="text-danger"><?= esc($errors['design_id']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="join_date">تاريخ التعيين</label>
                        <input type="date" class="form-control <?= isset($errors['join_date']) ? 'is-invalid' : '' ?>"
                               id="join_date" name="join_date" value="<?= old('join_date') ?>" placeholder="تاريخ التعيين">
                        <?php if (!empty($errors['join_date'])): ?>
                            <span class="text-danger"><?= esc($errors['join_date']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="termination_date">تاريخ انتهاء الخدمة</label>
                        <input type="date" class="form-control <?= isset($errors['termination_date']) ? 'is-invalid' : '' ?>"
                               id="termination_date" name="termination_date" value="<?= old('termination_date') ?>" placeholder="تاريخ انتهاء الخدمة">
                        <?php if (!empty($errors['termination_date'])): ?>
                            <span class="text-danger"><?= esc($errors['termination_date']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="termination_reason">سبب انتهاء الخدمة</label>
                        <input type="text" class="form-control en <?= isset($errors['termination_reason']) ? 'is-invalid' : '' ?>"
                               id="termination_reason" name="termination_reason" value="<?= old('termination_reason') ?>" placeholder="سبب انتهاء الخدمة">
                        <?php if (!empty($errors['termination_reason'])): ?>
                            <span class="text-danger"><?= esc($errors['termination_reason']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="sec_id">المراقبة</label>
                        <select class="form-control <?= isset($errors['sec_id']) ? 'is-invalid' : '' ?>" id="sec_id" name="sec_id">
                            <?php if (session('type') == "admin"): ?>
                                <?php foreach($secs as $sec): ?>
                                    <option value="<?= $sec->id; ?>" <?= old('sec_id') == $sec->id ? 'selected' : '' ?>>
                                        <?= esc($sec->name_arabic); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (!empty($errors['sec_id'])): ?>
                            <span class="text-danger"><?= esc($errors['sec_id']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="sub_sec_id">القسم</label>
                        <select class="form-control <?= isset($errors['sub_sec_id']) ? 'is-invalid' : '' ?>" id="sub_sec_id" name="sub_sec_id">
                        </select>
                        <?php if (!empty($errors['sub_sec_id'])): ?>
                            <span class="text-danger"><?= esc($errors['sub_sec_id']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="experience">الخبرة</label>
                        <input type="text" class="form-control <?= isset($errors['experience']) ? 'is-invalid' : '' ?>"
                               id="experience" name="experience" value="<?= old('experience') ?>" placeholder="Experience">
                        <?php if (!empty($errors['experience'])): ?>
                            <span class="text-danger"><?= esc($errors['experience']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="payroll_category">فاتورة</label>
                        <select class="form-control <?= isset($errors['payroll_category']) ? 'is-invalid' : '' ?>" id="payroll_category" name="payroll_category">
                            <option value="mmd" <?= old('payroll_category') == 'mmd' ? 'selected' : '' ?>>ميكانيكا</option>
                            <option value="imd" <?= old('payroll_category') == 'imd' ? 'selected' : '' ?>>أجهزة</option>
                        </select>
                        <?php if (!empty($errors['payroll_category'])): ?>
                            <span class="text-danger"><?= esc($errors['payroll_category']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="has_overtime">إضافي</label>
                        <select class="form-control <?= isset($errors['has_overtime']) ? 'is-invalid' : '' ?>" id="has_overtime" name="has_overtime">
                            <option value="0" <?= old('has_overtime') == '0' ? 'selected' : '' ?>>غير مسموح</option>
                            <option value="1" <?= old('has_overtime') == '1' ? 'selected' : '' ?>>مسموح</option>
                        </select>
                        <?php if (!empty($errors['has_overtime'])): ?>
                            <span class="text-danger"><?= esc($errors['has_overtime']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="permanent">نوع التوظيف</label>
                        <select class="form-control <?= isset($errors['permanent']) ? 'is-invalid' : '' ?>" id="permanent" name="permanent">
                            <option value="1" <?= old('permanent') == '1' ? 'selected' : '' ?>>دائم</option>
                            <option value="0" <?= old('permanent') == '0' ? 'selected' : '' ?>>مؤقت</option>
                        </select>
                        <?php if (!empty($errors['permanent'])): ?>
                            <span class="text-danger"><?= esc($errors['permanent']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="active">بالخدمة</label>
                        <select class="form-control <?= isset($errors['active']) ? 'is-invalid' : '' ?>" id="active" name="active">
                            <option value="1" <?= old('active') == '1' ? 'selected' : '' ?>>بالخدمة</option>
                            <option value="0" <?= old('active') == '0' ? 'selected' : '' ?>>ليس بالخدمة</option>
                        </select>
                        <?php if (!empty($errors['active'])): ?>
                            <span class="text-danger"><?= esc($errors['active']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <div class="form-group text-right">
                        <label for="remarks">ملاحظات</label>
                        <textarea class="form-control <?= isset($errors['remarks']) ? 'is-invalid' : '' ?>" id="remarks" name="remarks" rows="3"><?= old('remarks') ?></textarea>
                        <?php if (!empty($errors['remarks'])): ?>
                            <span class="text-danger"><?= esc($errors['remarks']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">إضافة</button>
                        <a href="<?= site_url('employees'); ?>" class="btn btn-secondary">رجوع</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<?= $this->endSection(); ?>