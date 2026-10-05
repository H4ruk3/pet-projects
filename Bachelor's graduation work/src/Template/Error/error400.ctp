<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

//echo(json_encode($error));
/*var_dump($event);*/
//print_r ($obj);
//$this->layout = 'error';

/*if (isset($user)) {
    var_dump($user);
}*/
/*print_r($event->subject->RequestHandler->request);
foreach ($event->subject->RequestHandler as $key => $value){
    echo "$key => $value </br>";
}*/
/*
$this->layout = 'error';

if (Configure::read('debug')):
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error400.ctp');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
        <?php Debugger::dump($error->params) ?>
<?php endif; ?>
<?= $this->element('auto_table_warning') ?>
<?php
    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;

    $this->end();
endif;
?>
<h2><?= h($message) ?></h2>
<p class="error">
    <strong><?= __d('cake', 'Error') ?>: </strong>
    <?= sprintf(
        __d('cake', 'The requested address %s was not found on this server.'),
        "<strong>'{$url}'</strong>"
    ) ?>
</p>
*/
?>

<style type="text/css">
    .dialogwraper {
  display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100%;
    height: auto;
}
.dialog {
    width: 80%;
    max-width:600px;
    background-color: rgb(245, 245, 245);
    box-shadow: 0px 0px 3.84px 4.16px rgba(56, 56, 56, 0.1);
    padding: 20px 0px;
  }
  .logo {
    text-align: center;
}
.dialog h1 {
    text-align: center;
    margin: 20px 0;
    font-size: 24px;
    font-family: "Arial";
    color: rgb(97, 97, 97);
}
.w {
    min-height: 100%;
}
main {
        position: absolute;
    width: 100%;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
}
</style>

<?= $this->Html->css('redesign/modal.css');?>
<div class="dialogwraper">
    <div class=dialog>
        <p class="logo">
            <img src="/img/logo_site.png" />
        </p>
        <H1>Запрошенная вами страница недоступна</H1>
    </div>
</div>