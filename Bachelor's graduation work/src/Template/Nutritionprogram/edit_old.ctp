<link rel="stylesheet" href="/css/newmain.css">
<style>
.col-lg-6+.fixblock {
	overflow-y: visible;
}
.fixblock {
	overflow-y: visible;	
}
.nutrition-part {
	margin-bottom: 10px;
	width:100%;
}
</style>
<section id="content">
<div class = "col-lg-6">
 <?php echo $this->Form->create(); ?>
     <div class="fixblock left">
      <h1><?= $eatings->name?></h1>
      <div class="header-hint">Программа питания</div>

      <div class="editor">
         <div class="editor-column">
        
            <? $cnt = 0; foreach($eatings->eating as $eat) { $cnt++;?>  

            <article class="training-day" id=<?= $eat->id;?>>
               <h3>Первый прием пищи</h3>
               <table class="nutrition-part" id="<?= $cnt; ?>">
            <tr>
                <th class="table-header nutrition-caption-name">Продукт</th>
                <th class="table-header nutrition-caption-weight">Граммы</th>
                <th class="table-header nutrition-caption-pfc">Б</th>
                <th class="table-header nutrition-caption-pfc">Ж</th>
                <th class="table-header nutrition-caption-pfc">У</th>
                <th class="table-header nutrition-caption-calories">Ккал</th>
                <th class="button-contains nutrition-caption-delete"></th>
            </tr>

            <? $cnt = 0; foreach($eat->foods as $food) { $cnt++;?>  
				<tr>
                <td><?= $food->name; ?></td>
                <td class="input-edit-contains"><input type="number" value="<?= $food->_joinData->cnt;?>" class="input-edit" name="food[<?= $eat->id;?>][<?= $food->id;?>][cnt]" onclick="changecnt(this);"></td>
                <td><?= $food->proteins; ?></td>
                <td><?= $food->fats ; ?></td>
                <td><?= $food->hidrocarbonats ; ?></td>
                <td><?= $food->colories ; ?></td>
                <td class="button-contains">
                    <button><i class="fa fa-times color-blue" aria-hidden="true"></i></button>
                </td>
            </tr>	

			<? } ?>
 <!--           <tr>
                <td>Овсяная каша</td>
                <td class="input-edit-contains">
                <!--<? echo $this->Form->input('cntfood', array(
      'type' => 'number',
      'id' => 'cntfood',
      'onchange' => 'changeCnt()',
      'min' => 100,
      'value' => 100,
      'class' => 'form-control'
    )); ?>-->
<!--    <? echo $this->Form->input('foodid', array(
      'type' => 'hidden',
      'id' => 'foodid',
      'value' => 1,
      'class' => 'form-control'
    )); ?>
                <input type="number" value="100" class="input-edit" name="[<?= $eat->id?>][1]">
                </td>
                <td>3.2</td>
                <td>4.1</td>
                <td>14.2</td>
                <td>102</td>
                <td class="button-contains">
                    <button><i class="fa fa-times color-blue" aria-hidden="true"></i></button>
                </td>
            </tr>
            <tr>
                <td>Манная каша</td>
                <td class="input-edit-contains"><input type="number" value="100" class="input-edit"></td>
                <td>3.0</td>
                <td>3.2</td>
                <td>15.3</td>
                <td>98</td>
                <td class="button-contains">
                    <button><i class="fa fa-times color-blue" aria-hidden="true"></i></button>
                </td>
            </tr>-->
            <tr class="total">
                <td colspan="2" class="table-header">Норма</td>
                <td>35</td>
                <td>20</td>
                <td>70</td>
                <td>500</td>
                <td class="button-contains"></td>
            </tr>
            <tr>
                <td colspan="2" class="table-header">Фактическое значение</td>
                <td>6.2</td>
                <td>7.3</td>
                <td>29.5</td>
                <td>200</td>
                <td class="button-contains"></td>
            </tr>
        </table>
               <div class="droparea">
                  Перетащите сюда упражнения, чтобы добавить их на этот день.
               </div>
            </article>
<? } ?>
            <!--<article class="training-day">
               <h3>Второй прием пищи</h3>
               <table class="nutrition-part">
            <tr>
                <th class="table-header nutrition-caption-name">Продукт</th>
                <th class="table-header nutrition-caption-weight">Граммы</th>
                <th class="table-header nutrition-caption-pfc">Б</th>
                <th class="table-header nutrition-caption-pfc">Ж</th>
                <th class="table-header nutrition-caption-pfc">У</th>
                <th class="table-header nutrition-caption-calories">Ккал</th>
                <th class="button-contains nutrition-caption-delete"></th>
            </tr>
            <tr>
                <td>Овсяная каша</td>
                <td class="input-edit-contains"><input type="number" value="100" class="input-edit"></td>
                <td>3.2</td>
                <td>4.1</td>
                <td>14.2</td>
                <td>102</td>
                <td class="button-contains">
                    <button><i class="fa fa-times color-blue" aria-hidden="true"></i></button>
                </td>
            </tr>
            <tr>
                <td>Манная каша</td>
                <td class="input-edit-contains"><input type="number" value="100" class="input-edit"></td>
                <td>3.0</td>
                <td>3.2</td>
                <td>15.3</td>
                <td>98</td>
                <td class="button-contains">
                    <button><i class="fa fa-times color-blue" aria-hidden="true"></i></button>
                </td>
            </tr>
            <tr>
                <td>Мясо курицы отварное</td>
                <td class="input-edit-contains"><input type="number" value="100" class="input-edit"></td>
                <td>25.2</td>
                <td>7.4</td>
                <td>0</td>
                <td>170</td>
                <td class="button-contains">
                    <button><i class="fa fa-times color-blue" aria-hidden="true"></i></button>
                </td>
            </tr>
            <tr class="total">
                <td colspan="2" class="table-header">Норма</td>
                <td>35</td>
                <td>20</td>
                <td>70</td>
                <td>500</td>
                <td class="button-contains"></td>
            </tr>
            <tr>
                <td colspan="2" class="table-header">Фактическое значение</td>
                <td>6.2</td>
                <td>7.3</td>
                <td>29.5</td>
                <td>200</td>
                <td class="button-contains"></td>
            </tr>
        </table>

               <div class="droparea">
                  Перетащите сюда упражнения, чтобы добавить их на этот день.
               </div>
            </article>

            <article class="training-day">
               <h3>Третий прием пищи</h3>
               <table class="nutrition-part">
            <tr>
                <th class="table-header nutrition-caption-name">Продукт</th>
                <th class="table-header nutrition-caption-weight">Граммы</th>
                <th class="table-header nutrition-caption-pfc">Б</th>
                <th class="table-header nutrition-caption-pfc">Ж</th>
                <th class="table-header nutrition-caption-pfc">У</th>
                <th class="table-header nutrition-caption-calories">Ккал</th>
                <th class="button-contains nutrition-caption-delete"></th>
            </tr>
            <tr>
                <td>Овсяная каша</td>
                <td class="input-edit-contains"><input type="number" value="100" class="input-edit"></td>
                <td>3.2</td>
                <td>4.1</td>
                <td>14.2</td>
                <td>102</td>
                <td class="button-contains">
                    <button><i class="fa fa-times color-blue" aria-hidden="true"></i></button>
                </td>
            </tr>
            <tr>
                <td>Манная каша</td>
                <td class="input-edit-contains"><input type="number" value="100" class="input-edit"></td>
                <td>3.0</td>
                <td>3.2</td>
                <td>15.3</td>
                <td>98</td>
                <td class="button-contains">
                    <button><i class="fa fa-times color-blue" aria-hidden="true"></i></button>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="table-header">Норма</td>
                <td>35</td>
                <td>20</td>
                <td>70</td>
                <td>500</td>
                <td class="button-contains"></td>
            </tr>
            <tr>
                <td colspan="2" class="table-header">Фактическое значение</td>
                <td>6.2</td>
                <td>7.3</td>
                <td>29.5</td>
                <td>200</td>
                <td class="button-contains"></td>
            </tr>
        </table>
               <div class="droparea">
                  Перетащите сюда упражнения, чтобы добавить их на этот день.
               </div>
            </article>-->
         </div>
         </div>
