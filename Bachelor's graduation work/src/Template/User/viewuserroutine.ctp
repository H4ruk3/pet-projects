<?= $this->Html->css('redesign/routine.css') ?>
<?= $this->Html->css('redesign/profile.css') ?>
<?= $this->Html->css('admin/user.css') ?>

<?php  $this->extend('/Routine/index'); $this->end();  ?>

<?
    if ($userrole == "admin") 
    	$this->Html->addCrumb('Пользователи', ['prefix' => 'admin', 'controller' => 'users', 'action' => 'index']);
    else
    	$this->Html->addCrumb('Участники', ['controller' => 'user', 'action' => 'index']);
	if (isset($admin))
		$this->Html->addCrumb($user->username, '/admin/users/userinfo/'.$user->id);	
	else
		$this->Html->addCrumb($user->username, '/user/userinfo/'.$user->id);
	$this->Html->addCrumb("Распорядок дня", '#');
echo $this->Html->getCrumbList(array(
  'lastClass' => 'current',
  'id' => 'breadcrumb',
  'escape' => false
)); ?>

