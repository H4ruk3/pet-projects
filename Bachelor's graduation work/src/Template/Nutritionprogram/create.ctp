<?= $this->Html->css('/perfectscrollbar/css/perfect-scrollbar.min.css') ?>
<?= $this->Html->css('redesign/trainingprogram.css') ?>
<?= $this->Html->css('redesign/nutritionprogram.css') ?>
<?= $this->Html->css('redesign/cropper.min.css') ?>

<?= $this->fetch("content") ?>

<script>
    var routines = [];
    <? foreach($routines as $routine) { ?>
        routines[<?= $routine->id ?>] = <?= json_encode($routine); ?>;
    <? } ?>
    var mode = 'CREATE';
    var bgunorm = <?= json_encode($bgunorm); ?>;
    var numbers = ['Первый', 'Второй', 'Третий', 'Четвёртый', 'Пятый', 'Шестой', 'Седьмой', 'Восьмой', 'Девятый', 'Десятый'];
</script>


<div id="left" class="col-lg-6 left">
  <div class="block">
    <div class="title row box cap">
      <a href="/nutritionprogram/index" class="back"><i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i></a>
      <span>Создание программы питания</span>
      <a href="#" class="righttoolbar" onclick="hide(this)"><i class="glyphicon glyphicon-th"></i></a>
      <a href="#" class="righttoolbar active" onclick="list(this)"><i class="glyphicon glyphicon-th-list"></i></a>
    </div>
    <form class="block" method="POST" <?= isset($redirect)?"action='$redirect'":""?> >
    <div id="content" class="row box content <?= isset($assign)?"withcrumb":""?> ps ps--theme_default" data-ps-id="ab09a7ca-63e5-6b8f-42ea-31cfd2422edb">
      
        <? if (isset($user)) { ?>
        <input type="hidden" name="user" value=<?= isset($user['id']) ? $user['id'] : (isset($user->id) ? $user->id : '') ?> />
        <? } ?>
        <? if (isset($program)) { ?>
        <input type="hidden" name="program" value=<?= $program->id ?> />
        <? } ?>
        <div class="form solid">
          <div class="row">
            <div class="col-lg-6">
              <div class="formelement">
                <label for="name">Название программы питания</label>
                <input id="name" type="text" required name="name" class="form-controll" value="<? if (isset($nutrition)) echo $nutrition->name; ?>">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="formelement">
                <label for="name">Распорядок дня</label>
                <select id="routine" size=1 name="routine" onchange="changeroutine(this)">
                  <? foreach($routines as $routine) { ?>
                  <option value=<?= $routine->id?> <? if (($routine->active == true) && (!isset($program))) echo "selected"?> <? if (isset($program) && $program->routine_id == $routine->id) echo "selected" ?> ><?= $routine->name?></option>
                  <? } ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="form solid">
          <div id="buttongroup">
            <div id="buttons" class="btn-group" role="group" aria-label="...">
            <? if (isset($program)) { 
              $cnt = 0; foreach($program->days as $day) { $cnt++; ?>
              <button type="button" class="btn btn-default daybtn <? if ($cnt == 1) echo 'active'?>" onclick='changeday(this)' data="<?= $cnt?>">День <?= $cnt?></button>
            <? } } else { ?>
              <button type="button" class="btn btn-default daybtn active" onclick='changeday(this)' data="1">День 1</button>
            <? } ?>
              <button type="button" class="btn-default btn" onclick="addday(this)"><i class="glyphicon glyphicon-plus"></i></button>
            </div>
          </div>
        </div>
<!--Дни с питанием -->
        <div class="days">
        
        </div>
        
    </div>
    <div class="row button">
          <input value="Сохранить" type="submit">
        </div>
      </form>
  </div>
</div>

