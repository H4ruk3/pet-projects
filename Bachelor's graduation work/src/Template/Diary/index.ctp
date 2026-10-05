<?= $this->Html->css('redesign/routine.css') ?>
<?= $this->Html->css('redesign/diary.css') ?>
<?= $this->Html->css('redesign/modal.css') ?>
<?= $this->Html->css('/perfectscrollbar/css/perfect-scrollbar.min.css') ?>
<?= $this->Html->css('calendar-redhead.css') ?>
<?= $this->Html->css('star-rating.min.css') ?>
<?= $this->Html->css('redesign/nutritionprogram.css') ?>

<? $aimTrains = ["Похудение", "Набор мышечной массы", "Поддержание мышечной массы"]; ?>

<?= $this->fetch("content") ?>
<div class="row">
	<div id="left" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 left">
    <div class="block">
      <div class="row box cap" style="height: auto">
        <span>Дневник тренировок</span>
        <!--<a href="#paramse" class="collapsed" data-toggle="collapse"><i class="fa fa-sliders" aria-hidden="true"></i></a>
        <div id="paramse" class="params collapse">          
        </div>-->
      </div>
      <div class="alert alert-danger" id="alertmain" role="alert" style="opacity: 0; display: none">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <strong>Ошибка!</strong>
        <div class="message"></div>
      </div>
      <div id="progs">
      <!-- Calendar -->
        <div id="hellopreloader_preload"></div>
        <div class="calendar-left" style="display:none">
          <div class="num-date">27</div>
          <div class="dayleft">THURSDAY</div>
          <!--day -->
          <div class="current-events">
            <span class="posts">События</span>
            <ul>
              <li>Тренировка
              <div id="buttons" class="row">
                <a href="&#10;          /trainingprogram/edit/50            "><div class="btn"><span class="glyphicon glyphicon-pencil"></span></div></a>
                <div class="btn" onclick="show(50);"><span class="glyphicon glyphicon-eye-open"></span></div>
                <a href="&#10;          /trainingprogram/delete/50"><div class="btn"><span class="glyphicon glyphicon-trash"></span></div></a>
              </div>
              </li>
            </ul>
          </div>
          <!--current-events -->

          <div class="create-event">
            <span>Создать тренировку</span>
          <!-- create-event -->
            <div class="add-event" onclick="addDay(this)"><span class="plus">+</span></div>
          </div>
    
          <hr class="event-line" />
        <!-- add-event -->

        </div>
        <!-- calendar-left -- -->
        <div class="calendar"></div><!-- /Calendar -->
      </div>  
    </div>
  </div>
  <!-- Right block -->
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 right">
    <ul class="nav-tabs tabs">
      <li class="active"><a data-toggle="tab" href="#tab1" class="righttoolbar">Тренировка</a></li>
      <li><a data-toggle="tab" href="#tab2" class="righttoolbar">Питание</a></li>
    </ul>
    <div class="tab-content">
      <div id="tab1" class="tab-pane fade in active">
        <div class="row box cap orange">
          <? $day1 = $diarydays->first(); ?>
          <span id="progname">Дата: <?= $day1->date ?> День <?= $day1->trainingprogramday["number"] ?> Оценка: <?= $day1->mark ?></span>
        </div>
        <div id="contentblock" class="row box content helpblock">
          <? if (! (is_object($diarydays) && (count($diarydays) > 0))) echo "<h4>У вас нет ни одной записи в дневнике тренировок. Выберите тренировочные дни в календаре.</h4>"; ?> 
          <div id="infoblock" class="cont" style="left:17%">
          </div>
        </div>
      </div>
      <div id="tab2" class="tab-pane fade"></div>
    </div>
  </div>
</div>

<!-- Модальное окно -->
<div id="addExerciseDialog" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Добавление упражнения</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
        <div class="custom-select" style="width:100%;">
            <select id="tmpprogs" class="form-control">
              <? foreach($musculgroups as $musculgroup) { ?>
              <option group disabled="disabled" value="<?= $musculgroup->id?>" > <?= $musculgroup->name ?> </option>
              <? foreach($musculgroup->exercises as $exercise) { ?>
                <option value="<?= $exercise->id ?>" musculgroup="<?= $musculgroup->name ?>"> <?= $exercise->name ?> </option>
              <? }} ?>
            </select>
        </div>
        <div class="alert alert-danger" id="alertadd" role="alert" style="opacity: 0">
				<button type="button" class="close" data-dismiss="alertadd" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<strong>Ошибка!</strong>
				<div class="message"></div>
			</div>
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <input type="button" class="" data-dismiss="modal" value="Отмена">
        <input type="button" class="" onclick="addexercise();" value="Создать">
      </div>
    </div>
  </div>
</div>

