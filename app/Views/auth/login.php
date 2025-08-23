<!doctype html>
<html lang="en">
    <head>
        <title> برنامج :: العمالة الموردة </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="noindex">
        <link rel="icon" href="<?= base_url('assets/images/logo_icon.png'); ?>">
        <?= view('bootstrap4'); ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/fonts.css'); ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/css/general.css'); ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/css/browse_style.css'); ?>" />
        <script src="<?= base_url('assets/js/google-ajax.js'); ?>"></script>
        <style>
            .container {
                background: none;
                width: 90%;
            }

            .login {
                background-image: url("<?= base_url('assets/images/c50.png'); ?>");
                border-radius: 10px;
                padding: 10px;
            }
            .login_logo {
                width: 256px;
                height: 256px;
            }
            .supervision {
                background-repeat: repeat;
                border-radius: 10px;
                font-family: 'abdo';
                color: white;
                font-size: 36px;
                text-align: center;
                text-shadow: 2px 2px #000;
                vertical-align: middle;
                line-height: 1.3;
            }
            .supervision_sub {
                background-repeat: repeat;
                border-radius: 10px;
                font-family: 'abdo';
                color: white;
                padding-top: 10px;
                font-size: 32px;
                text-align: center;
                text-shadow: 2px 2px #000;
                vertical-align: middle;
                line-height: 1.3;
            }

            .designby {
                background-repeat: repeat;
                border-radius: 10px;
                width: 100%;
                font-family: 'abdo';
                color: white;
                font-size: 28px;
                text-align: center;
                text-shadow: 2px 2px #000;
                vertical-align: middle;
            }
            @media only screen and (max-width: 600px) {
                .container {
                    margin-top: 10px;
                }
                .login_logo {
                    width: 192px;
                    height: 192px;
                }
                .supervision {
                    padding-top: 8px;
                    font-size: 28px;
                    line-height: 1.3;
                }
                .supervision_sub {
                    padding-top: 8px;
                    font-size: 24px;
                    line-height: 1.3;
                }
                .designby {
                    padding-top: 8px;
                    font-size: 20px;
                }
            }
        </style>
    </head>
    <body>

        <div class="container">

            <div class="row">
                <div class="col" style="text-align: center;">
                    <img src="<?= base_url('assets/images/besm_black.png'); ?>" style="width: 50%;" alt="">
                </div>
            </div>

            <?php if (isset($msg) && !empty($msg)) { ?>
            <div class="row">
                <div class="col">
                    <div class="alert alert-danger" role="alert">
                        Error
                        <?= esc($msg); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <form action="<?= site_url('doLogin'); ?>" method="POST">
                <?= csrf_field(); ?>
                <div class="login">
                    <div class="row">
                        <div class="col-12" style="text-align: center;">
                            <img class="mb-2 login_logo" src="<?= base_url('assets/images/logo_v.png'); ?>" alt="">
                        </div>
                        <div class="col-12" style="font-family: abdo; font-size: 22pt; color: #006699; text-align: center; text-shadow: 1px 1px grey;">
                            إدارة العمالة الموردة
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="username" style="color: #006699; font-family: calibri;">User Name</label>
                                    <input type="text" class="form-control" id="username" name="username" required autofocus />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password" style="color: #006699; font-family: calibri;">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn fav" style="font-family: calibri;">Sign in</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row" style="direction: rtl;">
                <div class="col-12 col-md-6 supervision">
                    إشراف م / محمد مشلح الشمري <br />
                    مدير محطة الدوحة الغربية
                </div>
                <div class="col-12 col-md-6 supervision_sub">
                    إدارة م / عبد الكريم عبد العزيز السندي  <br />
                    مراقب الصيانة الميكانيكية
                </div>
                <div class="col-12 designby">
                    برمجة وتصميم / أحمد رجب
                </div>
            </div>

        </div>

    </body>
</html>
