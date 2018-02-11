<?php

class Login_Model extends Model {

    function __construct() {
        parent::__construct();
    }

    public function run() {
        $login = $_POST['login'];
        $password = Hash::create('sha256', $_POST['password'], HASH_PASSWORD_KEY);
        
        $sth = $this->db->prepare("SELECT id, role 
                                    FROM user 
                                    WHERE login = :login 
                                        AND password = :password");
        $sth->execute(array(
            ':login' => $login,
            ':password' => $password
        ));

        $data = $sth->fetch();
                
        $count = $sth->rowCount();
        if ($count > 0) {
            // login
            Session::init();
            Session::set('role', $data['role']);
            Session::set('loggedIn', true);
            header('location: ' . URL . 'dashboard');
        } else {
            // show an error!
            header('location: ' . URL . 'login');
        }
    }

}