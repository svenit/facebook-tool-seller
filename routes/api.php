<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ExecuteController;
session_start();

$request = json_decode(file_get_contents('php://input'),TRUE);
$exec = new ExecuteController();

if(isset($_SESSION['user'][0]->id))
{
    if($_SESSION['user'][0]->expired >= date('Y-m-d'))
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
        }
    }
    else
    {
        echo json_encode([
            'msg' => 'Tài khoản của bạn đã hết hạn sử dụng',
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