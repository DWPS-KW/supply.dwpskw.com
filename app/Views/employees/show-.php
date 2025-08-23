<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?><?= $pageTitle ?><?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container-fluid pt-6">

    <div class="card shadow-sm rounded mb-4">
        <div class="card-body text-center">
            <?php
            $allowPhoto = session()->get('allow_photo');  // Get session value

            // Default photos
            $maleDefault = 'assets/images/male_avatar.jpg';
            $femaleDefault = 'assets/images/female_avatar.jpg';
            $noPhoto = 'assets/images/no_photo.png'; // you can define a placeholder for 'None' or restricted

            // Determine if we can show photo based on allow_paper and employee gender
            $showPhoto = false;
            if ($allowPhoto === 'All') {
                $showPhoto = true;
            } elseif ($allowPhoto === 'Male' && strtolower($row->gender) === 'Male') {
                $showPhoto = true;
            } elseif ($allowPhoto === 'Female' && strtolower($row->gender) === 'Female') {
                $showPhoto = true;
            } elseif ($allowPhoto === 'None') {
                $showPhoto = false;
            }
            if ($showPhoto && file_exists(FCPATH . $row->photo) && $row->photo != null) {
                $photo = $row->photo;
            } else {
                if ($allowPhoto === 'None') {
                    $photo = $noPhoto;
                } else {
                    if (strtolower($row->gender) === 'Female') {
                        $photo = $femaleDefault;
                    } else {
                        $photo = $maleDefault;
                    }
                }
            }
            ?>
            <img src="<?= base_url($photo) ?>" class="img-fluid rounded-circle" alt="<?= esc($row->name_english); ?>" style="max-width: 150px;">
            <h2 class="text-primary"><?= $pageTitle ?></h2>
            <?php if (session()->get("type") == "Admin" || session()->get("type") == "Depart"): ?>
                <a href="<?= base_url('employees/edit/' . $row->id); ?>" class="btn btn-warning">تحديث البيانات</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm rounded mb-4">
        <div class="card-header bg-light">
            <span>إجراءات الموظف</span>
        </div>
        <div class="card-body">
            <div class="d-grid gap-2"> <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#newL">تسجيل إجازة</button>
                <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#newMed">تسجيل نموذج علاج</button>
                <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#newFdayPerm">تسجيل إذن يوم كامل</button>
                <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#allL">
                    عدد الاجازات <span class="badge border border-dark text-dark bg-light"><?php echo count($allLeaves); ?></span>
                </button>
                <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#allMed">
                    عدد الطبيات <span class="badge border border-dark text-dark bg-light"><?= count($allMeds); ?></span>
                </button>
                <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#allFdays">
                    عدد اذونات اليوم الكامل <span class="badge border border-dark text-dark bg-light"><?= count($allFdays); ?></span>
                </button>
                 <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#vfp">
                    عرض بصمة
                </button>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded mb-4">
        <div class="card-header bg-light">
            <span>معلومات الموظف</span>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">الاسم - عربي</label>
                <input type="text" class="form-control" value="<?= esc($row->name_arabic); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">الاسم - انجليزي</label>
                <input type="text" class="form-control" value="<?= esc($row->name_english); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">رقم الملف</label>
                <input type="text" class="form-control" value="<?= esc($row->file_no); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">الرقم المدني</label>
                <input type="text" class="form-control" value="<?= esc($row->civil_id); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">الموبايل</label>
                <input type="text" class="form-control" value="<?= esc($row->mobile); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">الجنس</label>
                <?php
                    $genderDisplay = ($row->gender == 'Male') ? 'ذكر' : (($row->gender == 'Female') ? 'أنثى' : 'غير محدد');
                ?>
                <input type="text" class="form-control" value="<?= $genderDisplay; ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">الجنسية</label>
                <input type="text" class="form-control" value="<?= esc($row->nation); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">تاريخ الميلاد</label>
                <input type="text" class="form-control" value="<?= esc($myFuns->reverseDateToSeeArabic($row->birth_date)); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">المؤهل العلمي</label>
                <input type="text" class="form-control" value="<?= esc($row->edu_cert); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">الوظيفة</label>
                <input type="text" class="form-control" value="<?= esc($designModel->getDesignNameById($row->design_id)); ?>" readonly>
            </div>
             <?php if (session()->get("type") == "ADMIN" || session()->get("type") == "DEPART") { ?>
                <div class="mb-3">
                    <label class="form-label">فاتورة</label>
                    <?php
                        $payTypes = [
                            null => 'غير محدد',
                            'MMD' => 'ميكانيكا',
                            'IMD' => 'أجهزة'
                        ];
                        $payDisplay = $payTypes[$row->pay] ?? 'غير محدد';
                    ?>
                    <input type="text" class="form-control" value="<?= $payDisplay; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">إضافي</label>
                    <input type="text" class="form-control" value="<?= ($row->ot == 1) ? "مسموح" : "غير مسموح"; ?>" readonly>
                </div>
            <?php } ?>
            <div class="mb-3">
                <label class="form-label">الراتب</label>
                <input type="text" class="form-control" value="<?= esc($designModel->find($row->design_id)->total_salary); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">المراقبة</label>
                <input type="text" class="form-control" value="<?= ($row->sec_id && $secModel->find($row->sec_id)) ? esc($secModel->find($row->sec_id)->name_arabic) : 'غير محدد'; ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">القسم</label>
                <input type="text" class="form-control" value="<?= ($row->sub_sec_id && $subSecModel->find($row->sub_sec_id)) ? esc($subSecModel->find($row->sub_sec_id)->name_arabic) : 'غير محدد'; ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">الخبرة</label>
                <input type="text" class="form-control" value="<?= esc($row->experience); ?>" readonly>
            </div>
             <div class="mb-3">
                <label class="form-label">نوع التوظيف</label>
                <input type="text" class="form-control" value="<?= $row->permanent ? 'دائم' : 'مؤقت'; ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">بالخدمة</label>
                <input type="text" class="form-control" value="<?= $row->active ? 'بالخدمة' : 'ليس بالخدمة'; ?>" readonly>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded mb-4">
        <div class="card-header bg-light">
            <span>ملاحظات</span>
        </div>
        <div class="card-body">
            <textarea class="form-control" rows="3" readonly><?= esc($row->remarks); ?></textarea>
        </div>
    </div>


    <div class="modal fade" id="newL" tabindex="-1" aria-labelledby="newLLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('leaves/create'); ?>" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title emp_info_title_color" id="newLLabel">تسجيل إجازة</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group text-right">
                        <label class="emp_info_title_color" for="lbegin">من</label>
                        <input type="date" name="begin" id="lbegin" class="form-control" required>
                    </div>
                    <div class="form-group text-right">
                        <label class="emp_info_title_color" for="lend">إلى</label>
                        <input type="date" name="end" id="lend" class="form-control" required>
                    </div>
                    <div class="form-group text-right">
                        <label class="emp_info_title_color" for="Remarks">ملاحظات</label>
                        <textarea name="remarks" id="Remarks" rows="3" class="form-control"></textarea>
                        <input type="hidden" name="emp_id" value="<?= $row->id; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">تسجيل</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="newMed" tabindex="-1" aria-labelledby="newMedLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('medicals/create'); ?>" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title emp_info_title_color" id="newMedLabel">تسجيل نموذج علاج</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group text-right">
                        <label class="emp_info_title_color" for="date_med">التاريخ</label>
                        <input type="date" name="date" id="date_med" class="form-control" required>
                    </div>
                    <div class="form-group text-right">
                        <label class="emp_info_title_color" for="duration">عدد الأيام</label>
                        <input type="text" name="duration" id="duration" class="form-control" required>
                    </div>
                    <div class="form-group text-right">
                        <label class="emp_info_title_color" for="descrp">التشخيص</label>
                        <input type="text" name="descrp" id="descrp" class="form-control" required>
                    </div>
                    <div class="form-group text-right">
                        <label class="emp_info_title_color" for="remarks_med">ملاحظات</label>
                        <textarea name="remarks" id="remarks_med" rows="3" class="form-control"></textarea>
                        <input type="hidden" name="emp_id" value="<?= $row->id; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">تسجيل</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="newFdayPerm" tabindex="-1" aria-labelledby="newFdayPermLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('fdays/create'); ?>" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title emp_info_title_color" id="newFdayPermLabel">تسجيل إذن يوم كامل</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group text-right">
                        <label class="emp_info_title_color" for="date_fday">التاريخ</label>
                        <input type="date" name="date" id="date_fday" class="form-control" required>
                    </div>
                    <div class="form-group text-right">
                        <label class="emp_info_title_color" for="remarks_fday">ملاحظات</label>
                        <textarea name="remarks" id="remarks_fday" rows="3" class="form-control"></textarea>
                        <input type="hidden" name="emp_id" value="<?= $row->id; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary">تسجيل</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="allL" tabindex="-1" aria-labelledby="allLLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allLLabel">سجل الاجازات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المدة</th>
                                <th>تبدأ</th>
                                <th>تنتهي</th>
                                <th>ملاحظات</th>
                                <th>إعدادات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($allLeaves as $leave) {
                            ?>
                                <tr>
                                    <th><?php echo $i; ?></th>
                                    <td><?php echo $leave->duration; ?></td>
                                    <td><?php echo $leave->begin; ?></td>
                                    <td><?php echo $leave->end; ?></td>
                                    <td><?php echo $leave->remarks; ?></td>
                                    <td>
                                        <?php if (session()->get("type") == "ADMIN" || session()->get("type") == "DEPART") { ?>
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#update<?php echo 'l-' . $leave->id; ?>">
                                                تعــديل
                                            </button>

                                            <div class="modal fade" id="update<?php echo 'l-' . $leave->id; ?>" tabindex="-1" aria-labelledby="updateModalLabel<?php echo $leave->id; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="<?php echo base_url(); ?>leaves/updatingL" method="post">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="updateModalLabel<?php echo $leave->id; ?>">تعديل اجازة</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group text-right">
                                                                    <label for="lbegin">من</label>
                                                                    <input type="date" class="form-control" name="begin" value="<?php echo $leave->begin; ?>">
                                                                </div>
                                                                <div class="form-group text-right">
                                                                    <label for="lend">إلى</label>
                                                                    <input type="date" class="form-control" name="end" value="<?php echo $leave->end; ?>">
                                                                </div>
                                                                <div class="form-group text-right">
                                                                    <label for="Remarks">ملاحظــات</label>
                                                                    <textarea class="form-control" name="remarks" rows="3"><?php echo $leave->remarks; ?></textarea>
                                                                    <input type="hidden" name="id" value="<?php echo $leave->id; ?>" />
                                                                    <input type="hidden" name="emp_id" value="<?php echo $leave->emp_id; ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">تعديل</button>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delThingConf<?php echo 'l-' . $leave->id; ?>">
                                                حـــذف
                                            </button>

                                            <div class="modal fade" id="delThingConf<?php echo 'l-' . $leave->id; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $leave->id; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel<?php echo $leave->id; ?>">تأكيد حذف بيانات</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            تأكيد حذف بيانات
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                            <a href="<?php echo base_url() . 'leaves/deletingL/' . $leave->id; ?>" class="btn btn-primary">حــــذف</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="allMed" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">سجل الطبيات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">التاريخ</th>
                                <th scope="col">المدة</th>
                                <th scope="col">إعدادات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($allMeds as $med): ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= esc($med->date); ?></td>
                                <td><?= esc($med->duration); ?></td>
                                <td>
                                    <?php if (session()->get("type") == "ADMIN" || session()->get("type") == "DEPART"): ?>
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#update<?= 'med-' . $med->id; ?>">
                                        تعــديل
                                    </button>

                                    <div class="modal fade" id="update<?= 'med-' . $med->id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="<?= base_url('medicals/updatingMED'); ?>" method="post">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">تعديل طبية</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group text-right">
                                                            <label for="date">التاريخ</label>
                                                            <input type="date" class="form-control" id="date" name="date" value="<?= esc($med->date); ?>" />
                                                        </div>
                                                        <div class="form-group text-right">
                                                            <label for="duration">عدد الأيام</label>
                                                            <input type="text" class="form-control" id="duration" name="duration" value="<?= esc($med->duration); ?>" />
                                                        </div>
                                                        <div class="form-group text-right">
                                                            <label for="descrp">التشخيص</label>
                                                            <input type="text" class="form-control" id="descrp" name="descrp" value="<?= esc($med->descrp); ?>" />
                                                        </div>
                                                        <div class="form-group text-right">
                                                            <label for="remarks">ملاحظــات</label>
                                                            <textarea class="form-control" id="remarks" name="remarks" rows="3"><?= esc($med->remarks); ?></textarea>
                                                            <input type="hidden" name="id" value="<?= esc($med->id); ?>" />
                                                            <input type="hidden" name="emp_id" value="<?= esc($med->emp_id); ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">تعديل</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delThingConf<?= 'med-' . $med->id; ?>">
                                        حـــذف
                                    </button>

                                    <div class="modal fade" id="delThingConf<?= 'med-' . $med->id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تأكيد الحذف</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    تأكيد حذف بيانات
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                    <a href="<?= base_url('medicals/deletingMED/' . $med->id); ?>" class="btn btn-primary">حــــذف</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $i++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="allFdays" tabindex="-1" aria-labelledby="allFdaysLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allFdaysLabel">سجل الاذونات اليوم الكامل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">التاريخ</th>
                                <th scope="col">إعدادات</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; foreach ($allFdays as $fday) { ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= esc($fday->date); ?></td>
                                <td>
                                    <?php if (session()->get("type") == "ADMIN" || session()->get("type") == "DEPART") { ?>
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#updatefday<?= $fday->id; ?>">
                                            تعــديل
                                        </button>

                                        <div class="modal fade" id="updatefday<?= $fday->id; ?>" tabindex="-1" aria-labelledby="updatefdayLabel<?= $fday->id; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="<?= base_url('fdays/updatingFDay'); ?>" method="post">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="updatefdayLabel<?= $fday->id; ?>">تعديل اذن يوم كامل</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group text-right">
                                                                <label for="date<?= $fday->id; ?>">التاريخ</label>
                                                                <input type="date" class="form-control" id="date<?= $fday->id; ?>" name="date" value="<?= esc($fday->date); ?>" />
                                                            </div>
                                                            <div class="form-group text-right">
                                                                <label for="remarks<?= $fday->id; ?>">ملاحظــات</label>
                                                                <textarea class="form-control" id="remarks<?= $fday->id; ?>" name="remarks" rows="3"><?= esc($fday->remarks); ?></textarea>
                                                                <input type="hidden" name="id" value="<?= esc($fday->id); ?>" />
                                                                <input type="hidden" name="emp_id" value="<?= esc($fday->emp_id); ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">تعديل</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (session()->get("type") == "ADMIN" || session()->get("type") == "DEPART") { ?>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delThingConf<?= $fday->id; ?>">
                                            حـــذف
                                        </button>

                                        <div class="modal fade" id="delThingConf<?= $fday->id; ?>" tabindex="-1" aria-labelledby="delThingConfLabel<?= $fday->id; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="delThingConfLabel<?= $fday->id; ?>">تأكيد حذف بيانات</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        تأكيد حذف بيانات
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                        <a href="<?= base_url('fdays/deletingFDay/' . $fday->id); ?>" class="btn btn-primary">حــــذف</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php $i++; } ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="vfp" tabindex="-1" aria-labelledby="vfpLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= base_url('cpanel/fingerPrintForEmp'); ?>" method="get" target="_blank">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vfpLabel">عرض بصمة موظف</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>

                    <div class="modal-body">
                        <?php
                            $current_year = date("Y");
                            $start_year = ($current_year - 5);
                            $end_year = ($current_year + 5);
                        ?>
                        <label style="color: black;">من</label>
                        <div class="row row-cols-2">
                            <div class="col mb-2">
                                <label for="from_month" style="color: black;">اختر الشهر</label>
                                <select name="from_month" id="from_month" class="form-control">
                                    <?php
                                        $months = [
                                            'January' => 'يناير', 'February' => 'فبراير', 'March' => 'مارس',
                                            'April' => 'أبريل', 'May' => 'مايو', 'June' => 'يونيو',
                                            'July' => 'يوليو', 'August' => 'أغسطس', 'September' => 'سبتمبر',
                                            'October' => 'أكتوبر', 'November' => 'نوفمبر', 'December' => 'ديسمبر'
                                        ];
                                        foreach ($months as $en => $ar) {
                                            echo "<option value=\"$en\">$ar</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col mb-2">
                                <label for="from_year" style="color: black;">اختر السنة</label>
                                <select name="from_year" id="from_year" class="form-control">
                                    <option value="">اختر السنة</option>
                                    <?php
                                        for ($i = $start_year; $i < $end_year; $i++) {
                                            $selected = (date('Y') == $i) ? "selected" : "";
                                            echo "<option value=\"$i\" $selected>$i</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <label style="color: black;">إلى</label>
                        <div class="row row-cols-2">
                            <div class="col mb-2">
                                <label for="to_month" style="color: black;">اختر الشهر</label>
                                <select name="to_month" id="to_month" class="form-control">
                                    <?php
                                        foreach ($months as $en => $ar) {
                                            echo "<option value=\"$en\">$ar</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col mb-2">
                                <label for="to_year" style="color: black;">اختر السنة</label>
                                <select name="to_year" id="to_year" class="form-control">
                                    <option value="">اختر السنة</option>
                                    <?php
                                        for ($i = $start_year; $i < $end_year; $i++) {
                                            $selected = (date('Y') == $i) ? "selected" : "";
                                            echo "<option value=\"$i\" $selected>$i</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col mb-2">
                                <label for="db_table_fp" style="color: black;">البصمة (قبل - بعد)</label>
                                <select name="db_table" id="db_table_fp" class="form-control">
                                    <option value="before">البصمة (قبل)</option>
                                    <option value="after">البصمة (بعد)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" id="emp_id" name="emp_id" value="<?= $row->id; ?>" />
                        <button type="submit" class="btn btn-primary">عـــرض</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<?= $this->section('script'); ?>
		<script>
			$(document).ready(function () {
				function countDays(startDate, endDate) {
					const dt1 = new Date(startDate);
					const dt2 = new Date(endDate);
					return Math.floor((Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate()) - Date.UTC(dt1.getFullYear(), dt1.getMonth(), dt1.getDate())) / (1000 * 60 * 60 * 24)) + 1;
				}

				function countFridays(startDate, endDate) {
					let count = 0;
					const curDate = new Date(startDate.getTime());
					while (curDate <= endDate) {
						const dayOfWeek = curDate.getDay();
						if (dayOfWeek === 5) count++;
						curDate.setDate(curDate.getDate() + 1);
					}
					return count;
				}

				function calculate() {
					const days = countDays($('#lbegin').val(), $('#lend').val());
					const fridays = countFridays(new Date($('#lbegin').val()), new Date($('#lend').val()));
					// Replaced alert with a custom message box or console log, as alerts are not allowed.
					// For a real application, you'd implement a Bootstrap modal or similar for user feedback.
					console.log("Days: " + days + ", Fridays: " + fridays);
				}

				// The original code had an alert, which is not allowed.
				// If you need to display this information to the user, you should implement a custom modal or display it directly on the page.
				// For now, I've commented out the original alert and added a console.log.
				// $('#calculate').on('click', function () {
				// 	calculate();
				// });
				// $('[data-bs-toggle="modal"]').on('click', function(event) { event.stopPropagation(); });

			});
		</script>
<?= $this->endSection(); ?>

<?= $this->endSection(); ?>
