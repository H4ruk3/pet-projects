<?= $this->Html->css('redesign/routine.css') ?>
<?= $this->Html->css('redesign/profile.css') ?>
<?= $this->Html->css('admin/user.css') ?>
<style>
  .cap span {
  font-size: 1vw;
  }

</style>

<div class="row">
		<div id="left" class="col-lg-4 left">
      <div class="block">    

      <div class="row box cap" >
        <span>Список категорий продуктов</span>
      </div>

      <? foreach($foodcategories as $foodcategorie) { ?>   

      <div id="content" class="row box content contentleft item">
        <div class="header">
        <h2 id=<?= $foodcategorie->id ?>><?= $foodcategorie->name ?></h2>
      </div>
        
        <div class="form">
        <div class="row">
          <div class="btn" onclick="edit(<?= $foodcategorie->id ?>, '<?= $foodcategorie->name ?>');"><span class="glyphicon glyphicon-pencil"></span></div>
          <a href="/admin/foodcategory/delete/<?= $foodcategorie->id?>"><div class="btn"><span class="glyphicon glyphicon-trash"></span></div></a>
        </div>
      </div>
      </div>
      <? } 
    ?>
      
<div class="btnblock">
  
      <input type="button" class="btn-11" data-target="#myModalBox" data-toggle="modal" value ="Добавить">
    </div>
      </div>
    </div>
		<div class="col-lg-8 ">

    

  </div>
</div>

<!-- Модальное окно -->
<style>
  .checkbox {
    padding-left: 30px;
}
.modal-footer>input {
  width:auto;
}
</style>
<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Добавление категории продуктов</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <form method="POST" action="/admin/foodcategory/create">
      <div class="modal-body">
        
            <label>Название категории продуктов</label>
            <input type="text" name="name">
        
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <input type="button" class="" data-dismiss="modal" value="Отмена">
        <input type="submit" class="" value="Создать">
        
      </div>
      </form>
    </div>
  </div>
</div>

<div id="myModalBox1" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Изменение категории продуктов</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <form method="POST" action="/admin/foodcategory/edit">
      <div class="modal-body">
            <input type="hidden" name="id" id="id">
            <label>Название категории продуктов</label>
            <input type="text" name="name" id="name">
        
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <input type="button" class="" data-dismiss="modal" value="Отмена">
        <input type="submit" class="" value="Изменить">
        
      </div>
      </form>
    </div>
  </div>
</div>


<script type="text/javascript" src="/js/jsrender.min.js"></script>
  <script type="text/javascript" src="/js/jquery-ui.min.js"></script>

<script type="text/javascript">
  /*function addgroup() {
    $.POST()
  }*/

    function edit(id, name) {
      $("#myModalBox1 #id").val(id);
      $("#myModalBox1 #name").val(name);
      $("#myModalBox1").modal('show');
    }
</script>