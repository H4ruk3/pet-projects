<div class = "style_form">
    <br>
    <?= $this->Form->create() ?>
    <fieldset>
        <?= $this->Form->input('username', array('label' => 'Логин', 'class' => 'form-control', 'templateVars' => ["id"=>"username", "label"=>"Логин", "type"=>"text", "name"=>"username", "value"=>""])) ?>
        <?= $this->Form->input('password', array('label' => 'Пароль', 'class' => 'form-control', 'templateVars' => ["id"=>"password", "label"=>"Пароль", "type"=>"password", "name"=>"password", "value"=>""])) ?>
    </fieldset>
    <br>
    <?= $this->Form->button('Войти', ['class' => 'btn btn-primary']); ?>
    &nbsp<a href = "reg" class = 'btn btn-info'>Пройти регистрацию</a>
    <?= $this->Form->end() ?>
</div>