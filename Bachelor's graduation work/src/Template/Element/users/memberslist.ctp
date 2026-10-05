<?= $this->Html->css('redesign/invitelist.css') ?>

<div id="panel1" class="block tab-pane fade in active" >
  <div id="search">
  <!--<div class="row box cap">
    <span>Выберите пользователей</span>
  </div>
  <div class="row box find">
    <input type="text" name="find" class="search" id="filter" value="" placeholder="Введите имя пользователя">
  </div>-->


<div class="list">
  <? foreach($users as $user) { ?>
  <div id="content" class="row box content contentleft item">
    <div class="header" onclick="show(<?= $user->id?>, this);">
      <img src="/img/excersices/<?= isset($user->profile->avatar) && $user->profile->avatar!=null ? $user->profile->avatar : 'no_image_available.jpg'?>">
      <div class="userinfo">
        <h2 id="<?= $user->id?>"><?= isset($user->profile->fam) ? $user->profile->fam : '' ?> <?= isset($user->profile->name) ? $user->profile->name : '' ?></h2>
        <span class="name"><?= $user->username?></span>
      </div>
      <div class="buttons">
        <button type="button" onclick="unsettrainer(<?= $user->id?>, this, event)" class="btn btn-primary startbutton"><i class="fa fa-trash" aria-hidden="true"></i></button>
        <button type="button" onclick="document.location = '/user/userdiary/<?= $user->id ?>'; event.stopPropagation(); return false;" class="btn btn-primary startbutton"><i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i></button>
      </div>
    </div>
  </div>
  <? } ?>
</div>
<ul class="pagination"></ul>
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

  function unsettrainer(uid, obj, e) {
    var ob = {};
    ob.id = uid;
    var element = obj;
    $.post( "/user/unsettrainer", ob).done(function( data ) {
      alert(data);
      $(element.parentNode.parentNode.parentNode).remove();
    });
    e.stopPropagation();
  }

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