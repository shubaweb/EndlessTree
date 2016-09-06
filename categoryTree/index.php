<?php
spl_autoload_register(function($class){
    require_once '../classes/'.$class.'.class.php';
});
if (empty($_POST)){
    include 'view/editTree.php';
} else {
    switch ($_POST['caller_name']){
        case 'setparent':
            $result = Node::getChildNodes($_POST['parent_id']);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            break;
        case 'add-node':
            Node::addNode($_POST['name'], $_POST['parent_id'], $_POST['level']); 
    }   
}