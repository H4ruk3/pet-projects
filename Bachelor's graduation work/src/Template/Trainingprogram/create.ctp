<?= $this->Html->css('/perfectscrollbar/css/perfect-scrollbar.min.css') ?>
<?= $this->Html->css('redesign/cropper.min.css') ?>
<?= $this->Html->css('redesign/modal.css') ?>
<?= $this->Html->css('redesign/trainingprogramcreate.css') ?>

<?= $this->fetch("content") ?>

<div id="left" class="col-lg-6 left">
      <div class="block">
      <div class="row box cap">
        <a href="/trainingprogram/index" class="back"><i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i></a>
        <span>Создание программы тренировок</span>
        <a href="#" class="righttoolbar" onclick="hide(this)"><i class="glyphicon glyphicon-th"></i></a>
        <a href="#" class="righttoolbar active" onclick="list(this)"><i class="glyphicon glyphicon-th-list"></i></a>
      </div>
      <div id="content" class="row box content <?= isset($user)?"withcrumb":""?> ps ps--theme_default" data-ps-id="ab09a7ca-63e5-6b8f-42ea-31cfd2422edb">
        <form id="programform" method="POST" <?= isset($redirect)?"action='$redirect'":""?> >
          <? if (isset($user)) { ?>
          <input type="hidden" name="user" value=<?= $user['id'] ?> />
          <? } ?>
          <? if (isset($program)) { ?>
          <input type="hidden" name="program" value=<?= $program->id ?> />
          <? } ?>
          <div class="form solid">
          <div class="row">
            <div class="col-lg-6">
          <div class="formelement">
            <label for="name">Название программы тренировок</label>
            <input id="name" maxlength="100" type="text" required name="name" class="form-controll" value="<? if (isset($program)) echo $program->name; ?>">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="formelement">
            <label for="name">Цель тренировки</label>
            <!--<input id="name" type="text" required name="name" class="form-controll" value="<? if (isset($program)) echo $program->name; ?>">-->
            <div class="custom-select" style="width:100%;">
            <select id="aimtrain" size=1 name="aimTrain">
              <option value=1 <? if (isset($program) && $program->aimTrain=="1") echo "selected"; else if (isset($user["profile"]) && $user["profile"]->aimTrain == "1") echo "selected" ?> >Похудение</option>
              <option value=2 <? if (isset($program) && $program->aimTrain=="2") echo "selected"; else if (isset($user["profile"]) && $user["profile"]->aimTrain == "2") echo "selected" ?> >Набор мышечной массы</option>
              <option value=3 <? if (isset($program) && $program->aimTrain=="3") echo "selected"; else if (isset($user["profile"]) && $user["profile"]->aimTrain == "3") echo "selected"?> >Поддержание мышечной массы</option>
            </select>
            </div>
          </div>
        </div>
        </div>
        </div>
          
          <div class="form solid">
          <div id="buttongroup">
          <div id="buttons" class="btn-group" role="group" aria-label="...">
            <? if (isset($program)) { 
              $cnt = 0; foreach($program->trainingprogramday as $day) { $cnt++; ?>
              <button type="button" class="btn btn-default daybtn <? if ($cnt == 1) echo 'active'?>" onclick='changeday(this)' data="<?= $cnt?>">День <?= $cnt?></button>
            <? } } else { ?>
            <button type="button" class="btn btn-default daybtn active" onclick='changeday(this)' data="1">День 1</button>
            <? } ?>
            <button type="button" class="btn-default btn" onclick="addday(this)"><i class="glyphicon glyphicon-plus"></i></button>
          </div>
        </div>
          
        </div>
        
          <div id="days">
            <? if (isset($program)) { 
            $cnt = 0; foreach($program->trainingprogramday as $day) { $cnt++;
            ?>
              <div class="day" id="<?= $cnt ?>">
            <div class="header active"><H3>День <?= $cnt ?> </H3><a href="#" onclick="removeday('#<?= $cnt ?>')"><span class="glyphicon glyphicon-trash" ></span></a> </div>
            <div class="exersicesblock">
              <? $cntex = 0; foreach($day->trainingprogramday_exercise as $ex) { $cntex++;?> 
              <div class="row excersice" data="<?= $ex->exercise->id ?>">
              <div class="header">
              <h4><?= $ex->exercise->name ?></h4>
              <a href="#" class="exclose" onclick="return removeex(<?= $cnt ?>, this)"><span class="glyphicon glyphicon-remove"></span></a>
              </div>
              <input type="hidden" name="exercise[<?= $cnt ?>][<?= $cntex ?>][excersiceid]" value="<?= $ex->exercise->id ?>"/> 
              <div class="col-lg-6" style="padding-left: 0">
                <div class="formelement">
                  <label for="cnt">Количество подходов</label>
                    <div class="input-group">
                      <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="down(this.parentNode.querySelector('input[type=number]'));"></span>
                      <input type="number" min="1" max="10" step=1 id="cnt" class="form-control" name="exercise[<?= $cnt ?>][<?= $cntex ?>][podhod]" value = "<?= $ex->podhod ?>">
                      <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="up(this.parentNode.querySelector('input[type=number]'));"></span>
                    </div>
                  </div>

                <div class="formelement">
                  <label for="cnt">Минимальный вес</label>
                    <div class="input-group">
                      <input type="number" min="0" max="100" id="cnt" class="form-control" name="exercise[<?= $cnt ?>][<?= $cntex ?>][minweight]" value="<?= $ex->minweight ?>">
                      <span class="input-group-addon numbutton">%</span>
                    </div>
                  </div>
              </div>
              <div class="col-lg-6"  style="padding-right: 0">
              <div class="formelement">
                  <label for="cnt">Количество повторений</label>
                    <div class="input-group">
                      <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="down(this.parentNode.querySelector('input[type=number]'));"></span>
                      <input type="number" min="0" max="100" step=1 id="cnt" class="form-control" name="exercise[<?= $cnt ?>][<?= $cntex ?>][repeat]" value="<?= $ex->repeats ?>">
                      <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="up(this.parentNode.querySelector('input[type=number]'));"></span>
                    </div>
                  </div>
                <div class="formelement">
                  <label for="cnt">Максимальный вес</label>
                    <div class="input-group">
                      <input type="number" min="0" max="100" id="cnt" class="form-control" name="exercise[<?= $cnt ?>][<?= $cntex ?>][maxweight]" value="<?= $ex->maxweight ?>" >
                      <span class="input-group-addon numbutton">%</span>
                    </div>
                  </div>
              </div>
            </div>
            <? } ?>



          </div>
            <div class="row drophear">
              
              <div class="col-lg-12 add droparea">
                Перетащите сюда упражнения из списка, что бы добавить их на этот день
              </div>
            </div>
          </div>
          <? } ?>    
            <? } else { ?>
          <div class="day" id="1">
            <div class="header active"><H3>День 1 </H3><a href="#" onclick="removeday('#1')"><span class="glyphicon glyphicon-remove" ></span></a> </div>
            <div class="exersicesblock">
          </div>
            <div class="row drophear">
              
              <div class="col-lg-12 add droparea">
                Перетащите сюда упражнения из списка, что бы добавить их на этот день
              </div>
            </div>
          </div>
          <? } ?>
        </div>
        
      </div>
      <div class="row button">
      <input value="Сохранить" type="submit" onclick="checkvalidity()">
      </form>
    </div>
    </div>

    </div>
    <div class="col-lg-6 right">
    <div class="block">
      <div class="row box cap">
        <span>Список упражнений</span>
      </div>
      <div id="rightbox" class="box1 ps ps--theme_default">
        
        <div class="panel-group" >
          <div class="form" id="accordion">
  <!-- 1 панель -->
  
