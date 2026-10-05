<style>
.col-xs-12 {
  float: none;
}
input {
  float: none;
}
</style>

<h4 class = "head">Добавление группы мышц</h4>

<div class = "style_form">

<?php 

echo $this->Form->create(); ?>

<div id="step1content">

<?php

    echo $this->Form->input('name', [
      'class' => 'form-control',
      'templateVars' => ["id"=>"name", "label"=>"Название", "type"=>"text", "name"=>"name", "value"=>""]
    ]);

    echo $this->Form->input(__('Save'), array(
      'type' => 'submit',
      'class' => 'btn btn-primary', 
      'onclick' => 'onSubmit();'
    ));
    
?>