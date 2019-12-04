<?php

    namespace App\Controllers\Auth;
    use App\Model\Connect as Builder;
    use App\Helper\Curl;
    use PDO;

    class RegisterController extends Builder
    {
        public function register($name,$username,$password,$repassword)
        {
            try
            {
                $name = htmlspecialchars(addslashes(strip_tags($name)));
                $username = htmlspecialchars(addslashes(strip_tags($username)));
                $password = htmlspecialchars(addslashes(strip_tags($password)));
                $password = htmlspecialchars(addslashes(strip_tags($password)));

                $stsm = $this->connect->prepare("SELECT * FROM users WHERE username = ?");
                $stsm->execute([$username]);

                if(!trim($username) || !trim($password) || !trim($repassword) || !trim($name))
                {
                    return [
                        'status' => 201,
                        'type' => 'error',
                        'msg' => 'Không được để trống'
                    ];
                }
                elseif(!preg_match('/^[a-zA-Z0-9]+$/',$username))
                {
                    return [
                        'status' => 201,
                        'type' => 'error',
                        'msg' => 'Tên tài khoản không được có kí tự đặc biệt'
                    ];
                }
                elseif(strlen($name) < 4 || strlen($name) > 50)
                {
                    return [
                        'status' => 201,
                        'type' => 'error',
                        'msg' => 'Tên chỉ được từ 4-50 kí tự'
                    ];
                }
                elseif(strlen($username) < 4 || strlen($username) > 16)
                {
                    return [
                        'status' => 201,
                        'type' => 'error',
                        'msg' => 'Tên tài khoản chỉ được từ 4-16 kí tự'
                    ];
                }
                elseif(strlen($password) < 4 || strlen($password) > 30)
                {
                    return [
                        'status' => 201,
                        'type' => 'error',
                        'msg' => 'Mật khẩu chỉ được từ 4-30 kí tự'
                    ];
                }
                elseif($password !== $repassword)
                {
                    return [
                        'status' => 201,
                        'type' => 'error',
                        'msg' => 'Mật khẩu không trùng nhau'
                    ];
                }
                else
                {
                    $checkAccount = $this->connect->prepare("SELECT * FROM users WHERE username = ?");
                    $checkAccount->execute([$username]);
                    if($checkAccount->rowCount() == 1)
                    {
                        return [
                            'status' => 201,
                            'type' => 'error',
                            'msg' => 'Tài khoản này đã tồn tại'
                        ];
                    }
                    else
                    {
                        $createAccount = $this->connect->prepare("INSERT INTO users(name,password,username,expired) VALUES(?,?,?,?)");
                        if($createAccount->execute([$name,md5($password),$username,date('Y-m-d', strtotime(date('Y-m-d'). ' + 3 days'))]))
                        {
                            return [
                                'status' => 200,
                                'type' => 'success',
                                'msg' => 'Tạo tài khoản thành công'
                            ];
                        }
                        else
                        {
                            return [
                                'status' => 201,
                                'type' => 'success',
                                'msg' => 'Đã có lỗi xảy ra'
                            ];
                        }
                    }
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