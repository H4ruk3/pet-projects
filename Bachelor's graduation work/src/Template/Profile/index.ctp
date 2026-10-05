<?= $this->Html->css('redesign/routine.css') ?>
<?= $this->Html->css('redesign/profile.css') ?>
<?= $this->Html->css('redesign/avatar.css') ?>
<?= $this->Html->css('redesign/modal.css') ?>
<?= $this->Html->css('redesign/cropper.min.css') ?>

<?= $this->Html->css('redesign/nutritionprogram.css') ?>



<div class="row">
    <div class="col-sm-4 col-md-4 col-lg-4 left">
    <!--Содержимое левого блока-->
    

      <? if ($user->role == "trainer") echo $this->element('profile/trainerinfo', ["user" => $user]); else echo $this->element('profile/info', ["user" => $user]);?>

      <? if (isset($user->trainer)) { ?>
        <div class="row box content" style="padding: 0 40px; margin-top: 20px;">
           <H2>Ваш тренер</H2><hr>
          <? echo $this->element('profile/yourtrainer', ["user" => $user->trainer]);?>           
        <hr>
        </div>
      <? } ?>


      <input type="button" class="btn-11" onclick="window.location = 'auth/logout';" value="Выход из профиля">
      
  </div>

<?php 
/**************************************
Функция подстановки числительных
*****************************************/
function plural_form($number, $after) {
  $cases = array (2, 0, 1, 1, 1, 2);
  echo $after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
} ?>

  <div class="col-sm-8 col-md-8 col-lg-8 ">
  <?= $this->Flash->render() ?>
  <? if (isset($message)) echo $this->element('message') ?>
  <? if (isset($user->corpuser)) { ?>
    <div id="usergroup" class="row box cap group">
      <span>Вы состоите в группе пользователей <b><?= $user->corpuser; ?></b></span>
      <input type="button" value="Выйти из группы" onclick = "unsubscribe()" />
    </div>
  <? } ?>

  <div class="alert alert-danger" role="alert" style="opacity: 0">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <strong>Ошибка!</strong>
    <div id="message"></div>
  </div>

<? if ($user->role == "trainer") { ?>  
  <div class="membersblock">
  <H2>Ваши подопечные</H2>
<div class="row" style="margin:0">
<? echo $this->element('profile/members');?>
</div>
<div class="row" style="margin:0; margin-bottom:20px">
  <a href="/user" class="btn btn-primary profbtn">Все участники</a>
  <a href="/user/invite" class="btn btn-primary profbtn">Пригласить участника</a>
</div>
</div>
<? } ?>

