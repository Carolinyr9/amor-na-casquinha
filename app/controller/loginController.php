<?php 
require '../model/login.php';

class LoginController{
    public function __construct(){
        $this->login = new Login();
    }

    public function login($email){
        $this->login->login($email);
    }
}

?>