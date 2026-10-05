<?= $this->Html->css('../bootstrap/css/jquery-clockpicker.min.css') ?>
<?= $this->Html->css('redesign/routine.css') ?>

<?= $this->fetch("content") ?>

<style type="text/css">
  input[type="number"] {
  -webkit-appearance: textfield;
  -moz-appearance: textfield;
  appearance: textfield;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
}

.number-input {
  border: 2px solid #ddd;
  display: inline-flex;
}

.number-input,
.number-input * {
  box-sizing: border-box;
}

.number-input button {
  outline:none;
  -webkit-appearance: none;
  background-color: transparent;
  border: none;
  align-items: center;
  justify-content: center;
  width: 3rem;
  height: 3rem;
  cursor: pointer;
  margin: 0;
  position: relative;
}

.number-input button:before,
.number-input button:after {
  display: inline-block;
  position: absolute;
  content: '';
  width: 1rem;
  height: 2px;
  background-color: #212121;
  transform: translate(-50%, -50%);
}
.number-input button.plus:after {
  transform: translate(-50%, -50%) rotate(90deg);
}
.numbutton {
  top: 0px;
}
.numbutton:hover {
  cursor: pointer;
}
input[type="number"] {
  text-align: center;
}
.clockpicker .input-group-addon {
    cursor: pointer;
    right: 0px;
    position: relative; 
    height: auto;
    top: 0px;
}
</style>
<form method="POST" <?= isset($assign)?"action='/user/saveuserroutine'":""; ?>>
  <? if (isset($user)) { ?>
      <input type="hidden" name="userid" value=<?= $user['id']; ?>>
  <? } ?>
