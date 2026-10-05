<? foreach ($message as $mess) { 
if ($mess->type == 1) {?>
<div id="msgblock" style="display: block" class="row box cap msg">
          <span>Пользователь <b><?= $mess->sender['username'] ?></b> пригласил вас вступисть в группу.</span>
          <hr>
          <div class="msgbuttons">
            <input type="button" name="" value="Отклонить" onclick = "reject(<?= $mess->sender['id']; ?>)">
            <input type="button" name="" value="Вступить" onclick = "subscribe(<?= $mess->sender['id']; ?>)">
          </div>
 </div>
 <? } else {?>
  <div id="msgblock" style="display: block" class="row box cap msg">
          <span>Пользователь <b><?= $mess->sender['username'] ?></b> предлагает Вам свои услуги тренера.</span>
          <hr>
          <div class="msgbuttons">
            <input type="button" name="" value="Отклонить" onclick = "reject(<?= $mess->sender['id']; ?>)">
            <input type="button" name="" value="Принять" onclick = "confirmtrainer(<?= $mess->sender['id']; ?>)">
          </div>
 </div>
 <? } }?>
 <script>
 	function subscribe(id) {
 		$.post( "/profile/subscribe", {"id" : id}).done(function( data ) {
      		//alert(data);
      		var dataobj = $.parseJSON(data);
      		$("#msgblock").css("display", "none");
      		$("#msgblock").after("<div id='usergroup' class='row box cap group'> \
          <span>Вы состоите в группе пользователей <b>" + dataobj.corpuser + "</b></span> \
          <input type='button' value='Выйти из группы' onclick = 'unsubscribe()'' /> \
 </div>");
  		});
 	}

  function reject(id) {
    $.post( "/profile/reject", {"id" : id}).done(function( data ) {
          //alert(data);
          var dataobj = $.parseJSON(data);
          if (dataobj.status == "success")
            $("#msgblock").css("display", "none");
      });
  }

  function confirmtrainer(id) {
    $.post( "/profile/confirmtrainer", {"id" : id}).done(function( data ) {
      var dataobj = $.parseJSON(data);
      $("#msgblock").css("display", "none");
      $("#msgblock").after("<div id='usergroup' class='row box cap group'> \
      <span>Вы состоите в группе пользователей <b>" + dataobj.corpuser + "</b></span> \
      <input type='button' value='Выйти из группы' onclick = 'unsubscribe()'' /> \
      </div>");
    });
  }
 </script>