<!--Правый блок-->
<div class="col-lg-6 right">
  <div class="block">
    <!--<div class="row box cap">
      <a href="http://coachme:8080/redesign/trainingprogram/create#" class="back"><i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i></a>
      <span>Список продуктов</span>
    </div>-->
    <div class="row box cap" >
        <div class="title">
          <span id="foodstitle" class="find collapse in">Список продуктов</span>
          <div id="find" class="find collapse width" >
            <input type="text" name="find" id="filter" value="" >
          </div>
        </div>
        <button id="findbtn" data-target=".find" class="collapsed" data-toggle="collapse"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></a>
        
    </div>
    <div id="rightbox" class="box1 ps ps--theme_default">
      <div class="wraper form" id="accordion">
        <? $id = 0; foreach($foodcategories as $foodcategorie) {  ?>
          <div class="panel panel-default">
            <!-- Заголовок 1 панели -->
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $id ?>">
            <div class="panel-heading pantitle">
              <h4 class="panel-title">
                <?= $foodcategorie->name ?>
              </h4>
            </div>
            </a>
            <div id="collapse<?= $id ?>" class="panel-collapse collapse" aria-expanded = "false">
              <table cellpadding="2" cellspacing="1" width="100%" id="products">
        <thead>
          <tr>
            <th class="table-header grocery-list-name" style="width: 299px;">Продукт</th>
            <td class="table-header grocery-list-pfc" style="width: 55px;">Б</td>
            <td class="table-header grocery-list-pfc" style="width: 55px;">Ж</td>
            <td class="table-header grocery-list-pfc" style="width: 54px;">У</td>
            <td class="table-header grocery-list-pfc" style="width: 54px;">Ккал</td>
          </tr>
        </thead>
        <tbody>
            <? $j = 1; foreach($foodcategorie->foods as $food) { ?>
              <!--<div class="item <?= $j==count($foodcategorie->foods)?"last":""?> title="">-->
                  <tr class="ui-draggable ui-draggable-handle drugel" id=<?= $food->id ?>>
            <td><?= $food->name ?></td>
            <td><?= $food->proteins ?></td>
            <td><?= $food->fats ?></td>
            <td><?= $food->hidrocarbonats ?></td>
            <td><?= $food->colories ?></td>
          </tr>
                
                <!--
                <div class="foodheader ui-draggable ui-draggable-handle drugel" id=<?= $food->id ?>>
                  <div class="table-header grocery-list-name" style="flex-grow: 1;"><?= $food->name ?></div>
                  <div class="table-header grocery-list-pfc" style="width: 55px;"><?= $food->proteins ?></div>
                  <div class="table-header grocery-list-pfc" style="width: 55px;"><?= $food->fats ?></div>
                  <div class="table-header grocery-list-pfc" style="width: 54px;"><?= $food->hidrocarbonats ?></div>
                  <div class="table-header grocery-list-pfc" style="width: 54px;"><?= $food->colories ?></div>  
                </div>
                

                   
              </div>-->
              <? $j++; } ?> 
              </tbody>

      </table>              
            </div>
          </div>
        <?$id = $id + 1; }?>
        </div>






          
        
        
    </div>
  </div>
</div>

<script src="/js/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="/perfectscrollbar/js/perfect-scrollbar.jquery.min.js"></script>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>

<script id="daytmp" type="text/x-jsrender">
    <div class="day" id="{{:id}}">
      <div class="header active">
        <H3 id="dayname">День {{:num}} </H3>
        <a id="delbtn" href="#" ><span class="glyphicon glyphicon-remove" ></span></a> 
        <div class="daytype">
          <span>Тип дня</span>
          <select id="daytype" size="1" class="typeselect">
            <option value=0>Тренировочный день</option>
            <option value=1>День отдыха</option>
          </select>
        </div>
      </div>
      <div class="exersicesblock">
      </div>
    </div>
</script>
<!--Шаблон одного приёма пищи-->
  <script id="eatingtmp" type="text/x-jsrender">
    <div class="eating">
              {{:eating}}
            </div>
            <table id="{{:tableid}}" class="nutritiontable">
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
              <tr class="total">
                <td colSpan="2" className="table-header">Итого</td>
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
  
  <script id="totaltmp" type="text/x-jsrender">
    <div class="eating">
              Итоги за день
            </div>
            <table id="{{:tableid}}" class="nutritiontable">
            <thead>
              <tr>
                <th className="table-header nutrition-caption-name" colspan=2></th>
                <th className="table-header nutrition-caption-pfc">Б</th>
                <th className="table-header nutrition-caption-pfc">Ж</th>
                <th className="table-header nutrition-caption-pfc">У</th>
                <th className="table-header nutrition-caption-calories">Ккал</th>
                <th className="button-contains nutrition-caption-delete"></th>
              </tr>
            </thead>
            <tfoot>
              <? if (isset($bgunorm)) { ?>
              <tr className="total">
                <td colSpan="2" className="table-header">Норма</td>
                <td><?= round($bgunorm["avePrCf"] , 2) ?></td>
                <td><?= round($bgunorm["aveFtCf"] , 2) ?></td>
                <td><?= round($bgunorm["aveCaCf"] , 2) ?></td>
                <td><?= round($bgunorm["Kkal"] , 2) ?></td>
                <td className="button-contains"></td>
              </tr>
              <? } ?>
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
  </script>

  <!--Шаблон одного приёма пищи-->
  <script id="onefoodtmp" type="text/x-jsrender">
    <tr>
      <td style="width:25%">{{:name}}</td>
      <td style="width:25%; padding:0">
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


