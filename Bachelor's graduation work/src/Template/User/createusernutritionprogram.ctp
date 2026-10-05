

<?php  $this->extend('/Nutritionprogram/create'); $this->end();  ?>

<?
    $this->Html->addCrumb('Пользователи', ['controller' => 'user', 'action' => 'index']);
	if (isset($admin))
		$this->Html->addCrumb($user->username, '/admin/users/userinfo/'.$user->id);	
	else
		$this->Html->addCrumb($user->username, '/user/userinfo/'.$user->id);
	$this->Html->addCrumb("Программы питания", '/user/usernutritionprogram/'.$user->id);
	if (isset($mode) && ($mode == "create"))
		$this->Html->addCrumb("Создание программы питания", '#');
	else if (isset($mode) && ($mode == "edit"))
		$this->Html->addCrumb("Редактирование программы питания", '#');
echo $this->Html->getCrumbList(array(
  'lastClass' => 'current',
  'id' => 'breadcrumb',
  'escape' => false
)); ?>

