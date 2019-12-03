<?php

    namespace App\Helper;

    trait Curl 
    {
        public function requestWithCookie($url,$cookie)
        {
            $ch = curl_init();

            CURL_SETOPT_ARRAY($ch,[
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14',
                CURLOPT_ENCODING => '',
                CURLOPT_COOKIE => $cookie,
                CURLOPT_HTTPHEADER => [
                    'Connection: keep-alive',
                    'Keep-Alive: 300',
                    'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                    'Accept-Language: en-us,en;q=0.5'
                ],
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_CONNECTTIMEOUT => 60,
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_HTTPHEADER => [
                    'Expect:',
                ]
            ]);
            $excec = curl_exec($ch);
            curl_close($ch);
            return $excec;
        }
        public function requestWithFields($url,$data,$cookie)
        {
            $ch = curl_init();

            CURL_SETOPT_ARRAY($ch,[
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14',
                CURLOPT_ENCODING => '',
                CURLOPT_COOKIE => $cookie,
                CURLOPT_HTTPHEADER => [
                    'Connection: keep-alive',
                    'Keep-Alive: 300',
                    'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                    'Accept-Language: en-us,en;q=0.5',
                    'access-control-allow-origin:*',
                    'X-Requested-With'
                ],
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => TRUE,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_CONNECTTIMEOUT => 60,
                CURLOPT_FOLLOWLOCATION => TRUE,
            ]);
            $excec = curl_exec($ch);
            curl_close($ch);
            return $excec;
        }
        public function requestRaw($url)
        {
            $ch = curl_init();

            CURL_SETOPT_ARRAY($ch,[
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14',
                CURLOPT_ENCODING => '',
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_CONNECTTIMEOUT => 60,
                CURLOPT_FOLLOWLOCATION => TRUE,
            ]);
            $excec = curl_exec($ch);
            curl_close($ch);
            return $excec;
        }
    }
?>