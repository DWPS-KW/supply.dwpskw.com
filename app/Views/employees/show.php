
<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?><?= $pageTitle ?><?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container-fluid pt-6">

<input type="hidden" name="overlap_check_field" value="1" />

    <div class="card shadow-sm rounded mb-4">
        <div class="card-header bg-light d-flex justify-content-between">
            <span>إجراءات الموظف</span>
            <button class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#actionsSection"
                aria-expanded="false" aria-controls="actionsSection">
                عرض/إخفاء الإجراءات
            </button>
        </div>

        <div class="collapse" id="actionsSection">
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <button class="btn btn-outline-dark w-100" data-bs-toggle="modal" data-bs-target="#newL">
                            تسجيل إجازة
                        </button>
                    </div>

                    <div class="col-md-4">
                        <button class="btn btn-outline-dark w-100" data-bs-toggle="modal" data-bs-target="#newMed">
                            تسجيل نموذج علاج
                        </button>
                    </div>

                    <div class="col-md-4">
                        <button class="btn btn-outline-dark w-100" data-bs-toggle="modal"
                            data-bs-target="#newFdayPerm">
                            تسجيل إذن يوم كامل
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>


    <div class="modal fade" id="newL" tabindex="-1" aria-labelledby="newLLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('leaves/create'); ?>" method="post" class="modal-content">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title emp_info_title_color" id="newLLabel">تسجيل إجازة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title emp_info_title_color" id="newMedLabel">تسجيل نموذج علاج</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="emp_id" value="<?= $row->id; ?>">
                    <div class="form-group text-right mb-3">
                        <label for="start_date_med" class="emp_info_title_color">تاريخ البدء</label>
                        <input type="date" name="start_date" id="start_date_med" class="form-control" required>
                    </div>
                    <div class="form-group text-right mb-3">
                        <label for="end_date_med" class="emp_info_title_color">تاريخ الانتهاء</label>
                        <input type="date" name="end_date" id="end_date_med" class="form-control" required>
                    </div>
                    <div class="form-group text-right mb-3">
                        <label for="descrp" class="emp_info_title_color">التشخيص</label>
                        <input type="text" name="descrp" id="descrp" class="form-control" maxlength="255">
                    </div>
                    <div class="form-group text-right mb-3">
                        <label for="remarks_med" class="emp_info_title_color">ملاحظات</label>
                        <textarea name="remarks" id="remarks_med" rows="3" class="form-control" maxlength="255"></textarea>
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
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title emp_info_title_color" id="newFdayPermLabel">تسجيل إذن يوم كامل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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


    <div class="card mb-3 justify-content-center align-items-center"
        style="text-align: center; width: 80%; max-width: 700px;">
        <div class="row no-gutters">
            <div class="col-md-3 col-sm-3">

                <img src="<?= base_url($row->photo) ?>" class="card-img" id="emp_photo" alt="<?= esc($row->name_english); ?>">

            </div>

            <div class="col-md-9 col-sm-9">
                <div class="card-body">


                    <h2 class="text-primary"><?= $pageTitle ?></h2>
                    <?php if (session()->get("type") == "admin" || session()->get("type") == "depart"): ?>
                        <a href="<?= base_url('employees/edit/' . $row->id); ?>" class="btn btn-warning">تحديث البيانات</a>
                    <?php endif; ?>

                    <button type="button" class="btn btn-outline-dark" style="margin: 10px;" data-bs-toggle="modal"
                        data-bs-target="#allL">
                        عدد الاجازات <span class="badge border border-dark text-dark bg-light"><?= count($allLeaves); ?></span>
                    </button>

                    <!-- <div class="modal fade" id="allL" tabindex="-1" aria-labelledby="allLLabel" style="display: none;" inert> -->
                    <div class="modal fade" id="allL" tabindex="-1" aria-labelledby="allLLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header d-flex align-items-center">
                                    <h5 class="modal-title" id="allLLabel">سجل الاجازات</h5>
                                    <div class="mx-auto">
                                        <button class="btn btn-outline-dark" data-bs-toggle="modal"
                                            data-bs-target="#newL">
                                            تسجيل إجازة
                                        </button>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
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
                                            <?php $i = 1;
                                            foreach ($allLeaves as $leave): ?>
                                                <tr>
                                                    <th><?= $i; ?></th>
                                                    <td><?= $leave->duration; ?></td>
                                                    <td><?= $leave->begin; ?></td>
                                                    <td><?= $leave->end; ?></td>
                                                    <td><?= $leave->remarks; ?></td>
                                                    <td>
                                                        <?php if (session()->get("type") == "admin" || session()->get("type") == "depart"): ?>
															
														<button type="button" class="btn btn-secondary btn-sm edit-leave-btn"
															data-bs-toggle="modal" data-bs-target="#editLeaveModal"
															data-id="<?= $leave->id ?>" data-emp_id="<?= $leave->emp_id ?>"
															data-begin="<?= $leave->begin ?>" data-end="<?= $leave->end ?>"
															data-remarks="<?= $leave->remarks ?>"
															data-action="<?= base_url('leaves/update') ?>">
															تعــديل
														</button>

                                                            <form method="POST"
                                                                action="<?= site_url('leaves/delete') ?>"
                                                                id="deleteLeaveForm<?= $leave->id ?>" style="display:inline;">
                                                                <?= csrf_field() ?>
                                                                <input type="hidden" name="id" value="<?= $leave->id ?>">
                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                    onclick="confirmDelete('leave', <?= $leave->id ?>)">حذف</button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php $i++;
                                            endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editLeaveModal" tabindex="-1" aria-labelledby="editLeaveModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editLeaveForm" method="post">
                                    <?= csrf_field() ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editLeaveModalLabel">تعديل اجازة</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group text-right">
                                            <label for="modal_begin">من</label>
                                            <input type="date" class="form-control" name="begin" id="modal_begin">
                                        </div>
                                        <div class="form-group text-right">
                                            <label for="modal_end">إلى</label>
                                            <input type="date" class="form-control" name="end" id="modal_end">
                                        </div>
                                        <div class="form-group text-right">
                                            <label for="modal_remarks">ملاحظــات</label>
                                            <textarea class="form-control" name="remarks" id="modal_remarks" rows="3"></textarea>
                                        </div>
                                        <input type="hidden" name="id" id="modal_id">
                                        <input type="hidden" name="emp_id" id="modal_emp_id">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">تعديل</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <button type="button" class="btn btn-outline-dark" style="margin: 10px;" data-bs-toggle="modal"
                        data-bs-target="#allMed">
                        عدد الطبيات <span class="badge border border-dark text-dark bg-light"><?= count($allMeds); ?></span>
                    </button>

                    <div class="modal fade" id="allMed" tabindex="-1" aria-labelledby="allMedLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="allMedLabel">سجل الطبيات</h5>
                                    <div class="mx-auto">
                                        <button class="btn btn-outline-dark w-100" data-bs-toggle="modal"
                                            data-bs-target="#newMed">
                                            تسجيل نموذج علاج
                                        </button>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">تاريخ البدء</th>
                                                <th scope="col">المدة</th>
                                                <th scope="col">تاريخ الإنتهاء</th>
                                                <th scope="col">التشخيص</th>
                                                <th scope="col">ملاحظات</th>
                                                <th scope="col">إعدادات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1;
                                            foreach ($allMeds as $med): ?>
                                                <tr>
                                                    <th scope="row"><?= $i; ?></th>
                                                    <td><?= esc($med->start_date); ?></td>
                                                    <td><?= esc($med->duration); ?></td>
                                                    <td><?= esc($med->end_date); ?></td>
                                                    <td><?= esc($med->descrp); ?></td>
                                                    <td><?= esc($med->remarks); ?></td>
                                                    <td>
                                                        <?php if (session()->get("type") == "admin" || session()->get("type") == "depart"): ?>
                                                            <button type="button" class="btn btn-secondary btn-sm edit-medical-btn"
                                                                data-bs-toggle="modal" data-bs-target="#editMedicalModal"
                                                                data-id="<?= esc($med->id); ?>"
                                                                data-start_date="<?= esc($med->start_date); ?>"
                                                                data-end_date="<?= esc($med->end_date); ?>"
                                                                data-descrp="<?= esc($med->descrp, 'attr'); ?>"
                                                                data-remarks="<?= esc($med->remarks, 'attr'); ?>"
                                                                data-emp_id="<?= esc($med->emp_id); ?>"
                                                                data-action="<?= base_url('medicals/update') ?>">
                                                                تعــديل
                                                            </button>

                                                            <form id="deleteMedForm<?= $med->id ?>"
                                                                action="<?= base_url('medicals/delete') ?>" method="post"
                                                                style="display:inline;">
                                                                <?= csrf_field() ?>
                                                                <input type="hidden" name="id" value="<?= $med->id ?>">
                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                    onclick="confirmDelete('medical', <?= $med->id ?>)">حـــذف</button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php $i++;
                                            endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editMedicalModal" tabindex="-1" aria-labelledby="editMedicalModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editMedicalForm" method="post">
                                    <?= csrf_field() ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editMedicalModalLabel">تعديل طبية</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group text-right">
                                            <label for="modal_med_start_date">تاريخ البدء</label>
                                            <input type="date" class="form-control" id="modal_med_start_date" name="start_date">
                                        </div>
                                        <div class="form-group text-right">
                                            <label for="modal_med_end_date">تاريخ الإنتهاء</label>
                                            <input type="date" class="form-control" id="modal_med_end_date" name="end_date">
                                        </div>
                                        <div class="form-group text-right">
                                            <label for="modal_med_descrp">التشخيص</label>
                                            <input type="text" class="form-control" id="modal_med_descrp" name="descrp">
                                        </div>
                                        <div class="form-group text-right">
                                            <label for="modal_med_remarks">ملاحظــات</label>
                                            <textarea class="form-control" id="modal_med_remarks" name="remarks" rows="3"></textarea>
                                        </div>
                                        <input type="hidden" name="id" id="modal_med_id">
                                        <input type="hidden" name="emp_id" id="modal_med_emp_id">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">تعديل</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <button type="button" class="btn btn-outline-dark" style="margin: 10px;" data-bs-toggle="modal"
                        data-bs-target="#allFdays">
                        عدد اذونات اليوم الكامل <span class="badge border border-dark text-dark bg-light"><?= count($allFdays); ?></span>
                    </button>

                    <div class="modal fade" id="allFdays" tabindex="-1" aria-labelledby="allFdaysLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="allFdaysLabel">سجل الاذونات اليوم الكامل</h5>
                                    <div class="mx-auto">
                                        <button class="btn btn-outline-dark" data-bs-toggle="modal"
                                            data-bs-target="#newFdayPerm">
                                            تسجيل إذن يوم كامل
                                        </button>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="إغلاق"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">التاريخ</th>
                                                <th scope="col">ملاحظات</th>
                                                <th scope="col">إعدادات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1;
                                            foreach ($allFdays as $fday) { ?>
                                                <tr>
                                                    <th scope="row"><?= $i; ?></th>
                                                    <td><?= esc($fday->date); ?></td>
                                                    <td><?= esc($fday->remarks); ?></td>
                                                    <td>
                                                        <?php if (session()->get("type") == "admin" || session()->get("type") == "depart") { ?>
                                                            <button type="button" class="btn btn-secondary btn-sm edit-fday-btn"
                                                                data-bs-toggle="modal" data-bs-target="#editFdayModal"
                                                                data-id="<?= esc($fday->id); ?>"
                                                                data-date="<?= esc($fday->date); ?>"
                                                                data-remarks="<?= esc($fday->remarks); ?>"
                                                                data-emp_id="<?= esc($fday->emp_id); ?>"
                                                                data-action="<?= base_url('fdays/update') ?>">
                                                                تعــديل
                                                            </button>

                                                            <form id="deleteFdayForm<?= $fday->id ?>"
                                                                action="<?= base_url('fdays/delete') ?>" method="post"
                                                                style="display:inline;">
                                                                <?= csrf_field() ?>
                                                                <input type="hidden" name="id" value="<?= $fday->id ?>">
                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                    onclick="confirmDelete('fday', <?= $fday->id ?>)">حـــذف</button>
                                                            </form>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php $i++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editFdayModal" tabindex="-1" aria-labelledby="editFdayModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editFdayForm" method="post">
                                    <?= csrf_field() ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editFdayModalLabel">تعديل اذن يوم كامل</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="إغلاق"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group text-right">
                                            <label for="modal_fday_date">التاريخ</label>
                                            <input type="date" class="form-control" id="modal_fday_date" name="date">
                                        </div>
                                        <div class="form-group text-right">
                                            <label for="modal_fday_remarks">ملاحظــات</label>
                                            <textarea class="form-control" id="modal_fday_remarks" name="remarks" rows="3"></textarea>
                                        </div>
                                        <input type="hidden" name="id" id="modal_fday_id">
                                        <input type="hidden" name="emp_id" id="modal_fday_emp_id">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">تعديل</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <button type="button" class="btn btn-outline-dark" style="margin: 10px;" data-bs-toggle="modal"
                        data-bs-target="#vfp">
                        عرض بصمة
                    </button>

                    <div class="modal fade" id="vfp" tabindex="-1" aria-labelledby="vfpLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="<?= base_url('attendance/fingerPrintForEmp'); ?>" method="get" target="_blank">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="vfpLabel">عرض بصمة موظف</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="إغلاق"></button>
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

            </div>
        </div>
    </div>

    <div class="row row-clear">
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="name_arabic">الاسم - عربي</label>
                <input type="text" class="form-control" id="name_arabic" name="name_arabic"
                    value="<?= esc($row->name_arabic); ?>" placeholder="اسم الموظف بالعربي" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="name_english">الاسم - انجليزي</label>
                <input type="text" class="form-control en" id="name_english" name="name_english"
                    value="<?= esc($row->name_english); ?>" placeholder="Full name English" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="file_no">رقم الملف</label>
                <input type="text" class="form-control" id="file_no" name="file_no" value="<?= esc($row->file_no); ?>"
                    placeholder="File No" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="civil_id">الرقم المدني</label>
                <input type="text" class="form-control" id="civil_id" name="civil_id"
                    value="<?= esc($row->civil_id); ?>" placeholder="Civil ID" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="mobile">الموبايل</label>
                <input type="text" class="form-control" id="mobile" name="mobile" value="<?= esc($row->mobile); ?>"
                    placeholder="Mobile" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="gender">الجنس</label>
                <?php
                $genderDisplay = ($row->gender == 'male') ? 'ذكر' : (($row->gender == 'female') ? 'أنثى' : 'غير محدد');
                ?>
                <input type="text" class="form-control" id="gender" name="gender" value="<?= $genderDisplay; ?>"
                    placeholder="Gender" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="nation">الجنسية</label>
                <input type="text" class="form-control" id="nation" name="nation" value="<?= esc($row->nation); ?>"
                    placeholder="Nationality" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="birth_date">تاريخ الميلاد</label>
                <input type="text" class="form-control" id="birth_date" name="birth_date"
                    value="<?= esc($row->birth_date); ?>" placeholder="Birth Date" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="edu_cert">المؤهل العلمي</label>
                <input type="text" class="form-control" id="edu_cert" name="edu_cert"
                    value="<?= esc($row->edu_cert); ?>" placeholder="Education Certificate" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="design">الوظيفة</label>
                <input type="text" class="form-control en" id="design" name="design"
                    value="<?= esc($designModel->getDesignNameById($row->design_id)); ?>" placeholder="Designation" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="join_date">تاريخ التعيين</label>
                <input type="text" class="form-control" id="join_date" name="join_date"
                    value="<?= esc($row->join_date); ?>" placeholder="Join Date" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="termination_date">تاريخ إنهاء الخدمة</label>
                <input type="text" class="form-control" id="termination_date" name="termination_date"
                    value="<?= esc($row->termination_date) ?? 'مازال بالخدمة'; ?>"
                    placeholder="Termination Date" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="termination_reason">سبب إنهاء الخدمة</label>
                <input type="text" class="form-control" id="termination_reason" name="termination_reason"
                    value="<?= esc($row->termination_reason); ?>" placeholder="" readonly>
            </div>
        </div>

        <?php if (session()->get("type") == "admin" || session()->get("type") == "depart") { ?>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="has_overtime">إضافي</label>
                    <input type="text" class="form-control" id="has_overtime" name="has_overtime"
                        value="<?= ($row->has_overtime == 1) ? "مسموح" : "غير مسموح"; ?>" placeholder="has_overtime" readonly>
                </div>
            </div>
        <?php } ?>

        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="salary">الراتب</label>
                <input type="text" class="form-control" id="salary" name="salary"
                    value="<?= esc($designModel->find($row->design_id)->total_salary); ?>" placeholder="Salary" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="sec_id">المراقبة</label>
                <input type="text" class="form-control" id="sec_id" name="sec_id"
                    value="<?= ($row->sec_id && $secModel->find($row->sec_id)) ? esc($secModel->find($row->sec_id)->name_arabic) : 'غير محدد'; ?>"
                    placeholder="Department" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="sub_sec_id">القسم</label>
                <input type="text" class="form-control" id="sub_sec_id" name="sub_sec_id"
                    value="<?= ($row->sub_sec_id && $subSecModel->find($row->sub_sec_id)) ? esc($subSecModel->find($row->sub_sec_id)->name_arabic) : 'غير محدد'; ?>"
                    placeholder="Section" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="experience">الخبرة</label>
                <input type="text" class="form-control" id="experience" name="experience"
                    value="<?= esc($row->experience); ?>" placeholder="Experience" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="permanent">نوع التوظيف</label>
                <input type="text" class="form-control" id="permanent" name="permanent"
                    value="<?= $row->permanent ? 'دائم' : 'مؤقت'; ?>" placeholder="Employment Type" readonly>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="form-group text-right">
                <label for="active">بالخدمة</label>
                <input type="text" class="form-control" id="active" name="active"
                    value="<?= $row->active ? 'بالخدمة' : 'ليس بالخدمة'; ?>" placeholder="Status" readonly>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group text-right">
                <label for="remarks">ملاحظات</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3" readonly><?= esc($row->remarks); ?></textarea>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {


        // Handle general success messages
        <?php if (session()->getFlashdata('success')) : ?>
            alert('<?= session()->getFlashdata('success') ?>');
        <?php endif; ?>

        // Handle general error messages (e.g., database failure, record not found)
        <?php if (session()->getFlashdata('error')) : ?>
            alert('<?= session()->getFlashdata('error') ?>');
        <?php endif; ?>

        // Handle validation errors (array of messages)
        <?php if (session()->getFlashdata('errors')) : ?>
            var errors = <?= json_encode(session()->getFlashdata('errors')) ?>;
            var errorMessage = "Leave creation failed due to the following reasons:\n";
            var hasErrors = false;

            for (var field in errors) {
                if (errors.hasOwnProperty(field)) {
                    errorMessage += "- " + errors[field] + "\n";
                    hasErrors = true;
                }
            }

            if (hasErrors) {
                alert(errorMessage);
            }
        <?php endif; ?>


        console.log("DOM Content Loaded - show.php script started.");

        // --- Leave Modal Handler ---
        const editLeaveModal = document.getElementById('editLeaveModal');
        if (editLeaveModal) {
            editLeaveModal.addEventListener('show.bs.modal', function (event) {
                console.log("Leave edit modal is about to be shown.");
                const button = event.relatedTarget; // Button that triggered the modal

                // Extract info from data attributes
                const id = button.getAttribute('data-id');
                const emp_id = button.getAttribute('data-emp_id');
                const begin = button.getAttribute('data-begin');
                const end = button.getAttribute('data-end');
                const remarks = button.getAttribute('data-remarks');
                const action = button.getAttribute('data-action');

                console.log("Leave data:", { id, emp_id, begin, end, remarks, action });

                // Populate modal fields
                document.getElementById('modal_id').value = id ?? '';
                document.getElementById('modal_emp_id').value = emp_id ?? '';
                document.getElementById('modal_begin').value = begin ?? '';
                document.getElementById('modal_end').value = end ?? '';
                document.getElementById('modal_remarks').value = remarks ?? '';
                document.getElementById('editLeaveForm').action = action ?? '';
            });
        }

        // --- Medical Modal Handler ---
        const editMedicalModal = document.getElementById('editMedicalModal');
        if (editMedicalModal) {
            editMedicalModal.addEventListener('show.bs.modal', function (event) {
                console.log("Medical edit modal is about to be shown.");
                const button = event.relatedTarget; // Button that triggered the modal

                // Extract info from data attributes
                const id = button.getAttribute('data-id');
                const emp_id = button.getAttribute('data-emp_id');
                const start_date = button.getAttribute('data-start_date');
                const end_date = button.getAttribute('data-end_date');
                const descrp = button.getAttribute('data-descrp');
                const remarks = button.getAttribute('data-remarks');
                const action = button.getAttribute('data-action');

                console.log("Medical data:", { id, emp_id, start_date, end_date, descrp, remarks, action });

                // Update the modal's content.
                document.getElementById('modal_med_id').value = id ?? '';
                document.getElementById('modal_med_emp_id').value = emp_id ?? '';
                document.getElementById('modal_med_start_date').value = start_date ?? '';
                document.getElementById('modal_med_end_date').value = end_date ?? '';
                document.getElementById('modal_med_descrp').value = descrp ?? '';
                document.getElementById('modal_med_remarks').value = remarks ?? '';
                document.getElementById('editMedicalForm').action = action ?? '';
            });
        }

        // --- Fday Modal Handler ---
        const editFdayModal = document.getElementById('editFdayModal');
        if (editFdayModal) {
            editFdayModal.addEventListener('show.bs.modal', function (event) {
                console.log("Fday edit modal is about to be shown.");
                const button = event.relatedTarget; // Button that triggered the modal

                // Extract info from data attributes
                const id = button.getAttribute('data-id');
                const emp_id = button.getAttribute('data-emp_id');
                const date = button.getAttribute('data-date');
                const remarks = button.getAttribute('data-remarks');
                const action = button.getAttribute('data-action');

                console.log("Fday data:", { id, emp_id, date, remarks, action });

                // Update the modal's content.
                document.getElementById('modal_fday_id').value = id ?? '';
                document.getElementById('modal_fday_emp_id').value = emp_id ?? '';
                document.getElementById('modal_fday_date').value = date ?? '';
                document.getElementById('modal_fday_remarks').value = remarks ?? '';
                document.getElementById('editFdayForm').action = action ?? '';
            });
        }

        // --- Unified Confirm Delete Function ---
        window.confirmDelete = function (type, id) {
            let message = "هل أنت متأكد من حذف هذا السجل؟";
            let formId;

            switch (type) {
                case 'leave':
                    formId = "deleteLeaveForm" + id;
                    message = "هل أنت متأكد من حذف سجل الإجازة هذا؟";
                    break;
                case 'medical':
                    formId = "deleteMedForm" + id;
                    message = "هل أنت متأكد من حذف السجل الطبي هذا؟";
                    break;
                case 'fday':
                    formId = "deleteFdayForm" + id;
                    message = "هل أنت متأكد من حذف إذن اليوم الكامل هذا؟";
                    break;
                default:
                    console.error("Unknown delete type:", type);
                    return;
            }

            if (confirm(message)) {
                document.getElementById(formId).submit();
            }
        };
    });
</script>
<?= $this->endSection(); ?>
