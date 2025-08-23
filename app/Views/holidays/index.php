<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?><?= $pageTitle ?? 'العطلات'; ?><?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container-fluid pt-6">

    <div class="row mb-4">
        <div class="col-12">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newHolidayModal">
                <i class="fas fa-plus-circle me-2"></i> تسجيل عطلة جديدة
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">قائمة العطلات</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($holidays)): ?>
                        <div class="alert alert-info text-center" role="alert">
                            لا توجد عطلات مسجلة حالياً.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover display" id="Holidays_table">
                                <thead class="table-light">
                                    <tr> <th scope="col" style="width: 5%;">#</th>
                                        <th scope="col" style="width: 15%;">بداية العطلة</th>
                                        <th scope="col" style="width: 15%;">نهاية العطلة</th>
                                        <th scope="col" style="width: 25%;">اسم العطلة</th>
                                        <th scope="col" style="width: 30%;">الوصف</th>
                                        <th scope="col" style="width: 10%;">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach ($holidays as $holiday): ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $holiday->start_date; ?></td>
                                            <td><?= $holiday->end_date; ?></td>
                                            <td><?= $holiday->name; ?></td>
                                            <td><?= !empty($holiday->descrp) ? $holiday->descrp : 'لا يوجد وصف'; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-secondary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#updateHolidayModal<?= $holiday->id; ?>">
                                                    <i class="fas fa-edit"></i> تعديل
                                                </button>

                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteHolidayConfirmModal<?= $holiday->id; ?>">
                                                    <i class="fas fa-trash-alt"></i> حذف
                                                </button>

                                                <div class="modal fade" id="updateHolidayModal<?= $holiday->id; ?>" tabindex="-1" aria-labelledby="updateHolidayModalLabel<?= $holiday->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="<?= base_url('holidays/update'); ?>" method="post">
                                                                <div class="modal-header bg-secondary text-white">
                                                                    <h5 class="modal-title" id="updateHolidayModalLabel<?= $holiday->id; ?>">تعديل عطلة</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body"> <input type="hidden" name="holiday_id" value="<?= $holiday->id; ?>">
                                                                    <div class="mb-3">
                                                                        <label for="update_start_date<?= $holiday->id; ?>" class="form-label modal_label">بداية العطلة:</label> <input type="date" class="form-control" id="update_start_date<?= $holiday->id; ?>" name="start_date" value="<?= $holiday->start_date; ?>" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="update_end_date<?= $holiday->id; ?>" class="form-label modal_label">نهاية العطلة:</label> <input type="date" class="form-control" id="update_end_date<?= $holiday->id; ?>" name="end_date" value="<?= $holiday->end_date; ?>" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="update_name<?= $holiday->id; ?>" class="form-label modal_label">اسم العطلة:</label> <input type="text" class="form-control" id="update_name<?= $holiday->id; ?>" name="name" value="<?= $holiday->name; ?>" placeholder="اسم العطلة" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="update_descrp<?= $holiday->id; ?>" class="form-label modal_label">الوصف:</label> <textarea class="form-control" id="update_descrp<?= $holiday->id; ?>" name="descrp" rows="3" placeholder="وصف العطلة (اختياري)"><?= $holiday->descrp; ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="deleteHolidayConfirmModal<?= $holiday->id; ?>" tabindex="-1" aria-labelledby="deleteHolidayConfirmModalLabel<?= $holiday->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title" id="deleteHolidayConfirmModalLabel<?= $holiday->id; ?>">تأكيد الحذف</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                هل أنت متأكد أنك تريد حذف العطلة "<strong><?= $holiday->name; ?></strong>" من تاريخ <strong><?= $holiday->start_date; ?></strong> إلى <strong><?= $holiday->end_date; ?></strong>؟ هذا الإجراء لا يمكن التراجع عنه.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                                <form action="<?= base_url('holidays/delete'); ?>" method="post" style="display:inline;">
                                                                    <input type="hidden" name="holiday_id" value="<?= $holiday->id; ?>">
                                                                    <button type="submit" class="btn btn-danger">حذف</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newHolidayModal" tabindex="-1" aria-labelledby="newHolidayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('holidays/create'); ?>" method="post">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="newHolidayModalLabel">تسجيل عطلة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
					<div class="mb-3 text-right">
                        <label for="new_start_date" class="form-label modal_label">بداية العطلة:</label>
						<input type="date" class="form-control" id="new_start_date" name="start_date" required>
                    </div>
                    <div class="mb-3 text-right">
                        <label for="new_end_date" class="form-label modal_label">نهاية العطلة:</label>
						<input type="date" class="form-control" id="new_end_date" name="end_date" required>
                    </div>
                    <div class="mb-3 text-right">
                        <label for="new_name" class="form-label modal_label">اسم العطلة:</label>
						<input type="text" class="form-control" id="new_name" name="name" placeholder="مثال: عيد الفطر" required>
                    </div>
                    <div class="mb-3 text-right">
                        <label for="new_descrp" class="form-label modal_label">الوصف:</label>
						<textarea class="form-control" id="new_descrp" name="descrp" rows="3" placeholder="وصف موجز للعطلة (اختياري)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تسجيل</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>