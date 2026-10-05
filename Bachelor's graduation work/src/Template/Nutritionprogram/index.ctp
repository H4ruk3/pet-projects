<?= $this->Html->css('redesign/routine.css') ?>
<?= $this->Html->css('redesign/modal.css') ?>
<?= $this->Html->css('redesign/nutritionprogram.css') ?>
<?= $this->Html->css('/perfectscrollbar/css/perfect-scrollbar.min.css') ?>

<?= $this->fetch("content") ?>
<div class="row">
    <div id="left" class="col-lg-4 left">
    <?= $this->Flash->render() ?>
    
      <div class="block">
      <? $active = null;
      if (isset($eatingprograms) && (count($eatingprograms) > 0)) { 
      foreach($eatingprograms as $eatingprogram) {
        if ($eatingprogram->active && $eatingprogram->routine_id == $activeroutineid)
        {
          $active = $eatingprogram;
          break;
        }
      }
      if ($active != null) {
      ?>
      
      <div class="row box cap">
        <span>Активная программа питания</span>
      </div>
      <div id="content" class="row box content contentleft active">
        <div class="header">
        <h2 id=<?= $active->id ?>><?= $active->name ?></h2>
        <? if($active->lastmodified != null) { ?>
        <p class="datecreate">Дата последнего изменеия: <span><?= $active->lastmodified->format("Y-m-d H:i")?></span></p>
        <? } ?>
      </div>
        <div class="form">
        <div id="buttons" class="row">
          <a href="<? if (isset($assign)) 
            echo '/user/editusernutritionprogram/'.$user->id.'/'.$active->id;
          else 
            echo '/nutritionprogram/edit/'.$active->id;
          ?>"><div class="btn"><span class="glyphicon glyphicon-pencil" ></span></div></a>
          <div class="btn" onclick="show(<?= $active->id ?>);"><span class="glyphicon glyphicon-eye-open" ></span></div>
        </div>
      </div>
      </div>

      <? } ?>
      <div class="row box cap" style="margin-top:10px; height: auto">
        <span>Список программ питания</span>
      </div>
      <div id="progs">
      <? foreach($eatingprograms as $program) {

        if (!($program->active)) {  
        if (!isset($first))
          $first = $program; ?>
      <div id="content" class="row box content contentleft">
        <div class="header">
        <h2 id=<?= $program->id ?>><?= $program->name ?></h2>
        <? if($program->lastmodified != null) { ?>
        <p class="datecreate">Дата последнего изменеия: <span><?= $program->lastmodified->format("Y-m-d H:i")?></span></p>
        <? } ?>
      </div>

        <div class="form">
        <div id="buttons" class="row">
          <a href="
          <? if (isset($assign)) 
            echo '/user/editusernutritionprogram/'.$user->id.'/'.$program->id;
          else 
            echo '/nutritionprogram/edit/'.$program->id;
          ?>
            "><div class="btn"><span class="glyphicon glyphicon-pencil"></span></div></a>
          <div class="btn" onclick="show(<?= $program->id ?>);"><span class="glyphicon glyphicon-eye-open"></span></div>
          <a href="
          <? if (isset($assign)) 
            echo '/user/deletenutritionprogram/'.$user->id.'/'.$program->id;
          else 
            echo '/nutritionprogram/delete/'.$program->id;
          ?>"><div class="btn"><span class="glyphicon glyphicon-trash"></span></div></a>
          <a href="
          <? if (isset($assign)) 
            echo '/user/activenutritionprogram/'.$user->id.'/'.$program->id;
          else 
            echo '/nutritionprogram/active/' . $program->routine_id . "/" . $program->id;
          ?>
           " <?if ($program->routine_id != $activeroutineid) echo "style='pointer-events:none; display: inline-block' disabled data-title='Нельзя сделать активной программу тренировок не соответствующую текущей цели.'";?>><div class="btn"><span class="glyphicon glyphicon-ok"></span></div></a>
        </div>
      </div>
      </div>
      <? } 
    } ?>
    <!-- Рендеринг шаблонов. -->
<? if(isset($template)) foreach($template as $program) {
if (!($program->active)) {  
  if (!isset($first))
    $first = $program; 
    ?>
<div id="content" class="row box content contentleft">
<div class="ribbon ribbon-top-right"><span>шаблон</span></div>
  <div class="header">
  <h2 id=<?= $program->id ?>><?= $program->name ?></h2>
  <? if($program->lastmodified != null) { ?>
        <p class="datecreate">Дата последнего изменеия: <span><?= $program->lastmodified->format("Y-m-d H:i")?></span></p>
        <? } ?>
</div>

<div class="form">
<div id="buttons" class="row">
  <div class="btn" onclick="show(<?= $program->id ?>);"><span class="glyphicon glyphicon-eye-open"></span></div>
</div>
</div>
</div>
<? } 
} ?>


    </div>  
<? } else { ?>
      <div class="row box cap">
        <span>Список программ питания</span>
      </div>
      <div id="progs">
      <!-- Рендеринг шаблонов. -->
<? if(isset($template)) foreach($template as $program) {
if (!($program->active)) {  
  if (!isset($first))
    $first = $program; 
    ?>
<div id="content" class="row box content contentleft">
<div class="ribbon ribbon-top-right"><span>шаблон</span></div>
  <div class="header">
  <h2 id=<?= $program->id ?>><?= $program->name ?></h2>
  <? if($program->lastmodified != null) { ?>
        <p class="datecreate">Дата последнего изменеия: <span><?= $program->lastmodified->format("Y-m-d H:i")?></span></p>
        <? } ?>
</div>

<? if (!$program->can_use) { ?>
<div class="tmpwarning" alt="Для данной программы питания нет ни одного распордка дня соответствующего по количеству приёмов пищи. Данная программа не может быть активирована или использована в качестве шаблона для создания новых программ."><img src="/img/warning_icon.png" alt="Предупреждение" /><span>Нет соответствующего распорядка дня</span>
<div class="altmsg">
Для данной программы питания нет ни одного распордка дня соответствующего по количеству приёмов пищи. Данная программа не может быть активирована или использована в качестве шаблона для создания новых программ.
</div>
</div>
<? } ?>

<div class="form">
<div id="buttons" class="row">
  <div class="btn" onclick="show(<?= $program->id ?>);"><span class="glyphicon glyphicon-eye-open"></span></div>
</div>
</div>
</div>
<? } 
} ?>
</div>
    <? } ?>
      </div>
      <input type="button" class="btn-11" data-target="#myModalBox" data-toggle="modal"  value =<?= isset($assign)?"Назначить":"Создать"?>>
    </div>
    <div class="col-lg-8 ">

    <div class="row box cap orange">
        <span id="progname">Подробная информация</span>
        <span id="routinename"></span>
      </div>
    
    <!--Содержимое левого блока-->
    <div id="contentblock" class="row box content helpblock">
      
    
