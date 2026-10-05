<?= $this->Html->css('redesign/routine.css') ?>
<?= $this->Html->css('redesign/modal.css') ?>

<style>
.form {
      padding-top: 0px;
}
.panel .collapse {
    margin: 15px;
    margin-bottom: 0px;
}
.panel-collapse.collapse hr {
      margin: 0 -54px;
    border-color: #ddd;
}

.panel {
  box-shadow: none;
}
.panel-default {
  margin: 0 -40px;
}
.panel-default .panel-heading {
  color: rgb(56, 56, 56);
background-color: #ffffff;
border-color: #ddd;
    border-bottom: 1px solid #d5d5d5;
    padding: 10px 40px;
    border-radius: 0px;
    
}
.panel-default .panel-heading>h4 {
  font-weight: bold;
}
.panel-default .panel-heading:hover {
      background-color: #ddd;
}
.panel-default a[aria-expanded=true] .panel-heading {
  background-color: #589be6;
  color: #ffffff;
}
.panel-default .panel-heading.colapsed{
  background-color: #589be6;
  color: #ffffff    ;
}
.panel-group .panel+.panel {
    margin-top: 0px;
}
.panel-group .panel {
  border-radius: 0px;
  border: none;
}

.number {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 24px;
    -ms-flex: 0 0 24px;
    flex: 0 0 24px;
    max-width: 24px;
    height: 24px;
    /*background-color: rgb(56, 56, 56);*/
    line-height: 24px;
    text-align: center;
    color: rgb(56, 56, 56);
    border-radius: 4px;
        font-weight: normal;
}
.itemtitle {
    padding-top: 5px;
    padding-left: 7px;
    line-height: 1!important;
    -webkit-flex-basis: 0;
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
    color: #ffa726;
}
.itemtitle a{
    color: rgb(56, 56, 56);
    font-weight: normal;
}
.itemtitle a:hover{
    color: rgb(56, 56, 56);
    text-decoration: none;
}
.item {
    align-items: center;
    border-bottom: 1px dashed #ddd;
    padding-top: 7px;
    padding-bottom: 7px;
}
.item.last {
  border:none;
}
.item>.wraper {
        width: 100%;
    display: flex;
}

.excersice {
    
margin: 0;
    padding: 0 60px;
position: relative;
}
.excersice .exclose {
    position: absolute;
top: 10px;
right: 20px;
}
a:focus, a:hover {
  text-decoration: none;
}

