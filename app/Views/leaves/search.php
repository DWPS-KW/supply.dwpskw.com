<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?><?= $pageTitle ?? 'احصائيات الإجازات'; ?><?= $this->endSection(); ?>

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

    /* Basic styling for pagination */
    .pagination {
        display: flex;
        justify-content: center;
        padding: 20px 0;
        list-style: none;
    }

    .pagination li {
        margin: 0 5px;
    }

    .pagination li a,
    .pagination li span {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #007bff;
        background-color: #fff;
    }

    .pagination li.active a,
    .pagination li.active span {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .pagination li.disabled a,
    .pagination li.disabled span {
        color: #6c757d;
        pointer-events: none;
        background-color: #e9ecef;
    }
</style>

<div class="container-fluid pt-6">
    <form action="<?= base_url('leaves'); ?>" method="get">

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
                    <input type="date" class="form-control" id="from" name="from" placeholder="From"
                               value="<?= isset($from) ? $from : ''; ?>">
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="to">إلى</label>
                    <input type="date" class="form-control" id="to" name="to" placeholder="To"
                               value="<?= isset($to) ? $to : ''; ?>">
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="arrange_by">ترتيب النتائج</label>
                    <select class="form-control" id="arrange_by" name="arrange_by">
                        <option value="undefined" <?= !isset($arrange_by) || $arrange_by === 'undefined' ? 'selected' : ''; ?>>غير
                            محدد
                        </option>
                        <option value="begin" <?= isset($arrange_by) && $arrange_by === 'begin' ? 'selected' : ''; ?>>التاريخ
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <label for="arrange_order">تصاعدي / تنازلي</label>
                    <select class="form-control" id="arrange_order" name="arrange_order">
                        <option value="undefined" <?= !isset($arrange_order) || $arrange_order === 'undefined' ? 'selected' : ''; ?>>
                            غير محدد
                        </option>
                        <option value="asc" <?= isset($arrange_order) && $arrange_order === 'asc' ? 'selected' : ''; ?>>
                            تصاعدي
                        </option>
                        <option value="desc" <?= isset($arrange_order) && $arrange_order === 'desc' ? 'selected' : ''; ?>>
                            تنازلي
                        </option>
                    </select>
                </div>
            </div>

        </div>

        <div class="row" style="text-align: left;">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group text-right">
                    <br/>

                    <button type="submit" class="btn btn-success">
                        بحــــث
                    </button>

                    &nbsp; &nbsp; &nbsp;

                    <?php $query = $_SERVER['QUERY_STRING']; ?>

                    <a href="<?= base_url('printing/leaves?' . $query); ?>" target="_blank">
                        <button type="button" class="btn fav">
                            طبــــاعة
                        </button>
                    </a>

                    &nbsp; &nbsp; &nbsp;

                        <a href="<?= base_url('leaves/exportToExcel?' . http_build_query($filters)); ?>" class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i> تحميل Excel
                        </a>

                    &nbsp; &nbsp; &nbsp;

                    <a href="<?= base_url('leaves'); ?>">
                        <button type="button" class="btn fav">
                            إلغاء البحث
                        </button>
                    </a>

                </div>
            </div>
        </div>

    </form>

    <div class="row">
        <div class="col" style="text-align: right; font-size: 12pt; color: white;">
            عدد نتائج البحث : <?= $result['total']; ?>
        </div>
    </div>

    <div class="row">

        <div class="col-12" id="srchRes_mobile">

            <?php foreach ($result['pagedLeaves'] as $row) { ?>

                <div style="background: #eee; margin: 10px 0px; border-radius: 5px; text-align: right; padding: 10px;">

                    <a href="<?= base_url('employees/display/' . $row->id); ?>" style="text-decoration: none;">
                        <?= $row->emp_name_english; ?>
                        <br/>
                        <?= $row->emp_design_name; ?>
                        <br/>
                    </a>
                    لمدة (<?= $row->duration; ?>)
                    <br/>
                    من (<?= $row->begin; ?>)
                    <br/>
                    إلى (<?= $row->end; ?>)
                    <br/>
                    <?= $row->emp_sec_name_arabic ?? ''; ?>
                    <br/>
                    <?= $row->emp_sub_sec_name_arabic ?? ''; ?>
                </div>
            <?php } ?>
        </div>


        <table class="table table-striped table-hover display" id="srchRes_desktop" style="direction: rtl;">
            <thead>
            <tr>
                <th scope="col" style="width: 5%; text-align: center">#</th>
                <th scope="col" style="width: 20%; text-align: center">الأسم</th>
                <th scope="col" style="width: 20%; text-align: center">الوظيفة</th>
                <th scope="col" style="width: 5%; text-align: center">المدة</th>
                <th scope="col" style="width: 10%; text-align: center">من</th>
                <th scope="col" style="width: 10%; text-align: center">إلى</th>
                <th scope="col" style="width: 15%; text-align: center">المراقبة</th>
                <th scope="col" style="width: 15%; text-align: center;">القسم</th>
            </tr>
            </thead>
            <tbody>
            <?php
			$currentPage = $result['page'];
            $perPage = $result['perPage'];
            $i = ($currentPage - 1) * $perPage + 1;
			foreach ($result['pagedLeaves'] as $row) { ?>

                <tr>
                    <td scope="col"><?= $i; ?></td>
                    <td scope="col" class="en_text">
                        <a href="<?= base_url('employees/display/' . $row->emp_id); ?>" style="text-decoration: none;">
                            <?= $row->emp_name_english; ?>
                        </a>
                    </td>
                    <td>
                        <?= $row->emp_design_name; ?>
                    </td>
                    <td scope="col"><?= $row->duration; ?></td>
                    <td scope="col"><?= $row->begin; ?></td>
                    <td scope="col"><?= $row->end; ?></td>
                    <td scope="col"><?= $row->emp_sec_name_arabic ?? ''; ?></td>
                    <td scope="col"><?= $row->emp_sub_sec_name_arabic ?? ''; ?></td>
                </tr>

                <?php $i++;
            } ?>
            </tbody>
        </table>

    </div>

    <?php
    // Pagination logic
    $currentPage = $result['page'];
    $perPage = $result['perPage'];
    $totalItems = $result['total'];
    $totalPages = ceil($totalItems / $perPage);

    // Get current query parameters, excluding 'page'
    $queryParams = $_GET;
    unset($queryParams['page']);
    $queryString = http_build_query($queryParams);
    ?>

    <?php if ($totalPages > 1): ?>
        <ul class="pagination">
            <li class="<?= ($currentPage == 1) ? 'disabled' : ''; ?>">
                <a href="<?= base_url('leaves?' . $queryString . '&page=' . ($currentPage - 1)); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <li class="<?= ($p == $currentPage) ? 'active' : ''; ?>">
                    <a href="<?= base_url('leaves?' . $queryString . '&page=' . $p); ?>"><?= $p; ?></a>
                </li>
            <?php endfor; ?>
            <li class="<?= ($currentPage == $totalPages) ? 'disabled' : ''; ?>">
                <a href="<?= base_url('leaves?' . $queryString . '&page=' . ($currentPage + 1)); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    <?php endif; ?>

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