<!-- Модальное окно для выбора продуктов-->
<div id="addProductDialog" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Добавление продукта</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
        <div class="custom-select" style="width:100%;">
            <select id="tmpfood" class="form-control">
              <? foreach($foodcategories as $foodcategory) { ?>
              <option group disabled="disabled" value="<?= $foodcategory->id?>" > <?= $foodcategory->name ?> </option>
              <? foreach($foodcategory->foods as $food) { ?>
                <option colories="<?= $food->colories?>" hidrocarbonats="<?= $food->hidrocarbonats?>" fats ="<?= $food->fats ?>" proteins ="<?= $food->proteins ?>" id ="<?= $food->id ?>" value="<?= $exercise->id ?>" musculgroup="<?= $musculgroup->name ?>"> <?= $food->name ?> </option>
              <? }} ?>
            </select>
        </div>
        <div class="alert alert-danger" id="alertaddfood" role="alert" style="opacity: 0">
        <button type="button" class="close" data-dismiss="alertaddfood" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <strong>Ошибка!</strong>
        <div class="message"></div>
      </div>
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <input type="button" class="" data-dismiss="modal" value="Отмена">
        <input type="button" class="ok" onclick="addfood(this);" value="Создать">
      </div>
    </div>
  </div>
</div>

<!-- Модальное окно для выбора продуктов-->
<div id="RatingDialog" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Оцените проведённую тренировку</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
        <div id="rate"></div>
        
      
      </div>
      <!-- Футер модального окна -->
      <!--<div class="modal-footer">
        <input type="button" class="" data-dismiss="modal" value="Отмена">
        <input type="button" class="ok" onclick="addfood(this);" value="Создать">
      </div>-->
    </div>
  </div>
</div>


  <script type="text/javascript" src="/js/jsrender.min.js"></script>
  <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/js/jquery.dotdotdot.min.js"></script>
  <script type="text/javascript" src="/perfectscrollbar/js/perfect-scrollbar.jquery.min.js"></script>
  <script src="/js/moment-2.2.1.js"></script>
  <script src="/js/calendar.js"></script>
  <!--<script src="/js/star-rating.js"></script>-->
  <script src="/js/jquery.raty.min.js"></script>
  <script src="/js/diary.js"></script>
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
      <a href="#" alert="Изменить"><div class="btn"><span class="glyphicon glyphicon-pencil"></span></div></a>
      <div class="btn" onclick="show({{>id}});"><span class="glyphicon glyphicon-eye-open"></span></div>
      <a href="/trainingprogram/delete/{{>id}}"><div class="btn"><span class="glyphicon glyphicon-trash"></span></div></a>
      <a href="/trainingprogram/active/{{>id}}"><div class="btn"><span class="glyphicon glyphicon-ok"></span></div></a>
    </div>
  </div>
</div>
</script>

