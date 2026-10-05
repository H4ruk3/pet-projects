<?= $this->Html->css('redesign/profile.css') ?>
<?= $this->Html->css('redesign/cropper.min.css') ?>
<?= $this->Html->css('../bootstrap/css/bootstrap-datetimepicker.min.css') ?>
<?= $this->Html->css('editor.css') ?>
<?= $this->Html->script('editor.js') ?>

<?= $this->fetch("content") ?>

<div class="row">
    <div class="col-lg-4 col-sm-6 col-md-4 left">
      <!--Название левого блока-->    
      <div class="row box cap">
        <span>Заполнение данных профиля</span>
      </div>    
      <!--Содержимое левого блока-->
      <form method="POST" action="" onsubmit="return saveprofile(<?= isset($assign)?"'/user/saveprofile'":"" ?>);">
        <? if (isset($user1)) { ?>
          <input type="hidden" value="<?= $user->id ?>" name="profileid">
          <input type="hidden" value="<?= $user1->id ?>" id="user1id">
        <? } ?>
        <div id="content" class="row box content">
          <div class="header active">
          <H2>Личные данные<div onclick="showhelp('personinfo', this)" class="help">?</div></H2>
        </div>
        <div class="form">
          <div class="formelement">
            <label for="fam">Фамилия</label>
            
            <input id="fam" type="text" name="fam" required maxlength = "100" class="form-control" value="<?= is_object($user)?$user->fam:"" ?>"/>
          </div>
          <div class="formelement">
            <label for="name">Имя</label>
            <input id="name" type="text" name="name" required maxlength = "100" class="form-control" value="<?= is_object($user)?$user->name:"" ?>"/>
          </div>
          <div class="formelement">
            <label for="otch">Отчество</label>
            <input id="otch" type="text" name="otch" required maxlength = "100" class="form-control" value="<?= is_object($user)?$user->otch:"" ?>"/>
          </div>
          <div class="formelement">
            <label for="sex">Пол</label>
            <div class="row radiogroup">
              <div class="col-lg-6">
                <input id="sex" type="radio" name="sex"  value="male" <?= is_object($user) && $user->sex=="male"?"checked":"" ?>/> Мужской
              </div>
              <div class="col-lg-6">
                <input id="sex" type="radio" name="sex"  value="female" <?= is_object($user) && $user->sex=="female"?"checked":"" ?>/> Женский
              </div>
            </div>
          </div>
          <div class="formelement">
            <label for="birthday">Дата рождения</label>
            <div class="input-group date" id="datetimepicker1">
              <input date id="date1" pattern="\d\d\.\d\d\.\d\d\d\d" type="text" class="form-control" required name="birthday" placeholder="дд.мм.гггг" value="<?= is_object($user) && $user->birthday!=null?$user->birthday->i18nFormat("dd.MM.yyyy"):""?>">
              <span class="input-group-addon">
                <span class="glyphicon-calendar glyphicon"></span>
              </span>
            </div>
          </div>

          <? if ($mode == "create") { ?>
          <div class="formelement">
            <label for="avatar">Аватар</label>
            <div class="input-group img" id="datetimepicker1">
              <input type="text" class="form-control" name="file_info" id="file_info" onclick="openFileOption();">
              <span class="input-group-addon file_upload" onclick="openFileOption();">
                <span class="glyphicon glyphicon-upload"></span>
                <input type="file" id="file_upload" name="avatar" onchange="readURL(this);">
              </span>
            </div>
            
            <div class="image_container">
              <img id="blah" src="/img/excersices/no_image_available.jpg" alt="your image" />
            </div>
            <div id="image_error"></div>
          </div>
        <? } ?>
      </div>
    </div>

    <div id="content" class="row box content">
      <div class="header">
        <H2>Профессиональные достижения<div onclick="showhelp('somatype', this)"  class="help">?</div></H2>
      </div>
      <div class="form">
          <div class="formelement">
            <label for="growth">Город</label>
            <div class="input-group">
              <input type="text" value="<?= is_object($user) && isset($user->city) ? $user->city : '' ?>" class="form-control" id="city" name="city">
            </div>
          </div>
          
          <div class="formelement">
            <label for="growth">Стаж</label>
            <div class="input-group">
              <input type="text" value="<?= is_object($user) && isset($user->stage) ? $user->stage : '' ?>" class="form-control" id="stage" name="stage">
              <span class="input-group-addon">лет</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Клуб</label>
            <div class="input-group">
              <input type="text" value="<?= is_object($user) && isset($user->club) ? $user->club : '' ?>" class="form-control" id="club" name="club">
            </div>
          </div>

          <div class="formelement">
            <label for="trainingtype">Тип ренировок</label>
            <div class="input-group">
              <input type="text" value="<?= is_object($user) && isset($user->trainingtype) ? $user->trainingtype : '' ?>" class="form-control" id="trainingtype" name="trainingtype">
            </div>
          </div>

      </div>
  </div>

  <div id="content" class="row box content">
      <div class="header">
        <H2>Информация о себе<div onclick="showhelp('somatype', this)"  class="help">?</div></H2>
      </div>
      <div class="form">
        <div class="formelement">
        <?php
    echo $this->Form->input('description', [
      'type' => 'textarea',
      'class' => 'form-control',
      'templateVars' => ["id"=>"txtEditor", "label"=>"", "type"=>"textarea", "name"=>"description", "value"=>isset($exercise)?$exercise->description:"", "rows"=>10, "cols" => 10]
    ]); ?>
        </div>
      </div>
  </div>
     
</div>

  <div class="helpblock col-lg-8 col-sm-6 col-md-8 ">

    <div class="row box cap">
        <span>Рекомендации по заполнению</span>
      </div>
    
    <!--Содержимое левого блока-->
    <div id="content" class="row box content edithelpblock">
      <div class="header active">
      <H2>Название блока к которому идут подсказки</H2>
    </div>
    <div class="form">
      Какие-то данные
    </div>
    <div class="foot"></div>
    <div class="alert alert-danger" role="alert" style="opacity: 0">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <strong>Ошибка!</strong>
      <div id="message"></div>
    </div>
  </div>

  </div>
</div>

<div class="row btnblock">
  <div class="col-lg-4 col-sm-6 col-md-4">
      <input type="submit" onclick="validate()" value ="Сохранить">
      <? if (isset($mode) && $mode == "create") {?><input type="button" onclick="document.location.href = '/profile'" value ="Пропустить"><? } ?>
    </div>
    </div>
    
  </form>  


<!--Скрипты-->
<script src="/js/moment-with-locales.min.js"></script>
<script src="/js/bootstrap-datetimepicker.min.js"></script>
<script src="/js/cropper.min.js"></script>
<script type="text/javascript" src="/js/somatotype.js"></script>
<script src="/js/profilecreate.js"></script>
<script>
      $(document).ready(function() {
                $("#txtEditor").Editor();
                <? if (isset($exercise)) { ?>
                $("#txtEditor").Editor("setText", `<?= $exercise->description ?>`);
            <? } ?>
      });

      function validate() {
        $("#txtEditor").val($("#txtEditor").Editor("getText"));
      }
    </script>


<script type="text/javascript" defer>
    /*Глобальные переменные*/
    <? if ($mode == "create") echo "var isCreate = true;"; else echo "var isCreate = false;"; ?>
    <? if (isset($assign)) echo "var isAssign = true;"; else echo "var isAssign = false;"; ?>
</script>