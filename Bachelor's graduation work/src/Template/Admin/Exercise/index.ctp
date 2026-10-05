<?= $this->Html->css('redesign/routine.css') ?>

<style>
.form {
      padding-top: 0px;
}
.panel .collapse, .panel .collapsing {
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
                    <div class="form">
        <div class="row">
          <!--<div class="btn" onclick="edit(<?= $musculgroup->id ?>, '<?= $musculgroup->name ?>');"><span class="glyphicon glyphicon-pencil"></span></div>-->
          <a href="/admin/exercise/edit/<?= $excersice->id?>"><div class="btn"><span class="glyphicon glyphicon-pencil"></span></div></a>
          <a href="/admin/exercise/delete/<?= $excersice->id?>"><div class="btn"><span class="glyphicon glyphicon-trash"></span></div></a>
        </div>
      </div>
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
  
      <input type="button" class="btn-11" onclick="document.location = '/admin/exercise/create'" value ="Добавить">
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



<script type="text/javascript">
  var exercisedesc = [];

  <? foreach($execisesdesc as $key => $ex) {?>
  exercisedesc[<?= $key ?>] = <?= utf8_decode(json_encode($ex));?>;
  <? } ?>

  function show(id) {

  var data = '<div id="menu-' + exercisedesc[id].id + '" class="excersicedata"><h2>' + exercisedesc[id].name + '</h2><img src="/img/excersices/' + (exercisedesc[id].img != null?exercisedesc[id].img:'no_image_available.jpg') + '">' + exercisedesc[id].desc + (exercisedesc[id].video != null?('<div><video src="/video/exercises/' + exercisedesc[id].video +'" controls></video></div>'):'') +'</div>';
    $('#contentblock').empty();
    $('#contentblock').append(data);
    
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