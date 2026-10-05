<?= $this->Html->css('redesign/routine.css') ?>
<?= $this->Html->css('redesign/profile.css') ?>
<?= $this->Html->css('admin/user.css') ?>
<style>
  .cap span {
  font-size: 1vw;
  }
  .box>#findbtn {
    float: right;
    margin: 5px;
    margin-right: 20px;
  }
  .row.box.cap {
    height: auto;
    min-height: 40px;
  }
  .find {
    width: 100%;
  }

</style>

<div class="row">
		<div id="left" class="col-lg-4 left">
      <div id="search" class="block">    

      <div class="row box cap" >
        <span>Список зарегистрированных пользователей</span>
        <a id="findbtn" href="#find" class="collapsed" data-toggle="collapse"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></a>
        <div id="find" class="find collapse" >
        <input type="text" name="find" id="filter" value="" >
      </div>
      </div>

      <!--<div class="row box find" >
        <input type="text" name="find" id="filter" value="" >
      </div>-->

<div class="list">
      <? foreach($users as $user) {
        ?> 
      
      <div id="content" class="row box content contentleft item">
        <div class="header">
        <h2 id=<?= $user->id ?> class="name"><?= $user->username ?></h2>
        <? if ($user->role == "admin") {?>
          <div class="admin">Администратор</div>
        <? } else if ($user->role == "corp") {?>
          <div class="corpuser">Корпоративный пользователь</div>
        <? } else if ($user->role == "trainer") {?>
          <div class="admin">Тренер</div>
        <? } ?>

      </div>
        
        <div class="form">
        <div class="row">
          <!--<a href="/admin/user/edit/<?= $routine->id?>"><div class="btn"><span class="glyphicon glyphicon-pencil"></span></div></a>-->
          <div class="btn" onclick="show(<?= $user->id ?>);"><span class="glyphicon glyphicon-eye-open"></span></div>
          <a href="/admin/users/delete/<?= $user->id?>"><div class="btn"><span class="glyphicon glyphicon-trash"></span></div></a>
          <!--<a href="/redesign/routine/active/<?= $routine->id?>"><div class="btn"><span class="glyphicon glyphicon-ok"></span></div></a>-->
        </div>
      </div>
      </div>
    
      <? } 
    ?>
  </div>
  <ul class="pagination"></ul>
      </div>
    </div>
		<div class="col-lg-8 ">

    <div class="row box cap">
        <span id="routinename">Подробная информация</span>
      </div>
    
    <!--Содержимое левого блока-->
    <div id="contentblock" class="row box content helpblock">

      <!-- Пример отображения -->
      
    </div>

  </div>
</div>

<script type="text/javascript" src="/js/jsrender.min.js"></script>
  <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/js/list.js"></script>

<!--Шаблон упражнения в дне тренировки-->
<script id="usertmp" type="text/x-jsrender">
  <div class="row">
        <div class="row">
          <div class="leftimg">
            <img src="/img/excersices/15095476092787559f9de599dd74.png" "="" id="avatar">
          </div>
          <div class="col-lg-6">
            <h2>{{:profile.username}}</h2>
            <hr>
            <p> <select size=1 onchange="changerole({{:profile.userid}}, this)">
              <option value="user" {{if (profile.role == 'user' || profile.role == null) }} selected {{/if}}>Пользователь</option>
              <option value="corp" {{if profile.role == 'corp'}} selected {{/if}}>Корпоративный пользователь</option>
              <option value="trainer" {{if profile.role == 'trainer'}} selected {{/if}}>Тренер</option>
              <option value="admin" {{if profile.role == 'admin'}} selected {{/if}}>Администратор</option>
            </select></p>
            <p> <strong>Фамилия:</strong> {{:profile.fam}}</p>
            <p> <strong>Имя:</strong> {{:profile.name}}</p>
            <p> <strong>Пол:</strong> {{if profile.sex == "male"}} мужской {{else profile.sex}} женский {{/if}}</p>
            <p> <strong>Возраст:</strong> {{:profile.age}}</p>
            <p> <strong>Рост:</strong> {{:profile.growth}}</p>
            <p> <strong>Вес:</strong> {{:profile.weight}}</p>
            <p> <strong>Телосложение:</strong> {{:profile.somatotype}} </p>
            <p> <strong>Цель тренировки: </strong>{{:profile.aimTrain}}</p>
          </div>
        </div>
        <div class="row ">
          <div class="row box tabs">
            <a class="clear" href="/user/viewuserroutine/{{:profile.userid}}">
              <div class="col-lg-4 center active">
                <p>{{:stat.routine}}</p>
                <p> Распорядков дня </p>
              </div>
            </a>    
            <a class="clear" href="/user/usertrainingprogram/{{:profile.userid}}">
              <div class="col-lg-4 center">
                <p>{{:stat.trainingprogram}}</p>
                <p> Программа тренировок </p>
              </div>
            </a>
            <a class="clear" href="/user/usernutritionprogram/{{:profile.userid}}">
            <div class="col-lg-4 center">
              <p>{{:stat.eatings}}</p>
              <p> Программ питания </p>
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
                <div class="empty">
                  <h4>У вас не выбрано ни одной активной программы питания</h4>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
