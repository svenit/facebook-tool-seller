<?php
    session_start();
    $_SESSION['js'] = 'app.js';
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
                    <div class="col-lg-8">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Lấy Cookie & Token</h4>
                                <p class="text-muted m-b-30">Lấy cookie & token siêu nhanh</p>
                                <form class="" action="#" novalidate="">
                                    <p class="alert alert-warning">Tắt xác thực 2 bước trước khi sử dụng</p>
                                    <div class="form-group">
                                        <label>Tài khoản</label>
                                        <div><input data-parsley-type="text" v-model='cookie.username' type="text" class="form-control post-id" required="" placeholder=""></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Mật khẩu</label>
                                        <div><input data-parsley-type="text" v-model='cookie.password' type="password" class="form-control post-id" required="" placeholder=""></div>
                                    </div>
                                    <div class="form-group">
                                        <div>
                                            <button :disabled="loading" type="submit" @click="getCookie()" class="btn btn-primary waves-effect waves-light submit">OK</button> 
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-lg-4">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Thống Kê</h4>
                                <p class="loading-notice"></p>
                                <p class="data-response">
                                    <div class="row">
                                        <div class="col-12">
                                            <center>
                                                <img v-if="loading" width="50%" src="https://i.imgur.com/qQcTjCQ.gif">
                                            </center>
                                        </div>
                                        <div v-if="data.token" class="col-12">
                                            <p>Access Token</p>
                                            <div class='data-list'>
                                                {{ data.token }}
                                            </div>
                                        </div>
                                        <div v-if="data.cookie" class="col-12">
                                            <p>Cookie</strong></p>
                                            <div class='data-list'>
                                                {{ data.cookie }}
                                            </div>
                                        </div>
                                    </div>
                                </p>
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