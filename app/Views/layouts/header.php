<nav class="navbar fixed-top navbar-expand-sm navbar-light">

    <a class="navbar-brand" href="#">
        <img src="<?= base_url('assets/images/logo_h.png'); ?>" class="d-inline-block navbar-logo" alt="" loading="lazy" style="width: 180px; height: auto;">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown" style="text-align: center;">
        <ul class="navbar-nav">
            <li class="nav-item header-search" style="padding: 7px 0px 0px 0px;">
                <form action="<?= base_url('employees/index'); ?>" method="GET">
                    <div class="input-group mb-3" style="flex-wrap: nowrap;"> <input type="text" id="search_text" name="search_text"
                        value="<?= isset($search_text) && $search_text != null ? esc($search_text) : ''; ?>"
                        placeholder="ابحث هنا" class="form-control form-control-sm" aria-label="Search input"
                        aria-describedby="basic-addon1" style="direction: rtl;" autofocus>

                        <div class="btn-group"> <button class="btn btn-sm btn-outline-secondary fav dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">ابحـــث</button>
                            <ul class="dropdown-menu"> <li><button class="dropdown-item" name="search_in" value="undefined" type="submit">غير محدد</button></li>
                                <li><button class="dropdown-item" name="search_in" value="name" type="submit">بالاسم</button></li>
                                <li><button class="dropdown-item" name="search_in" value="civil_id" type="submit">بالرقم المدني</button></li>
                                <li><button class="dropdown-item" name="search_in" value="file_no" type="submit">برقم الملف</button></li>
                                <li><button class="dropdown-item" name="search_in" value="mobile" type="submit">بالتيليفون</button></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="<?= base_url('employees/index'); ?>">الرئيسية</a>
            </li>
            <?php if (session('type') === 'admin' || (session('type') === 'depart' && session('sec') == 2)) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('employees/new'); ?>">إضافة موظف</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('employees/searching'); ?>">بحث مفصل</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReps" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        احصائيات
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownReps">
                        <li><a class="dropdown-item" href="<?= base_url('employees/searching'); ?>">الموظفين</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('fdays/'); ?>">الأذونات</a></li>
                        <?php if (session('type') === 'admin' || (session('type') === 'depart' && session('allow_paper') === 'all')) : ?>
                            <li><a class="dropdown-item" href="<?= base_url('leaves'); ?>">الإجازات</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('medicals'); ?>">المرضيات</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('attendance'); ?>">الحضور</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('holidays'); ?>">العطــلات</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('logout'); ?>" style="text-decoration: none;">
                    <button type="button" id="signoutBtn" class="btn fav btn-sm">تسجيل خروج</button>
                </a>
            </li>
        </ul>
    </div>

</nav>

<br />