<style>
  .middle {
    margin: auto;
  }
  .nutrition-table {
    width: 700px;
  }
</style>
<link rel="stylesheet" href="/css/newmain.css">
<link rel="stylesheet" href="/bootstrap/css/bootstrap-select.css">

<div class="container clearfix site-body">        
  <div id="breadrumb" style="margin: 25px; margin-left: 40px;">
    <div id="nutrition-content">
    <div class="middle">
      <h1><?= $eatings->name; ?></h1>
        <h5>Распорядок дня</h5>
        <h4><?= $eatings->routine->name; ?></h4>
        <? $days = []; 
        foreach($eatings->routineeatingmenu as $menu) {
          //echo ($days[$menu->day_number]); 
          //$days[$menu->day_number][$menu->eating_id] = 2;
          if ((array_key_exists($menu->day_number, $days)) && (array_key_exists($menu->eating_id, $days[$menu->day_number])))
            $days[$menu->day_number][$menu->eating_id][count($days[$menu->day_number][$menu->eating_id])] = $menu;
          else 
            $days[$menu->day_number][$menu->eating_id][0] = $menu;
        } ?>
        <? foreach($days as $key => $day) { ?>
        <div class="panel-heading" style="width: 725px;">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href=".collapse1" onClick="toggle()">День <?= $key+1;?> <span></span></a> 
            </h4>
            <div id="content" class = "collapse1 panel-collapse collapse in" aria-expanded = "true">

            <? $numbers = ["Первый", "Второй", "Третий", "Четвёртый", "Пятый", "Шестой", "Седьмой", "Восьмой", "Девятый", "Десятый"]; $i = 0; foreach($day as $key => $eating) {  ?>
            <article class="training-day" style="width: 725px">
                <h3><?= $numbers[$i]; ?> прием пищи</h3> <span> <? /*$menu->*/?>

        <table class="nutrition-table">
          <tbody>
            <tr>
              <th class="table-header nutrition-caption-name">Продукт</th>
              <th class="table-header nutrition-caption-weight">Граммы</th>
              <th class="table-header nutrition-caption-pfc">Б</th>
              <th class="table-header nutrition-caption-pfc">Ж</th>
              <th class="table-header nutrition-caption-pfc">У</th>
              <th class="table-header nutrition-caption-calories">Ккал</th>
              
            </tr>
            <? $total["hidrocarbonats"] =  0; $total["fats"] =  0; $total["proteins"] =  0; $total["colories"] =  0; foreach($eating as $food)  {?>
            <tr>
              <td><?= $food->food->name ?></td>
              <td><?= $food->cnt ?></td>
              <td><?= $food->food->proteins  * ($food->cnt / 100)?></td>
              <td><?= $food->food->fats  * ($food->cnt / 100)?></td>
              <td><?= $food->food->hidrocarbonats * ($food->cnt / 100) ?></td>
              <td><?= $food->food->colories  * ($food->cnt / 100)?></td>
            </tr>
            <? $total["hidrocarbonats"] +=  $food->food->hidrocarbonats  * ($food->cnt / 100); $total["fats"] +=  $food->food->fats  * ($food->cnt / 100); $total["proteins"] +=  $food->food->proteins * ($food->cnt / 100); $total["colories"] +=  $food->food->colories * ($food->cnt / 100);} ?>
            <tr>
                <td colspan="2" class="table-header">Норма</td>
                <td>35</td>
                <td>20</td>
                <td>70</td>
                <td>500</td>
                
            </tr>
            <tr>
                <td colspan="2" class="table-header">Фактическое значение</td>
                <td><?= $total["hidrocarbonats"] ?></td>
                <td><?= $total["fats"] ?></td>
                <td><?= $total["proteins"] ?></td>
                <td><?= $total["colories"] ?></td>
                
            </tr>
          </tbody>
        </table>
        </article>
       <? $i++; } ?> 
    </div>
    </div>
    <? } ?>
  </div>
</div>