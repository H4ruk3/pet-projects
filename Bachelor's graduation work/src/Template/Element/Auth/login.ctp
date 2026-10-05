<div id="login-form">
    <?= $this->Form->create() ?>
    <? $this->Form->templates(array("inputContainer" => "{{content}}")); ?>
    <div class="input-group input-group-sm">
        <span class="input-group-addon"><i class="fa fa-lg fa-user color-blue"></i></span>
        <?= $this->Form->input('username', array('label' => "", 'placeholder' => 'Логин',
            'id' => 'username', "div" => false)) ?>
    </div>
    <div class="input-group input-group-sm">
        <span class="input-group-addon"><i class="fa fa-lg fa-lock color-blue"></i></span>
        <?= $this->Form->input('password', array('label' => "", 'placeholder' => 'Пароль', 'id' => 'password')) ?>
    </div>
    <?= $this->Form->input('remember', array('type' => 'checkbox', 'label' => 'Запомнить?',
        'id' => 'remember')) ?>
    <a href="#" id="forgot-pass-link">Забыли пароль?</a>
    <?= $this->Form->button('Войти <i class="fa fa-lg fa-sign-in" aria-hidden="true"></i>', array('name' => 'login', 'type' => 'submit')); ?>
    <div id="create-account-link"><a href="#">Создать аккаунт</a></div>
    <?= $this->Form->end(); ?>
</div>