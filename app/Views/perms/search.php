<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?><?= $pageTitle ?? 'احصائيات الاذونات'; ?><?= $this->endSection(); ?>

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
    <form action="<?= base_url('fdays'); ?>" method="get">

        <div id="repSelects" class="row no-print repSelects">

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
                    <label for="from">من</label>
                    <input type="date" class="form-control" id="from" name="from" value="<?= $filters['from'] ?? ''; ?>">
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="to">إلى</label>
                    <input type="date" class="form-control" id="to" name="to" value="<?= $filters['to'] ?? ''; ?>">
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="arrange_by">ترتيب النتائج</label>
                    <select class="form-control" id="arrange_by" name="arrange_by">
                        <option value="undefined" <?= !isset($filters['arrange_by']) || $filters['arrange_by'] === "undefined" ? 'selected' : ''; ?>>غير محدد</option>
                        <option value="date" <?= isset($filters['arrange_by']) && $filters['arrange_by'] === "date" ? 'selected' : ''; ?>>التاريخ</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="arrange_order">تصاعدي / تنازلي</label>
                    <select class="form-control" id="arrange_order" name="arrange_order">
                        <option value="undefined" <?= !isset($filters['arrange_order']) || $filters['arrange_order'] === "undefined" ? 'selected' : ''; ?>>غير محدد</option>
                        <option value="asc" <?= isset($filters['arrange_order']) && $filters['arrange_order'] === 'asc' ? 'selected' : ''; ?>>تصاعدي</option>
                        <option value="desc" <?= isset($filters['arrange_order']) && $filters['arrange_order'] === 'desc' ? 'selected' : ''; ?>>تنازلي</option>
                    </select>
                </div>
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
                        <a href="<?= base_url('fdays/exportToExcel?' . http_build_query($filters)); ?>" class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i> تحميل Excel
                        </a>
                    </div>

                    
                </div>
            </div>

    </form>

    <div class="row">
        <div class="col" style="text-align: right; font-size: 12pt; color: white;">
            عدد نتائج البحث : <?= $result['total'] ?? 0; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12" id="srchRes_mobile">
            <?php if (isset($result['pagedFdays'])) : ?>
                <?php foreach ($result['pagedFdays'] as $row) : ?>
                    <div style="background: #eee; margin: 10px 0px; border-radius: 5px; text-align: right; padding: 10px;">
                        <a href="<?= base_url('employees/display/' . $row->emp_id); ?>" style="text-decoration: none;">
                            <?= $row->emp_name_english; ?><br />
                        </a>
                        <?= $row->emp_file_no; ?><br />
                        <?= $row->emp_sec_name_arabic ?? ''; ?><br />
                        <?= $row->emp_sub_sec_name_arabic ?? ''; ?><br />
                        <?= $row->date; ?><br />
                        <?= $row->remarks; ?>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No results found.</p>
            <?php endif; ?>
        </div>

        <table class="table table-striped table-hover display" id="srchRes_desktop" style="direction: rtl;">
            <thead>
                <tr>
                    <th scope="col" style="width: 5%; text-align: center;">#</th>
                    <th scope="col" style="width: 20%; text-align: center;">الأسم</th>
                    <th scope="col" style="width: 15%; text-align: center">الوظيفة</th>
                    <th scope="col" style="width: 10%; text-align: center;">رقم الملف</th>
                    <th scope="col" style="width: 15%; text-align: center;">التاريخ</th>
                    <th scope="col" style="width: 15%; text-align: center;">المراقبة</th>
                    <th scope="col" style="width: 10%; text-align: center;">القسم</th>
                    <th scope="col" style="width: 10%; text-align: center;">ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($result['pagedFdays'])) : ?>
                    <?php $i = 1;
                    foreach ($result['pagedFdays'] as $row) : ?>
                        <tr>
                            <td scope="col"><?= $i++; ?></td>
                            <td scope="col" class="en_text">
                                <a href="<?= base_url('employees/display/' . $row->emp_id); ?>" style="text-decoration: none;">
                                    <?= $row->emp_name_english; ?>
                                </a>
                            </td>
                            <td scope="col"><?= $row->emp_design_name; ?></td>
                            <td scope="col"><?= $row->emp_file_no; ?></td>
                            <td scope="col"><?= $row->date; ?></td>
                            <td scope="col"><?= $row->emp_sec_name_arabic ?? ''; ?></td>
                            <td scope="col"><?= $row->emp_sub_sec_name_arabic ?? ''; ?></td>
                            <td scope="col"><?= $row->remarks; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center">No results found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-12 text-center">
            <?php if (isset($result['total']) && $result['total'] > 0) : ?>
                <p>Showing <?= ($result['page'] - 1) * $result['perPage'] + 1 ?> to <?= min($result['total'], $result['page'] * $result['perPage']) ?> of <?= $result['total'] ?> entries</p>
                <?php if ($result['hasMore']) : ?>
                    <a href="<?= base_url('fdays?' . http_build_query(array_merge($filters, ['page' => $result['page'] + 1]))) ?>" class="btn btn-primary">Load More</a>
                <?php endif; ?>
            <?php else : ?>
                <p>No results found</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        console.log("Document ready!");

        <?php if (session('type') == "admin" || session('type') == "depart") : ?>
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
                    data: {
                        sec_id: sec_id_param
                    },
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

        <?php else : ?>
            console.log("User is not admin or depart. Sub-section loading will not be active.");
        <?php endif; ?>
    });
</script>

<?= $this->endSection(); ?>