<?php

    namespace App\Helper;

    trait Server
    {
        public function config($name)
        {
            require '../../config/database.php';

            if(is_array($name))
            {
                foreach($name as $key => $arr)
                {
                    return $this->config($arr);
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
                        return $this->config($config[$first][$conf[0]]);
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