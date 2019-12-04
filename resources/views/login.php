<?php
    ob_flush();
    error_reporting(0);
    $_SESSION['js'] = 'index/no.js';

    require __DIR__.'../../../vendor/autoload.php';
    require __DIR__.'../../../app/middleware/auth.php';

    $_SESSION['js'] = 'index/no.js';
    if(isset($_SESSION['user']) || !$auth->config('auth.authenticate'))
    {
        header("Location: index.php");
    }
    use App\Controllers\Auth\LoginController;
    use App\Controllers\Auth\RegisterController;

    $login = new LoginController();
    $signup = new RegisterController();


?>
<!DOCTYPE html>
<html>

<head>
    <?php
        @include __DIR__.'../../../template/meta.php';
    ?>
</head>

<body>
    <div id="app">
        <?php 
            @include __DIR__.'../../../template/header.php';
        ?>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Đăng Ký</h4>
                                <p class="text-muted m-b-30">Đăng ký ngay hôm nay để sử dụng bộ cung cụ Facebook Tool Seller Miễn Phí</p>
                                <form method="POST" class="" action="#" novalidate="">
                                    <div class="form-group">
                                        <label>Tên tài khoản</label>
                                        <div><input value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>" name="username" type="text" class="form-control post-id" required="" placeholder=""></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Họ & Tên</label>
                                        <div><input value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>" name="name" type="text" class="form-control post-id" required="" placeholder=""></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Mật khẩu</label>
                                        <div><input name="password" type="password" class="form-control post-id" required="" placeholder=""></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nhập lại mật khẩu</label>
                                        <div><input name="repassword" type="password" class="form-control post-id" required="" placeholder=""></div>
                                    </div>
                                    <div class="g-recaptcha" data-sitekey="6LecFLcUAAAAAIO2GvlabDd4DosYnj4_zXtUCOYL"></div>
                                    <br>
                                    <div class="form-group">
                                        <div>
                                            <button type="submit" name="signup" class="btn btn-primary waves-effect waves-light submit">Đăng Ký</button> 
                                            <button type="reset" class="btn btn-secondary waves-effect m-l-5">Hủy</button>
                                        </div>
                                    </div>
                                </form>
                                <?php
                                    if(isset($_POST['signup']))
                                    {
                                        if(empty($_POST['g-recaptcha-response']))
                                        {
                                            ?>
                                                <script>
                                                    toastr['warning']('Bạn chưa nhập captcha');
                                                </script>
                                            <?php
                                        }
                                        else
                                        {
                                            $signup = $signup->register($_POST['name'],$_POST['username'],$_POST['password'],$_POST['repassword']);
                                            if(isset($signup['status']))
                                            {
                                                ?>
                                                    <script>
                                                        toastr['<?php echo $signup['type'] ?>']('<?php echo $signup['msg'] ?>');
                                                    </script>
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-lg-6">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Đăng Nhập</h4>
                                <p class="text-muted m-b-30">Đăng nhập để sử dụng hệ thống hỗ trợ Facebook cực chất ngay hôm nay</p>
                                <form method="POST" class="" action="#" novalidate="">
                                    <div class="form-group">
                                        <label>Tên tài khoản</label>
                                        <div><input value="<?php echo isset($_POST['login_username']) ? $_POST['login_username'] : '' ?>" name="login_username" type="text" class="form-control post-id" required="" placeholder=""></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Mật khẩu</label>
                                        <div><input name="login_password" type="password" class="form-control post-id" required="" placeholder=""></div>
                                    </div>
                                    <div class="form-group">
                                        <div>
                                            <button type="submit" name="login" class="btn btn-primary waves-effect waves-light submit">Đăng Nhập</button> 
                                            <button type="reset" class="btn btn-secondary waves-effect m-l-5">Hủy</button>
                                        </div>
                                    </div>
                                </form>
                                <?php
                                    if(isset($_POST['login']))
                                    {

                                        $login = $login->attempt($_POST['login_username'],$_POST['login_password'],$_POST['remember']);
                                        if(isset($login['status']))
                                        {
                                            ?>
                                                <script>
                                                    toastr['<?php echo $login['type'] ?>']('<?php echo $login['msg'] ?>');
                                                </script>
                                            <?php
                                            if($login['status'] == 200)
                                            {
                                                ?>
                                                    <script>
                                                        window.location = 'index.php';
                                                    </script>
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end container -->
        </div>
        <!-- end wrapper -->
        <!-- Footer -->
        <?php
            @include __DIR__.'../../../template/footer.php';
        ?>

    </div>
</body>
<?php
    @include __DIR__.'../../../template/script.php';
?>

</html>
<?php
    ob_end_flush();
?>