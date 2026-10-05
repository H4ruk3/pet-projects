<div id="logout-form">
    <?= $this->Form->create() ?>
    <? $this->Form->templates(array("inputContainer" => "{{content}}")); ?>
    <div id="logout-form-username">Пользователь</div>
    <?= $this->Form->button('Выйти <i class="fa fa-lg fa-sign-out" aria-hidden="true"></i>', array('name' => 'logout', 'type' => 'submit')); ?>
    <?= $this->Form->end(); ?>
</div>