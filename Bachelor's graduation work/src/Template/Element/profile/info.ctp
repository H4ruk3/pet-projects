<?  $dict = [
    "aimTrain" => ['Похудение', 'Набор мышечной массы', 'Поддержание текущего веса'],
    "somatotype" => ['Эктоморф', 'Мезоморф', 'Эндоморф'],
    "activity" => [
                "1.2" => "минимум или отсутствие физической нагрузки",
                "1.375" => "занятия фитнесом 3 раза в неделю",
                "1.4625" => " занятия фитнесом 5 раз в неделю",
                "1.55" => " интенсивная физическая нагрузка 5 раз в неделю",
                "1.6375" => " занятия фитнесом каждый день",
                "1.725" => "каждый день интенсивно или по два раза в день",
                "1.9" => "ежедневная физическая нагрузка плюс физическая работа"
    ]
  ];

  ?>
<div class="row box content">
      <div class="avatar"><img src="/img/excersices/<?$user = (object)$user; echo(property_exists($user, "avatar")); if (property_exists($user, "avatar") && $user->avatar != null) echo($user->avatar); else echo('no_image_available.jpg'); ?>"" id="avatar" />
      <div class="wall"><div data-target="#myModalBox" data-toggle="modal"><img src="/icon/upload.svg">Изменить</div></div>
      </div>
      <hr>
      <H2><?= $user->username ?></H2>
        <hr>
          <p> <strong>Фамилия:</strong> <?= isset($user->fam)?$user->fam:" - " ?></p>
          <p> <strong>Имя:</strong> <?= isset($user->name)?$user->name:" - " ?></p>
          <p> <strong>Пол:</strong> <? if (isset($user->sex))  echo $user->sex=="male"? "мужской":"женский"; else echo (" - ") ?></p>
          <p> <strong>Возраст:</strong> <?= isset($user->age)?$user->age:" - " ?></p>
          <p> <strong>Рост:</strong> <?= isset($user->growth)?$user->growth:" - " ?></p>
          <p> <strong>Вес:</strong> <?= isset($user->weight)?$user->weight:" - " ?></p>
          <p> <strong>Телосложение:</strong> <?= isset($user->somatotype) && $user->somatotype>0?$dict["somatotype"][$user->somatotype-1]:" - "?> </p>
          <p> <strong>Цель тренировки: </strong><?= isset($user->aimTrain) && $user->aimTrain>0?$dict["aimTrain"][$user->aimTrain-1]:" - " ?></p>
          <p> <strong>Коэффициент активности: </strong><?= isset($user->activity) && $user->activity>0?$dict["activity"][strval($user->activity)]:" - " ?></p>
          <a href="profile/edit/<?= $user->id ?>">Изменить</a>
      </div>