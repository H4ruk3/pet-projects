<?= $this->Html->css('admin/user.css') ?>

<?php  $this->extend('/Diary/index'); $this->end();  ?>

<?
    $this->Html->addCrumb('Пользователи', ['controller' => 'user', 'action' => 'index']);
	if (isset($admin))
		$this->Html->addCrumb($user->username, '/admin/users/userinfo/'.$user->id);	
	else
		$this->Html->addCrumb($user->username, '/user/userinfo/'.$user->id);
	$this->Html->addCrumb("Дневник", '#');
echo $this->Html->getCrumbList(array(
  'lastClass' => 'current',
  'id' => 'breadcrumb',
  'escape' => false
)); ?>