</div>
<?php
  echo $this->Form->input(__('Save'), array(
      'type' => 'submit',
      'class' => 'btn btn-primary',
      'onclick' => 'return validate()'
    ));



 echo $this->Form->end(); ?>

</div>
<div class = "col-lg-6 fixblock">
         <div class="editor-column">
            <div class="excercises">
               

		<div id="container"><table border="1" cellpadding="2" cellspacing="1" width="100%">
<thead>
  <tr bgcolor="#cccccc">
    <th class="table-header grocery-list-name">Продукт</th>
                    <td class="table-header grocery-list-pfc">Б</td>
                    <td class="table-header grocery-list-pfc">Ж</td>
                    <td class="table-header grocery-list-pfc">У</td>
                    <td class="table-header grocery-list-pfc">Ккал</td>
  </tr>
</thead>
<tbody>
        <? foreach($foods as $food) { ?>  
			<tr class="drugel" id="<?= $food->id;?>">
                    <td id="title"  val="<?= $food->id;?>"><?= $food->name; ?></td>
                    <td id="p2"><?= $food->proteins; ?></td>
                    <td id="p3"><?= $food->fats; ?></td>
                    <td id="p4"><?= $food->hidrocarbonats; ?></td>
                    <td id="p5"><?= $food->colories; ?></td>
                </tr>
        <? } ?>
                
