<?php

use RedBeanPHP\R as R;
require "../model/UserModel.php";

class PostsController
{
    public function user()
    {
        $user = new userModel();
        return $user;
    }

    public function create()
    {
        switch (true) {
            case isset($_SESSION['user_id']):
                $user = $this->user()->userData($_SESSION['user_id']);
                $image = $this->user()->userinfoData($_SESSION['user_id']);
                $this->postUploads();

                break;
            break;
            default:
                $user = '';
                $image = '';
                break;
        }
        
        //display template
        displayView('../views/files', 'create.twig', ['user' => $user, 'image' => $image]);
    }

    public function postUploads()
    {
        if (isset($_POST['createPost'])) {
            $file = $_FILES['fileImg'];
            $filename = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            $uploadFileName = uniqid('upload-', true) . '.' . $fileExt;
            $destination = '../uploads/posts/' . $uploadFileName;
            move_uploaded_file($fileTmpName, $destination);

            $title = $_POST['postTitle'];
            $description = $_POST['post_description'];
            $language = $_POST['language'];

            $this->user()->uploadPost($title, $description, $uploadFileName, $language, $_SESSION['user_id']);

            header("Location: http://localhost");
        }
    }
}