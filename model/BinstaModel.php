<?php

require_once "../vendor/autoload.php";
use RedBeanPHP\R as Binsta; 

//requires database called Binsta
class BinstaModel
{
    public function connect()
    {
        Binsta::setup('mysql:host=localhost;dbname=binsta', 'bit_academy', 'bit_academy');

        //Drop if already exists
        Binsta::wipe("user");
        Binsta::wipe("userinfo");
        Binsta::wipe("posts");
    }

    public function createTables()
    {
        //create {user} table
        $user = Binsta::dispense('user');
        $user->email = 'testuser@gmail.com';
        $password = '123';
        $user->gebruikersnaam = 'future tech leader';
        $user->password = password_hash($password, PASSWORD_DEFAULT);

        //stores the info in table {user}
        $UserId = Binsta::store($user);
        Binsta::load('user', $UserId);

        //create {userinfo} table
        $userinfo = Binsta::dispense('userinfo');
        $userinfo->beschrijving = 'test user voor het testen van de website';
        $userinfo->datum = '00-00-0000';
        $userinfo->developer = 'test';
        $userinfo->profile = '';
        $userinfo->user_id = $UserId;
        $user->user_id = $UserId;

        //stores the info in table {userinfo}
        $userInfoId = Binsta::store($userinfo);
        Binsta::load('userinfo', $userInfoId);

        //creates the {posts} table
        $sql = 'CREATE TABLE posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            codeimage VARCHAR(255) NOT NULL,
            likes INT DEFAULT 0 NOT NULL,
            comment VARCHAR(200) NOT NULL,
            programming_language VARCHAR(70) NOT NULL,
            follows INT NOT NULL,
            user_id INT NOT NULL,
            upload_time DATETIME NOT NULL
          )
        ';
        Binsta::exec($sql);
    }

    public function insertData()
    {
        //connect database binsta
        $this->connect();

        //create tables
        $this->createTables();
    }
}

$Binsta = new BinstaModel();
$Binsta->insertData();

echo 'Tables created successfully.';

