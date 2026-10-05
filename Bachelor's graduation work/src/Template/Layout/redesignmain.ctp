<!DOCTYPE html>
<?= $this->Html->css('/bootstrap/css/bootstrap.min.css') ?>
<?= $this->Html->css('redesign/main.css') ?>
<?= $this->Html->css('redesign/mainmenu.css') ?>
<?= $this->Html->css('/bootstrap/css/bootmetro-icons.css') ?>
<?= $this->Html->css('font-awesome.min.css') ?>

<?= $this->Html->script('jquery-1.10.0.min') ?>
<?= $this->Html->script('/bootstrap/js/bootstrap.min.js') ?>
<?= $this->Html->script('/js/jquery-1.10.0.min.js') ?>
<?= $this->Html->script('/js/jquery-ui.min.js') ?>
<?= $this->Html->script('/js/main.js') ?>

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
        <? if (isset($title)) echo $title; else echo __($this->fetch('title'));  ?>
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <?= $this->Html->meta('icon') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

</head>
<body>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(53036506, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/53036506" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<div class="w">
<nav class="navbar navbar-default">
  <!-- Контейнер (определяет ширину Navbar) -->
  <div class="container-fluid">
    <!-- Заголовок -->
    <div class="navbar-header">
      <!-- Кнопка «Гамбургер» отображается только в мобильном виде (предназначена для открытия основного содержимого Navbar) -->
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <!-- Бренд или название сайта (отображается в левой части меню) -->
      <a class="navbar-brand" href="/"><img src="/img/logo.png" alt="Ваш личный тренер"/> </a>
    </div>
    <!-- Основная часть меню (может содержать ссылки, формы и другие элементы) -->
    <div class="collapse navbar-collapse" id="navbar-main">
      <!-- Содержимое основной части -->
      <ul class="nav navbar-nav">
        <li <? if ($section=="training") echo 'class="active"' ?>><a href="/trainingprogram">Тренировка</a></li>
        <li <? if ($section=="routine") echo 'class="active"' ?>><a href="/routine">Распорядок дня</a></li>
        <li <? if ($section=="eating") echo 'class="active"' ?>><a href="/nutritionprogram">Программа питания</a></li>
        <li <? if ($section=="diary") echo 'class="active"' ?>><a href="/diary">Дневник</a></li>
        <li class="divider-vertical"></li>
        <li <? if ($section=="calc") echo 'class="active"' ?> class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-calculator" aria-hidden="true"></i><span>Калькуляторы</span> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="/calculator/somatype">Определение телосложения</a></li>
          <li><a href="/calculator/kalories">Суточная потребностьв калориях</a></li>
        </ul>
        </li>
        <li <? if ($section=="guide") echo 'class="active"'?> class="dropdown" ><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-book" aria-hidden="true"></i><span>Справочники</span> <b class="caret"></b></a>
    <ul class="dropdown-menu">
      <li><a href="/guide/exercises">Упражнения</a></li>
      <li><a href="/guide/products">Продукты питания</a></li>
    </ul>
        </li>
      </ul>
      
    </div>
    <ul class="nav navbar-nav right">
        <li <? if ($section=="profile") echo 'class="active"' ?>><a href="/profile">Профиль</a></li>
      </ul>
  </div>
</nav>
<main>
  <div class="container">
        <?= $this->fetch('content') ?>

    </div>
  </main>
<footer class="navbar-fixed-bottom">
  <div class="container">
    <div class="copyright">ИТ КОНЦЕПТ &copy; 2017 - <?= date('Y')?></div>
    <div class="contacts">
      <span class="title">Наши контакты:</span><br>
      <!--<span class="glyphicon glyphicon-earphone"></span><a href="tel:+7(4862)42-36-12"> +7 (4862) 42-36-12</a><br>-->
        <span class="glyphicon glyphicon-envelope"></span><a href="mailto:support@coach-me.ru"> support@coach-me.ru </a>
      </div>
  </div>
</footer>
</div>
</body>
</html>

