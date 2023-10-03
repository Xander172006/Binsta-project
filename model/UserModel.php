<?php

require_once "../vendor/autoload.php";
use RedBeanPHP\R as User;

class UserModel
{
    public function getEmail($email)
    {
        $getEmail = User::findOne('user', 'email = ?', [$email]);
        return $getEmail;
    }

    public function signupAccount($email, $username, $hashPassword)
    {
        $signUp = User::dispense('user');
        $signUp->email = $email;
        $signUp->gebruikersnaam = $username;
        $signUp->password = $hashPassword;
        User::store($signUp);

        return $signUp->id;
    }

    public function userData($id)
    {
        $users = User::find('user', 'id = ?', [$id]);
        foreach ($users as $data) {
            $user = $data;
        }
    
        return $user;
    }

    public function userinfoData($id)
    {
        $userinfo = User::findOne('userinfo', 'user_id = ?', [$id]);
        return $userinfo;
    }

    public function uploadProfilePicture($id, $newFileName)
    {
        $userinfo = User::load('userinfo', $id);
        $userinfo->profile = $newFileName;
        $userinfo->user_id = $id;
        User::store($userinfo);
    }

    public function checkPosts()
    {
        $posts = User::count('posts');
        return $posts;
    }

    public function getAllUserData()
    {
        $users = User::getAll('SELECT userinfo.profile, user.*
        FROM userinfo INNER JOIN user 
        ON userinfo.user_id = user.id;');

        return $users;
    }

    public function postlikes($postId)
    {
        $post = User::load('posts', $postId);
        $post->likes++;
        User::store($post);
    }

    public function displayPosts()
    {
        $posts = User::getAll('SELECT user.gebruikersnaam, userinfo.profile, posts.*
            FROM `user`
            LEFT JOIN userinfo ON userinfo.user_id = user.id
            INNER JOIN posts ON posts.user_id = user.id
            ORDER BY posts.likes DESC;        
        ');

        return $posts;
    }

    public function searchBar()
    {
        $search = $_POST['Searchbar'];
        
        $users = User::getAll('SELECT user.gebruikersnaam, userinfo.profile
        FROM `user`
        LEFT JOIN userinfo ON userinfo.user_id = user.id
        WHERE user.gebruikersnaam LIKE ?;', ["%$search%"]);

        return $users;
    }

    public function updateUserInfo($naam, $about, $date, $developer)
    {
        //update username
        $user = User::load('user', $_SESSION['user_id']);
        $user->gebruikersnaam = $naam;
        User::store($user);

        //update userinfo
        $userinfo = User::load('userinfo', $_SESSION['user_id']);
        $userinfo->beschrijving = $about;
        $userinfo->datum = $date;
        $userinfo->developer = $developer;
        User::store($userinfo);
    }

    public function updatePassword($password, $hashPassword)
    {
        $user = User::load('user', $_SESSION['user_id']);
      
        if (password_verify($password, $user->password)) {
            $user->password = $hashPassword;
            User::store($user);
        } else {
            return 'incorrect old password!'; 
        }
    }

    public function findUserUrl()
    {
        $finduser = explode("/", $_GET['params']);
        $user = $finduser[2];

        $users = User::find('user', 'gebruikersnaam = ?', [$user]);
    
        foreach ($users as $data) {
            $details = $data;
        }
    
        return $details;
    }

    public function userDetailsInformation()
    {
        $userId = $this->findUserUrl();
        $detailsinformation = User::findOne('userinfo', 'user_id = ?', [$userId['id']]);

        return $detailsinformation;
    }

    public function countLikes()
    {
        $userId = $this->findUserUrl();
        $totalLikes = User::getCell('SELECT SUM(likes) FROM posts WHERE user_id = ?', [$userId['id']]);

        return $totalLikes;
    }

    public function viewPosts()
    {
        $userId = $this->findUserUrl();
        $totalPosts = User::find('posts', 'user_id = ?', [$userId['id']]);

        return $totalPosts;
    }

    public function countUserLikes($id)
    {
        $totalLikes = User::getCell('SELECT SUM(likes) FROM posts WHERE user_id = ?', [$id]);

        return $totalLikes;
    }

    public function uploadPost($title, $description, $uploadFileName, $language, $id)
    {
        $post = User::dispense('posts');
        $post->title = $title;
        $post->comment = $description;
        $post->codeimage = $uploadFileName;

        $post->programming_language = $language;
        $post->upload_time = date('Y-m-d H:i:s');
        $post->user_id = $id;

        User::store($post);
    }

    public function increaseFollows()
    {
        $userId = $this->findUserUrl();
        $user = User::load('userinfo', $userId['id']);
        $user->follows += 1;
        User::store($user);
    }
}