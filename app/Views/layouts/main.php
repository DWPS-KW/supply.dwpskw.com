<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?? 'الموظفين'; ?></title>
    <link rel="icon" href="<?= base_url('assets/images/logo_icon.png'); ?>">
    <meta name="robots" content="noindex">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/fonts.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/general.css'); ?>" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?= view('layouts/header'); ?>

<main>
    <?= $this->renderSection('content'); ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>