<div class="row">
		<div id="left" class="col-lg-4 left">
      <div class="block">
			<div class="row box cap">
				<a href="/routine" class="back"><i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i></a>
				<span>Создание распорядка дня</span>
			</div>
			<div id="content" class="row box content">
				<div class="form solid">
					<div class="formelement">
						<label for="name">Название распорядка дня</label>
						<input id="name" type="text" name="name" required maxlength="128" class="form-controll" value="<?= isset($routine)?$routine->name:'' ?>" onfocus="showrecomendation('name');"/>
					</div>
					<div class="btn-group" role="group" aria-label="...">
					  <button type="button" id="btn1" class="btn btn-default active" onclick="showday1();">Тренировочный день</button>
					  <button type="button" id="btn2" class="btn btn-default" onclick="showday2();">День отдыха</button>
					</div>

					<div class="day1">
						<div id="namegroup" class="formelement form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время подъёма</label>
              <!--<div class="col-xs-12" style="padding: 0px">-->
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" pattern="\d\d:\d\d" required="" value="<?= isset($routine)?(date('H:i', strtotime($routine->routineday[0]->wakeupTime->toDateTimeString()))):''  ?>" name="day[0][wakeupTime]" id="time01" onchange="validate(this);" onfocus="showrecomendation('time01');">
            <span class="input-group-addon glyphicon glyphicon-time">
                <!--<span class="glyphicon glyphicon-time"></span>-->
            </span>
        <!--</div>-->
        </div>
            </div>
            <div id="namegroup" class="formelement form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время тренировки</label>
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="<?= isset($routine)?(date('H:i', strtotime($routine->routineday[0]->trainTime->toDateTimeString()))):''  ?>" name="day[0][trainTime]" id="time02" onchange="validate(this);" onfocus="showrecomendation('time02');">
            <span class="input-group-addon glyphicon glyphicon-time"></span>
        </div>
            </div>

          <div id="namegroup" class="formelement form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Продолжительность тренировки</label>
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="<?= isset($routine)?(date('H:i', strtotime($routine->routineday[0]->trainDuration->toDateTimeString()))):''  ?>" name="day[0][trainDuration]" id="time03" onchange="validate(this);" onfocus="showrecomendation('time03');">
            <span class="input-group-addon glyphicon glyphicon-time"></span>
        </div>
            </div>

            <div id="namegroup" class="formelement form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время сна</label>
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="<?= isset($routine)?(date('H:i', strtotime($routine->routineday[0]->sleepTime->toDateTimeString()))):''  ?>" name="day[0][sleepTime]" id="time04" onchange="validate(this);" onfocus="showrecomendation('time04');">
            <span class="input-group-addon glyphicon glyphicon-time"></span>
        </div>
            </div>

            <div class="formelement">
                <label for="cnt">Количество приёмов пищи</label>
                  <? if (!isset($routine)) { ?>
                  <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="down(this.parentNode.querySelector('input[type=number]')); changeCnt('#times1','#eatCntId');"></span>
                    <input type="number" min="1" max="10" maxlength="2" step=1 id="eatCntId" value="<?= isset($routine)?count($routine->routineday[0]->eating):1  ?>" class="form-control" name="day[0][eatCount]" onchange="changeCnt('#times1','#eatCntId')">
                    <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="up(this.parentNode.querySelector('input[type=number]')); changeCnt('#times1','#eatCntId')"></span>
                  </div>                    
                    <? } else { ?>
                    <input type="number" disabled id="eatCntId" value="<?= count($routine->routineday[0]->eating)  ?>" class="form-control" name="day[0][eatCount]">
                      <? } ?>

              </div>

					</div>


          <div class="day2" style="display: none">
            <div id="namegroup" class="formelement form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время подъёма</label>
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="<?= isset($routine)?(date('H:i', strtotime($routine->routineday[1]->wakeupTime->toDateTimeString()))):''  ?>" name="day[1][wakeupTime]" id="time05" onchange="validate(this);" onfocus="showrecomendation('time05');">
            <span class="input-group-addon glyphicon glyphicon-time"></span>
        </div>
            </div>
            <div id="namegroup" class="formelement form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время сна</label>
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="<?= isset($routine)?(date('H:i', strtotime($routine->routineday[1]->sleepTime->toDateTimeString()))):''  ?>" name="day[1][sleepTime]" id="time06" onchange="validate(this);" onfocus="showrecomendation('time06');">
            <span class="input-group-addon glyphicon glyphicon-time"></span>
        </div>
            </div>

            <div class="formelement">
                <label for="cnt">Количество приёмов пищи</label>
                  <? if (!isset($routine)) { ?>
                  <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="down(this.parentNode.querySelector('input[type=number]')); changeCnt('#times2','#eatCntId2');"></span>
                    <input type="number" min="1" max="10" maxlength="2" step=1 id="eatCntId2" value="<?= isset($routine)?count($routine->routineday[1]->eating):1  ?>" class="form-control" name="day[1][eatCount]" onchange="changeCnt('#times2','#eatCntId2')" >
                    <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="up(this.parentNode.querySelector('input[type=number]')); changeCnt('#times2','#eatCntId2')"></span>
                  </div>
                  <? } else { ?>
                    <input type="number" disabled id="eatCntId" value="<?= count($routine->routineday[1]->eating)  ?>" class="form-control" name="day[0][eatCount]">
                  <? } ?>

              </div>

          </div>

          </div>
        </div>

				
			</div>

      <div id="content" class="row box content">
        <div class="header">
      <h2>Время приёмов пищи<div class="help">?</div></h2>
    </div>
      <!--<h2>Время приёмов пищи<div class="help">?</div></h2>-->
        <!--<hr>-->
          <div class="form solid">
          <div class="day1" id="times1">
            <? if (isset($routine)) {
                  $i = 1;
                foreach($routine->routineday[0]->eating as $eat) {
             ?>
             <div id="eatTimeId">
          <div id="namegroup" class="formelement form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время <?= $i ?> приёма пищи</label>
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="<?= isset($routine)?(date('H:i', strtotime($eat->time->toDateTimeString()))):''  ?>" name="day[0][eatTime][<?= $i-1?>]" id="time01" onchange="validate(this);" onfocus="showrecomendation('eattimes');">
            <span class="input-group-addon glyphicon glyphicon-time">
            </span>
        </div>
      </div>
            </div>
            <? $i++; } } else { ?>



            <div id="eatTimeId">
          <div id="namegroup" class="formelement form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время 1 приёма пищи</label>
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="" name="day[0][eatTime][0]" id="time01" onchange="validate(this);" onfocus="showrecomendation('eattimes');">
            <span class="input-group-addon glyphicon glyphicon-time">
            </span>
        </div>
      </div>
            </div>
          <? } ?>