.excersicedata {
  padding: 20px;
  box-sizing: border-box;
}
.excersicedata>img {
max-width: 200px;
      float: left;
    padding-right: 20px;
    padding-bottom: 20px;
}
.excersicedata>h2 {
      text-align: center;
    font-size: 16px;
}
.excersicedata>p {
    font-size: 14px;
    margin: 0;
}
.helpblock.affix {
  width: 55.66%;
    height: auto;
}
.btnblock>input {
  margin-bottom: 10px;
}
.glyphicon:hover {
  color: #ffa726;
  cursor: pointer;
}
.panel-heading {
  display:flex;
}
.panel-heading>h4 {
  flex-grow: 1;
}
.glyphicon {
  width:20px
}
</style>
<div class="row">
		<div id="left" class="col-lg-4 left">
      <div class="block">
			   <div class="panel-group" >
          <div class="form" id="accordion">
  <!-- 1 панель -->
  
          <? $execisesdesc = []; $id = 0; foreach($musculgroups as $musculgroup) {  ?>

            <div class="panel panel-default">
            <!-- Заголовок 1 панели -->
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $id ?>" <?= $id==0?"aria-expanded='true'":""; ?>>
                <div class="panel-heading pantitle">
                  <h4 class="panel-title">
                    <?= $musculgroup->name ?>
                  </h4>
                  <? if ($musculgroup->owner == $user["id"]) { ?>
                    <span class="glyphicon glyphicon-pencil" onclick = "edit(<?= $musculgroup->id ?>, '<?= $musculgroup->name ?>');"></span>
                    <span class="glyphicon glyphicon-trash" aria-hidden="true" onclick="document.location = 'deletemusculgroup/<?= $musculgroup->id ?>'"></span>
                  <? } ?>
                </div>
              </a>
              <div id="collapse<?= $id ?>" class="panel-collapse collapse <?if ($id==0) echo("in");?>" <?if ($id==0) echo("aria-expanded = true"); else echo("aria-expanded = false");?>>
                <? $j = 1; foreach($musculgroup->exercises as $excersice) { 
                  $execisesdesc[$excersice->id] = array ('id' => $excersice->id, 'name' => $excersice->name, 'img' => $excersice->img, 'desc' => $excersice->description, 'video' => $excersice->video );
                ?>
                <div class="item <?= $j==count($musculgroup->exercises)?"last":""?> title="">
                  <div class="wraper drugel" id="<?= $excersice->id ?>" onclick="show(<?= $excersice->id ?>);">
                    <div class="number"><?= $j ?></div>
                    <div class="itemtitle"><a href="#" ><?= $excersice->name; ?></a></div>
                    <? if ($excersice->owner == $user["id"]) { ?>
                    <span class="glyphicon glyphicon-pencil" onclick = "document.location = 'editexercise/<?= $excersice->id ?>'"></span>
                    <span class="glyphicon glyphicon-trash" aria-hidden="true" onclick="document.location = 'deleteexercise/<?= $excersice->id ?>'"></span>
                  <? } ?>
                  </div>
                </div>
              <? $j++; } ?>
              <hr>
            </div>
          </div>
          <? $id = $id + 1;} ?>
        </div>
      </div>

      <div class="btnblock">
        <input type="button" class="btn-11" onclick="document.location = 'createexercise'" value ="Добавить упражнение">
        <input type="button" class="btn-11" data-target="#addMusculgroup" data-toggle="modal" value ="Добавить группу">
      </div>
      
      </div>
    </div>
		<div class="col-lg-8 ">

    <div class="row box cap">
        <span id="routinename">Подробная информация</span>
      </div>
    
    <!--Содержимое левого блока-->
    <div id="contentblock" class="row box content" >

    </div>

  </div>
</div>
<style>
  

</style>

<!-- Модальное окно -->
<div id="addMusculgroup" class="modal fade">
  <div class="modal-dialog">
    <form id="addMusculgroup" method="POST" action="createmusculgroup">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Добавление группы мышц</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
      
        <input type=text name="name" />
      

      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <input type="button" class="" data-dismiss="modal" value="Отмена">
        <input type="submit" class="" value="Создать">
      </div>
    </div>
    </form>
  </div>
</div>


<!-- Модальное окно -->
<div id="editMusculgroup" class="modal fade">
  <div class="modal-dialog">
    <form id="addMusculgroup" method="POST" action="editmusculgroup">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Добавление группы мышц</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
      
        <input id="id" type="hidden" name="id" />
        <input id="name" type=text name="name" />
      

      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <input type="button" class="" data-dismiss="modal" value="Отмена">
        <input type="submit" class="" value="Сохранить">
      </div>
    </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  var exercisedesc = [];

  <? foreach($execisesdesc as $key => $ex) {?>
  exercisedesc[<?= $key ?>] = <?= utf8_decode(json_encode($ex));?>;
  <? } ?>

  function show(id) {

  var data = '<div id="menu-' + exercisedesc[id].id + '" class="excersicedata"><h2>' + exercisedesc[id].name + '</h2><img src="/img/excersices/' + (exercisedesc[id].img != null&&exercisedesc[id].img.length > 0?exercisedesc[id].img:'no_image_available.jpg') + '">' + exercisedesc[id].desc + (exercisedesc[id].video != null?('<video src="' + exercisedesc[id].video +'" controls></video>'):'') +'</div>';
    $('#contentblock').empty();
    $('#contentblock').append(data);
    
  }

  function edit(id, name) {
      $("#editMusculgroup #id").val(id);
      $("#editMusculgroup #name").val(name);
      $("#editMusculgroup").modal('show');
    }

  /*function showday1() {
      $(".day1").attr("style", "display: block");
      $(".day2").attr("style", "display: none");
      $("#btn2").removeClass("active");
      $("#btn1").addClass("active");
    }
    function showday2() {
      $(".day2").attr("style", "display: block");
      $(".day1").attr("style", "display: none");
      $("#btn1").removeClass("active");
      $("#btn2").addClass("active");
    }*/
//$('#leftblock').perfectScrollbar();
  </script>