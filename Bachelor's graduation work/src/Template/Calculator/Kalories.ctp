<?= $this->Html->css('redesign/profile.css') ?>
<?= $this->Html->css('redesign/cropper.min.css') ?>
<?= $this->Html->css('../bootstrap/css/bootstrap-datetimepicker.min.css') ?>

<?= $this->fetch("content") ?>

<style type="text/css">
  #description span {
    font-size: 18px;
  }
</style>

<div class="row">
    <div class="col-lg-4 col-sm-6 col-md-4 left">
      <!--Название левого блока-->    
      <div class="row box cap">
        <span>Рассчёт калорий</span>
      </div>    
      <!--Содержимое левого блока-->
      <div id="content" class="row box content">
        <div class="form">
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
            <label for="activity">Коэффициент активности</label>
            <div class="input-group">
              <select id="activity" class="form-control">
                <option value="1.2"> минимум или отсутствие физической нагрузки</option>
                <option value="1.375"> занятия фитнесом 3 раза в неделю</option>
                <option value="1.4625">  занятия фитнесом 5 раз в неделю</option>
                <option value="1.55">  интенсивная физическая нагрузка 5 раз в неделю</option>
                <option value="1.6375">  занятия фитнесом каждый день</option>
                <option value="1.725"> каждый день интенсивно или по два раза в день</option>
                <option value="1.9"> ежедневная физическая нагрузка плюс физическая работа</option>

              </select>
            </div>
          </div>

          <div class="formelement radiogroup">
            <label for="aimTrain">Цель тренировки</label> <br />
            <input id="aimTrain" type="radio" name="aimTrain"  value = "1" <?= is_object($user) && $user->aimTrain=="1"?"checked":"" ?>/> Похудение <br />
        <input id="aimTrain" type="radio" name="aimTrain"  value = "2" <?= is_object($user) && $user->aimTrain=="2"?"checked":"" ?>/> Набор мышечной массы <br />
        <input id="aimTrain" type="radio" name="aimTrain"  value = "3" <?= is_object($user) && $user->aimTrain=="3"?"checked":"" ?>/> Поддержание мышечной массы <br />
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
              <input type="number" min="20" max="250" value="<?= is_object($user)?$user->weight:"" ?>" class="form-control" id="weight">
              <span class="input-group-addon">кг</span>
            </div>
          </div>

          <div class="formelement">
            <label for="age">Возраст</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="<?= is_object($user)?$user->age:"" ?>" class="form-control" id="age">
              <span class="input-group-addon">лет</span>
            </div>
          </div>

          <div class="formelement">
            <label for="body">Обхват талии</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="" class="form-control" id="body">
              <span class="input-group-addon">см</span>
            </div>
          </div>
      <div id="colapseblock">  
        <div class="accordion-group">
        <div class="collapse in" id="form1">
          <div class="formelement radiogroup" id="somatotypes">
            <label for="somatotype">Укажите своё телосложение</label><br />
            <input id="somatotype" type="radio" name="somatotype"  value = "1" <?= is_object($user) && $user->somatotype=="1"?"checked":"" ?>/> Эктоморф <br />
            <input id="somatotype" type="radio" name="somatotype" value = "2" <?= is_object($user) && $user->somatotype=="2"?"checked":"" ?>/> Мезоморф <br />
            <input id="somatotype" type="radio" name="somatotype"  value = "3" <?= is_object($user) && $user->somatotype=="3"?"checked":"" ?>/> Эндоморф <br />
          </div>
          <a href="#form2" onclick="hideel1()" data-toggle="collapse" data-parent="#colapseblock">Определить телосложение при помощи калькулятора</a>
        </div>
        <div class="form2 collapse" id="form2">
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
          <input type="button" onclick="calc()" name="" value="Рассчитать">
        </div>
      </div>
    </div>
    <div class="col-lg-8 col-sm-6 col-md-8 helpblock">
      <div class="row box cap">
        <span>Ваша суточная норма потребления калорий</span>
      </div>
    
    <!--Содержимое левого блока-->
      <div id="content" class="row box content">
      <div id="description" class="form">
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="/js/somatotype.js"></script>
<script type="text/javascript" src="/js/kalories.js"></script>
<script type="text/javascript">

/***************************************
 * Переключение между формами вычисления соматотипа 
 */
function hideel1() {
    $('#form1').collapse('hide');
}

function hideel2() {
    $('#form2').collapse('hide');
    var type = defineSomatotype();
    $("#somatotype:checked").removeAttr("checked");
   //$("#somatotypes:nth-child("+(type*2)+")").attr('checked', 'checked');
   //$("#somatotypes:nth-child("+(type*2)+")").prop('checked', true);
   $($("#somatotypes")[0].children[type*2]).attr('checked', 'checked');
   $($("#somatotypes")[0].children[type*2]).prop('checked', true);

}

/**
 * Перемещение подсказки при скролле 
 */
function onScroll(e) {
    $(".helpblock").css("top", window.scrollY);
}
document.addEventListener('scroll', onScroll);

  function calc(){
    var result = defineKalories();
    var html = "<p> Калорий в сутки <span>" + result[0].kalories.toFixed(2) + "</span> кКал</p> \
        <p> Белков <span>" + result[1].bel.toFixed(2) + "</span> кКал <span>" + result[1].belgram.toFixed(2) + "</span> граммов</p> \
        <p> Жиров <span>" + result[2].fat.toFixed(2) + "</span> кКал <span>" + result[2].fatgram.toFixed(2) + "</span> граммов</p> \
        <p> Углеводов <span>" + result[3].prot.toFixed(2) + "</span> кКал <span>" + result[3].protgram.toFixed(2) + "</span> граммов</p>"
    $("#description")[0].innerHTML = html;
    //console.log(result);
  }
</script>