<? if ($user->role == "trainer") echo $this->element('profile/events'); ?>

  <? if ($profile) { ?>

  <div class="row box tabs">
    <a class="clear" href="/routine">
    <div class="col-lg-4 col-xs-4 center active">
      <p><?= $stat->routine?></p>
      <p> <?plural_form(
              $stat->routine,
              array('Распорядок','Распорядка','Распорядков')
          );?> дня </p>
    </div>
    </a>    
    <a class="clear" href="/trainingprogram">
    <div class="col-lg-4 col-xs-4 center">
      <p><?= $stat->trainingprogram?></p>
      <p> <?plural_form(
            $stat->trainingprogram,
            array('Программа','Программы','Программ')
          );?> тренировок </p>
    </div>
    </a>
    <a class="clear" href="/nutritionprogram">
    <div class="col-lg-4 col-xs-4 center">
      <p><?= $stat->eatings ?></p>
      <p> <?plural_form(
            $stat->eatings,
            array('Программа','Программы','Программ')
          );?> питания </p>
    </div>
    </a>
  </div>  
    
  <!--Табы-->
  <ul class="row colortabs">
    <li class="col-lg-4 col-xs-4 center active hvr-underline-from-center" data-toggle="tab" data-target="#panel1">
      <p> Текущий распорядок дня </p>
    </li>    
    <li class="col-lg-4 col-xs-4 center hvr-underline-from-center" data-toggle="tab" data-target="#panel2">
      <p > Текущая программа тренировок </p>
    </li>
    <li class="col-lg-4 col-xs-4 center hvr-underline-from-center" data-toggle="tab" data-target="#panel3">
      <p> Текущая программа питания </p>
    </li>
  </ul>
  
  <div id="content" class="row box ">  
    <div class="data tab-content">
      <div id="panel1" class="tab-pane fade in active">
        <? if ($currentroutine == null) { ?>
          <div class="empty">
            <H4 class="emptymsg">У вас не выбрано ни одного активного распорядка дня</H4>
          </div>
        <? } else { ?>
          <div class="header day blue">
            <h2>Тренировочный день</h2>
          </div>
          <div class="form">
          <? $arr = [];
              function cmp($a, $b)
              {
                  if ($a["time"] == $b["time"]) {
                      return 0;
                  }
                  return ($a["time"] < $b["time"]) ? -1 : 1;
              }
              $arr[0]["time"] = $currentroutine->routineday[0]->wakeupTime;
              $arr[0]["mess"] = "Подъём";
              $arr[1]["time"] = $currentroutine->routineday[0]->trainTime;
              $arr[1]["mess"] = "Тренировка";
              $arr[2]["time"] = $currentroutine->routineday[0]->sleepTime;
              $arr[2]["mess"] = "Сон";
              $i=3;
              foreach ($currentroutine->routineday[0]->eating as $j => $value) {
                $arr[$i]["time"] = $value->time;
                $arr[$i]["mess"] = ($i-2) . " приём пищи";
                $i++;
              }
              usort($arr, "cmp");
              foreach ($arr as $i => $val) {
                echo "<p> <span>" . $val["time"]->format("H:i") . "</span> " . $val["mess"] . "</p>";
              }
          ?>
          </div>
          <div class="header day blue"><h2>День отдыха</h2></div>
          <div class="form">
            <?
              $arr2 = [];
              $arr2[0]["time"] = $currentroutine->routineday[1]->wakeupTime;
              $arr2[0]["mess"] = "Подъём";
              $arr2[1]["time"] = $currentroutine->routineday[1]->sleepTime;
              $arr2[1]["mess"] = "Сон";
              $i=2;
              foreach ($currentroutine->routineday[1]->eating as $j => $value) {
                $arr2[$i]["time"] = $value->time;
                $arr2[$i]["mess"] = ($i-1) . " приём пищи";
                $i++;
              }
              usort($arr2, "cmp");
              foreach ($arr2 as $i => $val) {
                echo "<p> <span>" . $val["time"]->format("H:i") . "</span> " . $val["mess"] . "</p>";
              }
            ?>
          </div>
          <? } ?>
        </div>
      <div id="panel2" class="tab-pane fade">
        <? if ($program == null) { ?>
          <div class="empty">
            <H4>У вас не выбрано ни одной активной программы тренировок</H4>
          </div>
        <? } else {?>
          <? foreach($program->trainingprogramday as $day) { ?> 
          <div class="c1" id="1">
            <div class="dayheader">
              <H4>День <?= $day->number ?></H4>
            </div>
            <? $ii = 1; foreach($day->trainingprogramday_exercise as $ex) { ?>  
            <div class="wp-block property list">
              <div class="wp-block-body">
                <div class="wp-block-content">
                <small>
                  <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Упражнение <?= $ii ?></small>
                <h4 class="content-title"><?= $ex->exercise->name ?></h4>
                <div class="description">
                  <div class="imgcontainer">
                    <img src="/img/excersices/<?if ($ex->exercise->img != null) echo($ex->exercise->img); else echo('no_image_available.jpg'); ?>" alt="">
                  </div>
                <? if (strlen($ex->exercise->description) <> 0) { ?>
                  <div id="menu-<?= $day->number ?>-<?= $ii?>" class="excersicedata" style="height: 100px">
                    <?= $ex->exercise->description ?>
                  </div>
                  <a href="#" id="collapse" onclick="return toggle('#menu-<?= $day->number ?>-<?= $ii?>', this);" >Подробнее</a>
                <? } ?>
                <div style="clear: both"></div>
                </div>
                <span class="pull-left">
                  <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>   
                <? $jj = 0; foreach($ex->exercise->musculgroups as $muscul) { 
                  if ($jj == 0) 
                    echo($muscul->name);
                  else
                    echo(', '.$muscul->name);
                  $jj++;
                  }
                ?>
                </span>
              </div>
              <div class="wp-block-footer">
                <ul class="aux-info">
                  <li title="<?= $ex->podhod?> <?plural_form(
                    $ex->podhod,
                    array('подход','подхода','подходов')
                  );?>"><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   enable-background="new 0 0 100 100"
   height="30"
   id="Layer_1"
   version="1.1"
   viewBox="0 0 100 100"
   width="30"
   xml:space="preserve"
   sodipodi:docname="count2.svg"
   inkscape:version="0.92.3 (2405546, 2018-03-11)"><metadata
     id="metadata19"><rdf:RDF><cc:Work
         rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" /><dc:title></dc:title></cc:Work></rdf:RDF></metadata><defs
     id="defs17" /><sodipodi:namedview
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1"
     objecttolerance="10"
     gridtolerance="10"
     guidetolerance="10"
     inkscape:pageopacity="0"
     inkscape:pageshadow="2"
     inkscape:window-width="1366"
     inkscape:window-height="705"
     id="namedview15"
     showgrid="false"
     inkscape:zoom="3.337544"
     inkscape:cx="39.902904"
     inkscape:cy="44.759261"
     inkscape:window-x="-8"
     inkscape:window-y="-8"
     inkscape:window-maximized="1"
     inkscape:current-layer="Layer_1" /><g
     id="g10"><g
       id="g8"><g
         id="g4"><g
           id="g2" /></g><g
         id="g6" /></g></g><path
     d="m 100,69 h -7.086 c -0.393,-2.083 -2.22,-4 -4.416,-4 h -3 C 85.327,65 85,64.366 85,64.385 V 57.834 C 85,55.353 82.979,53 80.498,53 h -7 C 71.017,53 69,55.353 69,57.834 V 69 H 31 V 57.834 C 31,55.353 28.979,53 26.498,53 h -7 C 17.017,53 15,55.353 15,57.834 v 6.613 C 15,64.428 14.669,65 14.498,65 h -3 C 9.324,65 7.505,66.947 7.089,69 H 0 v 14 h 7.008 c 0.057,2.433 2.044,4 4.49,4 h 3 C 14.669,87 15,87.365 15,87.346 v 7.488 C 15,97.315 17.017,99 19.498,99 h 7 C 28.979,99 31,97.315 31,94.834 V 83 H 69 V 94.834 C 69,97.315 71.017,99 73.498,99 h 7 C 82.979,99 85,97.315 85,94.834 V 87.283 C 85,87.302 85.327,87 85.498,87 h 3 c 2.425,0 4.394,-1.597 4.483,-4 H 100 Z M 4,73 h 3 v 6 H 4 Z m 11,9.5 c 0,0.276 -0.224,0.5 -0.5,0.5 h -3 C 11.224,83 11,82.776 11,82.5 v -13 C 11,69.224 11.224,69 11.5,69 h 3 c 0.276,0 0.5,0.224 0.5,0.5 z M 27,94.834 C 27,95.105 26.769,95 26.498,95 h -7 C 19.227,95 19,95.105 19,94.834 v -11.938 -13 -12.062 C 19,57.563 19.227,57 19.498,57 h 7 C 26.769,57 27,57.563 27,57.834 Z M 31,79 v -6 h 38 v 6 z M 81,94.834 C 81,95.105 80.769,95 80.498,95 h -7 C 73.227,95 73,95.105 73,94.834 v -37 C 73,57.563 73.227,57 73.498,57 h 7 C 80.769,57 81,57.563 81,57.834 v 12 13 z M 89,82.5 c 0,0.276 -0.224,0.5 -0.5,0.5 h -3 C 85.224,83 85,82.776 85,82.5 v -13 C 85,69.224 85.224,69 85.5,69 h 3 c 0.276,0 0.5,0.224 0.5,0.5 z M 96,79 h -3 v -6 h 3 z"
     id="path12"
     inkscape:connector-curvature="0"
     style="fill:#d4d4d4" /><text
     xml:space="preserve"
     style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none"
     x="49.844742"
     y="48.796612"
     id="text23"
     width="45"><tspan
       sodipodi:role="line"
       id="tspan21"
       x="49.844742"
       y="48.796612"
       style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:58.66666794px;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Bold';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-feature-settings:normal;text-align:center;writing-mode:lr-tb;text-anchor:middle"><?= $ex->podhod ?></tspan></text>
<rect
     id="rect27"
     width="38"
     height="5.762712"
     x="31"
     y="73"
     style="fill:#d4d4d4;stroke-width:0.75912529" /><rect
     style="fill:#d4d4d4;stroke-width:0.99090517"
     id="rect29"
     width="8.1238441"
     height="37.9795"
     x="18.876156"
     y="56.8545" /><rect
     style="fill:#d4d4d4;stroke-width:0.99090517"
     id="rect29-8"
     width="8.1238441"
     height="37.9795"
     x="72.87616"
     y="56.8545" /><rect
     style="fill:#d4d4d4;stroke-width:1.07399845"
     id="rect46"
     width="4.0572968"
     height="15.008934"
     x="84.942703"
     y="68.989174" /><rect
     style="fill:#d4d4d4;stroke-width:1.07760835"
     id="rect46-1"
     width="4.0572968"
     height="15.11"
     x="10.942703"
     y="68.389999" /><rect
     style="fill:#d4d4d4"
     id="rect63"
     width="3"
     height="6"
     x="93"
     y="73" /><rect
     style="fill:#d4d4d4"
     id="rect63-2"
     width="3"
     height="6"
     x="4"
     y="73" /></svg></li>
                  <li  title="<?= $ex->repeats?> <?plural_form(
  $ex->repeats,
  array('повторение','повторения','повторений')
);?>">
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   version="1.1"
   id="Capa_1"
   x="0px"
   y="0px"
   viewBox="0 0 669.688 489.688"
   xml:space="preserve"
   width="40"
   height="40"
   sodipodi:docname="repeat2.svg"
   inkscape:version="0.92.3 (2405546, 2018-03-11)"><metadata
   id="metadata45"><rdf:RDF><cc:Work
       rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type
         rdf:resource="http://purl.org/dc/dcmitype/StillImage" /><dc:title></dc:title></cc:Work></rdf:RDF></metadata><defs
   id="defs43" /><sodipodi:namedview
   pagecolor="#ffffff"
   bordercolor="#666666"
   borderopacity="1"
   objecttolerance="10"
   gridtolerance="10"
   guidetolerance="10"
   inkscape:pageopacity="0"
   inkscape:pageshadow="2"
   inkscape:window-width="1366"
   inkscape:window-height="705"
   id="namedview41"
   showgrid="false"
   inkscape:zoom="0.4609375"
   inkscape:cx="79.186441"
   inkscape:cy="171.56339"
   inkscape:window-x="-8"
   inkscape:window-y="-8"
   inkscape:window-maximized="1"
   inkscape:current-layer="Capa_1" />
<g
   id="g8"
   transform="matrix(1.3776544,0,0,1.1736487,0,-33.771462)">
  <g
   id="g6">
    <path
   d="m 485.65,84.294 -111.9,-75.3 c -6.1,-4.1 -14.3,0.3 -14.3,7.6 v 40.7 H 40.65 c -19.1,0 -34.6,15.5 -34.6,34.6 v 183.9 h 69.2 v -149.2 h 284.1 v 40.8 c 0,7.4 8.2,11.7 14.3,7.6 l 111.9,-75.4 c 5.5,-3.6 5.5,-11.6 0.1,-15.3 z"
   id="path2"
   style="fill:#d4d4d4"
   inkscape:connector-curvature="0" />
    <path
   d="m 414.45,362.994 h -284.1 v -40.7 c 0,-7.4 -8.2,-11.7 -14.3,-7.6 l -112,75.3 c -5.4,3.6 -5.4,11.6 0,15.3 l 111.9,75.4 c 6.1,4.1 14.3,-0.3 14.3,-7.6 v -40.8 h 318.7 c 19.1,0 34.6,-15.5 34.6,-34.6 v -183.9 h -69.2 v 149.2 z"
   id="path4"
   inkscape:connector-curvature="0"
   style="fill:#d4d4d4" />
  </g>
</g>
<g
   id="g10">
</g>
<g
   id="g12">
</g>
<g
   id="g14">
</g>
<g
   id="g16">
</g>
<g
   id="g18">
</g>
<g
   id="g20">
</g>
<g
   id="g22">
</g>
<g
   id="g24">
</g>
<g
   id="g26">
</g>
<g
   id="g28">
</g>
<g
   id="g30">
</g>
<g
   id="g32">
</g>
<g
   id="g34">
</g>
<g
   id="g36">
</g>
<g
   id="g38">
</g>
<text
   xml:space="preserve"
   style="font-style:normal;font-weight:normal;font-size:221.24539185px;line-height:1.25;font-family:sans-serif;text-align:center;letter-spacing:-0.11517344px;word-spacing:0px;text-anchor:middle;fill:#000000;fill-opacity:1;stroke:none;stroke-width:0.90181547"
   x="358.12866"
   y="344.99747"
   id="text3752"
   width="210"
   transform="scale(0.94290555,1.0605516)"><tspan
     sodipodi:role="line"
     id="tspan3750"
     x="358.12866"
     y="344.99747"
     style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:272.62319946px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Bold';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-feature-settings:normal;text-align:center;letter-spacing:0px;writing-mode:lr-tb;text-anchor:middle;stroke-width:0.90181547"><?= $ex->repeats?></tspan></text>
</svg> </li>
                  <li title="Минимальный вес <?= $ex->minweight?> кг" ><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   version="1.1"
   id="Capa_1"
   x="0px"
   y="0px"
   width="30"
   height="30"
   viewBox="0 0 210.676 210.676"
   xml:space="preserve"
   sodipodi:docname="weights2.svg"
   inkscape:version="0.92.3 (2405546, 2018-03-11)"><metadata
   id="metadata41"><rdf:RDF><cc:Work
       rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type
         rdf:resource="http://purl.org/dc/dcmitype/StillImage" /><dc:title></dc:title></cc:Work></rdf:RDF></metadata><defs
   id="defs39" /><sodipodi:namedview
   pagecolor="#ffffff"
   bordercolor="#666666"
   borderopacity="1"
   objecttolerance="10"
   gridtolerance="10"
   guidetolerance="10"
   inkscape:pageopacity="0"
   inkscape:pageshadow="2"
   inkscape:window-width="1366"
   inkscape:window-height="705"
   id="namedview37"
   showgrid="false"
   inkscape:zoom="0.4609375"
   inkscape:cx="772.61451"
   inkscape:cy="82.440678"
   inkscape:window-x="-8"
   inkscape:window-y="-8"
   inkscape:window-maximized="1"
   inkscape:current-layer="g4" />
<g
   id="g4">
  <path
   d="m 176.97445,84.163386 c 3.97238,-7.614801 6.04331,-16.629051 6.04331,-27.12492 C 183.01776,25.584242 148.46163,0 105.9646,0 63.462451,0 28.911463,25.584242 28.911463,57.038466 c 0,11.222555 2.216009,20.173894 5.858428,27.417648 -7.49283,12.473072 -11.86322,27.018356 -11.86322,42.630556 0,45.87497 37.185547,83.05667 83.056649,83.05667 45.87497,0 83.05666,-37.1817 83.05666,-83.05667 0.001,-15.73032 -4.45384,-30.380882 -12.04553,-42.923284 z m -75.5253,74.710044 H 92.947175 L 74.141917,131.48788 67.840544,137.969 v 20.92112 H 61.340167 54.832086 V 96.624903 h 6.484971 6.524771 V 122.4043 L 91.690239,96.624903 h 8.30297 8.295251 l -24.85112,25.410917 26.51376,36.8543 h -8.50195 z m 56.44143,-2.46252 c 0,7.30281 -1.79746,12.66437 -5.41035,16.12063 -3.6116,3.44855 -9.20169,5.19979 -16.84731,5.19979 -6.25387,0 -11.24181,-1.22741 -14.94072,-3.66682 -3.69763,-2.44582 -5.67612,-5.82376 -5.92519,-10.15435 h 13.1882 c 0.14765,1.57149 0.85122,2.73599 2.18006,3.46267 1.32883,0.71128 3.39463,1.06307 6.19224,1.06307 3.42416,0 5.87897,-0.82169 7.37214,-2.47921 1.50858,-1.64082 2.25967,-4.3614 2.25967,-8.18485 v -5.67611 l -0.99246,1.47777 c -2.66537,3.70533 -6.52862,5.55029 -11.56279,5.55029 -5.97269,0 -10.82069,-2.15823 -14.49392,-6.45801 -3.70662,-4.3062 -5.54259,-9.99001 -5.54259,-17.04118 0,-7.2322 1.80516,-13.04056 5.40136,-17.46616 3.58079,-4.41019 8.28756,-6.59923 14.13572,-6.59923 5.17539,0 9.19271,1.86935 12.03911,5.64401 0.51613,0.68817 1.03226,1.47135 1.50088,2.31487 v -6.73148 h 11.42926 v 43.6243 h 0.0167 z m 7.74063,-87.027762 c -15.10507,-15.612203 -36.22905,-25.35314 -59.66661,-25.35314 -23.473493,0 -44.61673,9.764047 -59.72051,25.408348 -0.848656,-3.729725 -1.321131,-7.829212 -1.321131,-12.39989 0,-22.624856 27.385551,-41.028254 61.041641,-41.028254 33.65611,0 61.04166,18.403398 61.04166,41.028254 0,4.066106 -0.35949,8.26317 -1.37505,12.344682 z m -22.21915,56.264272 c 1.81415,2.39191 2.70389,5.69923 2.70389,9.9438 0,4.20605 -0.88974,7.52107 -2.70389,9.94507 -1.79104,2.3919 -4.28437,3.5885 -7.48127,3.5885 -3.11988,0 -5.5901,-1.18119 -7.35674,-3.52559 -1.76665,-2.36109 -2.66537,-5.64401 -2.66537,-9.85135 0,-4.29207 0.89872,-7.64561 2.66537,-10.06191 1.76793,-2.41501 4.25227,-3.62701 7.39654,-3.62701 3.18792,0.009 5.65043,1.19659 7.44147,3.58849 z"
   id="path2"
   style="fill:#d4d4d4;fill-opacity:1;stroke-width:1.28389835"
   inkscape:connector-curvature="0" />
<rect
   id="rect43"
   width="123.45053"
   height="109.68021"
   x="43.634579"
   y="70.047775"
   style="fill:#d4d4d4;fill-opacity:1;stroke-width:0.44770968" /><text
   xml:space="preserve"
   style="font-style:normal;font-weight:normal;font-size:13.04901505px;line-height:1.25;font-family:sans-serif;text-align:center;letter-spacing:0px;word-spacing:0px;text-anchor:middle;fill:#000000;fill-opacity:1;stroke:none;stroke-width:0.32622537"
   x="107.57575"
   y="136.39679"
   id="text3750"
   transform="scale(0.97993505,1.0204758)"
   width="400"><tspan
     sodipodi:role="line"
     id="tspan3748"
     x="107.57575"
     y="136.39679"
     style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:67.85488129px;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Bold';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-feature-settings:normal;text-align:center;writing-mode:lr-tb;text-anchor:middle;stroke-width:0.32622537"><?= $ex->minweight ?></tspan></text>
<text
   xml:space="preserve"
   style="font-style:normal;font-weight:normal;font-size:12.78718758px;line-height:1.25;font-family:sans-serif;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;stroke-width:0.31967968"
   x="71.701378"
   y="188.05835"
   id="text3754"><tspan
     sodipodi:role="line"
     id="tspan3752"
     x="71.701378"
     y="188.05835"
     style="font-size:61.37850189px;stroke-width:0.31967968">кг</tspan></text>
</g>
<g
   id="g6">
</g>
<g
   id="g8">
</g>
<g
   id="g10">
</g>
<g
   id="g12">
</g>
<g
   id="g14">
</g>
<g
   id="g16">
</g>
<g
   id="g18">
</g>
<g
   id="g20">
</g>
<g
   id="g22">
</g>
<g
   id="g24">
</g>
<g
   id="g26">
</g>
<g
   id="g28">
</g>
<g
   id="g30">
</g>
<g
   id="g32">
</g>
<g
   id="g34">
</g>
</svg> </li>

<li  title="Максимальный вес <?= $ex->maxweight?> кг"><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   version="1.1"
   id="Capa_1"
   x="0px"
   y="0px"
   width="40"
   height="40"
   viewBox="0 0 210.676 210.676"
   xml:space="preserve"
   sodipodi:docname="weights2.svg"
   inkscape:version="0.92.3 (2405546, 2018-03-11)"><metadata
   id="metadata41"><rdf:RDF><cc:Work
       rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type
         rdf:resource="http://purl.org/dc/dcmitype/StillImage" /><dc:title></dc:title></cc:Work></rdf:RDF></metadata><defs
   id="defs39" /><sodipodi:namedview
   pagecolor="#ffffff"
   bordercolor="#666666"
   borderopacity="1"
   objecttolerance="10"
   gridtolerance="10"
   guidetolerance="10"
   inkscape:pageopacity="0"
   inkscape:pageshadow="2"
   inkscape:window-width="1366"
   inkscape:window-height="705"
   id="namedview37"
   showgrid="false"
   inkscape:zoom="0.4609375"
   inkscape:cx="772.61451"
   inkscape:cy="82.440678"
   inkscape:window-x="-8"
   inkscape:window-y="-8"
   inkscape:window-maximized="1"
   inkscape:current-layer="g4" />
<g
   id="g4">
  <path
   d="m 176.97445,84.163386 c 3.97238,-7.614801 6.04331,-16.629051 6.04331,-27.12492 C 183.01776,25.584242 148.46163,0 105.9646,0 63.462451,0 28.911463,25.584242 28.911463,57.038466 c 0,11.222555 2.216009,20.173894 5.858428,27.417648 -7.49283,12.473072 -11.86322,27.018356 -11.86322,42.630556 0,45.87497 37.185547,83.05667 83.056649,83.05667 45.87497,0 83.05666,-37.1817 83.05666,-83.05667 0.001,-15.73032 -4.45384,-30.380882 -12.04553,-42.923284 z m -75.5253,74.710044 H 92.947175 L 74.141917,131.48788 67.840544,137.969 v 20.92112 H 61.340167 54.832086 V 96.624903 h 6.484971 6.524771 V 122.4043 L 91.690239,96.624903 h 8.30297 8.295251 l -24.85112,25.410917 26.51376,36.8543 h -8.50195 z m 56.44143,-2.46252 c 0,7.30281 -1.79746,12.66437 -5.41035,16.12063 -3.6116,3.44855 -9.20169,5.19979 -16.84731,5.19979 -6.25387,0 -11.24181,-1.22741 -14.94072,-3.66682 -3.69763,-2.44582 -5.67612,-5.82376 -5.92519,-10.15435 h 13.1882 c 0.14765,1.57149 0.85122,2.73599 2.18006,3.46267 1.32883,0.71128 3.39463,1.06307 6.19224,1.06307 3.42416,0 5.87897,-0.82169 7.37214,-2.47921 1.50858,-1.64082 2.25967,-4.3614 2.25967,-8.18485 v -5.67611 l -0.99246,1.47777 c -2.66537,3.70533 -6.52862,5.55029 -11.56279,5.55029 -5.97269,0 -10.82069,-2.15823 -14.49392,-6.45801 -3.70662,-4.3062 -5.54259,-9.99001 -5.54259,-17.04118 0,-7.2322 1.80516,-13.04056 5.40136,-17.46616 3.58079,-4.41019 8.28756,-6.59923 14.13572,-6.59923 5.17539,0 9.19271,1.86935 12.03911,5.64401 0.51613,0.68817 1.03226,1.47135 1.50088,2.31487 v -6.73148 h 11.42926 v 43.6243 h 0.0167 z m 7.74063,-87.027762 c -15.10507,-15.612203 -36.22905,-25.35314 -59.66661,-25.35314 -23.473493,0 -44.61673,9.764047 -59.72051,25.408348 -0.848656,-3.729725 -1.321131,-7.829212 -1.321131,-12.39989 0,-22.624856 27.385551,-41.028254 61.041641,-41.028254 33.65611,0 61.04166,18.403398 61.04166,41.028254 0,4.066106 -0.35949,8.26317 -1.37505,12.344682 z m -22.21915,56.264272 c 1.81415,2.39191 2.70389,5.69923 2.70389,9.9438 0,4.20605 -0.88974,7.52107 -2.70389,9.94507 -1.79104,2.3919 -4.28437,3.5885 -7.48127,3.5885 -3.11988,0 -5.5901,-1.18119 -7.35674,-3.52559 -1.76665,-2.36109 -2.66537,-5.64401 -2.66537,-9.85135 0,-4.29207 0.89872,-7.64561 2.66537,-10.06191 1.76793,-2.41501 4.25227,-3.62701 7.39654,-3.62701 3.18792,0.009 5.65043,1.19659 7.44147,3.58849 z"
   id="path2"
   style="fill:#d4d4d4;fill-opacity:1;stroke-width:1.28389835"
   inkscape:connector-curvature="0" />
<rect
   id="rect43"
   width="123.45053"
   height="109.68021"
   x="43.634579"
   y="70.047775"
   style="fill:#d4d4d4;fill-opacity:1;stroke-width:0.44770968" /><text
   xml:space="preserve"
   style="font-style:normal;font-weight:normal;font-size:13.04901505px;line-height:1.25;font-family:sans-serif;text-align:center;letter-spacing:0px;word-spacing:0px;text-anchor:middle;fill:#000000;fill-opacity:1;stroke:none;stroke-width:0.32622537"
   x="107.57575"
   y="136.39679"
   id="text3750"
   transform="scale(0.97993505,1.0204758)"
   width="400"><tspan
     sodipodi:role="line"
     id="tspan3748"
     x="107.57575"
     y="136.39679"
     style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:67.85488129px;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Bold';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-feature-settings:normal;text-align:center;writing-mode:lr-tb;text-anchor:middle;stroke-width:0.32622537"><?= $ex->maxweight ?></tspan></text>
<text
   xml:space="preserve"
   style="font-style:normal;font-weight:normal;font-size:12.78718758px;line-height:1.25;font-family:sans-serif;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;stroke-width:0.31967968"
   x="71.701378"
   y="188.05835"
   id="text3754"><tspan
     sodipodi:role="line"
     id="tspan3752"
     x="71.701378"
     y="188.05835"
     style="font-size:61.37850189px;stroke-width:0.31967968">кг</tspan></text>
</g>
<g
   id="g6">
</g>
<g
   id="g8">
</g>
<g
   id="g10">
</g>
<g
   id="g12">
</g>
<g
   id="g14">
</g>
<g
   id="g16">
</g>
<g
   id="g18">
</g>
<g
   id="g20">
</g>
<g
   id="g22">
</g>
<g
   id="g24">
</g>
<g
   id="g26">
</g>
<g
   id="g28">
</g>
<g
   id="g30">
</g>
<g
   id="g32">
</g>
<g
   id="g34">
</g>
</svg></li>

                </ul>
              </div>
            </div>
          </div>
      <? $ii++; } ?>
        </div>
        <? }?>
      <? } ?>
          <!--<h3>Панель 2</h3>
          <p>Содержимое 2 панели...</p>-->
        </div>
        <div id="panel3" class="tab-pane fade">
          <? if ($currenteating == null) { ?>
            <div class="empty">
              <H4 class="emptymsg">У вас не выбрано ни одной активной программы питания</H4>
            </div>
          <? } else {?>

          <? $i = 0; foreach($currenteating->days as $day) { $i++;?>
            <div class="day" id="{{:id}}">
              <div class="header active day blue"><H3 id="dayname">День <?= $i ?> </H3>
              </div>
              <div class="exersicesblock">
              <? foreach ($day as $eating) {?>
                <div class="eating">
                    <?= $eating->number+1 ?> приём пищи
                  </div>
                  <table class="nutritiontable">
                  <thead>
                    <tr>
                      <th className="table-header nutrition-caption-name">Продукт</th>
                      <th className="table-header nutrition-caption-weight">Граммы</th>
                      <th className="table-header nutrition-caption-pfc">Б</th>
                      <th className="table-header nutrition-caption-pfc">Ж</th>
                      <th className="table-header nutrition-caption-pfc">У</th>
                      <th className="table-header nutrition-caption-calories">Ккал</th>
                    </tr>
                  </thead>
                  <tbody>
                  <? foreach ($eating->foods as $food) { ?>
                    <tr>
                      <td style="width:25%"><?= $food->food->name ?></td>
                      <td style="width:25%">
                        <?= $food->cnt ?>
                      </td>
                      <td id="proteins" style="width:10%"><?= $food->food->proteins ?></td>
                      <td id="fats" style="width:10%"><?= $food->food->fats ?></td>
                      <td id="hidrocarbonats" style="width:10%"><?= $food->food->hidrocarbonats ?></td>
                      <td id="colories" style="width:15%"><?= $food->food->colories ?></td>
                    </tr>
                  <? } ?>
                  </tbody>
                </table>
            <? } ?>
          </div>
        </div>
      <? } ?>


          <? } ?> 
        </div>
      </div>
    </div>
  </div> <? } else { ?>
    <div class="noprofile">
      <img src="/img/empty-profile.svg">
      <p>В данном режиме вы можете ознакомиться с типовыми тренировочными программами и программами питания. Для создания собственных персонифицированных программ трерировок и программ питания заполните анкету пользователя.</p>
      <a href="/profile/create" class="btn btn-11"> Заполнить анкету пользователя</a>
    </div>
  <? } ?>
</div>

<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Выберите новый аватар.</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
        <div class="formelement">
            <label for="avatar">Аватар</label>
            
            <div class="input-group img" id="datetimepicker1">
              <input type="text" class="form-control" name="file_info" id="file_info" onclick="openFileOption();">
                <span class="input-group-addon file_upload" onclick="openFileOption();">
                <span class="glyphicon glyphicon-upload"></span>
              
                <input type="file" id="file_upload" name="avatar" onchange="readURL(this);">
              </span>
            </div>


            <!--<input type="file" name="avatar" id="image" />-->
            <div class="image_container">
              <img id="blah" src="/img/excersices/<?if ($user->avatar != null) echo($user->avatar); else echo('no_image_available.jpg'); ?>" alt="" />
            </div>
          </div>
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <input type="button" class="" data-dismiss="modal" value="Отмена">
        <input type="button" class="" onclick="updateavatar(<?=$user->id ?>);" value="Создать">
      </div>
    </div>
  </div>
</div>

<script src="/js/moment-with-locales.min.js"></script>
<script src="/js/cropper.min.js"></script>
<script type="text/javascript" src="/js/jquery.dotdotdot.min.js"></script>
<script src="/js/profile.js"></script>