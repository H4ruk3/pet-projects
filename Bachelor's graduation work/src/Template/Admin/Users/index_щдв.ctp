<div class = "content">
<H4>Список пользователей</H4>
<table class = "table table-bordered table-stripled">
    <thead>
      <th>Название</th>
      <th>Роль</th>
      <th>Операции</th>         
    </thead>

    <? foreach($users as $user) { ?>   

      <tr>
        <td><?= $user->username ?></td>
        <td><?= $user->role ?></td>

        <td>
          <a href = "users/delete/<?= $user->id ?>" onclick = "return confirm('Вы действительно хотите удалить элемент?')">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
          </a>
        </td>
      </tr>

    <? } ?>

  </table>

</div>