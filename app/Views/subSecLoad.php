<option value="undefined" <?= $sub_sec_id === null || $sub_sec_id === '' || $sub_sec_id === 'undefined' ? 'selected' : ''; ?>>غير محدد</option>
<?php foreach ($sub_secs as $sub_sec): ?>
    <option value="<?= esc($sub_sec->id); ?>" <?= $sub_sec->id === $sub_sec_id ? 'selected' : ''; ?>>
        <?= esc($sub_sec->name_arabic); ?>
    </option>
<?php endforeach; ?>
