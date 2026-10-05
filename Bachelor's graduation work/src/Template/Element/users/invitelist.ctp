<?= $this->Html->css('redesign/invitelist.css') ?>
<ul class="colortabs">
  <li class="center hvr-underline-from-center active" data-toggle="tab" href="#panel1"><p>Пригласить участника</p></li>
  <li data-toggle="tab" class="center hvr-underline-from-center" href="#panel2"><p>Отправленные заявки</p></li>
</ul>

<div class="tab-content">

<div id="panel1" class="block tab-pane fade in active" >
  <div id="search">
  <div class="row box cap">
    <span>Выберите пользователей</span>
  </div>
  <div class="row box find">
    <input type="text" name="find" class="search" id="filter" value="" placeholder="Введите имя пользователя">
  </div>


<div class="list">
  <? foreach($users as $user) { ?>
  <div id="content" class="row box content contentleft item">
    <div class="header" onclick="show(<?= $user->id?>, this);">
      <img src="/img/excersices/<?= $user->profile['avatar']!=null?$user->profile['avatar']:'no_image_available.jpg'?>">
      <div class="userinfo">
        <h2 id="<?= $user->id?>"><?= $user->profile['fam']?> <?= $user->profile['name']?></h2>
        <span class="name"><?= $user->username?></span>
      </div>
      <div class="check">
        <button type="button" onclick="inviteuser(<?= $user->id?>, this)" class="btn btn-primary startbutton"><i class="fa fa-mail-forward" aria-hidden="true"></i><span>Пригласить</span></button>
      </div>
    </div>
  </div>
  <? } ?>
</div>
<ul class="pagination"></ul>
</div>

</div>

<div id="panel2" class="tab-pane fade">
    <div class="list">
  <? foreach($usernotices as $user) { ?>
  <div id="content" class="row box content contentleft item">
    <div class="header" onclick="show(<?= $user->user['id']?>, this);">
      <img src="/img/excersices/<?= $user->user['profile']['avatar']!=null?$user->user['profile']['avatar']:'no_image_available.jpg'?>">
      <div class="userinfo">
        <h2 id="<?= $user->user['id']?>"><?= $user->user['profile']['fam']?> <?= $user->user['profile']['name']?></h2>
        <span class="name"><?= $user->user['username']?></span>
      </div>
      <div class="check">
        <button type="button" onclick="uninviteuser(<?= $user->user['id']?>, this)" class="btn btn-primary startbutton"><i class="fa fa-trash-o" aria-hidden="true"></i><span>Отменить</span></button>
      </div>
    </div>
  </div>
  <? } ?>
</div>
</div>
</div>

<script type="text/javascript" src="/js/jsrender.min.js"></script>
  <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/list.js"></script>
<script type="text/javascript">
  var userslist = new List('search', {
  valueNames: ['name'],
  page: 5,
  pagination: true
});

  function inviteuser(uid, obj) {
    var ob = {};
    ob.users = [uid];
    var element = obj;
    $.post( "/user/inviteusers", ob).done(function( data ) {
      alert(data);
      $(element.parentNode.parentNode.parentNode).remove();
      //window.location = '/user/index?useradded';
    });
  }

  function uninviteuser(uid, obj) {
    var ob = {};
    ob.users = uid;
    var element = obj;
    $.post( "/user/uninviteuser", ob).done(function( data ) {
      try {
        obj = $.parseJSON(data);
        if (obj.status == "success") {
          $(element.parentNode.parentNode.parentNode).remove();
          $("#messageok").html("Заявка удалена успешно.");
          $("#alertsuccess").fadeTo(500, 1);
                    window.setTimeout(function() { $(".alert").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
        } else {
          $("#message").html("Не удалось загрузить файл.");
                    $("#alerterror").fadeTo(500, 1);
                    window.setTimeout(function() { $(".alert").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
        }
      } catch(e) {
        $("#message").html("Не удалось загрузить файл.");
                    $("#alerterror").fadeTo(500, 1);
                    window.setTimeout(function() { $(".alert").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
      }      
    });
    return false;
  }
</script>