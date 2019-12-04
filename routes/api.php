<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'../../app/middleware/auth.php';

use App\Controllers\ExecuteController;
use App\Controllers\Auth\Auth;

session_start();

$request = json_decode(file_get_contents('php://input'),TRUE);
$exec = new ExecuteController();
$guard = new Auth();

if($guard->check() || !$auth->config('auth.authenticate'))
{
    if(($guard->check() && $guard->user()->expired > date('Y-m-d')) || !$auth->config('auth.authenticate'))
    {
        switch($request['route'])
        {
            case 'check-cookie':
                echo $exec->checkCookie($request);
            break;
            case 'get-group-id':
                echo $exec->getGroupId($request);
            break;
            case 'share-live-stream':
                echo $exec->shareLiveStream($request);
            break;
            case 'share-post':
                echo $exec->sharePost($request);
            break;
            case 'post-group':
                echo $exec->postToGroup($request);
            break;
            case 'get-cookie-and-token':
                echo $exec->getCookie($request);
            break;
            default:
                if(isset($_POST))
                {
                    switch($_POST['route'])
                    {
                        case 'upload-image':
                            echo $exec->uploadImage($_POST);
                        break;
                    }
                }
            break;
        }
    }
    else
    {
        echo json_encode([
            'msg' => 'Tài khoản của bạn đã hết hạn sử dụng, vui lòng liên hệ admin để gia hạn',
            'type' => 'error',
            'status' => 201
        ]);
    }
}
else
{
    echo json_encode([
        'msg' => 'Bạn chưa đăng nhập',
        'type' => 'error',
        'status' => 201
    ]);
}