<?php

    namespace App\Helper;

    class Server
    {
        public function config($name,$set = false)
        {
            if(empty($set))
            {
                require __DIR__."../../../config/".explode('.',$name)[0].".php";   
            }
            if(is_array($name))
            {
                foreach($name as $key => $arr)
                {
                    return $this->config($arr,true);
                }
            }
            else
            {
                $conf = explode('.',$name);
                if(isset($conf[0]))
                {
                    if(isset($conf[1]))
                    {
                        $first = $conf[0];
                        array_shift($conf);
                        return $this->config($config[$first][$conf[0]],true);
                    }
                    else
                    {
                        return $name;
                    }
                }
                else
                {
                    return $name;
                }
            }
        }
    }
?>