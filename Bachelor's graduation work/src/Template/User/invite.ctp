<?= $this->Html->css('redesign/routine.css') ?>
<?= $this->Html->css('redesign/profile.css') ?>
<?= $this->Html->css('admin/user.css') ?>
<style>
  .cap span {
  font-size: 1vw;
  }
  .content .header:hover {
    cursor: pointer;
    background-color: #eee;
  }
  .content .header.shown {
   background-color: rgb(108, 196, 78);
  }

</style>

<div class="row">
		<div id="left" class="col-lg-4 left">
      <div id="alerterror" class="alert alert-danger" role="alert" style="opacity: 0">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <strong>Ошибка!</strong>
        <div id="message"></div>
      </div>
      <div id="alertsuccess" class="alert alert-success" role="alert" style="opacity: 0">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <strong>Успех!</strong>
        <div id="messageok"></div>
      </div>
      <div class="row box cap">
        <a href="/user" class="back"><i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i></a>
        <span>Приглашение участника</span>
      </div>
      <? echo $this->element('users/invitelist');?>


      <!--<div class="block">  
      

      <div class="row box cap" >
        <span>Выберите пользователей</span>
      </div>
      <div class="row box find" >
        <input type="text" name="find" id="filter" value="" placeholder="Введите имя пользователя">
      </div>
      <? foreach($users as $user) {
        ?> 

      <div id="content" class="row box content contentleft item">
        <div class="header" onclick = "show(<?= $user->id?>, this);">
        <h2 id=<?= $user->id ?>><?= $user->username ?></h2>
        <input type="checkbox" name="user" value=<?= $user->id ?> onclick="showno()"/>
      </div>
      </div>
      <? } 
    ?>
      </div>
      <input type="button" class="btn-11" onclick="showmodal('#myModalBox')" value="Пригласить пользователей">
    -->
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

<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Пригласить следующих пользователей</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
        <ol id="memberslist">
        </ol>
        
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" onclick="submit();">Отправить приглашения</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="/js/jsrender.min.js"></script>
  <script type="text/javascript" src="/js/jquery-ui.min.js"></script>

<!--Шаблон упражнения в дне тренировки-->
<script id="usertmp" type="text/x-jsrender">
  <div class="row">
        <div class="row">
          <div class="leftimg">
            <img src="/img/excersices/{{if profile.avatar !== '' }}{{:profile.avatar}}{{else}}no_image_available.jpg{{/if}}" id="avatar">
          </div>
          <div class="col-lg-6">
            <h2>{{:profile.username}}</h2>
            <hr>
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
      </div>
</script>

<script>
  var aimTrain = ['Похудение', 'Набор мышечной массы', 'Поддержание текущего веса'];
    //"somatotype" => ['Эктоморф', 'Эндоморф', 'Мезоморф'],
  var somatotype = ['Эндоморф', 'Эктоморф', 'Мезоморф'];
  var noShow = false;
  
  function showno() {
    noShow = true;
  }
  function showmodal(dlg) {
    var items = $("input[name='user']:checked");
    var arr = items.map( function( index, element ) {
      return "<li>" + $('#'+element.value).text() + "</li>";
    }).get();

    $('#memberslist').empty();
    $('#memberslist').append(arr);
    console.log(arr);
    $(dlg).modal('show');
  }

  function show(uid, ob) {
    if (!noShow) {

      $.post( "/user/getuserprofile", {"id" : uid}).done(function( data ) {
        alert(data);
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
        var html = myTemplate.render( dataobj);
        var content = $("#contentblock");
        content.empty();
        content.append(html);
        $('html, body').animate({
                      scrollTop: $("#contentblock").offset().top
                  }, 2000);
        
      });
      $(".header").removeClass("shown");
      $(ob).addClass("shown");
    } else {
      noShow = false;
    }
  }

  function submit() {
    var items = $("input[name='user']:checked").map(function( index, element ) {
      return element.value;
    }).get();
    console.log(items);
    var ob = {};
    ob.users = items;
    //alert (JSON.stringify(ob));
    $.post( "/user/inviteusers", ob).done(function( data ) {
      //alert(data);
      window.location = '/user/index?useradded';
    });
  }

  function changerole(uid, ob) {
    var userid = uid;
    var role = ob.value;
    $.post( "/admin/users/changerole", {"id" : uid, "role" : ob.value}).done(function( data ) {
      alert(data);
      var dataobj = $.parseJSON(data);
      alert(dataobj.status);
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
        var filter = $(this).val(), count = 0;
 
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
        });
 
        // Update the count
        /*var numberItems = count;
        $("#filter-count").text("Number of Comments = "+count);*/
    });
});
</script>