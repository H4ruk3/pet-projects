

<?php  $this->extend('/Trainingprogram/create'); $this->end();  ?>

<?
    $this->Html->addCrumb('Пользователи', ['controller' => 'user', 'action' => 'index']);
	if (isset($admin))
		$this->Html->addCrumb($user->username, '/admin/users/userinfo/'.$user->id);	
	else
		$this->Html->addCrumb($user->username, '/user/userinfo/'.$user->id);
	$this->Html->addCrumb("Тренировочные программы", '/user/usertrainingprogram/'.$user->id);
	if (isset($mode) && ($mode == "create"))
		$this->Html->addCrumb("Создание тренировочной программы", '#');
	else if (isset($mode) && ($mode == "edit"))
		$this->Html->addCrumb("Редактирование тренировочной программы", '#');
echo $this->Html->getCrumbList(array(
  'lastClass' => 'current',
  'id' => 'breadcrumb',
  'escape' => false
)); ?>

