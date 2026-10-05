<?= $this->Html->css('redesign/routine.css') ?>
<?= $this->Html->css('redesign/profile.css') ?>
<?= $this->Html->css('admin/user.css') ?>
<style>
  .cap span {
  font-size: 1vw;
  }

</style>
<?= $this->Flash->render() ?>
<div class="row">
		<div id="left" class="col-lg-4 left">
      <div class="block">      
      <div class="row box cap" >
        <span>Список зарегистрированных пользователей</span>
      </div>
      <? $users = $users->toArray();
      if (isset($users[0]->usergroup)) { foreach($users[0]->usergroup as $user) {
        ?> 

      <div id="content" class="row box content contentleft">
        <div class="header">
        <h2 id=<?= $user->member['id'] ?>><?= $user->member['username'] ?></h2>
        <? if ($user->member['role'] == "admin") {?>
          <div class="admin">Администратор</div>
        <? } else if ($user->member['role'] == "corp") {?>
          <div class="corpuser">Корпоративный пользователь</div>
        <? } else if ($user->member['role'] == "trainer") {?>
          <div class="admin">Тренер</div>
        <? } ?>

      </div>
        
        <div class="form">
        <div class="row">
          <div class="btn" onclick="show(<?= $user->member['id'] ?>);"><span class="glyphicon glyphicon-eye-open"></span></div>
          <a href="/user/groupdelete/<?= $user->member['id'] ?>"><div class="btn"><span class="glyphicon glyphicon-trash"></span></div></a>
        </div>
      </div>
      </div>
      <? } } ?>

<? echo $this->element('users/memberslist');?>

      </div>
      <? if (isset($userrole) && ($userrole == "corp" || $userrole == "trainer")) { ?>
      <input type="button" class="btn-11" onclick="window.location = '/user/invite';" value="Пригласить пользователя">
      <? } ?>
    </div>
		<div class="col-lg-8 ">

    <div class="row box cap">
        <span id="routinename">Подробная информация</span>
      </div>
    
    <!--Содержимое левого блока-->
    <div id="contentblock" class="row box content helpblock max">

      <!-- Пример отображения -->
      
    </div>

  </div>
</div>

<?php 
/**************************************
Функция подстановки числительных
*****************************************/
function plural_form($number, $after) {
  $cases = array (2, 0, 1, 1, 1, 2);
  echo $after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
} ?>

<script type="text/javascript" src="/js/jsrender.min.js"></script>
  <script type="text/javascript" src="/js/jquery-ui.min.js"></script>