<!--
                <tr>
                    <td>Овсяная каша</td>
                    <td class="grocery-list-pfc">3.2</td>
                    <td class="grocery-list-pfc">4.1</td>
                    <td class="grocery-list-pfc">14.2</td>
                    <td class="grocery-list-pfc">102</td>
                </tr>
                <tr class="drugel">
                    <td id="title">Манная каша</td>
                    <td id="p2">3.0</td>
                    <td id="p3">3.2</td>
                    <td id="p4">15.3</td>
                    <td id="p5">98</td>
                </tr>
                <tr>
                    <td>Мясо курицы отварное</td>
                    <td>25.2</td>
                    <td>7.4</td>
                    <td>0</td>
                    <td>170</td>
                </tr>
                <tr>
                    <td>Овсяная каша</td>
                    <td>3.2</td>
                    <td>4.1</td>
                    <td>14.2</td>
                    <td>102</td>
                </tr>
                <tr>
                    <td>Манная каша</td>
                    <td>3.0</td>
                    <td>3.2</td>
                    <td>15.3</td>
                    <td>98</td>
                </tr>
                <tr>
                    <td>Мясо курицы отварное</td>
                    <td>25.2</td>
                    <td>7.4</td>
                    <td>0</td>
                    <td>170</td>
                </tr>
                <tr>
                    <td>Овсяная каша</td>
                    <td>3.2</td>
                    <td>4.1</td>
                    <td>14.2</td>
                    <td>102</td>
                </tr>
                <tr>
                    <td>Манная каша</td>
                    <td>3.0</td>
                    <td>3.2</td>
                    <td>15.3</td>
                    <td>98</td>
                </tr>
                <tr>
                    <td>Мясо курицы отварное</td>
                    <td>25.2</td>
                    <td>7.4</td>
                    <td>0</td>
                    <td>170</td>
                </tr>
                <tr>
                    <td>Овсяная каша</td>
                    <td>3.2</td>
                    <td>4.1</td>
                    <td>14.2</td>
                    <td>102</td>
                </tr>
                <tr>
                    <td>Манная каша</td>
                    <td>3.0</td>
                    <td>3.2</td>
                    <td>15.3</td>
                    <td>98</td>
                </tr>
                <tr>
                    <td>Мясо курицы отварное</td>
                    <td>25.2</td>
                    <td>7.4</td>
                    <td>0</td>
                    <td>170</td>
                </tr>
                <tr>
                    <td>Овсяная каша</td>
                    <td>3.2</td>
                    <td>4.1</td>
                    <td>14.2</td>
                    <td>102</td>
                </tr>
                <tr>
                    <td>Манная каша</td>
                    <td>3.0</td>
                    <td>3.2</td>
                    <td>15.3</td>
                    <td>98</td>
                </tr>
                <tr>
                    <td>Мясо курицы отварное</td>
                    <td>25.2</td>
                    <td>7.4</td>
                    <td>0</td>
                    <td>170</td>
                </tr>
                <tr>
                    <td>Овсяная каша</td>
                    <td>3.2</td>
                    <td>4.1</td>
                    <td>14.2</td>
                    <td>102</td>
                </tr>
                <tr>
                    <td>Манная каша</td>
                    <td>3.0</td>
                    <td>3.2</td>
                    <td>15.3</td>
                    <td>98</td>
                </tr>
                <tr>
                    <td>Мясо курицы отварное</td>
                    <td>25.2</td>
                    <td>7.4</td>
                    <td>0</td>
                    <td>170</td>
                </tr>
                <tr>
                    <td>Овсяная каша</td>
                    <td>3.2</td>
                    <td>4.1</td>
                    <td>14.2</td>
                    <td>102</td>
                </tr>
                <tr>
                    <td>Манная каша</td>
                    <td>3.0</td>
                    <td>3.2</td>
                    <td>15.3</td>
                    <td>98</td>
                </tr>
                <tr>
                    <td>Мясо курицы отварное</td>
                    <td>25.2</td>
                    <td>7.4</td>
                    <td>0</td>
                    <td>170</td>
                </tr>
                <tr>
                    <td>Овсяная каша</td>
                    <td>3.2</td>
                    <td>4.1</td>
                    <td>14.2</td>
                    <td>102</td>
                </tr>
                <tr>
                    <td>Манная каша</td>
                    <td>3.0</td>
                    <td>3.2</td>
                    <td>15.3</td>
                    <td>98</td>
                </tr>
                <tr>
                    <td>Мясо курицы отварное</td>
                    <td>25.2</td>
                    <td>7.4</td>
                    <td>0</td>
                    <td>170</td>
                </tr>
                <tr>
                    <td>Овсяная каша</td>
                    <td>3.2</td>
                    <td>4.1</td>
                    <td>14.2</td>
                    <td>102</td>
                </tr>
                <tr>
                    <td>Манная каша</td>
                    <td>3.0</td>
                    <td>3.2</td>
                    <td>15.3</td>
                    <td>98</td>
                </tr>
                <tr>
                    <td>Мясо курицы отварное</td>
                    <td>25.2</td>
                    <td>7.4</td>
                    <td>0</td>
                    <td>170</td>
                </tr>
                -->