<!--          <div id="namegroup" class="form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время 2 приёма пищи</label>
              <div class="col-xs-12" style="padding-left: 0px">
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="" name="wakeupTime" id="time01">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-time"></span>
            </span>
        </div>
        </div>
            </div>

            <div id="namegroup" class="form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время 3 приёма пищи</label>
              <div class="col-xs-12" style="padding-left: 0px">
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="" name="wakeupTime" id="time01">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-time"></span>
            </span>
        </div>
        </div>
            </div>-->
          </div>

          <div class="day2" style="display: none" id="times2">
            
            <? if (isset($routine)) {
                  $i = 1;
                foreach($routine->routineday[1]->eating as $eat) {
             ?>
             <div id="eatTimeId">
          <div id="namegroup" class="formelement form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время <?= $i ?> приёма пищи</label>
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="<?= isset($routine)?(date('H:i', strtotime($eat->time->toDateTimeString()))):''  ?>" name="day[1][eatTime][<?= $i-1?>]" id="time01" onchange="validate(this);" onfocus="showrecomendation('eattimes');">
            <span class="input-group-addon glyphicon glyphicon-time">
            </span>
        </div>
      </div>
            </div>
            <? $i++; } } else { ?>


            <div id="eatTimeId">
          <div id="namegroup" class="formelement form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время 1 приёма пищи</label>
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="" name="day[1][eatTime][0]" id="time01" onchange="validate(this);" onfocus="showrecomendation('eattimes');">
            <span class="input-group-addon glyphicon glyphicon-time"></span>
        </div>
            </div>

          <!--
          <div id="namegroup" class="form-group has-feedback" style="margin: 0px">
              <label htmlfor="time01">Время 2 приёма пищи</label>
              <div class="col-xs-12" style="padding-left: 0px">
            <div class="input-group clockpicker">
            <input type="text" time class="form-control" required="" value="" name="wakeupTime" id="time01">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-time"></span>
            </span>
        </div>
        </div>
            </div>-->
          </div>
          <? } ?>
</div>
		</div>
  </div>
    </div>
		<div class="col-lg-8 ">

    <div class="row box cap">
        <span>Рекомендации по составлению распорядка дня</span>
      </div>
    
    <!--Содержимое левого блока-->
    <div id="content" class="row box content helpblock" data-spy="affix" data-offset-top="40">
      Какие-то данные
    </div>

  </div>
</div>
<div class="row btnblock">
  <div class="col-lg-4">
      <input type="submit" value="Сохранить" onclick="onSubmit(this);">
    </div>
    </div>
    </form>

  <script type="text/javascript" src="/bootstrap/js/jquery-clockpicker.min.js"></script>
  <script src="/js/routine.js"></script>
    <script type="text/javascript">

$(document).ready(function() {
  $("input[time]").keypress(function(e) {
    var key = e.keyCode || e.which;
    if ((key>=48 && key<58 || ($(this)[0].value.trim().length==2 && key == 58 )) && $(this)[0].value.trim().length<5)
      {
        if ($(this)[0].value.trim().length == 0 && (key < 48 || key > 50))
          return false;
        if ($(this)[0].value.trim().length == 1) {
          var time = $(this)[0].value + String.fromCharCode(e.which);
          if (parseInt(time)>24 )
            return false;
        }
        if ($(this)[0].value.trim().length == 3 && (key < 48 || key > 54))
          return false;
        if ($(this)[0].value.trim().length == 4) {
          var time = $(this)[0].value[3] + String.fromCharCode(e.which);
          if (parseInt(time)>59)
            return false;
        }
        if ($(this)[0].value.trim().length == 2 && key!=58)
        $(this)[0].value+=':';
      }
    else
      return false;
  });
  $("input[type=number]").keypress(function(e) {
    var val = $(this)[0].value + String.fromCharCode(e.which)
    if (parseInt(val)>10 || parseInt(val)<0)
      return false;
  });
})

