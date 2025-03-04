<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Post.php';

    //Instantiate DB & connect
    $database = new Database();//This object is instantiated from the class located in file Database.php
    $db = $database->connect();//The connect method is a method we created in Database.php

    //Instantiate blog post object
    $post = new Post($db);//This object is instantiated from the class located in file Post.php and we must pas in a $db object

    //Blog post query
    $result = $post->read();//The read method is a method we created in Post.php
    
    //Get row count
    $num = $result->rowCount();//rowCount is a predefined php method

    //Check if any posts
    if($num > 0){
        //Post array
        $posts_arr = array();
        $posts_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $post_item = array(
                'id' => $id,
                'title' => $title,
                'body' => html_entity_decode($body),
                'author' => $author,
                'category_id' => $category_id,
                'category_name' => $category_name
            );

            //Push to "data'
            array_push($posts_arr['data'], $post_item);
        }

        // turn to JSON & output
        echo json_encode($posts_arr);
    }else{
        //No Posts
        echo json_encode(
            array('message' => 'No Posts Found')
        );
    }

?>