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
      <form method="POST" action="" onsubmit="return saveprofile(<?= isset($assign)?"/redesign/user/saveprofile":"" ?>);">
        <? if (isset($user1)) { ?>
          <input type="hidden" value="<?= $user->id ?>" name="profileid">
          <input type="hidden" value="<?= $user1->id ?>" id="user1id">
        <? } ?>
        <div id="content" class="row box content">
          <div class="header active">
          <H2>Личные данные<div class="help">?</div></H2>
        </div>
        <div class="form">
          <div class="formelement">
            <label for="fam">Фамилия</label>
            <input id="fam" type="text" name="fam" required class="form-control" value="<?= $user->fam ?>"/>
          </div>
          <div class="formelement">
            <label for="name">Имя</label>
            <input id="name" type="text" name="name" required class="form-control" value="<?= $user->name ?>"/>
          </div>
          <div class="formelement">
            <label for="sex">Пол</label>
            <div class="row radiogroup">
              <div class="col-lg-6">
                <input id="sex" type="radio" name="sex"  value="male" <?= $user->sex=="male"?"checked":"" ?>/> Мужской
              </div>
              <div class="col-lg-6">
                <input id="sex" type="radio" name="sex"  value="female" <?= $user->sex=="female"?"checked":"" ?>/> Женский
              </div>
            </div>
          </div>
          <div class="formelement">
            <label for="birthday">Дата рождения</label>
            <div class="input-group date" id="datetimepicker1">
              <input id="date1" type="text" class="form-control" required name="birthday" placeholder="дд.мм.гггг" value="<?= $user->birthday!=null?$user->birthday->i18nFormat("dd.MM.yyyy"):""?>">
              <span class="input-group-addon">
                <span class="glyphicon-calendar glyphicon"></span>
              </span>
            </div>
          </div>
          <div class="formelement">
            <label for="growth">Рост</label>
            <div class="input-group">
              <input type="number" min="20" max="250" id="growth" value="<?= $user->growth ?>" class="form-control" name="growth">
              <span class="input-group-addon">см</span>
            </div>
          </div>
          <div class="formelement">
            <label for="weight">Вес</label>
            <div class="input-group">
              <input id="weight" type="number" name="weight" required min="20" max="250" class="form-control" value="<?= $user->weight ?>"/>
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
              <img id="blah" src="" alt="your image" />
            </div>
          </div>
        <? } ?>
      </div>
    </div>

    <div id="content" class="row box content">
      <div class="header">
        <H2>Телосложение<div class="help">?</div></H2>
      </div>
      <div id="colapseblock">  
        <div class="accordion-group">
        <div class="form collapse in" id="form1">
          <div class="formelement radiogroup" id="somatotypes">
            <label for="somatotype">Выберите один из предложенных вариантов</label><br />
            <input id="somatotype" type="radio" name="somatotype"  value = "1" <?= $user->somatotype=="1"?"checked":"" ?>/> Эктоморф <br />
            <input id="somatotype" type="radio" name="somatotype" value = "2" <?= $user->somatotype=="2"?"checked":"" ?>/> Мезоморф <br />
            <input id="somatotype" type="radio" name="somatotype"  value = "3" <?= $user->somatotype=="3"?"checked":"" ?>/> Эндоморф <br />
          </div>
          <a href="#form2" onclick="hideel1()" data-toggle="collapse" data-parent="#colapseblock">Определить телосложение при помощи калькулятора</a>
        </div>
        <div class="form form2 collapse" id="form2">
          <div class="formelement">
            <label for="growth">Длина ног</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="185" class="form-control" id="lenLeg">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Ширина плеч</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="185" class="form-control" id="widthShoulder">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Обхват запястья</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="185" class="form-control" id="girthWrist">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Ширина таза</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="185" class="form-control" id="widthPlev">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Длина рук</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="185" class="form-control" id="longArm">
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
        <input id="aimTrain" type="radio" name="aimTrain"  value = "1" <?= $user->aimTrain=="1"?"checked":"" ?>/> Похудение <br />
        <input id="aimTrain" type="radio" name="aimTrain"  value = "2" <?= $user->aimTrain=="2"?"checked":"" ?>/> Набор мышечной массы <br />
        <input id="aimTrain" type="radio" name="aimTrain"  value = "3" <?= $user->aimTrain=="3"?"checked":"" ?>/> Поддержание мышечной массы <br />
      </div>
    </div>
  </div>

   
</div>

  <div class="col-lg-8 col-sm-6 col-md-8 helpblock">

    <div class="row box cap">
        <span>Рекомендации по заполнению</span>
      </div>
    
    <!--Содержимое левого блока-->
    <div id="content" class="row box content">
      <div class="header active">
      <H2>Определение типа телосложения</H2>
    </div>
    <div class="form">
    <p><b>Ширина плеч</b> определяется как расстояние между плечевыми (акромиальными) точками, которые больше всего выступают в стороны при опущенных руках. Измерение следует проводить спереди, необходимо смотреть за правильным положением плеч, они не должны быть сильно приподняты или опущены. Измерение производится с надавливанием на мягкие ткани. При хорошо развитой мускулатуре плечевого пояса акромиальные отростки прощупываются с трудом. Чтобы их найти, нужно повращать плечом, при вращательных движениях руки точка остается неподвижной. </p>
    <br>
<p><b>Ширина таза</b> определяется расстоянием между подвздошно-остистыми передними точками правой и левой сторон. Для их определения необходимо найти наиболее выступающие участки тазовой кости. Измерение следует проводить спереди в положении стоя, с сомкнутыми пятками. Размер у тучных определяется при сильном нажатии. </p>
<br>
<p><b>Длина верхней конечности</b> определяется расстоянием от плечевой точки - наиболее выступающие точки акромиального отростка, до пальцевой точки, соответствующей мякоти ногтевой фаланги среднего пальца. </p>
<br>
<p><b>Длина нижней конечности</b> определяется высотой стояния от пола до вертельной точки. Отыскание этой точки у упитанных субъектов затруднено. Для контроля рекомендуется произвести сгибание и разгибание ноги в тазобедренном суставе, при этих движениях вертельная точка должна перемещаться. </p>
<br>
<p><b>Обхват запястья</b> определяется в наименее тонкой части запястья, чуть ниже кости на ведущей руке. Для правшей ведущей рукой является правая рука, для левшей - левая. При измерении не допускать неплотного прилегания измерительной ленты к руке и излишнего натяжения ленты. </p>
    </div>
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
    </div>
    </div>
    
  </form>  


<!--Скрипты-->
<script src="/js/moment-with-locales.min.js"></script>
<script src="/js/bootstrap-datetimepicker.min.js"></script>
<script src="/js/cropper.min.js"></script>
<script type="text/javascript" src="/js/somatotype.js"></script>
<script src="/js/profilecreate.js"></script>

<script type="text/javascript" defer>
    /*Глобальные переменные*/
    <? if ($mode == "create") echo "var isCreate = true;"; else echo "var isCreate = false;"; ?>
    <? if (isset($assign)) echo "var isAssign = true;"; else echo "var isAssign = false;"; ?>
</script>