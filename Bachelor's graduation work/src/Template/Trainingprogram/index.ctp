<?= $this->Html->css('redesign/trainingprogram.css') ?>
<?= $this->Html->css('redesign/modal.css') ?>
<?= $this->Html->css('/perfectscrollbar/css/perfect-scrollbar.min.css') ?>

<? $aimTrains = ["Похудение", "Набор мышечной массы", "Поддержание мышечной массы"]; ?>

<?= $this->fetch("content") ?>
<div class="row">
		<div id="left" class="col-lg-4 left">
      <input type="button" class="btn-11" data-target="#myModalBox" data-toggle="modal"  value =<?= isset($assign)?"Назначить":"Создать"?>>
      <div class="block">
			<? $active = null;
      if (isset($programs) && (count($programs) > 0)) { 
      foreach($programsinfo as $program) {
        if ($program->active && $program->aimTrain = $aimTrain)
        {
          $active = $program;
          break;
        }
      }
      if ($active != null) {
      ?>
      
      <div class="row box cap">
				<span>Активная тренировка</span>
			</div>
			<div id="content" class="row box content contentleft active current">
        <div class="header">
				<h2 id=<?= $active->id ?>><?= $active->name ?></h2>
      </div>
        <div class="form">
        <span>Цель занятий: </span><?= $active->aimTrain>0?$aimTrains[$active->aimTrain-1]:"" ?>
        <div id="buttons" class="row">
          <a href="<? if (isset($assign)) 
            echo '/user/editusertrainingprogram/'.$user->id.'/'.$active->id;
          else 
            echo '/trainingprogram/edit/'.$active->id;
          ?>"><div class="btn"><span class="glyphicon glyphicon-pencil" ></span></div></a>
          <div class="btn" onclick="show(<?= $active->id ?>);"><span class="glyphicon glyphicon-eye-open" ></span></div>
        </div>
      </div>
			</div>

      <? } ?>
      <div class="row box cap withselect">
        <span>Список тренировочных программ</span>
        <a href="#paramse" id="settingstbtn" class="collapsed" data-toggle="collapse"><i class="fa fa-sliders" aria-hidden="true"></i></a>
        <div id="paramse" class="params collapse">
          <div class="col-lg-6">
            <h4>По цели</h4>
            <ul class="filters">
              <li onclick="filter(0, 0, this)" <? if (isset($user) && $user["role"] != "admin" && $aimTrain == 1) /*echo "class='active'"*/ ?>>Похудение</li>
              <li onclick="filter(0, 1, this)" <? if (isset($user) && $user["role"] != "admin" && $aimTrain == 2) /*echo "class='active'"*/ ?>>Набор мышечной массы</li>
              <li onclick="filter(0, 2, this)" <? if (isset($user) && $user["role"] != "admin" && $aimTrain == 3) /*echo "class='active'"*/ ?>>Поддержание мышечной массы</li>
              <li onclick="filter(0, 3, this)" class='active' <? if (isset($user) && $user["role"] == "admin") echo "class='active'"?>>Все</li>            
            </ul>
          </div>
          <div calss="col-lg-6">
            <h4>По дате</h4>
            <ul class="filters">
              <li onclick="filter(1, 0, this)" class="active">По возрастанию</li>
              <li onclick="filter(1, 1, this)">По убыванию</li>
            </ul>
          </div>
        </div>
      </div>
      <div id="progs">
      <? foreach($programs as $program) {

        if (!($program->active && $program->aimTrain == $aimTrain) && ($program->aimTrain == $aimTrain) || ($user["role"] == "admin") ) { ?> 

      <div id="content" class="row box content contentleft">
        <div class="header">
        <h2 id=<?= $program->id ?>><?= $program->name ?></h2>
      </div>

        <div class="form">
          <span>Цель занятий: </span><?= $program->aimTrain>0?$aimTrains[$program->aimTrain-1]:"" ?>
        <div id="buttons" class="row">
          <a href="
          <? if (isset($assign)) 
            echo '/user/editusertrainingprogram/'.$user->id.'/'.$program->id;
          else 
            echo '/trainingprogram/edit/'.$program->id;
          ?>
            "><div class="btn"><span class="glyphicon glyphicon-pencil"></span></div></a>
          <div class="btn" onclick="show(<?= $program->id ?>);"><span class="glyphicon glyphicon-eye-open"></span></div>
          <a href="
          <? if (isset($assign)) 
            echo '/user/deletetrainingprogram/'.$user->id.'/'.$program->id;
          else 
            echo '/trainingprogram/delete/'.$program->id;
          ?>"><div class="btn"><span class="glyphicon glyphicon-trash"></span></div></a>
          <a href="
          <? if (isset($assign)) 
            echo '/user/activetrainingprogram/'.$user->id.'/'.$program->id;
          else 
            echo '/trainingprogram/active/' . $program->id;
          ?>
           "><div class="btn"><span class="glyphicon glyphicon-ok"></span></div></a>
        </div>
      </div>
      </div>
      <? } 
    } ?>
<!-- Рендеринг шаблонов. -->
<? if(isset($template)) foreach($template as $program) {

if (!($program->active && $program->aimTrain == $aimTrain) && ($program->aimTrain == $aimTrain) || ($user["role"] == "admin") ) {  
?> 

<div id="content" class="row box content contentleft">
<div class="ribbon ribbon-top-right"><span>шаблон</span></div>
<div class="header">
<h2 id=<?= $program->id ?>><?= $program->name ?></h2>
</div>

<div class="form">
  <span>Цель занятий: </span><?= $program->aimTrain>0?$aimTrains[$program->aimTrain-1]:"" ?>
<div id="buttons" class="row">
  <div class="btn" onclick="show(<?= $program->id ?>);"><span class="glyphicon glyphicon-eye-open"></span></div>
</div>
</div>
</div>
<? } 
} ?>



    </div>  

     <!-- <div id="content" class="row box content">
        <h2>Распорядок 2</h2>
        <hr>

        <div class="row">
          <div class="btn"><span class="glyphicon glyphicon-pencil"></span></div>
          <div class="btn"><span class="glyphicon glyphicon-eye-open"></span></div>
          <div class="btn"><span class="glyphicon glyphicon-trash"></span></div>
          <div class="btn"><span class="glyphicon glyphicon-ok"></span></div>
        </div>
      </div>
      <div id="content" class="row box content">
        <h2>Распорядок 3</h2>
        <hr>

        <div class="row">
          <div class="btn"><span class="glyphicon glyphicon-pencil"></span></div>
          <div class="btn"><span class="glyphicon glyphicon-eye-open"></span></div>
          <div class="btn"><span class="glyphicon glyphicon-trash"></span></div>
          <div class="btn"><span class="glyphicon glyphicon-ok"></span></div>
        </div>
      </div>      
-->
<? } else { ?>
      <div class="row box cap" style="margin-top:10px; height: auto">
        <span>Список тренировочных программ</span>
        <a href="#paramse" id="settingstbtn" class="collapsed" data-toggle="collapse"><i class="fa fa-sliders" aria-hidden="true"></i></a>
        <div id="paramse" class="params collapse">
          <div class="col-lg-6">
            <h4>По цели</h4>
            <ul class="filters">
              <li onclick="filter(0, 0, this)" <? if (isset($user) && $user["role"] != "admin" && $aimTrain == 1) /*echo "class='active'"*/ ?>>Похудение</li>
              <li onclick="filter(0, 1, this)" <? if (isset($user) && $user["role"] != "admin" && $aimTrain == 2) /*echo "class='active'"*/ ?>>Набор мышечной массы</li>
              <li onclick="filter(0, 2, this)" <? if (isset($user) && $user["role"] != "admin" && $aimTrain == 3) /*echo "class='active'"*/ ?>>Поддержание мышечной массы</li>
              <li onclick="filter(0, 3, this)" class='active' <? if (isset($user) && $user["role"] == "admin") echo "class='active'"?>>Все</li>            
            </ul>
          </div>
          <div calss="col-lg-6">
            <h4>По дате</h4>
            <ul class="filters">
              <li onclick="filter(1, 0, this)" class="active">По возрастанию</li>
              <li onclick="filter(1, 1, this)">По убыванию</li>
            </ul>
          </div>
        </div>
      </div>
      <div id="progs">
        <!-- Рендеринг шаблонов. -->
<? if(isset($template)) foreach($template as $program) {

if (!($program->active && $program->aimTrain == $aimTrain) && ($program->aimTrain == $aimTrain) || ($user["role"] == "admin") ) {  
?> 

<div id="content" class="row box content contentleft">
<div class="ribbon ribbon-top-right"><span>шаблон</span></div>
<div class="header">
<h2 id=<?= $program->id ?>><?= $program->name ?></h2>
</div>

<div class="form">
  <span>Цель занятий: </span><?= $program->aimTrain>0?$aimTrains[$program->aimTrain-1]:"" ?>
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
    </div>
		<div class="col-lg-8 ">

    <div class="row box cap orange">
        <span id="progname">Подробная информация</span>
      </div>
    
    <!--Содержимое левого блока-->
    <div id="contentblock" class="row box content helpblock">
      <!--<div class="header active">
        <h2 id="progname"></h2>
      </div>-->
      <!--<h2>Активный распорядок дня</h2>
      <h4>Тренировочный день</h4>
      <hr>
      <p> <span>09:00</span> Подъём</p>
      <p> <span>09:30</span> 1 приём пищи</p>
      <p> <span>14:00</span> Тренировка</p>
      <p> <span>17:30</span> 2 приём пищи</p>
      <p> <span>19:30</span> 3 приём пищи</p>
      <p> <span>22:00</span> Сон</p>
      <br>
      <h4>День отдыха</h4>
      <hr>
      <p> <span>09:00</span> Подъём</p>
      <p> <span>09:30</span> 1 приём пищи</p>
      <p> <span>14:00</span> 2 приём пищи</p>
      <p> <span>17:30</span> 3 приём пищи</p>
      <p> <span>19:30</span> 4 приём пищи</p>
      <p> <span>22:00</span> Сон</p>-->
      
    























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
.contentleft {
  position: relative;
}


.ribbon {
  width: 150px;
  height: 150px;
  overflow: hidden;
  position: absolute;
}
.ribbon::before,
.ribbon::after {
  position: absolute;
  z-index: -1;
  content: '';
  display: block;
  border: 5px solid #2980b9;
}
.ribbon span {
  position: absolute;
  display: block;
  width: 225px;
  padding: 15px 0;
  background-color: #3498db;
  box-shadow: 0 5px 10px rgba(0,0,0,.1);
  color: #fff;
  font: 700 18px/1 'Lato', sans-serif;
  text-shadow: 0 1px 1px rgba(0,0,0,.2);
  text-transform: uppercase;
  text-align: center;
}
.ribbon-top-right {
  top: -10px;
  right: -10px;
}
.ribbon-top-right::before,
.ribbon-top-right::after {
  border-top-color: transparent;
  border-right-color: transparent;
}
.ribbon-top-right::before {
  top: 0;
  left: 0;
}
.ribbon-top-right::after {
  bottom: 0;
  right: 0;
}
.ribbon-top-right span {
  left: -25px;
  top: 30px;
  transform: rotate(45deg);
}


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
}
.checkbox {
    padding-left: 30px;
}
.modal-footer>input {
  width:auto;
}
#settingstbtn {
      float: right;
    margin: 3px;
    margin-right: 20px;
}
a[disabled]>div {
  background-color:#cfcfcf;
}
a[disabled]:hover::after {
    content: attr(data-title); /* Выводим текст */
    position: absolute; /* Абсолютное позиционирование */
    left: 20%; top: 30%; /* Положение подсказки */
    z-index: 1; /* Отображаем подсказку поверх других элементов */
    background: rgba(255,255,230,0.9); /* Полупрозрачный цвет фона */
    font-family: Arial, sans-serif; /* Гарнитура шрифта */
    font-size: 11px; /* Размер текста подсказки */
    padding: 5px 10px; /* Поля */
    border: 1px solid #333; /* Параметры рамки */
   }
</style>

<div id="infoblock" class="cont" style="left:17%">
  <? if (! (is_object($programs) && (count($programs) > 0))) echo "<h4>У вас нет ни одной программы тренировок. Создайте новую программу тренировок.</h4>"; ?> 
        <? if ($active != null) { foreach($active->trainingprogramday as $day) { ?> 
        <div class="c1" id="1">
    <div class="dayheader">
      <H4>День <?= $day->number ?></H4>
      </div>
        <? $ii = 1; foreach($day->trainingprogramday_exercise as $ex) { ?>  
          <div class="wp-block property list">
        <div class="wp-block-body">
          <div class="wp-block-img">
            <a href="#">
              <img src="/img/excersices/<?if ($ex->exercise->img != null) echo($ex->exercise->img); else echo('no_image_available.jpg'); ?>" alt="">
            </a>
          </div>
          <div class="wp-block-content">
            <small>
<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Упражнение <?= $ii ?></small>
            <h4 class="content-title"><?= $ex->exercise->name ?></h4>
            <div class="description">
              <? if (strlen($ex->exercise->description) <> 0) { ?>
              <div id="menu-<?= $ii?>" class="excersicedata">
                <?= $ex->exercise->description ?>
              </div>
              <a href="#" id="collapse" onclick="return toggle(this, '#menu-<?= $ii?>');" >Подробнее</a>
              <? } ?>
            </div>
            <span class="pull-left">
              <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>   
              <? $jj = 0; foreach($ex->exercise->musculgroups as $muscul) { 
                if ($jj == 0) 
                  echo($muscul->name);
                else
                  echo(', '.$muscul->name);
                $jj++;
                }
              ?>
              
            </span>
            <!--<span class="pull-right">
              <span class="capacity">
                <i class="fa fa-user"></i>
              </span>
            </span>
          </div>-->
        </div>
        <div class="wp-block-footer">
          <ul class="aux-info">
            <li><img class="icon" src="/img/count.png"> <?= $ex->podhod ?></li>
            <li><img class="icon" src="/img/repeat.svg"> <?= $ex->repeats ?></li>
            <li><img class="icon" src="/img/weights.svg"> <?= $ex->weight ?></li>
          </ul>
        </div>
      </div>
      </div>
      <? $ii++; } ?>
        </div>
        <? } }?>
    
    </div>
 <script>
$(function() {
 //$("div[id*='menu-']").hide(); 
 $("div[id*='menu-']").css("height", "100px");
 $("#menu-1").dotdotdot();
var height = 0;
/*
 $("#contentblock").on('affixed-top.bs.affix', function(){
    //alert("fff");
    //this.offsetHeight = this.offsetHeight+40;
    //$(this).outerHeight($(this).outerHeight() + 40);
    //alert("s1");
     if (height == 0) 
         height = $(this).height()
     $(this).height(height + 40);
    /*$(this).on('affix-top.bs.affix', function(){
    //alert("fff");
    //this.offsetHeight = this.offsetHeight+40;
    $(this).outerHeight($(this).outerHeight() - 40)
  });*/
/*  });
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

$("#contentblock").on('affixed-bottom.bs.affix', function(){
    //alert("fff");
    //this.offsetHeight = this.offsetHeight+40;
    //if (height >0)
        //$(this).height(height);
    if (height>0) {
      //alert("resize");
      $(this).height(height-200);
      //$(this).attr("style", "position: fixed, height: "+ (height-200) + "px");
      $(this).attr("style", "height: "+ (height-60) + "px");
    }

    //alert("height");
     //$(this).outerHeight($(this).outerHeight() - 40)
  });*/

});

var filters = [[<?= isset($user) && $user["role"] != "admin"?$aimTrain:3 ?>],[]];
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
      return;
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
        <h4 class="modal-title">Создание тренировочной программы</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
        <label class="checkbox switch"><input type="checkbox" class="mycheck" id="mycheck" value="Создать на основе шаблона"><span class="slider round"></span>Создать на основе шаблона</label>
        <div class="custom-select" style="width:100%;">
        <select id="tmpprogs" class="form-control" disabled>
          <? if (isset($assign)) { foreach($templates as $program) { ?>   
          <option value=<?= $program->id; ?> > <?= $program->name ?> </option>
          <? } } else { foreach($programs as $program) { ?>   
          <option value=<?= $program->id; ?> > <?= $program->name ?> </option>
          <? } foreach($template as $program) { ?>
            <option value=<?= $program->id; ?> > <?= $program->name ?> </option>
        <? } } ?>
        </select>
      </div>
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <input type="button" class="" data-dismiss="modal" value="Отмена">
        <input type="button" class="" onclick="submit();" value="Создать">
      </div>
    </div>
  </div>
</div>

<!-- Добавьте модальное окно после открывающего тега body-->
<div class="modal fade" id="image-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <div class="modal-title">Просмотр изображения</div>
      </div>
      <div class="modal-body">
        <img class="img-responsive center-block" src="" alt="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
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
<div type={{if template != undefined  && template == true}}1 {{else}}2 {{/if}}  id="content" class="row box content contentleft">
        <div class="header">
          {{if template != undefined  && template == true}}
          <div class="ribbon ribbon-top-right"><span>шаблон</span></div>
          {{/if}}

          
        <h2 id={{:id}}>{{>name}}</h2>
      </div>

        <div class="form">
          <span>Цель занятий: </span>{{>aimTrainTxt}}
        <div id="buttons" class="row">
        {{if template != undefined && template == true}}
          <div class="btn" onclick="show({{>id}});"><span class="glyphicon glyphicon-eye-open"></span></div>
        {{else}}






        <a href={{if assign}}"/user/editusertrainingprogram/{{>users_id}}/{{>id}}"{{else}}"/trainingprogram/edit/{{>id}}"{{/if}} alert="Изменить"><div class="btn"><span class="glyphicon glyphicon-pencil"></span></div></a>
          <div class="btn" onclick="show({{>id}});"><span class="glyphicon glyphicon-eye-open"></span></div>
          <a href={{if assign}}"/user/deletetrainingprogram/{{>users_id}}/{{>id}}"{{else}}"/trainingprogram/delete/{{>id}}"{{/if}}><div class="btn"><span class="glyphicon glyphicon-trash"></span></div></a>
          <a href={{if assign}}"/user/activetrainingprogram/{{>users_id}}/{{>id}}"{{else}}"/trainingprogram/active/{{>id}}"{{/if}} {{if !canactive}} style="pointer-events:none; display: inline-block" disabled data-title="Нельзя сделать активной программу тренировок не соответствующую текущей цели."{{/if}} ><div class="btn"><span class="glyphicon glyphicon-ok"></span></div></a>
        {{/if}}
        </div>
      </div>
      </div>
  </script>

<!--Шаблон тренировочного дня-->
  <script id="daytmp" type="text/x-jsrender">

    <div class="row">
  <div class="header day blue"><H2>День {{:number}}</H2></div>
</div>
{{for trainingprogramday_exercise}}
{{if exercise!=null}}
<div class="row rowelement">
  <div class="col-lg-6 element">{{:#getIndex()+1}}. {{:exercise.name}}</div>
  <div class="col-lg-6 element">Группа мышц: {{for exercise.musculgroups}}
                {{if #index == 0}}
                  {{:name}}
                {{else}}
                  {{:name}}, 
                {{/if}}
                {{/for}}</div>
</div>
<div class="row rowelement">
  <div class="col-lg-3 element"><img class="icon" src="/img/count.png"> Подходы: {{:podhod}}</div>
  <div class="col-lg-3 element"><img class="icon" src="/img/repeat.svg"> Повторения: {{:repeats}}</div>
  <div class="col-lg-3 element"><img class="icon" src="/img/weights.svg"> Вес от: {{:minweight}}</div>
  <div class="col-lg-3 element"><img class="icon" src="/img/weights.svg"> Вес до: {{:maxweight}}</div>
</div>
<div class="row rowelement end">
  <div class="col-lg-4">
              <img class="elemimg" src="/img/excersices/{{if exercise.img}}{{:exercise.img}} {{else}}no_image_available.jpg{{/if}}" alt="">
  </div>
  <div class="col-lg-8">
            <div class="elemtitle"> Как выполнять</div><br>
            <div class="description">
              {{if exercise.description}}
                <div id="menu-{{:id}}" class="excersicedata">
                  {{:exercise.description}}
                </div>
                <a href="#" id="collapse" onclick="return toggle(this, '#menu-{{:id}}');" >Подробнее</a>
                {{/if}}
            </div>
    </div>
</div>
{{/if}}
{{/for}}
  </script>

  <script type="text/javascript">
    $('#contentblock').perfectScrollbar();

    var routinesinfo = [];
    <? foreach($programsinfo as $program) { if (!isset($first)) $first = $program;?>
      routinesinfo[<?= $program->id ?>] = <?= json_encode($program);?>;
      routinesinfo[<?= $program->id ?>].template = false;
      routinesinfo[<?= $program->id ?>].assign = <? echo (isset($assign)?"true":"false"); ?>;
      switch (routinesinfo[<?= $program->id ?>].aimTrain){
        case 1: routinesinfo[<?= $program->id ?>].aimTrainTxt = "Похудение"; break;
        case 2: routinesinfo[<?= $program->id ?>].aimTrainTxt = "Набор мышечной массы"; break;
        case 3: routinesinfo[<?= $program->id ?>].aimTrainTxt = "Поддержание мышечной массы"; break;
      }
      if (routinesinfo[<?= $program->id ?>].aimTrain == <?=$aimTrain ?>)
        routinesinfo[<?= $program->id ?>].canactive = true;
      else
        routinesinfo[<?= $program->id ?>].canactive = false;
    <? } ?>
    <? if (isset($template)) { ?>
    <? foreach($template as $program) { if (!isset($first)) $first = $program;?>
      routinesinfo[<?= $program->id ?>] = <?= json_encode($program);?>;
      routinesinfo[<?= $program->id ?>].template = true;
      routinesinfo[<?= $program->id ?>].assign = <? echo (isset($assign)?"true":"false");?>;
      switch (routinesinfo[<?= $program->id ?>].aimTrain){
        case 1: routinesinfo[<?= $program->id ?>].aimTrainTxt = "Похудение"; break;
        case 2: routinesinfo[<?= $program->id ?>].aimTrainTxt = "Набор мышечной массы"; break;
        case 3: routinesinfo[<?= $program->id ?>].aimTrainTxt = "Поддержание мышечной массы"; break;
      }
      <? if ($user["role"] != "trainer") { ?> 
      if (routinesinfo[<?= $program->id ?>].aimTrain == <?=$aimTrain ?>)
        routinesinfo[<?= $program->id ?>].canactive = true;
      else
        routinesinfo[<?= $program->id ?>].canactive = false;
        <? } else { ?>
          routinesinfo[<?= $program->id ?>].canactive = false;
        <? } ?> 
    <? } ?>
    <? } ?>
    /*routinesinfo.sort(function(a, b){
      if(a.template = false && b.template == true) return -1;
        else
          if(a.template = true && b.template == false)
            return 1;
          else
            return 0;
    })*/
    var aimTrain = <?= $aimTrain!=null?$aimTrain:0 ?>;
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
        document.location.href = "/user/createusertrainingprogramtmp/<?= $user->id?>/" + $("#tmpprogs")[0].selectedOptions[0].value;
      else 
        document.location.href = "/user/createusertrainingprogram/<?= $user->id?>";
      <? } else { ?>
      if ($("#mycheck")[0].checked)
        document.location.href = "/trainingprogram/createtmp/" + $("#tmpprogs")[0].selectedOptions[0].value;
      else 
        document.location.href = "/trainingprogram/create";
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
      var elements = [];
      routinesinfo.forEach( function(s) { 
     //for (var i = 0; i< routinesinfo.length; i++) {
     // ... do something with s ...
     if (!(s.active && s.aimTrain == aimTrain))
     /*if (all) {
      if (s.aimTrain == aimTrain){
          var html = myTemplate.render(s);
          progs.append(html);
        }
      } else {
      */  var html = myTemplate.render(s);
          elements[elements.length] = $(html);
          //progs.append(html);
      });
      elements.sort(function(a, b){
        if(a.attr("type")<b.attr("type"))
          return 1;
        else if(a.attr("type")>b.attr("type"))
          return -1;
        else
          return 0;
      })
      elements.forEach( function(s) { 
        progs.append(s);
      } );
      /*if (!all) {
        ob.value="Просмотреть только текущие" 
      } else {
        ob.value="Просмотреть все" 
      }*/
      //$ob.value
      //all=!all;

      /*for (var i = 0; i<routinesinfo.length; i++) {
        var html = myTemplate.render(routinesinfo[i]);
        info.append(html);
      }*/
    }

    function show(id) {
      var myTemplate = $.templates("#daytmp");
      var info = $("#infoblock");
      info.empty();
      for (var i = 0; i<routinesinfo[id].trainingprogramday.length; i++) {
        var html = myTemplate.render(routinesinfo[id].trainingprogramday[i]);
        info.append(html);
      }
      $("div[id*='menu-']").css("height", "100px");
      $("#menu-1").dotdotdot();
      $('.contentleft').removeClass("active");
      $('#left').find(".header").removeClass("active");
    $('#'+id).parent().addClass("active");
    $('#progname')[0].innerHTML = routinesinfo[id].name;
    if (routinesinfo[id].active)
      $($('#progname')[0].parentNode).addClass("current")
    else 
      $($('#progname')[0].parentNode).removeClass("current")
      //console.log(html);
    }

    

    $(document).ready(function() {
      filter(0, 3);
      <? if(isset ($active)) { ?>
      show(<?= $active->id ?>);
    <? } else if (isset($first)) {?>
      show(<?= $first->id ?>);
    <? } ?>
    $('.exerciseimgwrap').click(function(e) {
    //отменить стандартное действие браузера
    e.preventDefault();
    //присвоить атрибуту scr элемента img модального окна
    //значение атрибута scr изображения, которое обёрнуто
    //вокруг элемента a, на который нажал пользователь
    $('#image-modal .modal-body img').attr('src', $(this).find("img").attr('src'));
    //открыть модальное окно
    $("#image-modal").modal('show');
  });
  //при нажатию на изображение внутри модального окна 
  //закрыть его (модальное окно)
  $('#image-modal .modal-body img').on('click', function() {
    $("#image-modal").modal('hide')
  });
    })
  </script>





