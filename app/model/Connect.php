<?php

    namespace App\Model;
    use App\Helper\Server;
    use PDO;
    use PDOException;

    class Connect extends Server
    {   
        protected $connect;

        public function __construct()
        {
            if($this->config('auth.authenticate'))
            {
                try
                {
                    $this->connect = new PDO("mysql:host=".$this->config('database.hostname').";dbname=".$this->config('database.dbname'),$this->config('database.username'),$this->config('database.password'));
                    $this->connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                    $this->connect->exec('SET NAMES UTF8MB4');
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
        }
    }

?>