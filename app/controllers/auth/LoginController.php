<?php

    namespace App\Controllers\Auth;
    use App\Model\Connect as Builder;
    use PDO;
    use Exception;
    session_start();
    class LoginController extends Builder
    {
        public function attempt($username,$password,$remember)
        {
            try
            {
                $username = htmlspecialchars(addslashes(strip_tags($username)));
                $password = md5(htmlspecialchars(addslashes(strip_tags($password))));

                $checkAccount = $this->connect->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
                $checkAccount->execute([$username,$password]);

                if($checkAccount->rowCount() == 1)
                {
                    $_SESSION['user'] = $checkAccount->fetchAll(PDO::FETCH_OBJ);
                    return [
                        'status' => 200,
                        'type' => 'success',
                        'msg' => 'Đăng nhập thành công'
                    ];
                }
                else
                {
                    return [
                        'status' => 201,
                        'type' => 'error',
                        'msg' => 'Sai tài khoản hoặc mật khẩu'
                    ];
                }
            }
            catch(Exception $e)
            {
                return [
                    'status' => 201,
                    'type' => 'error',
                    'msg' => 'Lỗi'
                ];
            }
        }
    }

?>