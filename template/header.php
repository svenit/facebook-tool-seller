<?php
    session_start();
?>
<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">
            <!-- Logo container-->
            <div class="logo"><a href="/" class="logo"><img
                        src="https://image.flaticon.com/icons/svg/174/174848.svg" alt="" class="logo-small">
                    <img src="https://image.flaticon.com/icons/svg/174/174848.svg" alt="" class="logo-large">
                    Facebook Tool Seller</a></div>
            <!-- End Logo container-->
            <div class="menu-extras topbar-custom">
                <ul class="float-right list-unstyled mb-0">
                    <li class="dropdown notification-list d-none d-sm-block">
                        <form role="search" class="app-search">
                            <div class="form-group mb-0"><input type="text" class="form-control" placeholder="Search..">
                                <button type="submit"><i class="fas fa-search"></i></button></div>
                        </form>
                    </li>
                    <li class="dropdown notification-list">
                        <?php
                            if(isset($_SESSION['user']))
                            { 
                                ?>
                                    <div class="dropdown notification-list nav-pro-img xss">
                                        <a class="dropdown-toggle nav-link arrow-none nav-user" data-toggle="dropdown" href="#"
                                            role="button" aria-haspopup="false" aria-expanded="false"><img
                                                src="<?php echo isset($_SESSION['user']) ? "https://ui-avatars.com/api/?background=cef2ef&length=1&color=26998d&name=".$_SESSION['user'][0]->name : 'https://image.flaticon.com/icons/svg/149/149071.svg' ?>" alt="user"
                                                class="rounded-circle"></a>
                                        <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                                            <a class="dropdown-item d-block" href="#"><i
                                                    class="mdi mdi-account-circle m-r-5"></i> <?php echo $_SESSION['user'][0]->name ?></a>
                                            <a onclick="alert('ID : ' + <?php echo $_SESSION['user'][0]->id ?> + '\n' + 'Tên tài khoản : <?php echo $_SESSION['user'][0]->username ?>' + '\n' + 'Tên : <?php echo $_SESSION['user'][0]->name ?>' +  '\n' + 'Ngày hết hạn : <?php echo $_SESSION['user'][0]->expired ?>')" class="dropdown-item"><i
                                                    class="mdi mdi-memory m-r-5"></i> Xem Thông Tin</a>
                                            <a class="dropdown-item" href="https://facebook.com/sven307"><i class="mdi mdi-wallet m-r-5"></i> Liên
                                                hệ</a>
                                            <a class="dropdown-item" href="logout"><i class="mdi mdi-logout m-r-5"></i> Đăng Xuất</a>
                                        </div>
                                    </div>
                                <?php
                            }
                            else
                            {
                                ?>
                                    <div class="dropdown notification-list nav-pro-img">
                                        <a style="position:relative;background-color: #f8f9fa;top: 20px;padding: 9px;border: 1px solid #eee;border-radius: 25px;" href="login">Đăng Nhập <i class="mdi mdi-login"></i></a>
                                    </div>
                                <?php
                            }
                        ?>
                    </li>
                    <li class="menu-item">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle nav-link" id="mobileToggle">
                            <div class="lines"><span></span> <span></span> <span></span></div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>
                </ul>
            </div>
            <!-- end menu-extras -->
            <div class="clearfix"></div>
        </div>
        <!-- end container -->
    </div>
    <!-- end topbar-main -->
    <div class="container-fluid"></div>
    <!-- MENU Start -->
    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">
                    <li class="has-submenu"><a href="/"><i class="fas fa-home"></i>
                            <span>Trang Chủ</span></a></li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-comment-alt"></i>Bài Viết</a>
                        <ul class="submenu">
                            <li><a href="share-post?#">Chia Sẻ Lên Nhóm</a></li>
                            <li><a href="#">Quét Bình Luận</a></li>
                            <li><a href="#">Quét Gmail</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-video"></i> Live Stream</a>
                        <ul class="submenu megamenu">
                            <li>
                                <ul>
                                    <li><a href="share-live-stream?#">Chia Sẻ Lên Nhóm</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-user"></i> Cá Nhân</a>
                        <ul class="submenu">
                            <li><a href="#">Lấy Token ( Không Checkpoint )</a></li>
                            <li><a href="#">Lọc Bạn Bè</a></li>
                            <li><a href="#">Đếm Tin Nhắn</a></li>
                            <li><a href="#">Tin Nhắn Đầu Tiên</a></li>
                            <li><a href="#">Auto Chia Sẻ</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-users"></i> Nhóm</a>
                        <ul class="submenu">
                            <li><a href="post-market?#">Đăng Bài Viết Bán Hàng</a></li>
                            <li><a href="#">Quét Bài Viết</a></li>
                            <li><a href="#">Auto Tham Gia Nhóm</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-flag"></i> Fanpage</a>
                        <ul class="submenu">
                            <li><a href="count-liked.php?#">Đếm Like</a></li>
                            <li><a href="scan-posts-page.php?#">Quét Bài Viết</a></li>
                        </ul>
                    </li>
                </ul>
                <!-- End navigation menu -->
            </div>
            <!-- end navigation -->
        </div>
        <!-- end container-fluid -->
    </div>
    <!-- end navbar-custom -->
</header> <!-- End Navigation Bar-->