var recomendations = {'name' : 'Введите название распорядка дня. Это название будет отображаться в списке ваших распорядков дня.', 
    'time01': 'Введите время подъёма. Необходимо выбирать время подъёма таким образом чтобы продолжительность сна составляла не менее 8 часов',
    'time02': 'Введите время начала тренировки',
    'time03': 'Укажите сколько времени в среднем занимает ваша тренировка.',
    'time04': 'Введите время, когда вы ложитесь спать. Время сна должно выбираться таким образом чтобы продолжительность сна составляла не менее 8 часов. Время сна не должно быть позже 24:00 часов.',
    'time05': 'Введите время подъёма. Необходимо выбирать время подъёма таким образом чтобы продолжительность сна составляла не менее 8 часов',
    'time06': 'Введите время, когда вы ложитесь спать. Время сна должно выбираться таким образом чтобы продолжительность сна составляла не менее 8 часов. Время сна не должно быть позже 24:00 часов.',
    'eattimes': 'Введите время приёма пищи. <p>Не рекомендуется есть за час до начала тренировки.</p> <p>Не рекомендуется есть в течении часа после окончания тренировки. </p><p>Не рекомендуется есть за 1 час 30 минут до сна.</p>'
};

function showrecomendation(key) {
  $('.helpblock').empty();
  $('.helpblock').append("<p>" + recomendations[key] + "</p>");
}

$(".clockpicker").clockpicker({
                    donetext: "Установить"});

    function showday1() {
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
    }

function changeCnt(ob, ob2)
{

  var cnt = $(ob2).val();
  var times = $(ob);
  var time = $("#eatTimeId").clone();

  if (cnt < 1) {
    $(ob2)[0].value = 1;
    var cnt = 1;
  }

  var cntBefore = $(ob).children().length;
  console.log(cntBefore); 

  if (cnt > cntBefore)
  {
    for (i = cntBefore+1; i <= cnt; i++) {

    //var i = cntBefore;
        var time = time.clone();
        var label = time.find("label");
        label[0].innerText="Время "+i+" приёма пищи";
        var obj = time.find(":input");

        

        //obj[1].name = 'eatTime['+ i +'][minute]';

        times.append(time);
        if (ob=="#times1")
          obj[0].name = "day[0][eatTime]["+ (i-1) +']';
        else
          obj[0].name = "day[1][eatTime]["+ (i-1) +']';
        obj[0].value = '';
$(".clockpicker").clockpicker({
                    donetext: "Установить"});
      }
  }
  else
  if (cnt < cntBefore)
  {
    for (i = cnt; i<cntBefore; i++)
    $(ob).children().last().remove();  
  }
}
//$('#leftblock').perfectScrollbar();


