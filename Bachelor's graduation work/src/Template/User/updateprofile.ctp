<?= $this->Html->css('admin/user.css') ?>

<?php  $this->extend('/Profile/create'); $this->end();  ?>

<?
    $this->Html->addCrumb('Пользователи', ['controller' => 'user', 'action' => 'index']);
	$this->Html->addCrumb($user1->username, '/user/userinfo/'.$user1->id);
	$this->Html->addCrumb("Изменение данных профиля", '#');
echo $this->Html->getCrumbList(array(
  'lastClass' => 'current',
  'id' => 'breadcrumb',
  'escape' => false
)); ?>