</tbody>
<!--<tfoot>
  <tr bgcolor="#f5f5f5">
    <td>1</td><td>2</td><td>3</td><td>4</td><td>5</td>
  </tr>
</tfoot>-->
</table>
</div>
<script type="text/javascript">
var c = document.getElementById('container');
var t = c.firstChild;
var tH = t.tHead;
var tB = t.tBodies[0];
//var tF = t.tFoot;
var len = t.rows[0].cells.length;

for(var i = 0; i < len; i++){
  var w = tH.rows[0].cells[i].offsetWidth + t.cellPadding*2 + "px";
  tH.rows[0].cells[i].style.width = 
  tB.rows[0].cells[i].style.width = w;
  //tF.rows[0].cells[i].style.width = w;
}
t.style.width = (t.offsetWidth + t.cellPadding*len) + "px";

var header = t.cloneNode(false);
header.appendChild(tH);
c.parentNode.insertBefore(header, c);

/*var footer = t.cloneNode(false);
footer.appendChild(tF);
c.parentNode.insertBefore(footer, c.nextSibling);
*/
c.style.height = "70vh";
c.style.overflow = "auto";
c.style.width = (c.offsetWidth - c.clientWidth) + t.offsetWidth + "px";

$(function() {
  
    $('.drugel').draggable({ appendTo: 'body', /*helper: 'clone',*/
      helper: function () {
        return $(this).clone().css("width", $(this).width());
      }, 
      start: function (event, ui) {
            //$(this).hide();
            $(this).css("opacity", 0);
        },
        stop: function (event, ui) {
            //$(this).show();
            $(this).css("opacity", 100);
        }, 
        revert:  function(dropped) {
             var $draggable = $(this),
                 hasBeenDroppedBefore = $draggable.data('hasBeenDropped'),
                 wasJustDropped = dropped && dropped[0].id == "droppable";
             if(wasJustDropped) {
                 // don't revert, it's in the droppable
                 return false;
             } else {
                 if (hasBeenDroppedBefore) {
                     // don't rely on the built in revert, do it yourself
                     $draggable.animate({ top: 0, left: 0 }, 'slow');
                     return false;
                 } else {
                     // just let the built in revert work, although really, you could animate to 0,0 here as well
                     return true;
                 }
             }
        }
    });
//});

$('.droparea').droppable({
    drop: function(event, ui) {
      //var parent=$(this).parent;
      //var i = $(this).find('.c2').length;
      //i = i+1;
      //var c2 = $($('.c2')[0]).clone();
      var dat = $(ui.draggable[0].innerHTML);
      var title = dat[0].innerHTML;
      var c2 = $('\
        <tr>\
            <td>' + title +'</td>\
            <td class="input-edit-contains"><input type="number" value="100" class="input-edit" name="food['+$(this).parent()[0].id+']['+dat[0].attributes.val.value+'][cnt]">\
				<input type="hidden" id="fooddata" value="'+dat[0].attributes.val.value+'" class="input-edit" name="food['+$(this).parent()[0].id+']['+dat[0].attributes.val.value+'][id]" proteins="'+dat[2].innerHTML+'" fats="'+dat[4].innerHTML+'" hydrocarbonates="'+dat[6].innerHTML+'" calories="'+dat[8].innerHTML+'">\
            </td>\
                <td>'+dat[2].innerHTML+'</td>\
                <td>'+dat[4].innerHTML+'</td>\
                <td>'+dat[6].innerHTML+'</td>\
                <td>'+dat[8].innerHTML+'</td>\
                <td class="button-contains">\
                    <button><i class="fa fa-times color-blue" aria-hidden="true"></i></button>\
                </td>\
            </tr>\
 \
      ');

      	dat = $(ui.draggable[0].innerHTML);
      	$(this).parent().find('.total').before(c2);
/*
      $(c2).find('p')[0].innerHTML = ui.draggable[0].innerHTML + '<a class="glyphicon glyphicon-remove-circle" href="#" onclick="removeexercise(this)"></a>';
            var obj = $(c2).find(":input");

      obj[0].name = 'excersice['+$(this)[0].id+']['+ i +'][excersiceid]';
      obj[0].value = ui.draggable[0].id;
      obj[1].name = 'excersice['+$(this)[0].id+']['+ i +'][podhod]';
      obj[2].name = 'excersice['+$(this)[0].id+']['+ i +'][repeat]';
      obj[3].name = 'excersice['+$(this)[0].id+']['+ i +'][weight]';
      $(this).append(c2);*/
    }
  });
});

function changecnt(object) {
	var parent = $(object).parent();
	var val = $(object).value;
	var data = $(parent).find('#fooddata')[0];
	var proteins = (val / 100) * data.attributes.proteins.value;
	var fats = (val / 100) * data.attributes.fats.value;
	var hydrocarbonates = (val / 100) * data.attributes.hydrocarbonates.value;
}
</script>














            </div>
         </div>
      </div>
      </div>
   </section>