function validate(ob) {
    var time6 = new Date();
    var valid = true;
  if ($(ob)[0].value.match(/\d\d:\d\d/)==null) {
    valid = false;  correct = false;
  }
  else {
    var t = $(ob)[0].value.split(':');
    if (parseInt(t[0]) > 24 || parseInt(t[0])<0 || parseInt(t[1]) > 60 || parseInt(t[1]) < 0) {
      valid = false;  correct = false; 
    }
    else {
      time6.setHours(t[0]);
      time6.setMinutes(t[1]);
      time6.setSeconds(0);
        //valid = true;  
    }
  }

  if (!valid) {
    $(ob)[0].setCustomValidity('Введено неверное значение времени');
    $(ob)[0].validationMessage = "Введено неверное значение времени. Формат времени hh:mm";
    showday2();
  }
  else
    $(ob)[0].setCustomValidity('');
  if (!($(ob)[0].checkValidity()))
    {
      var formGroup = $(ob).parents('.form-group');
      $(formGroup).addClass('has-error');
      $(formGroup).removeClass('has-success');  
      var glyphicon = formGroup.find('.form-control-feedback');
      glyphicon.addClass('glyphicon-remove').removeClass('glyphicon-ok');
       correct = false;
    } else {
      var formGroup = $(ob).parents('.form-group');
      $(formGroup).removeClass('has-error');
      $(formGroup).addClass('has-success');  
      var glyphicon = formGroup.find('.form-control-feedback');
      glyphicon.removeClass('glyphicon-remove').addClass('glyphicon-ok');
    }
}

