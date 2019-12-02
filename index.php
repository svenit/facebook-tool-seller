<?php
    session_start();
    $_SESSION['js'] = 'index/no.js';
?>
<!DOCTYPE html>
<html>

<head>
    <?php
        @include __DIR__.'/template/meta.php';
    ?>
</head>

<body>
    <div id="app">
        <?php 
            @include __DIR__.'/template/header.php';
        ?>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Facebook Seller <small>( Version 1.1.0 )
                                    </small></h4>
                                <p><strong>Giới Thiệu</strong></p>
                                <p>
                                    Xin chào các bạn ! Chắc hẳn các bạn cũng đã biết ngày nay việc bán hàng online trên mạng xã hội đang rất phổ biến vì vậy mà đối thủ cạnh tranh ngày một tăng cao
                                    khiến cho việc bán hàng của bạn trở lên khó khăn hơn. Để giúp đỡ cho vấn đề đó
                                    chúng tôi team Dev cho ra mắt bộ công cụ siêu chất mang tên Facebook Seller giúp bạn bán hàng một cách dễ dàng nhất có thể.
                                </p>
                                <p>
                                    Chiến lược tiếp thị thành công của Facebook chạm đến mọi thứ, từ thử nghiệm quảng cáo đến phân tích mức độ tương tác. Chúng tôi cho ra mắt Facebook Seller giúp bạn giải quyến những vấn đề đó như chia nhỏ theo chức năng giúp chiến lược tiếp thị Facebook của bạn đi đúng hướng.
                                </p>
                                <p><strong>Bảo Mật</strong></p>
                                <p>Chúng tôi cam kết 100% tài khoản của bạn khi sử dụng tool tại đây sẽ không bị mất bất kì thông tin nào</p>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-lg-6">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Đánh Giá</h4>
                                <center><img style='opacity:0.6'
                                        src="https://image.flaticon.com/icons/svg/159/159777.svg" width='20%'></center>
                                <div class="fb-comments" data-href="http://ninjacode.top" data-numposts="5"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end wrapper -->
        <!-- Footer -->
        <?php
            @include __DIR__.'/template/footer.php';
        ?>

    </div>
</body>
<?php
    @include __DIR__.'/template/script.php';
?>

</html>