<!--Шаблон упражнения в дне тренировки-->
<script id="usertmp" type="text/x-jsrender">
  <div class="row">
        <div class="row">
          <div class="leftimg">
            <img src="/img/excersices/{{if (profile.avatar == null)}}no_image_available.jpg{{else}}{{:profile.avatar}}{{/if}}" id="avatar">
          </div>
          <div class="col-lg-6">
            <h2>{{:profile.username}}</h2>
            <hr>
            {{if userrole == 'trainer'}}

            {{else}}
            <p> <select size=1 onchange="changerole({{:profile.userid}}, this)">
              <option value="user" {{if (profile.role == 'user' || profile.role == null) }} selected {{/if}}>Пользователь</option>
              <option value="trainer" {{if profile.role == 'trainer'}} selected {{/if}}>Тренер</option>
            </select></p>
            <p><strong>Тренер</strong></p>
            <p><select size = 1 onchange="changetrainer({{:profile.userid}}, this)">
              <option value="null" selected>Нет тренера</option>
              {{for trainers}}
              <option value="{{:id}}" {{if (~root.profile.trainid == id)}} selected {{/if}}>{{:username}}</option>
              {{/for}}
            </select></p>
            {{/if}}
            <p> <strong>Фамилия:</strong> {{:profile.fam}}</p>
            <p> <strong>Имя:</strong> {{:profile.name}}</p>
            <p> <strong>Пол:</strong> {{if profile.sex == "male"}} мужской {{else profile.sex}} женский {{/if}}</p>
            <p> <strong>Возраст:</strong> {{:profile.age}}</p>
            <p> <strong>Рост:</strong> {{:profile.growth}}</p>
            <p> <strong>Вес:</strong> {{:profile.weight}}</p>
            <p> <strong>Телосложение:</strong> {{:profile.somatotype}} </p>
            <p> <strong>Цель тренировки: </strong>{{:profile.aimTrain}}</p>
            <p> <input type="button" value="Изменить" onclick = "window.location = '/user/updateprofile/{{:profile.userid}}/{{:profile.id}}'"></p>
          </div>
        </div>
        <div class="row ">
          <div class="row box tabs">
            <a class="clear" href="/user/viewuserroutine/{{:profile.userid}}">
              <div class="col-lg-4 center active">
                <p>{{:stat.routine}}</p>
                <p> {{:stat.routinestr}} </p>
              </div>
            </a>    
            <a class="clear" href="/user/usertrainingprogram/{{:profile.userid}}">
              <div class="col-lg-4 center">
                <p>{{:stat.trainingprogram}}</p>
                <p> {{:stat.trainingprogramstr}} </p>
              </div>
            </a>
            <a class="clear" href="/user/usernutritionprogram/{{:profile.userid}}">
            <div class="col-lg-4 center">
              <p>{{:stat.eatings}}</p>
              <p> {{:stat.eatingsstr}} </p>
            </div>
          </a>
          </div>
            
            <!--Содержимое левого блока-->
          <ul class="row colortabs">
            <li class="col-lg-4 center hvr-underline-from-center active" data-toggle="tab" data-target="#panel1">
              <p> Текущий распорядок дня </p>
            </li>    
            <li class="col-lg-4 center hvr-underline-from-center" data-toggle="tab" data-target="#panel2">
              <p> Текущая программа тренировок </p>
            </li>
            <li class="col-lg-4 center hvr-underline-from-center" data-toggle="tab" data-target="#panel3">
              <p> Текущая программа питания </p>
            </li>
          </ul>
          <div id="content" class="row box ">
              
            <div class="data tab-content">
                <!--<div class="form">Данные</div>-->
              <div id="panel1" class="tab-pane fade active in">
                {{:currentroutine}}
              </div>
              <div id="panel2" class="tab-pane fade">
                            {{:programinfo}}
              </div>
              <div id="panel3" class="tab-pane fade">
                {{if eating}}
                  <div class="header day blue"><h2>{{:eating.name}}</h2></div>
                  {{for eating.days}}
                    <div class="header day blue"><h3>День {{:#index+1}}</h3></div>
                    {{for #data}}
                      <div class="eating">{{:#index+1}} приём пищи</div>
                      <table class="nutritiontable" style="width:100%">
                        <thead><tr>
                          <th>Продукт</th><th>Граммы</th>
                          <th>Б</th><th>Ж</th><th>У</th><th>Ккал</th>
                        </tr></thead>
                        <tbody>
                          {{for foods}}
                          <tr>
                            <td>{{:food.name}}</td>
                            <td>{{:cnt}}</td>
                            <td>{{:food.proteins}}</td>
                            <td>{{:food.fats}}</td>
                            <td>{{:food.hidrocarbonats}}</td>
                            <td>{{:food.colories}}</td>
                          </tr>
                          {{/for}}
                        </tbody>
                      </table>
                    {{/for}}
                  {{/for}}
                {{else}}
                  <div class="empty">
                    <h4>У вас не выбрано ни одной активной программы питания</h4>
                  </div>
                {{/if}}
              </div>
            </div>
          </div>

        </div>
      </div>
</script>

<script>
  $("document").ready(function(){
    setTimeout(function(){
        $("div.alert").remove();
    }, 5000 ); // 5 secs

});

function plural_form(number, after) {
  var cases = [2, 0, 1, 1, 1, 2];
  return after[ (number%100>4 && number%100<20)?2:cases[Math.min(number%10, 5)] ];
} 
  
  var aimTrain = ['Похудение', 'Набор мышечной массы', 'Поддержание текущего веса'];
    //"somatotype" => ['Эктоморф', 'Эндоморф', 'Мезоморф'],
  var somatotype = ['Эндоморф', 'Эктоморф', 'Мезоморф'];

  var trainers = [
    <? 
      if (isset($trainers)) {
      $last = false;
    foreach($trainers as $trainer) {
      if ($last) {
        echo ", ";
      }
      echo "{'id' : " . $trainer->id . ", 'username' : '" . $trainer->username . "'}"; 
      $last = true;
    } 
  } else {
    // $trainers не задан — выводим пустой массив (тренер просматривает своих подопечных)
    echo "";
  }?>
    
  ];
  
  //alert(trainers[0].username);
  var current_user = null;
  
  <? if (isset($activeuser)) { echo "show(".$activeuser.");";}?>

  function show(uid) {
    $.post( "/user/getuserinfo", {"id" : uid}).done(function( data ) {
      var myTemplate = $.templates("#usertmp");
      if (!myTemplate) { alert("ОШИБКА: шаблон #usertmp не найден!"); return; }
      var dataobj;
      try {
        dataobj = $.parseJSON(data);
      } catch(e) {
        $("#contentblock").empty().append("<p style=\'color:red\'>Ошибка ответа сервера: " + data.substring(0, 200) + "</p>");
        return;
      }

      if (dataobj.status == "error") {
        var content = $("#contentblock");
        content.empty();
        content.append("<H2>Профиль для данного пользователя не создан.</H2>");
        return;
      }
      current_user = dataobj.profile;
      dataobj.profile.somatotype = somatotype[dataobj.profile.somatotype-1];
      dataobj.profile.aimTrain = aimTrain[dataobj.profile.aimTrain-1];
      dataobj.stat.routinestr = plural_form(
              dataobj.stat.routine,
              ['Распорядок','Распорядка','Распорядков']) + ' дня';
      dataobj.stat.trainingprogramstr = plural_form(dataobj.stat.trainingprogram, 
              ['Программа','Программы','Программ']) + ' тренировок';
      dataobj.stat.eatingsstr = plural_form(
            dataobj.stat.eatings,
            ['Программа','Программы','Программ']
          ) + ' питания';
      //Сортируем дни в активном распорядке дня
      var routineinfo = "";
      try {
        if (dataobj.currentroutine!=null && dataobj.currentroutine.routineday != null && dataobj.currentroutine.routineday.length > 0) {
          routineinfo+="<div class='header day blue'><h2>Тренировочный день</h2></div><div class='form'>"
            var dates = [];
            dates[0]={};
            dates[0]["date"] = new Date(dataobj.currentroutine.routineday[0].wakeupTime);
            dates[0]["title"] = "Подъём";
            dates[1]={};
            dates[1]["date"] = new Date(dataobj.currentroutine.routineday[0].trainTime);
            dates[1]["title"] = "Время тренировки";
            dates[2]={};
            dates[2]["date"] = new Date(dataobj.currentroutine.routineday[0].sleepTime);
            dates[2]["title"] = "Сон";

      for (var j=0; j<dataobj.currentroutine.routineday[0].eating.length; j++) {
        dates[j+3]={};
        dates[j+3]["date"] = new Date(dataobj.currentroutine.routineday[0].eating[j].time);
        dates[j+3]["title"] = "" + (j+1) + " приём пищи";
      }
      dates.sort(function(a,b){
        return b.date<a.date; 
      });
      $.each(dates, function( key, value ) {
        routineinfo+= "<p> <span>" + ((''+value.date.getHours()).length<2 ? '0' :'') + value.date.getHours() + ':' +
    ((''+value.date.getMinutes()).length<2 ? '0' :'') + value.date.getMinutes() + "</span> " + value.title + "</p>";
        //console.log('caste: ' + value.caste + ' | id: ' +value.id);
      });

        routineinfo+= "</div><div class='header day blue'><h2>День отдыха</h2></div><div class='form'>";

      //День 2
      var dates2 = [];
      dates2[0]={};
      dates2[0]["date"] = new Date(dataobj.currentroutine.routineday[1].wakeupTime);
      dates2[0]["title"] = "Подъём";
      dates2[1]={};
      dates2[1]["date"] = new Date(dataobj.currentroutine.routineday[1].sleepTime);
      dates2[1]["title"] = "Сон";

      for (var j=0; j<dataobj.currentroutine.routineday[1].eating.length; j++) {
        dates2[j+2]={};
        dates2[j+2]["date"] = new Date(dataobj.currentroutine.routineday[1].eating[j].time);
        dates2[j+2]["title"] = "" + (j+1) + " приём пищи";
      }
      dates2.sort(function(a,b){
        return b.date<a.date; 
      });
      $.each(dates2, function( key, value ) {
        routineinfo+= "<p> <span>" + ((''+value.date.getHours()).length<2 ? '0' :'') + value.date.getHours() + ':' +
    ((''+value.date.getMinutes()).length<2 ? '0' :'') + value.date.getMinutes() + "</span> " + value.title + "</p>";
        //console.log('caste: ' + value.caste + ' | id: ' +value.id);
      });
    }
    routineinfo+="</div>";
      } catch(e) {
        routineinfo = "<p>Ошибка распорядка: " + e.message + "</p>";
      }

      dataobj.trainers = trainers;
      dataobj.userrole = "<?= $userrole ?>";
      var programinfo = "";
      if (dataobj.program != null && dataobj.program.trainingprogramday) {
          programinfo += "<div class='form'>";
          $.each(dataobj.program.trainingprogramday, function(dayIndex, day) {
              programinfo += "<div class='header day blue'><h2>День " + (dayIndex + 1) + "</h2></div>";
              if (day.trainingprogramday_exercise && day.trainingprogramday_exercise.length > 0) {
                  programinfo += "<table class='table'><tr><th>Упражнение</th><th>Подходы</th><th>Повторения</th></tr>";
                  $.each(day.trainingprogramday_exercise, function(i, ex) {
                      var exName = (ex.exercise && ex.exercise.name) ? ex.exercise.name : "";
                      programinfo += "<tr><td>" + exName + "</td><td>" + (ex.cnt || "-") + "</td><td>" + (ex.repeatcnt || "-") + "</td></tr>";
                  });
                  programinfo += "</table>";
              }
          });
          programinfo += "</div>";
      } else {
          programinfo = "<div class='empty'><h4>Активная программа тренировок не выбрана</h4></div>";
      }
      dataobj.programinfo = programinfo;
      dataobj.currentroutine = routineinfo;
      // Преобразуем days из объекта в массив для jsrender
      if (dataobj.eating && dataobj.eating.days) {
        console.log('eating.days raw:', JSON.stringify(dataobj.eating.days));
        var daysObj = dataobj.eating.days;
        var daysArr = [];
        // days — объект с ключами day_number
        var dayKeys = Object.keys(daysObj).sort(function(a,b){ return a-b; });
        for (var di = 0; di < dayKeys.length; di++) {
          var dayObj = daysObj[dayKeys[di]];
          var eatingsArr = [];
          // каждый день — объект с числовыми ключами (индекс приёма пищи)
          var eatKeys = Object.keys(dayObj).sort(function(a,b){ return a-b; });
          for (var ei = 0; ei < eatKeys.length; ei++) {
            var eating = dayObj[eatKeys[ei]];
            // foods — объект с числовыми ключами
            if (eating.foods && !Array.isArray(eating.foods)) {
              eating.foods = Object.values(eating.foods);
            }
            eatingsArr.push(eating);
          }
          daysArr.push(eatingsArr);
        }
        dataobj.eating.days = daysArr;
        console.log('eating.days converted:', JSON.stringify(dataobj.eating.days));
      }
      var html = myTemplate.render( dataobj);
      var content = $("#contentblock");
      content.empty();
      content.append(html);
      $('html, body').animate({
                    scrollTop: $("#contentblock").offset().top
                }, 2000);
      
    });
  }

  function changetrainer(uid, ob) {
    var userid = uid;
    var trainid = ob.value;
    $.post( "/user/settrainer", {"userid" : uid, "trainid" : ob.value}).done(function( data ) {
      //alert(data);
      var dataobj = $.parseJSON(data);
      //alert(dataobj.status);
    });  
  }

  function changerole(uid, ob) {
    var userid = uid;
    var role = ob.value;
    $.post( "/admin/users/changerole", {"id" : uid, "role" : ob.value}).done(function( data ) {
      //alert(data);
      var dataobj = $.parseJSON(data);
      //alert(dataobj.status);
      $("#"+userid).parent().find("div").remove();
      if (dataobj.status == "success") {
        if (role == "corp")
          $("#"+userid).parent().append("<div class='corpuser'>Корпоративный пользователь</div>");
        else if (role == "admin")
          $("#"+userid).parent().append("<div class='admin'>Администратор</div>");
        else if (role == "user") 
          $("#"+userid).parent().find("div").remove();
        else if (role == "trainer") {
          $("#"+userid).parent().append("<div class='trainer'>Тренер</div>");
          trainers[trainers.length] = {id : current_user.userid, username : current_user.username};
        }
      }
    });

  }
</script>