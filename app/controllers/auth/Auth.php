<?php

    namespace App\Controllers\Auth;
    use App\Model\Connect as Builder;
    use PDO;
    use Exception;
    session_start();

    class Auth extends Builder
    {
        public function user()
        {
            $stsm = $this->connect->prepare("SELECT * FROM users WHERE id = ?");
            $stsm->execute([$_SESSION['user'][0]->id]);
            if($stsm->rowCount() == 1)
            {
                return $stsm->fetchAll(PDO::FETCH_OBJ)[0];
            }
        }
        public static function check()
        {
            return isset($_SESSION['user'][0]->id) ? true : false;
        }
    }
?>