<!--Шаблон тренировочного дня-->
<script id="daytmp" type="text/x-jsrender">
<div class="c1" exid="{{:exercise.id}}" id="1">
  <div class="row">
    <div class="header day blue"><H2 class="left"> {{:exercise.name}}</H2>
      <H2 class="right">Группа мышц: {{:exercise.musculgroups[0].name}}</H2>
    </div>
  </div>
  <div class="row rowelement">
    <div class="mycol-2 element tableheader"><img class="icon count" src="/img/count3.svg"> Подходы</div>
    <div class="mycol-4 element tableheader">
      <div class="firstrow row "><img class="icon" src="/img/repeat3.svg"> <span>Повторения</span></div>
      <div class="secondrow row ">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col">фактическое значение</div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col">по плану</div>
      </div>
    </div>
    <div class="mycol-4 element tableheader">
      <div class="firstrow row">
        <img class="icon" src="/img/weights3.svg"> <span>Вес</span>
      </div>
      <div class="secondrow row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col">фактическое значение</div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col">по плану</div>
      </div>
    </div>
  </div>
{{for sets}}
  <div class="row rowelement">
    <div class="mycol-2 element center" data="setnum">{{:#getIndex()+1}}</div>
    <div class="mycol-2 element center" data="repeats" exid="{{:#parent.parent.data.exercise.id}}">{{:repeat}}</div>
    <div class="mycol-2 element center" data="planrepeats" exid="{{:#parent.parent.data.exercise.id}}">{{if planrepeats != undefined}} {{:planrepeats}} {{else}} {{:plan_repeat}} {{/if}}</div>
    <div class="mycol-2 element center" data="weight" exid="{{:#parent.parent.data.exercise.id}}">{{:weight}}</div>
    <div class="mycol-2 element center" data="planweight" exid="{{:#parent.parent.data.exercise.id}}">{{if planweight != undefined}} {{:planweight}} {{else}} {{:plan_weight}} {{/if}}</div>
  </div>
{{/for}}
</div>
</script>




<script id="eatingdaytmp" type="text/x-jsrender">
  {{for days }}
    <div class="day" id="{{:~root.id}}">
      <div class="header active day blue"><H3 id="dayname">День {{:day_number + 1}} </H3>
      </div>
      <input type="hidden" value="{{:~root.date}}" name="date" />
      <input type="hidden" value="{{:~root.id}}" name="eatingprogram_id" />
      <input type="hidden" value="{{:~root.days[0].day_number}}" name="day_number" />
      <div class="exersicesblock">
        {{for data }}
          <div class="eating">
              {{:number+1}} приём пищи
            </div>
            <table date="{{:~root.date}}" eating_id="{{:eating.id}}" eatingprogram_id="{{:~root.id}}" 
            day_number="{{:~root.days[0].day_number}}" class="nutritiontable">
            <thead>
              <tr>
                <th rowspan="2" className="table-header nutrition-caption-name">Продукт</th>
                <th colspan="2" className="table-header nutrition-caption-weight">Граммы</th>
                <th colspan="2" className="table-header nutrition-caption-pfc">Б</th>
                <th colspan="2" className="table-header nutrition-caption-pfc">Ж</th>
                <th colspan="2" className="table-header nutrition-caption-pfc">У</th>
                <th colspan="2" className="table-header nutrition-caption-calories">Ккал</th>
              </tr>
              <tr>
                <th>план</th>
                <th>факт</th>
                <th>план</th>
                <th>факт</th>
                <th>план</th>
                <th>факт</th>
                <th>план</th>
                <th>факт</th>
                <th>план</th>
                <th>факт</th>
              </tr>
            </thead>
            <tbody>
            {{for foods}}
              <tr>
                <td style="width:25%">{{:real!=undefined?real.food.name:plan.food.name}}</td>
                <td style="width:12%">
                  {{:plan!=undefined?plan.cnt:0}}
                </td>
                <td style="width:13%" data="cnt" foodid = "{{:real!=undefined?real.food.id:plan.food.id}}">{{:real!=undefined?real.cnt:""}}</td>
                <td id="proteins" style="width:5%">{{:plan!=undefined?plan.food.proteins:0}}</td>
                <td style="width:5%" data="proteins" foodid = "{{:real!=undefined?real.food.id:plan.food.id}}">{{:real!=undefined?real.food.proteins:""}}</td>
                <td id="fats" style="width:10%">{{:plan!=undefined?plan.food.fats:0}}</td>
                <td style="width:5%" data="fats" >{{:real!=undefined?real.food.fats:""}}</td>
                <td id="hidrocarbonats" style="width:5%">{{:plan!=undefined?plan.food.hidrocarbonats:0}}</td>
                <td style="width:5%" data="hidrocarbonats" >{{:real!=undefined?real.food.hidrocarbonats:""}}</td>
                <td id="colories" style="width:7%">{{:plan!=undefined?plan.food.colories:0}}</td>
                <td style="width:8%" data="colories" >{{:real!=undefined?real.food.colories:""}}</td>
              </tr>
            {{/for}}
            <tfoot>
              <tr eating_id="{{:eating.id}}">
                <td colspan="3" style="width:50%">Итого</td>
                <td colspan="2" id="proteins" style="width:10%">{{:total.proteins}}</td>
                <td colspan="2" id="fats" style="width:10%">{{:total.fats}}</td>
                <td colspan="2" id="hidrocarbonats" style="width:10%">{{:total.hidrocarbonats}}</td>
                <td colspan="2" id="colories" style="width:15%">{{:total.colories}}</td>
              </tr>
            </tbody>
          </tfoot>
            </table>
        {{/for}}
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
              </tr>
            </thead>
            <tfoot>
              <tr className="total">
                <td colSpan="2" className="table-header">Норма</td>
                <td>{{:~root.norm.aveCaCf}}</td>
                <td>{{:~root.norm.aveFtCf}}</td>
                <td>{{:~root.norm.avePrCf}}</td>
                <td>{{:~root.norm.Kkal}}</td>
              </tr>
              {{if ~root.total != undefined}}
              <tr class="total">
                <td colSpan="2" className="table-header">Фактическое значение</td>
                <td id="proteins">{{:~root.total.proteins}}</td>
                <td id="fats">{{:~root.total.fats}}</td>
                <td id="hidrocarbonats">{{:~root.total.hidrocarbonats}}</td>
                <td id="colories">{{:~root.total.colories}}</td>
              </tr>
              {{/if}}
            </tfoot>
            </table>
      </div>
    </div>
  {{/for}}
</script>

<script type="text/javascript">
  var diary = [];
  var user_id = "";
  var userdiary = false;
  <?= isset($userdiary)?"userdiary = true;":""; ?>
  <?= isset($user)?("var user_id = '/".$user["id"]."';"):"" ?>
  var baseurl = '<?= isset($baseurl)?$baseurl:"/diary" ?>';
  <? foreach($diarydays as $day) { ?>
    diary[<?= $day->id ?>] = <?= json_encode($day);?>;
    diary[<?= $day->id ?>].date = '<?= $day->date->format("Y-m-d") ?>'
  <? } ?> 

  var eventArray = [
    <? foreach($diarydays as $day) { ?>
      { startDate: '<?= $day->date->format('Y-m-d') ?>', endDate: '<?= $day->date->format('Y-m-d') ?>', title: 'Тренировка', id: <?= $day->id ?> },
    <? } ?>
    ];

  var freeeatings = <?= json_encode($freeeatings) ?>;
  var trainingeatings = <?= json_encode($trainingeatings) ?>;

  var all = false;

</script>