<style>
.head {
  margin-left: 19%;
}
.c1 {
      border: 1px solid #122b40;
          border-radius: 4px;
          border-color: #ddd;
    /*border-top-left-radius: 3px;*/
    margin: 10px 0px;
    min-height: 100px
    /*padding: 10px;*/
}
.icon {
  width:15px;
  height:15px;
}
.dayheader {
    color: #fff;
        background-color: #ffa726;
    border-color: #204d74;
    padding: 1px 15px;
    border-bottom: 1px solid transparent;
    border-top-right-radius: 3px;
    border-top-left-radius: 3px;
}
.excersicedata{
  overflow: hidden;
}
.wp-block.property.list {
  border: 1px solid #e0eded;
  margin-bottom: 15px !important;
}
.wp-block {
  margin: 0 0 15px 0;
  -webkit-transition: all .3s linear;
  transition: all .3s linear;
  position: relative;
  cursor: default;
  border-radius: 2px;
}
.wp-block.property.list {
  padding: 15px 15px 0 15px;
}
.wp-block.property.list {
  margin: 0;
  padding: 0;
  font-size: 16px;
  font-weight: 400;
}
.wp-block .wp-block-body {
  padding: 15px;
}
.wp-block.property.list .wp-block-img {
  display: table-cell;
  width: 250px;
}
.wp-block.property.list .wp-block-img img {
  width: 100%;
}
.wp-block.property.list .wp-block-content .content-title {
  font-size: 20px;
  color: #3498db;
  margin-bottom: 5px;
}
.wp-block.property.list .wp-block-body .wp-block-content {
  display: table-cell;
  vertical-align: top;
  padding-left: 15px;
}
.wp-block.property.list .wp-block-content .description {
  padding-bottom: 10px;
  border-bottom: 1px solid #e0eded;
}
.wp-block.property.list .wp-block-footer ul.aux-info {
  width: 100%;
  margin: 0;
  padding: 0;
  display: block;
  background: #fcfcfc;
  border-top: 1px solid #e0eded;
}
.wp-block.property.list .wp-block-footer ul.aux-info li {
  display: table-cell;
  padding: 10px 15px;
  vertical-align: middle;
  border-right: 1px solid #e0eded;
}
.ribbon.base {
  background: #3498db;
  color: #fff;
  border-right: 5px solid #8bc4ea;
}
/*.ribbon {
  position: absolute;
  top: 20px;
  right: -5px;
  padding: 15px;
}*/
.base {
  background: #3498db;
  color: #fff !important;
}
.ribbon:before, .ribbon:after {
  content: '';
  position: absolute;
  left: -9px;
  border-left: 10px solid transparent;
}
.ribbon:before {
  top: 0;
}
.ribbon.base:before {
  border-top: 27px solid #3498db;
}
.ribbon.base:after {
  border-bottom: 27px solid #3498db;
}
.ribbon:after {
  bottom: 0;
}
.helpblock {
  height: calc(100% - 120px);
overflow: auto;
position: relative;
    width: 100%;
    padding: 0;
}
input[type="button"] {
  margin-top:10px;
}
.checkbox {
    padding-left: 30px;
}
.modal-footer>input {
  width:auto;
}
#infoblock {
  padding: 40px;
}
.tmpwarning {
  padding: 10px 20px;
}
.tmpwarning>img {
    height: 18px;
    padding-bottom: 4px;
    padding-right: 3px;
}
.altmsg {
  opacity:0;
      width: calc(100% - 40px);
    /* margin-right: 40px; */
    position: absolute;
    background: #fff;
    /* border: 1px solid #000; */
    box-shadow: 1;
    background-color: rgb(245, 245, 245);
    box-shadow: 0px 0px 3.84px 4.16px rgba(56, 56, 56, 0.1);
    height:0;
    display:none;
}
.tmpwarning:hover {
  cursor: pointer;
}
.tmpwarning:hover .altmsg{
  opacity:1;
  transition:1s;
  height:auto;
  display:block;
}
.select-items div.group {
  color: #cfcfcf;
  font-weight: normal;
}
#routinename {
      float: right;
    margin: 0 20px;
}
#routinename>span, #progname>span {
  font-weight: normal;
  font-size: 12px;
}
.datecreate>span {
  font-size: 1.2em;
}
.datecreate {
  margin: 0;
  padding-bottom: 5px;
}
#progname>span>span {
  padding-left:0;
}
a[disabled]>div {
    background-color: #cfcfcf;
}
</style>