</script>

<script>
  var aimTrain = ['Похудение', 'Набор мышечной массы', 'Поддержание текущего веса'];
    //"somatotype" => ['Эктоморф', 'Эндоморф', 'Мезоморф'],
  var somatotype = ['Эндоморф', 'Эктоморф', 'Мезоморф'];
  
  var userslist = new List('search', {
  valueNames: ['name'],
  page: 5,
  pagination: true
});

  function show(uid) {
    $.post( "/admin/users/getuserinfo", {"id" : uid}).done(function( data ) {
      //alert(data);
      var myTemplate = $.templates("#usertmp");
      var dataobj = $.parseJSON(data);

      if (dataobj.status == "error") {
        var content = $("#contentblock");
        content.empty();
        content.append("<H2>Профиль для данного пользователя не создан.</H2>");
        return;
      }
      dataobj.profile.somatotype = somatotype[dataobj.profile.somatotype-1];
      dataobj.profile.aimTrain = aimTrain[dataobj.profile.aimTrain-1];
      //Сортируем дни в активном распорядке дня
      var routineinfo = "";
        if (dataobj.currentroutine!=null && dataobj.currentroutine.routineday.length > 0) {
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

      var programinfo = "";
      if (dataobj.program != null && dataobj.program.trainingprogramday) {
        programinfo += "<div class='form'>";

        $.each(dataobj.program.trainingprogramday, function(dayIndex, day) {
          programinfo += "<div class='header day blue'><h2>День " + (dayIndex + 1) + "</h2></div>";

          if (day.trainingprogramday_exercise && day.trainingprogramday_exercise.length > 0) {
            programinfo += "<table class='table'><tr><th>Упражнение</th><th>Подходы</th><th>Повторения</th></tr>";

            $.each(day.trainingprogramday_exercise, function(i, ex) {
              var exName = (ex.exercise && ex.exercise.name) ? ex.exercise.name : "";
              programinfo += "<tr><td>" + exName + "</td><td>" +
                             (ex.cnt || "-") + "</td><td>" +
                             (ex.repeatcnt || "-") + "</td></tr>";
            });

            programinfo += "</table>";
          }
        });

        programinfo += "</div>";
      } else {
        programinfo = "<div class='empty'><h4>Активная программа тренировок не выбрана</h4></div>";
      }






      dataobj.currentroutine = routineinfo;
      dataobj.programinfo = programinfo;
      var html = myTemplate.render( dataobj);
      var content = $("#contentblock");
      content.empty();
      content.append(html);
      $('html, body').animate({
                    scrollTop: $("#contentblock").offset().top
                }, 2000);
      
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
        else if (role == "trainer")
          $("#"+userid).parent().append("<div class='trainer'>Тренер</div>");
      }
    });

  }

  $(document).ready(function(){
    $("#filter").keyup(function(){
 
        // Retrieve the input field text and reset the count to zero
        /*var filter = $(this).val(), count = 0;
 
        // Loop through the comment list
        $(".item").each(function(){
 
            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).fadeOut();
 
            // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show();
                count++;
            }

        });*/
        var searchString = $(this).val();
        userslist.search(searchString);
 
        // Update the count
        /*var numberItems = count;
        $("#filter-count").text("Number of Comments = "+count);*/
    });
});

  <? if (isset($activeuser)) echo 'show(' . $activeuser . ')'; ?>
</script>