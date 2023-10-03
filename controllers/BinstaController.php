<?php

use RedBeanPHP\R as R;
require_once "../controllers/UserController.php";

class BinstaController
{
    public function user()
    {
        $user = new UserController();
        return $user;
    }

    public function formatTime($timestamp) 
    {
        $current_time = new DateTime();
        $timestamp = new DateTime($timestamp);
        $time_difference = $current_time->diff($timestamp);
        
        switch (true) {
            case $time_difference->d > 0:
                return $time_difference->d . ' DAY AGO';
                break;
            case $time_difference->h > 0:
                return $time_difference->h . ' HOUR AGO';
                break;
            case $time_difference->i > 0:
                return $time_difference->i . ' MINUTES AGO';
                break;
            default:
                return 'Just now';
                break;
        }
    }

    public function index()
    {
        $model = $this->user()->userModel();

        //searchRequest
        if (isset($_POST['FindUser'])) {
            $nav = 'display: block;';
            $searchbar = $model->Searchbar();
        } else {
            $searchbar = 'nothing';
            $nav = ''; 
        }

        $displaypost = '';
        $format_time = '';

        // user account
        switch (true) {
            case isset($_SESSION['user_id']):
                
                $user = $model->userData($_SESSION['user_id']);
                $users = $model->getAllUserData();
                $userinfo = $model->userinfoData($_SESSION['user_id']);

                if (isset($_POST['LikeButton']) && isset($_POST['PostId'])) {
                    $postId = $_POST['PostId'];
                    $model->postlikes($postId);
                }
                
                $formatted_times = [];
                $checkPosts = $model->checkPosts();
                var_dump($checkPosts);

                if ($checkPosts > 0) {
                    $displaypost = $model->displayPosts();
                    
                    foreach ($displaypost as $time) {
                        $timestamp = $time['upload_time'];
                        $format_time = $this->formatTime($timestamp);
                        $formatted_times[] = $format_time;
                    }
                }

                break;
            default:
                $user = '';
                $users = '';
                $userinfo = '';
            
                break;
        }

        // display template
        displayView('../views/files', 'app.twig', [
            'user' => $user, 
            'users' => $users, 
            'userinfo' => $userinfo, 
            'posts' => $displaypost, 
            'time' => $format_time, 
            'search' => $searchbar,
            'nav' => $nav
        ]);
    }
}
