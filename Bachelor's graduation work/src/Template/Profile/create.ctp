<?= $this->Html->css('redesign/profile.css') ?>
<?= $this->Html->css('redesign/cropper.min.css') ?>
<?= $this->Html->css('../bootstrap/css/bootstrap-datetimepicker.min.css') ?>

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
          <input type="hidden" value="<?= isset($user1) ? $user1->id : '' ?>" id="user1id">
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
          <div class="formelement">
            <label for="growth">Рост</label>
            <div class="input-group">
              <input type="number" min="20" max="250" id="growth" value="<?= is_object($user)?$user->growth:"" ?>" class="form-control" name="growth">
              <span class="input-group-addon">см</span>
            </div>
          </div>
          <div class="formelement">
            <label for="weight">Вес</label>
            <div class="input-group">
              <input id="weight" type="number" name="weight" required min="20" max="250" class="form-control" value="<?= is_object($user)?$user->weight:"" ?>"/>
              <span class="input-group-addon">кг</span>
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
        <H2>Телосложение<div onclick="showhelp('somatype', this)"  class="help">?</div></H2>
      </div>
      <div id="colapseblock">  
        <div class="accordion-group">
        <div class="form collapse in" id="form1">
          <div class="formelement radiogroup" id="somatotypes">
            <label for="somatotype">Выберите один из предложенных вариантов</label><br />
            <input id="somatotype" type="radio" name="somatotype"  value = "1" <?= is_object($user) && $user->somatotype=="1"?"checked":"" ?>/> Эктоморф <br />
            <input id="somatotype" type="radio" name="somatotype" value = "2" <?= is_object($user) && $user->somatotype=="2"?"checked":"" ?>/> Мезоморф <br />
            <input id="somatotype" type="radio" name="somatotype"  value = "3" <?= is_object($user) && $user->somatotype=="3"?"checked":"" ?>/> Эндоморф <br />
          </div>
          <a href="#form2" onclick="hideel1()" data-toggle="collapse" data-parent="#colapseblock">Определить телосложение при помощи калькулятора</a>
        </div>
        <div class="form form2 collapse" id="form2">
          <div class="formelement">
            <label for="growth">Длина ног</label>
            <div class="input-group">
              <input type="number" value="" class="form-control" id="lenLeg">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Ширина плеч</label>
            <div class="input-group">
              <input type="number" value="" class="form-control" id="widthShoulder">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Обхват запястья</label>
            <div class="input-group">
              <input type="number" value="" class="form-control" id="girthWrist">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Ширина таза</label>
            <div class="input-group">
              <input type="number" value="" class="form-control" id="widthPlev">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Длина рук</label>
            <div class="input-group">
              <input type="number" value="" class="form-control" id="longArm">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <input type="button" onclick="hideel2()" name="" value="Определить телосложение" data-target="#form1" data-toggle="collapse" data-parent="#colapseblock">
        </div>
      </div>
    </div>
  </div>




  <div id="content" class="row box content">
    <div class="header">
      <H2>Цель тренировки<div class="help">?</div></H2>
    </div>
    <div class="form">
      <div class="formelement radiogroup">
        <label for="aimTrain">Выберите один из предложенных вариантов</label> <br />
        <input id="aimTrain" type="radio" name="aimTrain"  value = "1" <?= is_object($user) && $user->aimTrain=="1"?"checked":"" ?>/> Похудение <br />
        <input id="aimTrain" type="radio" name="aimTrain"  value = "2" <?= is_object($user) && $user->aimTrain=="2"?"checked":"" ?>/> Набор мышечной массы <br />
        <input id="aimTrain" type="radio" name="aimTrain"  value = "3" <?= is_object($user) && $user->aimTrain=="3"?"checked":"" ?>/> Поддержание мышечной массы <br />
      </div>
    </div>
  </div>

  <div id="content" class="row box content">
    <div class="header">
      <H2>Коэффициент активности<div class="help">?</div></H2>
    </div>
    <div class="form">
          <div class="formelement">
            <label for="activity">Выберите один из предложенных вариантов</label>
            <div class="input-group">
              <select id="activity" name="activity" class="form-control">
                <option value="1.2" <?= is_object($user) && $user->activity==1.2?"selected":"" ?>> минимум или отсутствие физической нагрузки</option>
                <option value="1.375" <?= is_object($user) && $user->activity==1.375?"selected":"" ?>> занятия фитнесом 3 раза в неделю</option>
                <option value="1.4625" <?= is_object($user) && $user->activity==1.4625?"selected":"" ?>>  занятия фитнесом 5 раз в неделю</option>
                <option value="1.55" <?= is_object($user) && $user->activity==1.55?"selected":"" ?>>  интенсивная физическая нагрузка 5 раз в неделю</option>
                <option value="1.6375" <?= is_object($user) && $user->activity==1.6375?"selected":"" ?>>  занятия фитнесом каждый день</option>
                <option value="1.725" <?= is_object($user) && $user->activity==1.725?"selected":"" ?>> каждый день интенсивно или по два раза в день</option>
                <option value="1.9" <?= is_object($user) && $user->activity==1.9?"selected":"" ?>> ежедневная физическая нагрузка плюс физическая работа</option>

              </select>
            </div>
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

<script type="text/javascript">
    /*Глобальные переменные*/
    <?php if ($mode == "create") echo "var isCreate = true;"; else echo "var isCreate = false;"; ?>
    <?php if (isset($assign)) echo "var isAssign = true;"; else echo "var isAssign = false;"; ?>
</script>
<script src="/js/profilecreate.js"></script>