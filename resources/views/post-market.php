<?php
    session_start();
    $_SESSION['js'] = 'post/share.js';
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
                                <h4 class="mt-0 header-title">Đăng Bài Viết Bán Hàng</h4>
                                <p class="text-muted m-b-30">Đăng bài viết bán hàng trên các nhóm bạn tham gia</p>
                                <form class="" action="#" novalidate="">
                                <div class="form-group">
                                    <label>Danh sách Cookies ( Cách nhau 1 dòng )</label>
                                    <div><textarea style="height:250px" v-model='input.cookie' class="form-control page-avoid-id" rows="5"></textarea></div>
                                </div>
                                <div class="form-group">
                                    <label>ID Bài Viết</label>
                                    <div><input data-parsley-type="number" v-model='input.postId' type="text" class="form-control post-id" required="" placeholder=""></div>
                                </div>
                                <div class="form-group">
                                    <label>Nội dung bài viết ( Cách nhau 1 dòng )</label>
                                    <div><textarea  v-model='input.content' class="form-control page-avoid-id" rows="5"></textarea></div>
                                </div>
                                <div style="margin-left:-20px" class="custom-control custom-switch">
                                    <p>{{ options.autoGetGroupId ? 'Tự động lấy Group ID' : 'Tùy chỉnh Group ID' }}</p>
                                    <div class="clearfix"></div>
                                    <input v-model="options.autoGetGroupId" type="checkbox" id="switch1" switch="none" data-parsley-multiple="switch1">
                                    <label for="switch1" data-on-label="Yes" data-off-label="No"></label>
                                </div>
                                <div v-if="!options.autoGetGroupId" class="form-group">
                                    <label>Danh sách ID nhóm ( Cách nhau 1 dòng )</label>
                                    <div><textarea v-model='input.groupId' class="form-control page-avoid-id" rows="5"></textarea></div>
                                </div>
                                <div class="form-group">
                                    <label>Thời gian nghỉ</label>
                                    <div><input v-model='input.sleep' data-parsley-type="number" type="text" class="form-control post-id" required="" placeholder=""></div>
                                </div>
                                <div class="form-group">
                                    <div>
                                        <button type="submit" @click='request()' class="btn btn-primary waves-effect waves-light submit">Tiến Hành</button> 
                                        <button type="reset" class="btn btn-secondary waves-effect m-l-5">Hủy</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-lg-6">
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
                                        <div v-if="listSuccess.length > 0" class="col-12">
                                            <p>Thành Công : <strong>{{ listSuccess.length }}</strong></p>
                                            <div class='data-list'>
                                                <li style='list-style-type: none;background:#fff;padding:20px;border-radius:25px;margin:10px;' v-for="(success,index) in listSuccess" :key="index">
                                                    {{ index + 1 }}. <a target="_blank" :href="'https://facebook.com/' + success.group_id">{{ success.group_name }}</a> ( <a target="_blank" :href="'https://facebook.com/' + success.post_id">{{ success.post_id }}</a> ) - <span style="color:#2a8618">{{ success.msg }}</span>
                                                </li>
                                            </div>
                                        </div>
                                        <div v-if="listFail.length > 0" class="col-12">
                                            <p>Thất Bại : <strong>{{ listFail.length }}</strong></p>
                                            <div class='data-list'>
                                                <li style='list-style-type: none;background:#fff;padding:20px;border-radius:25px;margin:10px;' v-for="(fail,index) in listFail" :key="index">
                                                    {{ index + 1 }}.  <a target="_blank" :href="'https://facebook.com/' + fail.group_id">{{ fail.group_name }}</a> - <span style="color:#d64646">{{ fail.msg }} ( {{ fail.reason }} )</span>
                                                </li>
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