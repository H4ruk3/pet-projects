<?= $this->Html->css('redesign/profile.css') ?>
<?= $this->Html->css('redesign/cropper.min.css') ?>
<?= $this->Html->css('../bootstrap/css/bootstrap-datetimepicker.min.css') ?>

<?= $this->fetch("content") ?>

<div class="row">
    <div class="col-lg-4 col-sm-6 col-md-4 left">
      <!--Название левого блока-->    
      <div class="row box cap">
        <span>Определение телосложения</span>
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
            <label for="growth">Рост</label>
            <div class="input-group">
              <input type="number" min="20" max="250" id="growth" class="form-control" name="growth">
              <span class="input-group-addon">см</span>
            </div>
          </div>
          <div class="formelement">
            <label for="growth">Длина ног</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="" class="form-control" id="lenLeg">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Ширина плеч</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="" class="form-control" id="widthShoulder">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Обхват запястья</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="" class="form-control" id="girthWrist">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Ширина таза</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="" class="form-control" id="widthPlev">
              <span class="input-group-addon">см</span>
            </div>
          </div>

          <div class="formelement">
            <label for="growth">Длина рук</label>
            <div class="input-group">
              <input type="number" min="20" max="250" value="" class="form-control" id="longArm">
              <span class="input-group-addon">см</span>
            </div>
          </div>
          <input type="button" onclick="calc()" name="" value="Определить телосложение">
        </div>
      </div>
    </div>
    <div class="col-lg-8 col-sm-6 col-md-8 helpblock">
      <div class="row box cap">
        <span>Ваш соматип</span>
      </div>
    
    <!--Содержимое левого блока-->
      <div id="content" class="row box content">
        <div class="header active">
        <H2 id="somatype"></H2>
      </div>
      <div id="description" class="form">
        Какие-то данные
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="/js/somatotype.js"></script>
<script type="text/javascript">
  function calc(){
    var type = defineSomatotype();
    switch (type) {
      case 1: 
        $("#somatype")[0].innerHTML = 'Эктоморф'; break;
      case 2: 
        $("#somatype")[0].innerHTML = 'Мезоморф'; break;
      case 3: 
        $("#somatype")[0].innerHTML = 'Эндоморф'; break;
    }
  }
</script>