function onSubmit(e) {
  var time1 = new Date()
  var correct = true;
  var valid = true
  if ($('#time01')[0].value.match(/\d\d:\d\d/)==null) {
    valid = false;  correct = false;
  }
  else {
    var t = $('#time01')[0].value.split(':');
    if (parseInt(t[0]) > 24 || parseInt(t[0])<0 || parseInt(t[1]) > 60 || parseInt(t[1]) < 0) {
      valid = false; correct = false;
    }
    else {
      time1.setHours(t[0]);
      time1.setMinutes(t[1]);
      time1.setSeconds(0);
      //valid = true;  
    }
  }

  if (!valid) {
    $('#time01')[0].setCustomValidity('Введено неверное значение времени');
    $('#time01')[0].validationMessage = "Введено неверное значение времени. Формат времени hh:mm";
    showday1();
  }
  else
    $('#time01')[0].setCustomValidity('');

  var time2 = new Date()
    valid = true
  if ($('#time02')[0].value.match(/\d\d:\d\d/)==null) {
    valid = false;  correct = false;
  }
  else {
    var t = $('#time02')[0].value.split(':');
    if (parseInt(t[0]) > 24 || parseInt(t[0])<0 || parseInt(t[1]) > 60 || parseInt(t[1]) < 0) {
      valid = false;  correct = false;
    }
    else {
      time2.setHours(t[0]);
      time2.setMinutes(t[1]);
      time2.setSeconds(0);
        //valid = true;  
    }
  }

  if (!valid) {
    $('#time02')[0].setCustomValidity('Введено неверное значение времени');
    $('#time02')[0].validationMessage = "Введено неверное значение времени. Формат времени hh:mm";
    showday1();
  }
  else
    $('#time02')[0].setCustomValidity('');



  var time3 = new Date()
    valid = true
  if ($('#time03')[0].value.match(/\d\d:\d\d/)==null) {
    valid = false;  correct = false;
  }
  else {
    var t = $('#time03')[0].value.split(':');
    if (parseInt(t[0]) > 24 || parseInt(t[0])<0 || parseInt(t[1]) > 60 || parseInt(t[1]) < 0) {
      valid = false;  correct = false;
    }
    else {
      time3.setHours(t[0]);
      time3.setMinutes(t[1]);
      time3.setSeconds(0);
        //valid = true;  
    }
  }

  if (!valid) {
    $('#time03')[0].setCustomValidity('Введено неверное значение времени');
    $('#time03')[0].validationMessage = "Введено неверное значение времени. Формат времени hh:mm";
    showday1();
  }
  else
    $('#time03')[0].setCustomValidity('');

  if (valid && time2 <= time1) {
    $('#time02')[0].setCustomValidity('Время тренировки не может быть раньше времени подъёма');
    $('#time02')[0].validationMessage = "Время тренировки не может быть раньше времени подъёма";
    showday1();
  }

  var time4 = new Date()
    valid = true
  if ($('#time04')[0].value.match(/\d\d:\d\d/)==null) {
    valid = false;  correct = false;
  }
  else {
    var t = $('#time04')[0].value.split(':');
    if (parseInt(t[0]) > 24 || parseInt(t[0])<0 || parseInt(t[1]) > 60 || parseInt(t[1]) < 0) {
      valid = false;  correct = false;
    }
    else {
      time4.setHours(t[0]);
      time4.setMinutes(t[1]);
      time4.setSeconds(0);
        //valid = true;  
    }
  }

  if (!valid) {
    $('#time04')[0].setCustomValidity('Введено неверное значение времени');
    $('#time04')[0].validationMessage = "Введено неверное значение времени. Формат времени hh:mm";
    showday1();
  }
  else
    $('#time04')[0].setCustomValidity('');

  var time5 = new Date()
    valid = true
  if ($('#time05')[0].value.match(/\d\d:\d\d/)==null) {
    valid = false;  correct = false;
  }
  else {
    var t = $('#time05')[0].value.split(':');
    if (parseInt(t[0]) > 24 || parseInt(t[0])<0 || parseInt(t[1]) > 60 || parseInt(t[1]) < 0) {
      valid = false;  correct = false;
    }
    else {
      time5.setHours(t[0]);
      time5.setMinutes(t[1]);
      time5.setSeconds(0);
        //valid = true;  
    }
  }

  if (!valid) {
    $('#time05')[0].setCustomValidity('Введено неверное значение времени');
    $('#time05')[0].validationMessage = "Введено неверное значение времени. Формат времени hh:mm";
    showday2();
  }
  else
    $('#time05')[0].setCustomValidity('');

  var time6 = new Date()
    //valid = true
  if ($('#time06')[0].value.match(/\d\d:\d\d/)==null) {
    valid = false;  correct = false;
  }
  else {
    var t = $('#time06')[0].value.split(':');
    if (parseInt(t[0]) > 24 || parseInt(t[0])<0 || parseInt(t[1]) > 60 || parseInt(t[1]) < 0) {
      valid = false;  correct = false; 
    }
    else {
      time6.setHours(t[0]);
      time6.setMinutes(t[1]);
      time6.setSeconds(0);
        //valid = true;  
    }
  }

  if (!valid) {
    $('#time06')[0].setCustomValidity('Введено неверное значение времени');
    $('#time06')[0].validationMessage = "Введено неверное значение времени. Формат времени hh:mm";
    showday2();
  }
  else
    $('#time06')[0].setCustomValidity('');

  //Проверка времени приёма пищи
  valid = true
  times = [];
  var eattimes = $('#times1').find(':input');
  for (var i=0; i < eattimes.length; i++) {
    if (eattimes[i].value.match(/\d\d:\d\d/)==null) {
      valid = false;  correct = false;
    }
    else {
      var t = eattimes[i].value.split(':');
      if (parseInt(t[0]) > 24 || parseInt(t[0])<0 || parseInt(t[1]) > 60 || parseInt(t[1]) < 0) {
        valid = false;  correct = false;
      }
      else {
        times[i] = new Date();
        times[i].setHours(t[0]);
        times[i].setMinutes(t[1]);
        times[i].setSeconds(0);
        //valid = true;  
      }
    }
    if (!valid) {
      eattimes[i].setCustomValidity('Введено неверное значение времени');
      eattimes[i].validationMessage = "Введено неверное значение времени. Формат времени hh:mm";
      showday1();
    }
    else
      eattimes[i].setCustomValidity('');
  }

  if (valid){
    var sorted = true;
    for (var i=0; i < eattimes.length-1; i++) {
      if (times[i]>= times[i+1]) {
        eattimes[i+1].setCustomValidity('Введено неверное значение времени');
        eattimes[i+1].validationMessage = "Введён неверный порядок приёмов пищи";
        showday1();
        valid = false;  correct = false;
        break;
      }
      else
        eattimes[i+1].setCustomValidity('');
    }    
    for (var i=0; i < eattimes.length; i++) {
      if (times[i] <= time1) {
        eattimes[i].setCustomValidity('Время приёма пищи не может быть раньше времени подъёма');
        eattimes[i].validationMessage = "Время приёма пищи не может быть раньше времени подъёма";
         showday1();
      }
      if (times[i] >= time4) {
        eattimes[i].setCustomValidity('Время приёма пищи не может быть позже времени сна');
        eattimes[i].validationMessage = "Время приёма пищи не может быть позже времени сна";
        showday1();
      }
      
    }
  }



//Для второго дня
//Проверка времени приёма пищи
  valid = true
  times = [];
  var eattimes = $('#times2').find(':input');
  for (var i=0; i < eattimes.length; i++) {
    if (eattimes[i].value.match(/\d\d:\d\d/)==null) {
      valid = false;  correct = false;
    }
    else {
      var t = eattimes[i].value.split(':');
      if (parseInt(t[0]) > 24 || parseInt(t[0])<0 || parseInt(t[1]) > 60 || parseInt(t[1]) < 0) {
        valid = false;  correct = false;
      }
      else {
        times[i] = new Date();
        times[i].setHours(t[0]);
        times[i].setMinutes(t[1]);
        times[i].setSeconds(0);
        //valid = true;  
      }
    }
    if (!valid) {
      eattimes[i].setCustomValidity('Введено неверное значение времени');
      eattimes[i].validationMessage = "Введено неверное значение времени. Формат времени hh:mm";
      showday2();
    }
    else
      eattimes[i].setCustomValidity('');
  }

  if (valid){
    var sorted = true;
    
      
    for (var i=0; i < eattimes.length-1; i++) {
      if (times[i]>= times[i+1]) {
        eattimes[i+1].setCustomValidity('Введено неверное значение времени');
        eattimes[i+1].validationMessage = "Неверный порядок приёмов пищи";
        showday2();
         correct = false;
        break;
      }
      else
        eattimes[i+1].setCustomValidity('');
    }  

    for (var i=0; i < eattimes.length; i++) {
      if (times[i] <= time5) {
        eattimes[i].setCustomValidity('Время приёма пищи не может быть раньше времени подъёма');
        eattimes[i].validationMessage = "Время приёма пищи не может быть раньше времени подъёма";
        showday2();
      }
      if (times[i] >= time6) {
        eattimes[i].setCustomValidity('Время приёма пищи не может быть позже времени сна');
        eattimes[i].validationMessage = "Время приёма пищи не может быть позже времени сна";
        showday2();
      }
    }

     //   }
      //}
  } 

  $('input, select').each(function() {
    if (!($(this)[0].checkValidity()))
    {
      var formGroup = $(this).parents('.form-group');
      $(formGroup).addClass('has-error');
      $(formGroup).removeClass('has-success');  
      var glyphicon = formGroup.find('.form-control-feedback');
      glyphicon.addClass('glyphicon-remove').removeClass('glyphicon-ok');
       correct = false;
    } else {
      var formGroup = $(this).parents('.form-group');
      $(formGroup).removeClass('has-error');
      $(formGroup).addClass('has-success');  
      var glyphicon = formGroup.find('.form-control-feedback');
      glyphicon.removeClass('glyphicon-remove').addClass('glyphicon-ok');
    }
  });    
    

    /*
    if ($('#name')[0].value == "")
    {
      $('#name')[0].checkValidity()
      var formGroup = $('#namegroup')[0];
      $(formGroup).addClass('has-error');
      $(formGroup).removeClass('has-success');
      //this.refs.formgroup.classList.add('has-error');
      //this.refs.formgroup.classList.remove('has-success');
        //добавить к glyphicon класс glyphicon-ok, удалить glyphicon-remove
      $('#glyphicon').addClass('glyphicon-remove');
      $('#glyphicon').removeClass('glyphicon-ok'); }*/
      if (correct)
        return true;
      else
        return false;
} 


  </script>