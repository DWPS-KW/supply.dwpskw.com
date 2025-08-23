<option value=""><?= esc('غير محدد') ?></option>
<?php foreach ($sub_secs as $sub_sec): ?>
    <option value="<?= esc($sub_sec->id) ?>">
        <?= esc($sub_sec->name_arabic) ?>
    </option>
<?php endforeach; ?>
