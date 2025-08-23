<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?>تعديل بيانات :: <?= esc($row->name_english) ?><?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<script>
$(document).ready(function() {
    console.log("Document ready!");
    <?php if (session('type') == "admin" || session('type') == "depart"): ?>
    console.log("User has edit permissions.");

    // Store selected sub section id if available
    var selected_sub_sec_id = "<?= old('sub_sec_id', $row->sub_sec_id ?? '') ?>";
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
    console.log("User doesn't have edit permissions. Sub-section loading will not be active.");
    <?php endif; ?>
});
</script>

<div class="container-fluid pt-6">
    
    <div class="h3" style="text-align: right; padding: 5px; border-bottom: thin solid #ccc;">تعديل بيانات الموظف</div>

    <form action="<?= site_url('employees/update/' . $row->id); ?>" method="post" enctype="multipart/form-data">
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?= base_url(!empty($row->photo) && file_exists(FCPATH . $row->photo) ? $row->photo : ($row->gender == "female" ? 'assets/images/female_avatar.jpg' : 'assets/images/male_avatar.jpg')) . '?' . rand(1, 1000000) ?>" 
                     class="card-img-top" alt="<?= esc($row->name_english) ?>">
                <div class="card-body text-center">
                    <div class="form-group">
                        <label class="btn btn-sm btn-primary">
                            تغيير الصورة <input type="file" name="file" style="display: none;">
                        </label>
                        <input type="hidden" name="civil_id" value="<?= esc($row->civil_id) ?>" />
                        <input type="hidden" name="emp_id" value="<?= esc($row->id) ?>" />
                    </div>
                    <?php if (!empty($row->photo) && file_exists(FCPATH . $row->photo)): ?>
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delEmpPhoto">
                        حذف الصورة
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="row row-clear">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="name_arabic">الاسم - عربي</label>
                        <input type="text" class="form-control" id="name_arabic" name="name_arabic" 
                               value="<?= old('name_arabic', $row->name_arabic) ?>" placeholder="اسم الموظف بالكامل عربي">
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="name_english">الاسم - انجليزي</label>
                        <input type="text" class="form-control en left" id="name_english" name="name_english" 
                               value="<?= old('name_english', $row->name_english) ?>" placeholder="Employee Name in English">
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="file_no">رقم الملف</label>
                        <input type="text" class="form-control" id="file_no" name="file_no" 
                               value="<?= old('file_no', $row->file_no) ?>" placeholder="رقم الملف" 
                               <?= session('type') != "admin" ? "readonly" : "" ?>>
                        <?php if (!empty(session('errors.file_no'))): ?>
                            <span class="text-danger"><?= esc(session('errors.file_no')) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="civil_id">الرقم المدني</label>
                        <input type="text" class="form-control" id="civil_id" name="civil_id" 
                               value="<?= old('civil_id', $row->civil_id) ?>" placeholder="الرقم المدني">
                        <?php if (!empty(session('errors.civil_id'))): ?>
                            <span class="text-danger"><?= esc(session('errors.civil_id')) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="mobile">الموبايل</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" 
                               value="<?= old('mobile', $row->mobile) ?>" placeholder="الموبايل">
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="gender">الجنس</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="male" <?= old('gender', $row->gender) == 'male' ? 'selected' : '' ?>>ذكر</option>
                            <option value="female" <?= old('gender', $row->gender) == 'female' ? 'selected' : '' ?>>أنثى</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="nation">الجنسية</label>
                        <input type="text" class="form-control" id="nation" name="nation" 
                               value="<?= old('nation', $row->nation) ?>" placeholder="الجنسية">
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="birth_date">تاريخ الميلاد</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" 
                               value="<?= old('birth_date', $row->birth_date) ?>" placeholder="تاريخ الميلاد">
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="edu_cert">المؤهل العلمي</label>
                        <input type="text" class="form-control en" id="edu_cert" name="edu_cert" 
                               value="<?= old('edu_cert', $row->edu_cert) ?>" placeholder="المؤهل الدراسي">
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="design_id">الوظيفة</label>
                        <select class="form-control en" id="design_id" name="design_id">
                        <?php foreach($designs as $design): ?>
                            <option value="<?= $design->id; ?>" <?= old('design_id', $row->design_id) == $design->id ? 'selected' : '' ?>>
                                <?= esc($design->name); ?>
                            </option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="join_date">تاريخ التعيين</label>
                        <input type="date" class="form-control" id="join_date" name="join_date" 
                               value="<?= old('join_date', $row->join_date) ?>" placeholder="تاريخ التعيين">
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="termination_date">تاريخ انتهاء الخدمة</label>
                        <input type="date" class="form-control" id="termination_date" name="termination_date" 
                               value="<?= old('termination_date', $row->termination_date) ?>" placeholder="تاريخ انتهاء الخدمة">
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="termination_reason">سبب انتهاء الخدمة</label>
                        <input type="text" class="form-control en" id="termination_reason" name="termination_reason" 
                               value="<?= old('termination_reason', $row->termination_reason) ?>" placeholder="سبب انتهاء الخدمة">
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="sec_id">المراقبة</label>
                        <select class="form-control" id="sec_id" name="sec_id">
                            <?php if (session('type') == "admin"): ?>
                                <?php foreach($secs as $sec): ?>
                                    <option value="<?= $sec->id; ?>" <?= old('sec_id', $row->sec_id) == $sec->id ? 'selected' : '' ?>>
                                        <?= esc($sec->name_arabic); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php elseif (session('type') == "DEPART"): ?>
                                <option value="<?= session('sec') ?>" selected>
                                    <?= $secModel->getSecById(session('sec'))->name_arabic ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="sub_sec_id">القسم</label>
                        <select class="form-control" id="sub_sec_id" name="sub_sec_id">
                            <?php if (session('type') == "depart"): ?>
                                <option value="">غير محدد</option>
                                <?php foreach ($secModel->getAllSubSec(session('sub_sec')) as $sub_sec): ?>
                                    <option value="<?= $sub_sec->id ?>" <?= old('sub_sec_id', $row->sub_sec_id) == $sub_sec->id ? 'selected' : '' ?>>
                                        <?= $sub_sec->name_arabic ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="experience">الخبرة</label>
                        <input type="text" class="form-control" id="experience" name="experience" 
                               value="<?= old('experience', $row->experience) ?>" placeholder="Experience">
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="payroll_category">فاتورة</label>
                        <select class="form-control" id="payroll_category" name="payroll_category">
                            <option value="mmd" <?= old('payroll_category', $row->payroll_category) == 'mmd' ? 'selected' : '' ?>>ميكانيكا</option>
                            <option value="imd" <?= old('payroll_category', $row->payroll_category) == 'imd' ? 'selected' : '' ?>>أجهزة</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="has_overtime">إضافي</label>
                        <select class="form-control" id="has_overtime" name="has_overtime">
                            <option value="0" <?= old('has_overtime', $row->has_overtime) == '0' ? 'selected' : '' ?>>غير مسموح</option>
                            <option value="1" <?= old('has_overtime', $row->has_overtime) == '1' ? 'selected' : '' ?>>مسموح</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="permanent">نوع التوظيف</label>
                        <select class="form-control" id="permanent" name="permanent">
                            <option value="1" <?= old('permanent', $row->permanent) == '1' ? 'selected' : '' ?>>دائم</option>
                            <option value="0" <?= old('permanent', $row->permanent) == '0' ? 'selected' : '' ?>>مؤقت</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group text-right">
                        <label for="active">بالخدمة</label>
                        <select class="form-control" id="active" name="active">
                            <option value="1" <?= old('active', $row->active) == '1' ? 'selected' : '' ?>>بالخدمة</option>
                            <option value="0" <?= old('active', $row->active) == '0' ? 'selected' : '' ?>>ليس بالخدمة</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col">
                    <div class="form-group text-right">
                        <label for="remarks">ملاحظات</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"><?= old('remarks', $row->remarks) ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                        <a href="<?= site_url('employees/show/' . $row->id); ?>" class="btn btn-secondary">إلغاء</a>
                        
                        <?php if (session('type') == "admin"): ?>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteEmpConfirm">
                            حذف الموظف
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<!-- Delete Photo Modal -->
<div class="modal fade" id="delEmpPhoto" tabindex="-1" aria-labelledby="delEmpPhotoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delEmpPhotoLabel">تأكيد حذف الصورة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                هل أنت متأكد من حذف صورة الموظف؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <a href="<?= site_url('employees/delEmpPhoto/' . $row->id) ?>" class="btn btn-danger">حذف</a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Employee Modal -->
<div class="modal fade" id="deleteEmpConfirm" tabindex="-1" aria-labelledby="deleteEmpConfirmLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEmpConfirmLabel">تأكيد حذف الموظف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                هل أنت متأكد من حذف بيانات الموظف:<br>
                <strong><?= esc($row->name_arabic) ?></strong>؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <a href="<?= site_url('employees/delete/' . $row->id) ?>" class="btn btn-danger">حذف</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>