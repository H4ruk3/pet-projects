<?= $this->Html->css('admin/user.css') ?>

<?php  $this->extend('/Routine/create'); $this->end();  ?>

<?
    $this->Html->addCrumb('Пользователи', ['controller' => 'user', 'action' => 'index']);
	$this->Html->addCrumb($user->username, '/user/userinfo/'.$user->id);
	$this->Html->addCrumb("Распорядки дня", '/user/viewuserroutine/'.$user->id);
	$this->Html->addCrumb("Создание распорядка дня", '#');
echo $this->Html->getCrumbList(array(
  'lastClass' => 'current',
  'id' => 'breadcrumb',
  'escape' => false
)); ?>

