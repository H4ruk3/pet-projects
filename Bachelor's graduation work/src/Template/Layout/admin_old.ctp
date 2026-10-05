<!DOCTYPE html>
<?= $this->Html->css('/bootstrap/css/bootstrap.min.css') ?>
<?= $this->Html->css('/bootstrap/css/jquery-clockpicker.min.css') ?>

<?= $this->Html->script('jquery-1.10.0.min') ?>
<?= $this->Html->script('/bootstrap/js/bootstrap.min.js') ?>
<?= $this->Html->script('/bootstrap/js/
bootstrap-timepicker.min.js') ?>
<?= $this->Html->script('/bootstrap/js/jquery-clockpicker.min.js') ?>
<?= $this->Html->script('/js/jquery-1.10.0.min.js') ?>
<?= $this->Html->script('/js/jquery-ui.min.js') ?>

<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'Ваш личный тренер';
?>

<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <?= $this->Html->meta('icon') ?>

    

    
    
    <?= $this->Html->css('main.css') ?>
    <?= $this->Html->css('../bootstrap/css/bootstrap-timepicker.min.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

</head>
<body class = 'site-back'>
   <nav class="navbar navbar-default site-header" role="navigation">

        <div class="container-fluid">

            <div class="navbar-header">

            <a class="nav navbar-nav" href="#"><div class = 'site-logo'></div></a>    

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
                <a class="navbar-brand" style = "color: #003f72;" href="#"><?= __($this->fetch('title')) ?></a>
            </div>

            
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                 <ul class="nav navbar-nav">

                    <?php if (isset($pathPrev)) { ?>
                       <!--  <a href= <?= $pathPrev ?> class = 'btn btn-default navbar-btn'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>&nbspНазад</a> -->
                    <?php } ?>    
                </ul>

                <ul class="nav navbar-nav">
                    <li><a href="admin/users/index" class = 'head-link'>Пользователи</a></li>
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle head-link" data-toggle="dropdown">Справочники <span class="caret"></span></a>
                      <ul class="dropdown-menu site-header">
                        <li><a href="musculgroup/index">Группы мышц</a></li>
                        <li><a href="exercise/index">Упражнения</a></li>
                        <li><a href="#">Продукты питания</a></li>
                      </ul>
                    <li><a href="/routine" class = 'head-link'>Распорядок дня</a></li>
                    <li><a href="/trainingprogram" class = 'head-link'>Тренировочные программы</a></li>
                    <li><a href="/nutritionprogram" class = 'head-link'>Программы питания</a></li>
                </ul>

                <? if (isset($user)) {?>
                <ul class="nav navbar-nav navbar-right navbar-link">
                    <li><p><?= $user["username"]; ?> | <a href="/auth/logout" class = 'head-link'>Выход</a></p></li>
                </ul>
                <? } ?>

            </div>
          
        </div>
    </nav>

    <?= $this->Flash->render() ?>
    <div class="container clearfix site-body">
        
        <div  id="breadrumb" style = "margin: 25px; margin-left: 40px;">
            <?php
                //if (isset($pathPrev))
                    //echo "<a href='$pathPrev' style = 'text-decoration: underline'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Назад</a></li>";
            ?>
        </div>

        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>

