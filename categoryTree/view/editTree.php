<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Endless tree of category</title>
    <!--jQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!--<script src="https://malsup.github.com/jquery.form.js"></script>-->
    <!-- Bootstrap -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <!--My scripts-->
    <script src="js/ajax.js"></script>

</head>
<body>
<div class="container-fluid">
    <h1 class="text-center">Дерево категорий бесконечной вложенности</h1>
    <div class="row text-left">
        <div class="col-xs-10 col-xs-offset-1">
            <ul class="nav nav-pills nav-justified">
                <li  class="active"><a data-toggle="pill" href="#tree">Иерархия категорий</a></li>
                <li><a data-toggle="pill" href="#add">Добавить категорию</a></li>
                <li><a data-toggle="pill" href="#edit">Редактировать категорию</a></li>
            </ul>
        </div>

        <div class="well col-xs-10 col-xs-offset-1 tab-content" style="margin-top: 10px">
            <div  id="tree" class="tab-pane fade  in active">
                <?= Node::showTree(0);?>
            </div>
            <div id="add" class="tab-pane fade">
                <form id="add-node" name="form-add" method="post" action="index.php" onsubmit="return false"></form>
                    <div class="form-group">
                        <label for="category-name" >Название категории</label>
                        <input name="catName" form="add-node" id="category-name" class="form-control" type="text" required>
                    </div>
                    <p class="text-left"><strong>Выбрать родителя</strong></p>
                    <div id="parents" class="form-group container-fluid" style="padding-left: 0; padding-right: 0">
                        <select data-form-id="add-node" id="0" class="col-xs-2" name="setparent" form="add-node" onchange="handlerChangePath(this)">
                            <option value="0" selected>Добавить в корень</option>
                        <?php
                        foreach (Node::getChildNodes() as $id => $name){
                            echo '<option value="'.$id.'">'.$name.'</option>';
                        }
                        ?>
                        </select>
                    </div>
                    <div class="container-fluid text-center">
                        <input form="add-node" type="submit" value="Добавить категорию" class="btn btn-default text-center" onclick="handlerSubmitAddForm(this.form)">
                    </div>
            </div>
            <div id="edit" class="tab-pane fade">
                <form id="edit-form" action="index.php">
                    
                </form>
                <input form="edit-form" type="submit" value="Редактировать категорию" class="btn btn-default">
            </div>
        </div>
    </div>
</div>
</body>
</html>