<div id="infoblock" class="cont" style="left:17%">
    <? if (! (isset($eatingprograms) && (count($eatingprograms) > 0))) echo "<h4>У вас нет ни одной программы питания. Создайте новую программу питания.</h4>"; ?>     
    
    </div>

<script id="daytmp" type="text/x-jsrender">
  {{for days }}
    <div class="day" id="{{:id}}">
      <div class="header active day blue"><H3 id="dayname">День {{:#index+1}} - 
      {{if #data[0].day_type == 1}}
      День отдыха
      {{else}}
      Тренировочный день
      {{/if}}
      </H3>
      </div>
      <div class="exersicesblock">
        {{for #data }}
          <div class="eating">
              {{:number+1}} приём пищи {{:eating.time.substr(11, 5)}}
            </div>
            <table class="nutritiontable">
            <thead>
              <tr>
                <th className="table-header nutrition-caption-name">Продукт</th>
                <th className="table-header nutrition-caption-weight">Граммы</th>
                <th className="table-header nutrition-caption-pfc">Б</th>
                <th className="table-header nutrition-caption-pfc">Ж</th>
                <th className="table-header nutrition-caption-pfc">У</th>
                <th className="table-header nutrition-caption-calories">Ккал</th>
              </tr>
            </thead>
            <tbody>
            {{for foods}}
              <tr>
                <td style="width:25%">{{:food.name}}</td>
                <td style="width:25%">
                  {{:cnt}}
                </td>
                <td id="proteins" style="width:10%">{{:food.proteins}}</td>
                <td id="fats" style="width:10%">{{:food.fats}}</td>
                <td id="hidrocarbonats" style="width:10%">{{:food.hidrocarbonats}}</td>
                <td id="colories" style="width:15%">{{:food.colories}}</td>
              </tr>
            {{/for}}
            </tbody>
            
            </table>
        {{/for}}
      </div>
    </div>
  {{/for}}
</script>
<!--Шаблон одного приёма пищи-->
  <script id="eatingtmp" type="text/x-jsrender">
    <div class="eating">
              {{:eating}}
            </div>
            <table class="nutritiontable">
            <thead>
              <tr>
                <th className="table-header nutrition-caption-name">Продукт</th>
                <th className="table-header nutrition-caption-weight">Граммы</th>
                <th className="table-header nutrition-caption-pfc">Б</th>
                <th className="table-header nutrition-caption-pfc">Ж</th>
                <th className="table-header nutrition-caption-pfc">У</th>
                <th className="table-header nutrition-caption-calories">Ккал</th>
                <th className="button-contains nutrition-caption-delete"></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr className="total">
                <td colSpan="2" className="table-header">Норма</td>
                <td></td>
                <td></td>
                <td></td>
                <td>500</td>
                <td className="button-contains"></td>
              </tr>
              <tr class="total">
                <td colSpan="2" className="table-header">Фактическое значение</td>
                <td id="proteins"></td>
                <td id="fats"></td>
                <td id="hidrocarbonats"></td>
                <td id="colories"></td>
                <td className="button-contains"></td>
              </tr>
            </tfoot>
            </table>
            <div class="row drophear">
            <div class="col-lg-12 add droparea">
              Перетащите сюда продукты, что бы добавить их на этот день.
            </div>
  </script>
  <!--Шаблон одного приёма пищи-->
  <script id="onefoodtmp" type="text/x-jsrender">
    <tr>
      <td style="width:25%">{{:name}}</td>
      <td style="width:25%">
        <input id="id" type="hidden" name="foods[{{:day}}][{{:eating}}][{{:number}}][0]" value={{:productid}} />
        <input type="number" id="cnt" name="foods[{{:day}}][{{:eating}}][{{:number}}][1]" value={{:cnt}} class="input-edit"/>
      </td>
      <td id="proteins" style="width:10%">{{:proteins}}</td>
      <td id="fats" style="width:10%">{{:fats}}</td>
      <td id="hidrocarbonats" style="width:10%">{{:hidrocarbonats}}</td>
      <td id="colories" style="width:15%">{{:colories}}</td>
      <td style="width:5%"><span id="delete" class="glyphicon glyphicon-trash" aria-hidden="true"></span></td>
    </tr>
  </script>



 <script>
//$(function() {
    
var eatingprograms = <?= json_encode($eatingprograms); ?>;
for (var property in eatingprograms) {
    if (eatingprograms.hasOwnProperty(property)) {
        if (typeof eatingprograms[property].days === 'object')
          eatingprograms[property].days = Object.values(eatingprograms[property].days);
    }
}

<? if (isset($template)) { ?>
var templates = <?= json_encode($template);
?>;
for (var property in templates) {
  eatingprograms[property] = templates[property];
    if (eatingprograms.hasOwnProperty(property)) {
        if (typeof eatingprograms[property].days === 'object')
          eatingprograms[property].days = Object.values(eatingprograms[property].days);
    }
}
<? } ?>
//});

 $("#contentblock").on('affix-top.bs.affix', function(){
    //alert("fff");
    //this.offsetHeight = this.offsetHeight+40;
    //alert("s2");
    if (height >0)
        $(this).height(height);
     //$(this).outerHeight($(this).outerHeight() - 40)
  });
 $("#contentblock").on('affix-bottom.bs.affix', function(){
  //$(this).height(height + 40);
  $(this).removeAttr("style");
 });

var filters = [[],[]];
var order = 0;

function filter(cat, item, elem) {
  if (cat == 0) {
    if (item == 3)
    {
      filters[cat] = [];
      filters[cat][filters[cat].length] = item;
      $(elem).parent().children().removeClass('active');
      $(elem).addClass('active');
      showall();
      exit();
    } else {
      var ind = filters[cat].indexOf(item);
      if (ind == -1) {
        if (filters[cat][0] == 3) {
          filters[cat] = [];
          $(elem).parent().children().removeClass('active');
        }
        filters[cat][filters[cat].length] = item;
        $(elem).addClass('active');
      } else {
        if (filters[cat].length > 1) {
          filters[cat].splice(ind, 1);
          $(elem).removeClass('active');
        }
      }
    }
  } else {
    order=item;
    $(elem).parent().children().removeClass('active');
    $(elem).addClass('active');
  }
  showfilteredprogs();
}

/*function filter(cat, item, elem,) {
  var ind = -1;
  if (cat==0 ) {
    if (item == 3) {
      filters[cat] = [];
      filters[cat][filters[cat].length] = item;
      showall();
    } else {
      if ((filters[cat].length > 0) && (filters[cat][0] == 3))
        filters[cat] = [];
      
      ind = filters[cat].indexOf(item);
      if (ind != -1) {
        if (filters[cat].length>1)
          filters[cat].splice(ind, 1);
      }
      else 
        filters[cat][filters[cat].length] = item;
      //showrazdel(item);
    }
  }
  else {
    order = item;
  }
  
  if (cat == 0) {
    if ((item == 3)) {
      $(elem).parent().children().removeClass('active');
    }
  } else {
    $(elem).parent().children().removeClass('active');
  }
/*
  if (!((cat == 0) && (!(item == 3))))
    $(elem).parent().children().removeClass('active');
  else {

  }*/
/*  if (cat == 0 && ind != -1 && filters[cat].size != 1) 
    $(elem).removeClass('active');  
    else 
  $(elem).addClass('active');
}*/

function toggle(button, objName) {
  //var button = $(this)[0];
 var obj = $(objName),
 blocks = $("div[id*='menu-']");
 if (obj.css("height")!='100px') {
  //blocks.trigger('destroy');
  
  obj.animate({ height: '100px' }, 500, "linear", function() {
    $(objName).dotdotdot();
    $(button)[0].innerText = "подробнее";
  }); 
 }
 else {
  obj.trigger('destroy');
  obj.animate({ height: '100%' }, 500, "linear", function() {
    //$(objName).dotdotdot();
    $(button)[0].innerText = "скрыть";
  }); 
 }
 return false;


/*
 if (obj.css("display") != "none") {
 obj.animate({ height: '100px' }, 500);
 } else {
 var visibleBlocks = $("div[id*='menu-']:visible");

 if (visibleBlocks.length < 101) {
 obj.animate({ height: 'show' }, 500);
 } else {
 $(visibleBlocks).animate({ height: '100px', overflow: hidden }, 500, function() {
 obj.animate({ height: 'show' }, 500);
 }); 
 }
 }*/
}
</script>



























    </div>

  </div>
</div>

<!--<div class="row btnblock">
  <div class="col-lg-4">
    <!--<input type="button" class="button2" onclick="showall(this)" value ="Просмотреть все">      onclick="window.location = '/trainingprogram/create';"-->
<!--      <input type="button" class="btn-11" data-target="#myModalBox" data-toggle="modal"  value ="Создать">
    </div>
    </div>-->


<!-- Модальное окно -->
<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Создание программы питания</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
        <label class="checkbox switch"><input type="checkbox" class="mycheck" id="mycheck" value="Создать на основе шаблона"><span class="slider round"></span>Создать на основе шаблона</label>
        <div class="custom-select" style="width:100%;">
        <select id="tmpprogs" class="form-control" disabled>
          <? if (isset($assign)) { foreach($templates as $program) { ?>   
          <option value=<?= $program->id; ?> > <?= $program->name ?> </option>
          <? } } else { foreach($eatingprograms as $program) { ?>   
          <option value=<?= $program->id; ?> > <?= $program->name ?> </option>
          <? }
            foreach($template as $program) { ?>
            <option <?= (!$program->can_use)?"group disabled='disabled'":"" ?> value=<?= $program->id; ?> > <?= $program->name ?> </option>
           <? } } ?>
        </select>
        </div>
        <span id="errormsg" style="display: none">Не выбран шаблон.</span>
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <input type="button" class="" data-dismiss="modal" value="Отмена">
        <input type="button" class="" onclick="submit();" value="Создать">
      </div>
    </div>
  </div>
</div>



  <script type="text/javascript" src="/js/jsrender.min.js"></script>
  <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/js/jquery.dotdotdot.min.js"></script>
  <script type="text/javascript" src="/perfectscrollbar/js/perfect-scrollbar.jquery.min.js"></script>
  <script type="text/javascript" src="/js/select.js"></script>

<!--Шаблон краткой информации о тренировочной программе-->
<script id="progtmp" type="text/x-jsrender">
<div id="content" class="row box content contentleft">
        <div class="header">
        <h2 id={{:id}}>{{>name}}</h2>
      </div>

        <div class="form">
          <span>Цель занятий: </span>{{>aimTrainTxt}}
        <div id="buttons" class="row">
          <a href="/trainingprogram/edit/{{>id}}" alert="Изменить"><div class="btn"><span class="glyphicon glyphicon-pencil"></span></div></a>
          <div class="btn" onclick="show({{>id}});"><span class="glyphicon glyphicon-eye-open"></span></div>
          <a href="/trainingprogram/delete/{{>id}}"><div class="btn"><span class="glyphicon glyphicon-trash"></span></div></a>
          <a href="/trainingprogram/active/{{>id}}"><div class="btn"><span class="glyphicon glyphicon-ok"></span></div></a>
        </div>
      </div>
      </div>
  </script>


<!--Шаблон одного приёма пищи-->
  <script id="eatingtmp" type="text/x-jsrender">
    <div class="eating">
              {{:eating}}
            </div>
            <table class="nutritiontable">
            <thead>
              <tr>
                <th className="table-header nutrition-caption-name">Продукт</th>
                <th className="table-header nutrition-caption-weight">Граммы</th>
                <th className="table-header nutrition-caption-pfc">Б</th>
                <th className="table-header nutrition-caption-pfc">Ж</th>
                <th className="table-header nutrition-caption-pfc">У</th>
                <th className="table-header nutrition-caption-calories">Ккал</th>
                <th className="button-contains nutrition-caption-delete"></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr className="total">
                <td colSpan="2" className="table-header">Норма</td>
                <td></td>
                <td></td>
                <td></td>
                <td>500</td>
                <td className="button-contains"></td>
              </tr>
              <tr class="total">
                <td colSpan="2" className="table-header">Фактическое значение</td>
                <td id="proteins"></td>
                <td id="fats"></td>
                <td id="hidrocarbonats"></td>
                <td id="colories"></td>
                <td className="button-contains"></td>
              </tr>
            </tfoot>
            </table>
            <div class="row drophear">
            <div class="col-lg-12 add droparea">
              Перетащите сюда продукты, что бы добавить их на этот день.
            </div>
  </script>
  <!--Шаблон одного приёма пищи-->
  <script id="onefoodtmp" type="text/x-jsrender">
    <tr>
      <td style="width:25%">{{:name}}</td>
      <td style="width:25%">
        <input id="id" type="hidden" name="foods[{{:day}}][{{:eating}}][{{:number}}][0]" value={{:productid}} />
        <input type="number" id="cnt" name="foods[{{:day}}][{{:eating}}][{{:number}}][1]" value={{:cnt}} class="input-edit"/>
      </td>
      <td id="proteins" style="width:10%">{{:proteins}}</td>
      <td id="fats" style="width:10%">{{:fats}}</td>
      <td id="hidrocarbonats" style="width:10%">{{:hidrocarbonats}}</td>
      <td id="colories" style="width:15%">{{:colories}}</td>
      <td style="width:5%"><span id="delete" class="glyphicon glyphicon-trash" aria-hidden="true"></span></td>
    </tr>
  </script>






  <script type="text/javascript">
    $('#contentblock').perfectScrollbar();

    /*var eatingprograms = [];
    <? foreach($eatingprograms as $program) { ?>
      eatingprograms[<?= $program->id ?>] = <?= json_encode($program);?>;
    <? } ?>*/
    var all = false;

    $("#mycheck").change(function() {
      if(this.checked) {
        $("#tmpprogs")[0].disabled = false;
        //Do stuff
      } else {
        $("#tmpprogs")[0].disabled = true;
      }
    });
  
    function submit() {
      <? if (isset($assign)) { ?>
        if ($("#mycheck")[0].checked)
        if ($("#tmpprogs")[0].selectedOptions.length>0) 
          document.location.href = "/user/createusernutritionprogramtmp/<?= $user->id?>/" + $("#tmpprogs")[0].selectedOptions[0].value;
        else {
          $("#errormsg").css("display", "block");
        }
      else 
        document.location.href = "/user/createusernutritionprogram/<?= $user->id?>";
      <? } else { ?>
      if ($("#mycheck")[0].checked)
        if ($("#tmpprogs")[0].selectedOptions.length>0) {
        document.location.href = "/nutritionprogram/createtmp/" + $("#tmpprogs")[0].selectedOptions[0].value;
        } else {
          $("#errormsg").css("display", "block");
        }
      else 
        document.location.href = "/nutritionprogram/create";
      <? } ?>
    }
    
    function showfilteredprogs() {
      var myTemplate = $.templates("#progtmp");
      var progs = $("#progs");
      progs.empty();
      //var html = myTemplate.render(routinesinfo);
      routinesinfo.forEach( function(s) { 
     // ... do something with s ...
     if (!(s.active && s.aimTrain == aimTrain))
      if (filters[0].indexOf(s.aimTrain-1) != -1){
          var html = myTemplate.render(s);
          progs.append(html);
        }
       /*else {
        var html = myTemplate.render(s);
          progs.append(html);
      }*/
      } );
      /*if (!all) {
        ob.value="Просмотреть только текущие" 
      } else {
        ob.value="Просмотреть все" 
      }*/
      //$ob.value
      //all=!all;
    }

    function showrazdel(r){
      var myTemplate = $.templates("#progtmp");
      var progs = $("#progs");
      progs.empty();
      //var html = myTemplate.render(routinesinfo);
      routinesinfo.forEach( function(s) { 
     // ... do something with s ...
     if (!(s.active && s.aimTrain == aimTrain))
      if (s.aimTrain == r+1){
          var html = myTemplate.render(s);
          progs.append(html);
        }
       else {
        var html = myTemplate.render(s);
          progs.append(html);
      }
      } );
      /*if (!all) {
        ob.value="Просмотреть только текущие" 
      } else {
        ob.value="Просмотреть все" 
      }*/
      //$ob.value
      all=!all;

      /*for (var i = 0; i<routinesinfo.length; i++) {
        var html = myTemplate.render(routinesinfo[i]);
        info.append(html);
      }*/
    }

    function showall(ob){
      var myTemplate = $.templates("#progtmp");
      var progs = $("#progs");
      progs.empty();
      //var html = myTemplate.render(routinesinfo);
      routinesinfo.forEach( function(s) { 
     // ... do something with s ...
     if (!(s.active && s.aimTrain == aimTrain))
     if (all) {
      if (s.aimTrain == aimTrain){
          var html = myTemplate.render(s);
          progs.append(html);
        }
      } else {
        var html = myTemplate.render(s);
          progs.append(html);
      }
      } );
      /*if (!all) {
        ob.value="Просмотреть только текущие" 
      } else {
        ob.value="Просмотреть все" 
      }*/
      //$ob.value
      all=!all;

      /*for (var i = 0; i<routinesinfo.length; i++) {
        var html = myTemplate.render(routinesinfo[i]);
        info.append(html);
      }*/
    }

    function show(id) {
      var myTemplate = $.templates("#daytmp");
      var info = $("#infoblock");
      info.empty();
      /*for (var i = 0; i<routinesinfo[id].trainingprogramday.length; i++) {
        var html = myTemplate.render(routinesinfo[id].trainingprogramday[i]);
        info.append(html);
      }*/
      var html = myTemplate.render(eatingprograms[id]);
      info.append(html);

      $("div[id*='menu-']").css("height", "100px");
      $("#menu-1").dotdotdot();
      $('.contentleft').removeClass("active");
      $('#left').find(".header").removeClass("active");
    $('#'+id).parent().addClass("active");
    $('#progname')[0].innerHTML = eatingprograms[id].name + (eatingprograms[id].lastmodified!=null?"<br><span>Дата последнего изменения: <span>"+ eatingprograms[id].lastmodified.substr(0, 10) + " " + eatingprograms[id].lastmodified.substr(11, 5) +"</span></span>":"");
    $('#routinename')[0].innerHTML = "<span>Создана на основе распорядка дня: </span>" + eatingprograms[id].routine_name;
    if (eatingprograms[id].active)
      $($('#progname')[0].parentNode).addClass("current")
    else 
      $($('#progname')[0].parentNode).removeClass("current")
      //console.log(html);
    }

    <? if(isset ($active)) { ?>
      show(<?= $active->id ?>);
    <? } else if(isset ($eatingprograms) && count($eatingprograms) > 0 || isset ($template) && count($template) > 0) { ?>
      show(<?= $first->id ?>);
    <? } ?>
  </script>