<? $id = 0; foreach($musculgroups as $musculgroup) {  ?>

  <div class="panel panel-default">
    <!-- Заголовок 1 панели -->
    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $id ?>">
    <div class="panel-heading pantitle">
      <h4 class="panel-title">
        <?= $musculgroup->name ?>
      </h4>
    </div>
    </a>
    <div id="collapse<?= $id ?>" class="panel-collapse collapse" aria-expanded = "false">
      <? $j = 1; foreach($musculgroup->exercises as $excersice) { ?>
        <div class="item <?= $j==count($musculgroup->exercises)?"last":""?> title="">
        <div class="wraper drugel" id="<?= $excersice->id ?>" onclick="toggle('#menu-<?= $excersice->id ?>');">
        <div class="number"><?= $j ?></div>
          <div class="itemtitle"><a href="#" ><?= $excersice->name; ?></a> 
          <?php if ($excersice->level == 1) 
              echo '<span class="beginlevel">Начальный уровень</span>';
            else
              if ($excersice->level == 2) 
                echo '<span class="mediumlevel">Средний уровень</span>';
              else
              if ($excersice->level == 3) 
                echo '<span class="prolevel">Продвинутый уровень</span>';
              ?>
          </div>
        </div>
          <div id="menu-<?= $excersice->id ?>" class="excersicedata">
                <div class="exerciseimgwrap">
                <img class="exerciseimg" src="/img/excersices/<?if ($excersice->img != null) echo($excersice->img); else echo('no_image_available.jpg'); ?>">  
                <div class="exercisedarc"></div>
                <div class="zoom">
                  <i class="glyphicon glyphicon-zoom-in"></i>
                </div>
                </div>
                <?= $excersice->description ?>
                <div style="clear: both"></div>
              </div>
</div>
      <? $j++; } ?>
      <!--</ul>-->
      <hr>
    </div>
  </div>
   <? $id = $id + 1;} ?>
