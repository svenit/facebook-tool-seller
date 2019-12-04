<?php

namespace App\Controllers;
use App\Helper\Curl;
use App\Model\Connect as Builder;
class BaseController extends Builder
{
    use Curl;

    public function __construct()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        header('Access-Control-Allow-Origin: *');
    }
}
?>