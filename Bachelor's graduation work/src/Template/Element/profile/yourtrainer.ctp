<?= $this->Html->css('redesign/invitelist.css') ?>
<div id="content" class="row box content contentleft item" style = "border: none; box-shadow: none;">
    <div class="header" style="display: flex; padding: 0;">
      <img src="/img/excersices/<?= $user->trainerprofile['avatar']!=null?$user->trainerprofile['avatar']:'no_image_available.jpg'?>">
      <div class="userinfo">
        <h2 id="<?= $user->id?>"><?= $user->trainerprofile['fam']?> <?= $user->trainerprofile['name']?></h2>
        <span><?= $user->username?></span>
      </div>
      <div class="check">
        <button type="button" onclick="unsettrainer(<?= $user->id?>, this)" class="btn btn-primary startbutton"><i class="fa fa-trash" aria-hidden="true"></i><span>Отписаться</span></button>
      </div>
    </div>
  </div>
<script>
  function unsettrainer(uid, obj) {
    var ob = {};
    ob.id = uid;
    var element = obj;
    $.post( "/user/unsettrainer", ob).done(function( data ) {
      alert(data);
      $(element.parentNode.parentNode.parentNode.parentNode).remove();
    });
  }
</script>