</div>
</div>
      </div>
    </div>

<!-- Добавьте модальное окно после открывающего тега body-->
<div class="modal fade" id="image-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <div class="modal-title">Просмотр изображения</div>
      </div>
      <div class="modal-body">
        <img class="img-responsive center-block" src="" alt="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>

  <script type="text/javascript" src="/perfectscrollbar/js/perfect-scrollbar.jquery.min.js"></script>
  <script type="text/javascript" src="/js/jsrender.min.js"></script>
  <script src="/js/input.js"></script>
  <script src="/js/trainingprogram.js"></script>
  <script type="text/javascript" src="/js/select.js"></script>
  
<!--Шаблон тренировочного дня-->
  <script id="daytmp" type="text/x-jsrender">
    <div class="day" id="{{:dayid}}">
            <div class="header active"><H3>{{:dayname}} </H3><a href="#" onclick="return removeday('#{{:dayid}}')"><span class="glyphicon glyphicon-remove" ></span></a> </div>
            <div class="exersicesblock">

            </div>

            <div class="row drophear">
              <div class="col-lg-12 add droparea ui-dropable">
                Перетащите сюда упражнения из списка, что бы добавить их на этот день
              </div>
            </div>
          </div>
  </script>

<!--Шаблон упражнения в дне тренировки-->
<script id="excersicetmp" type="text/x-jsrender">
  <div class="row excersice" data="{{:exid}}">
              <div class="header">
              <h4>{{:name}}</h4>
              <a href="#" class="exclose" onclick="return removeex({{:daynum}}, this)"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
              
              <input type="hidden" name="exercise[{{:daynum}}][{{:exnum}}][excersiceid]" value="{{:exid}}"/> 
              <div class="col-lg-6" style="padding-left: 0">
                <div class="formelement">
                  <label for="cnt">Количество подходов</label>
                    <div class="input-group">
                      <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="down(this.parentNode.querySelector('input[type=number]'));"></span>
                      <input day="{{:daynum}}" type="number" min="1" max="20" step=1 id="cnt" value="1" class="form-control" name="exercise[{{:daynum}}][{{:exnum}}][podhod]" onfocus="onFocus(this);" onBlur="onBlur(this);">
                      <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="up(this.parentNode.querySelector('input[type=number]'));"></span>
                    </div>
                  </div>

                <div class="formelement">
                  <label for="cnt">Минимальный вес</label>
                    <div class="input-group">
                      <input day="{{:daynum}}" type="number" min="0" max="100" id="cnt" value="1" class="form-control" name="exercise[{{:daynum}}][{{:exnum}}][minweight]" onfocus="onFocus(this);" onBlur="onBlur(this);">
                      <span class="input-group-addon numbutton">%</span>
                    </div>
                  </div>
              </div>
              <div class="col-lg-6"  style="padding-right: 0">
              <div class="formelement">
                  <label for="cnt">Количество повторений</label>
                    <div class="input-group">
                      <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="down(this.parentNode.querySelector('input[type=number]'))"></span>
                      <input day="{{:daynum}}" type="number" min="1" max="100" step=1 id="cnt" value="1" class="form-control" name="exercise[{{:daynum}}][{{:exnum}}][repeat]" onfocus="onFocus(this);" onBlur="onBlur(this);">
                      <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="up(this.parentNode.querySelector('input[type=number]'));"></span>
                    </div>
                  </div>
                <div class="formelement">
                  <label for="cnt">Максимальный вес</label>
                    <div class="input-group">
                      <input day="{{:daynum}}" type="number" min="0" max="100" id="cnt" value="1" class="form-control" name="exercise[{{:daynum}}][{{:exnum}}][maxweight]" onfocus="onFocus(this);" onBlur="onBlur(this);">
                      <span class="input-group-addon numbutton">%</span>
                    </div>
                  </div>
              </div>
            </div>
</script>