<script type="text/javascript" src="/js/eating.js"></script>
<script type="text/javascript">
  $('#content').perfectScrollbar();
  $('#rightbox').perfectScrollbar();
  var foods = [];
  <?foreach($foodcategories as $foodcategory) { 
    foreach($foodcategory->foods as $food) { ?>
      foods[<?= $food->id ?>] = <?= json_encode($food);?>;
  <? } 
  }?>
  var routines = [];
  <? foreach($routines as $routine) { ?>
    routines[<?= $routine->id ?>] = <?= json_encode($routine->routineday);?>;
    if (routines[<?= $routine->id ?>][0].eatCount == 0)
      routines[<?= $routine->id ?>][0].eatCount = routines[<?= $routine->id ?>][0].eating.length;
    if (routines[<?= $routine->id ?>][1].eatCount == 0)
      routines[<?= $routine->id ?>][1].eatCount = routines[<?= $routine->id ?>][1].eating.length;
  <? } ?>


/*  function changeroutine(obj) {
    //console.log(obj.selected);
    $(".exersicesblock").empty();
    for (var i=0; i<routines[obj.value].eating.length; i++) {
      var myTemplate = $.templates("#eatingtmp");
      var html = myTemplate.render([{eating : numbers[i] + ' приём пищи'}]);
      $(".exersicesblock").append(html);
    }

  }

  var drop = {
    over: function(event, ui) { 
      var el = $(this).parent().prev().find("div[data="+ui.draggable[0].id+"]");
      if (el.length > 0) {
        $(this).css("background", "#ff0000") 
        $(this).css("color", "#ffffff")
        this.innerHTML = "Данное упражнение уже добавлено в текущий день."
      }
      else {  
        $(this).css("background", "#ff9800");
       this.innerHTML = "" 
        }
         },
    out: function(event, ui) { $(this).css("background", "#ffffff");
                                $(this).css("color", "#333")
                              this.innerHTML = "Перетащите сюда упражнения из списка, что бы добавить их на этот день"; },
    drop: function(event, ui) {
      //var el1 = $(this).parent().prev().find("div[data="+ui.draggable[0].id+"]");
      //if (el1.length == 0)
        
//{
      /*var el = $(this)[0].parentElement.parentElement;
      var els = $(el).find('.excersice');
      var i = els.length + 1;
*/
/*      var myTemplate = $.templates("#onefoodtmp");
      //var ex = ($(el)[0].parentNode.children.length-1);
      /*var day = [{name: $(ui.draggable[0]).find('a')[0].innerHTML, daynum: $(el)[0].id, exnum: i, exid: ui.draggable[0].id}];*/
  /*    var obj = foods[ui.draggable[0].id];
      var html = myTemplate.render(obj);
      var ob = $(this).parent().parent().find(".nutritiontable tbody")[0];
      $(ob).append(html);
      //$(this).parent().prev().append(html);
      $(this).css("background", "#ffffff");
      $(this).css("color", "#333");
    //}
    //$(this).css("background", "#ffffff");
    //$(this).css("color", "#333")
    //                          this.innerHTML = "Перетащите сюда упражнения из списка, что бы добавить их на этот день";
  }
  }

  */

  $(function() {

    //var nutrition = new Nutrition();
  
    init();

    var leftHeight = $(".container").height() - $("#breadcrumb").height();
    $("#left").height(leftHeight);
    var btnHeight = $(".row.button").outerHeight(true) || 50;
    var capHeight = $(".row.box.cap").outerHeight(true) || 40;
    $("#content").height(leftHeight - btnHeight - capHeight - 2);



    //$(".droparea").droppable(drop);

    <? if (isset($program)) {ksort($program->days);?>
      var program = <?= json_encode($program) ?>;
      nutrition.setname(program.name);
      for (var i = 0; i<program.days.length; i++) {
        if (i > 0) {
          var day = new Day(i+1);
          nutrition.addday(day)
        }
        for (var j = 0; j<program.days[i].length; j++){
          for (var k = 0; k<program.days[i][j].foods.length; k++){
            nutrition.addfood(i, j, program.days[i][j].foods[k]);
            if (program.days[i][j].foods[k].eating.routinedayId == routines[$("#routine")[0].value][1].id)
              nutrition.changeroutineday(i, 1);
        }
      }

    }
    <? } ?>
  });
</script>

</html>