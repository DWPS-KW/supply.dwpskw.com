<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?>عرض الموظفين<?= $this->endSection(); ?>

<?= $this->section('content'); ?>


<div class="container-fluid">

    <div class="search-div" style="margin-top:70px; direction: rtl;">
        <form id="searchForm" action="<?= base_url('employees/index'); ?>" method="GET" class="d-flex align-items-center gap-2 flex-wrap w-100 justify-content-center">
            <input type="text" id="search_text" name="search_text" class="form-control"
                style="direction: rtl; width: 35%;"
                placeholder="أدخل نص البحث"
                value="<?= isset($search_text) ? esc($search_text) : ''; ?>">

            <select class="form-select" id="search_in" name="search_in" style="direction: rtl; width: 20%;">
                <option value="undefined" <?= ($search_in === 'undefined' || $search_in === null) ? 'selected' : ''; ?>>تحديداً</option>
                <option value="name" <?= ($search_in === 'name') ? 'selected' : ''; ?>>الاسم</option>
                <option value="civil_id" <?= ($search_in === 'civil_id') ? 'selected' : ''; ?>>الرقم المدني</option>
                <option value="file_no" <?= ($search_in === 'file_no') ? 'selected' : ''; ?>>رقم الملف</option>
                <option value="mobile" <?= ($search_in === 'mobile') ? 'selected' : ''; ?>>التليفون</option>
            </select>

            <?php if (session('type') === "admin"): ?>
                <select class="form-select" id="search_at" name="search_at" style="direction: rtl; width: 20%;">
                    <option value="undefined" <?= ($search_at === 'undefined' || $search_at === null) ? 'selected' : ''; ?>>جميع الأقسام</option>
                    <?php foreach ($secs as $sec): ?>
                        <option value="<?= $sec->id; ?>" <?= ($search_at == $sec->id) ? 'selected' : ''; ?>>
                            <?= esc($secModel->getSecById($sec->id)->name_arabic); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary" style="white-space: nowrap;">بـحـــث</button>
        </form>
    </div>

    <div class="container-div">
        <div class="row">
            <div class="col" style="text-align: right; font-size: 12pt; color: white;">
                <!-- We will update this dynamically -->
                <span id="resultCount">عدد نتائج البحث : 0</span>
            </div>
        </div>

        <!-- Employee cards container -->
        <div id="empsData" class="row row-cols-1 row-cols-md-4 row-cols-lg-5 row-cols-xl-5 g-4" style="padding: 30px;">
            <!-- Employee cards will be appended here dynamically -->
        </div>

        <!-- Optional loading indicator -->
        <div id="loadingIndicator" style="display:none; text-align:center; padding:20px;">
            <div class="spinner-border text-primary" role="status"></div>
            <div>تحميل المزيد ...</div>
        </div>
    </div>

</div>

<!-- Include jQuery from CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function() {
    let page = 1;             // Current page
    let loading = false;      // Loading lock
    let hasMore = true;       // More results flag

    // Function to render employee card HTML
    function renderEmp(emp) {
        // Determine if this employee is from the special section
        const cardClass = emp.sec_id != 2 ? 'special-section-card' : '';

        return `
            <div class="col">
                <a href="<?= base_url('employees/show') ?>/${emp.id}" class="text-decoration-none">
                    <div class="card ${cardClass}" style="font-family: Calibri;">
                        <img src="<?= base_url('') ?>${emp.photo}" class="card-img-top" alt="${emp.name_english}" style="object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">${emp.name_english}</h5>
                            <div class="card-subtitle">
                                ${emp.design_name ? `
                                    <div class="info-block">
                                        <div class="info-label"><strong>Designation:</strong></div>
                                        <div class="info-value">${emp.design_name}</div>
                                    </div>` : ''}
                                <div class="info-block">
                                    <div class="info-label"><strong>File No.:</strong></div>
                                    <div class="info-value">${emp.file_no}</div>
                                </div>
                                ${emp.sec_name_english ? `
                                    <div class="info-block">
                                        <div class="info-label"><strong>Department:</strong></div>
                                        <div class="info-value">${emp.sec_name_english}</div>
                                    </div>` : ''}
                                ${emp.sub_sec_name_english ? `
                                    <div class="info-block">
                                        <div class="info-label"><strong>Sub-Section:</strong></div>
                                        <div class="info-value">${emp.sub_sec_name_english}</div>
                                    </div>` : ''}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        `;
    }


    // Load employee data from server
    function loadEmps() {
        if (loading || !hasMore) return;
        loading = true;
        $('#loadingIndicator').show();

        $.ajax({
            url: "<?= base_url('employees/ajaxSearch') ?>",
            method: "GET",
            data: {
                search_text: $('#search_text').val(),
                search_in: $('#search_in').val(),
                search_at: $('#search_at').val(),
                page: page
            },
            success: function(response) {
                $('#loadingIndicator').hide();

                if (page === 1) {
                    $('#empsData').empty();
                }

                if (response.emps.length === 0 && page === 1) {
                    $('#empsData').html('<div class="col-12 text-center text-muted">لا توجد نتائج للبحث.</div>');
                } else {
                    response.emps.forEach(emp => {
                        $('#empsData').append(renderEmp(emp));
                    });
                }

                // Update results count
                $('#resultCount').text(`عدد نتائج البحث : ${response.total ?? 'غير معروف'}`);

                hasMore = response.hasMore;
                if (hasMore) {
                    page++;
                }
                loading = false;
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.log("Response:", xhr.responseText);
                $('#sub_sec_id').html('<option value="">خطأ في تحميل الأقسام الفرعية</option>');
            }
        });
    }

    // On page load, load first page of results
    loadEmps();

    // On scroll near bottom load more
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
            loadEmps();
        }
    });

    // On form submit, reset page and reload data
    $('#searchForm').submit(function(e) {
        // e.preventDefault();
        // page = 1;
        // hasMore = true;
        // loadEmps();
    });
});
</script>

<?= $this->endSection(); ?>
