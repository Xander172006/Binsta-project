<?php

use RedBeanPHP\R as R;
require "../model/UserModel.php";

class UserController
{
    public function userModel()
    {
        $user = new userModel();
        return $user;
    }

    public function errorhandle()
    {
        $error = '';
        
        switch (true) {
            case $this->loginPost() === 4:
                $error = 'Invalid email or password';
                break;
            case $this->signupPost() === 1:
                $error = 'emailadress already exists';
                break;
            case $this->signupPost() === 2:
                $error = 'incorrect confirm password';
                break;
            case $this->signupPost() === 3:
                $error = 'registerform cannot be empty';
                break;
        }

        return $error;
    }

    public function signup()
    {
        if (isset($_POST['SignupButton'])) {
            $error = $this->errorhandle();
        } else {
            $error = '';
        }

        displayView('../views/files', 'Signup.twig', ['error' => $error]);
    }

    public function login()
    {
        if (isset($_POST['Loginbutton'])) {
            $error = $this->errorHandle();
        } else {
            $error = '';
        }
        displayView('../views/files', 'login.twig', ['error' => $error]);
    }

    public function loginPost()
    {
        if (isset($_POST['Loginbutton'])) {
            $email = $_POST['emailadress'];
            $password = $_POST['password'];

            $model = $this->userModel();
            $account = $model->getEmail($email);

            if ($account && password_verify($password, $account->password)) {
                $_SESSION['user_id'] = $account->id;
                $_SESSION['email'] = $account->email;
                header('Location: http://localhost');
                exit();
            } else {
                return 4;
            }
        }
        return true;
    }

    public function signupPost()
    {
        if (isset($_POST['SignupButton'])) {
            $email = $_POST['emailadress'];
            $username = $_POST['username'];

            $password = $_POST['password'];
            $confirm = $_POST['confirm'];
            
            if (($email !== "") && ($password !== "") && ($confirm !== "")) {
                if ($password === $confirm) {
                    $model = $this->userModel();
                    $userExists = $model->getEmail($email);

                    if ($userExists) {
                        return 1;
                    } else {
                        $hashPassword = password_hash($password, PASSWORD_DEFAULT);

                        $signUp = $model->SignupAccount($email, $username, $hashPassword);

                        $_SESSION['user_id'] = $signUp;
                        header('Location: http://localhost');
                        exit();                
                    }
                } else {
                    return 2;
                }
            } else {
                return 3;
            }
        }
    }

    //settings template
    public function settings()
    {
        $model = $this->userModel();
        $error = '';

        switch (true) {
            case isset($_SESSION['user_id']):
                $user = $model->userData($_SESSION['user_id']);
                $userinfo = $model->userinfoData($_SESSION['user_id']);
                $this->profilePicture();
                $likes = $this->userModel()->countUserLikes($_SESSION['user_id']);

                switch (true) {
                    case isset($_POST['change_settings']):
                        $error = $this->updateSettings();
                        header("Refresh: 0;");
                        break;
                }

                break;
            default:
                $user = '';
                $userinfo = '';
                break;
        }

        displayView('../views/files', 'settings.twig', ['user' => $user, 'userinfo' => $userinfo, 'error' => $error, 'likes' => $likes]);
    }

    public function updateSettings()
    {
        $naam = $_POST['gebruikersnaam'];
        $about = $_POST['beschrijving'];
        $date = $_POST['geboorte'];
        $developer = $_POST['software'];

        $password = $_POST['wachtwoord'];
        $new_password = $_POST['new_wachtwoord'];
        $hashPassword = password_hash($new_password, PASSWORD_DEFAULT);
        
        var_dump($password);
        var_dump($new_password);

        $model = $this->userModel();
        $model->updateUserInfo($naam, $about, $date, $developer);
        return $model->updatePassword($password, $new_password, $hashPassword);
    }

    //add profile picture
    public function profilePicture()
    {
        if (isset($_POST['submitImage'])) {
            $model = $this->userModel();

            $file = $_FILES['editProfile'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $newFileName = uniqid('profile-', true) . '.' . $fileExt;
            $destination = '../uploads/' . $newFileName;
            move_uploaded_file($fileTmpName, $destination);

            $model->uploadProfilePicture($_SESSION['user_id'], $newFileName);

            $_SESSION['picture'] = $newFileName;

            header("Refresh: 0;");
            return $newFileName;
        }
    }

    public function details()
    {
        switch (true) {
            case isset($_SESSION['user_id']):
                $user = $this->userModel()->findUserUrl();
                $userinfo = $this->userModel()->userDetailsInformation();
                $likes = $this->userModel()->countLikes();
                $posts = $this->userModel()->viewPosts();

                $this->addFollows();

                break;
            default:
                $user = '';
                $userinfo = '';
                $likes = '';
                break;
        }

        displayView('../views/files', 'details.twig', ['user' => $user, 'userinfo' => $userinfo, 'likes' => $likes, 'posts' => $posts]);
    }

    public function addFollows()
    {
        if (isset($_POST['follow'])) {
            $this->userModel()->increaseFollows();
        }
    }
}