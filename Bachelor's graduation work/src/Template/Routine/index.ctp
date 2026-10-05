<?= $this->Html->css('redesign/routine.css') ?>

<?= $this->fetch("content") ?>

<div class="row">
	<div id="left" class="col-lg-4 col-sm-4 col-md-4 left">
    <div class="block">
			<? 
      if (isset($routines) && (count($routines) > 0)) { 
      $active = null;
      foreach($routines as $routine) {
        if ($routine->active)
        {
          $active = $routine;
          break;
        }
      }
      if ($active != null) {
      ?>
      <div class="row box cap">
        <span>Активный распорядок дня</span>
			</div>
			<div id="content" class="row box content contentleft active" style="margin-bottom: 10px">
        <div class="header">
				  <h2 id=<?= $active->id ?>><?= $active->name ?></h2>
        </div>
        <div class="form">
          <div class="row">
            <a href="/routine/edit/<?= $routine->id?>"><div class="btn"><span class="glyphicon glyphicon-pencil" ></span></div></a>
            <div class="btn" onclick="show(<?= $active->id ?>);"><span class="glyphicon glyphicon-eye-open" ></span></div>
          </div>
        </div>
			</div>
      <? } ?>

      <div class="row box cap" >
        <span>Список распорядков дня</span>
      </div>
      <? foreach($routines as $routine) {
        if (!$routine->active) { ?> 

      <div id="content" class="row box content contentleft">
        <div class="header">
          <h2 id=<?= $routine->id ?>><?= $routine->name ?></h2>
        </div>
        
        <div class="form">
          <div class="row">
            <a href="/routine/edit/<?= $routine->id?>"><div class="btn"><span class="glyphicon glyphicon-pencil"></span></div></a>
            <div class="btn" onclick="show(<?= $routine->id ?>);"><span class="glyphicon glyphicon-eye-open"></span></div>
            <a href="/routine/delete/<?= $routine->id?>"><div class="btn"><span class="glyphicon glyphicon-trash"></span></div></a>
            <a href="/routine/active/<?= $routine->id?>"><div class="btn"><span class="glyphicon glyphicon-ok"></span></div></a>
          </div>
        </div>
      </div>
      <? } 
    }
  } ?>
    </div>
    <? if ($this->Paginator->params()['pageCount'] > 1) { ?>
    <ul class="pagination">
      <?= $this->Paginator->prev('Предыдущие') ?>
      <?= $this->Paginator->numbers() ?>
      <?= $this->Paginator->next('Следующие') ?>
    </ul>
    <?}?>
    <div class="btnblock">

      <input type="button" class="btn-11" <?= isset($assign)?'data-target="#myModalBox" data-toggle="modal" value ="Назначить"':'onclick="window.location = \'routine/create\'" value = "Создать"' ?>>
    </div>
  </div>
	<div class="col-lg-8 col-sm-8 col-md-8 routineinfo">
    <div class="row box cap">
      <span id="routinename">Подробная информация</span>
    </div>
    <!--Содержимое левого блока-->
    <div id="contentblock" class="row box content">
      <? if (! (is_object($routines) && count($routines) > 0)) echo "<div class='empty'><h4 class='emptymsg'>У вас нет ни одного распорядка дня. Создайте новый распорядок дня.</h4></div>"; ?> 
    </div>

    <div class="btnblock backbutton">
      <input id="backbutton" type="button" class="btn-11" onclick="togglewindows()" value = "К списку распорядков" >
    </div>
  </div>
</div>

<!-- Модальное окно -->
<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Создание распорядка дня</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
        <label class="checkbox"><input type="checkbox" class="mycheck" id="mycheck" value="Создать на основе шаблона">Создать на основе шаблона</label>
        <select id="tmpprogs" class="form-control">
          <? if (isset($assign)) { foreach($templates as $routine) { ?>   
          <option value=<?= $routine->id; ?> > <?= $routine->name ?> </option>
          <? } } else { foreach($routines as $routine) { ?>   
          <option value=<?= $routine->id; ?> > <?= $routine->name ?> </option>
          <? } } ?>
        </select>
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <input type="button" class="" data-dismiss="modal" value="Отмена">
        <input type="button" class="" onclick="submit(<?= $user['id'] ?>);" value="Создать">
      </div>
    </div>
  </div>
</div>

<script src="/js/routine.js"></script>
<script type="text/javascript">
  var routinesinfo = [];
  <? foreach($routines as $routine) { ?>
    routinesinfo[<?= $routine->id ?>] = <?= json_encode($routine->routineday);?>;
  <? } ?>

  var width = window.innerWidth;
  if (width<=768) {
    $(".routineinfo").css("display", "none");
  }

  

  <? if (isset($active) && $active != null)
    echo "if (width >= 768) show(" . $active->id . ")";
  ?>;
</script>