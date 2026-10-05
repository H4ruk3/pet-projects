/************************************************************
Гдобальные переменные.
************************************************************/
var isList = true; //Способ отображения (список, таблица)
var days = [$("#day")]; //Список дней в программе тренировки

//Скрываем все элементы
$(function() {
  $("div[id*='menu-']").hide(); 
});

/***********************************************************
Callback вызываемый при перетаскивании элемента.
************************************************************/
var drop = {
    over: function(event, ui) { 
      	var el = $(this).parent().prev().find("div[data="+ui.draggable[0].id+"]");
      	if (el.length > 0) {
        	$(this).css("background", "#ff0000") 
        	$(this).css("color", "#ffffff")
        	this.innerHTML = "Данное упражнение уже добавлено в текущий день."
      	}
      	else {  
        	$(this).css("background", "#ff9800");
       		this.innerHTML = "" 
        }
    },
    out: function(event, ui) { 
    	$(this).css("background", "#ffffff");
        $(this).css("color", "#333")
        this.innerHTML = "Перетащите сюда упражнения из списка, что бы добавить их на этот день"; 
    },
    drop: function(event, ui) {
      	var el1 = $(this).parent().prev().find("div[data="+ui.draggable[0].id+"]");
      	if (el1.length == 0) {
      		var el = $(this)[0].parentElement.parentElement;
      		var els = $(el).find('.excersice');
      		var i = els.length + 1;
      		var myTemplate = $.templates("#excersicetmp");
      		var day = [{name: $(ui.draggable[0]).find('a')[0].innerHTML, daynum: $(el)[0].id, exnum: i, exid: ui.draggable[0].id}];
      		var html = myTemplate.render(day);
      		$(this).parent().prev().append(html);
      		$(this).css("background", "#ffffff");
      		$(this).css("color", "#333")
    	}
    	$(this).css("background", "#ffffff");
    	$(this).css("color", "#333")
        this.innerHTML = "Перетащите сюда упражнения из списка, что бы добавить их на этот день";
  	}
}

/************************************************************
Разворачиваем/сворачиваем выбранный элемент.
************************************************************/
function toggle(objName) {
 	var obj = $(objName),
 	blocks = $("div[id*='menu-']");
 
 	if (obj.css("display") != "none") {
 		obj.animate({ height: 'hide' }, 500);
 	} else {
 		var visibleBlocks = $("div[id*='menu-']:visible");
 		if (visibleBlocks.length < 1) {
 			obj.animate({ height: 'show' }, 500);
 		} else {
 			$(visibleBlocks).animate({ height: 'hide' }, 500, function() {
 				obj.animate({ height: 'show' }, 500);
 			}); 
 		}
 	}
}

/************************************************************
Добавление дня тренировки
************************************************************/
function addday(el) {
    $(el).before("<button type='button' class=' btn btn-default daybtn' onclick='changeday(this)' data='" + $(el)[0].parentNode.children.length +"'>День " + $(el)[0].parentNode.children.length + "</button>");
    var myTemplate = $.templates("#daytmp"); 
    var daynum = ($(el)[0].parentNode.children.length-1);
    var day = [{dayname: "День "+daynum, dayid: daynum}];
    var html = myTemplate.render(day);
    $("#days").append(html);
    $('.droparea').droppable(drop);
    changeday(el.previousSibling);
}

/************************************************************
Изменение выбранного дня тренировки.
************************************************************/    
function changeday(day) {
    $($(day)[0].parentNode.children).removeClass("active")
    $(day).addClass("active");
    if (isList) {
      	var dat = $($(day)[0]).attr("data"); 
        $('#content').scrollTop($('#'+dat).position().top);
    } else {
      	$(".day").attr("style", "display: none");
    	var dat = $($(day)[0]).attr("data"); 
    	$('#'+dat).attr("style", "display:block");
    }
}

/************************************************************
Удаление дня тренировки
************************************************************/
function removeday(id) {
    var elid = $(id)[0].id;
    $(id).remove();
    var buttons = $("#buttons").children();
    $(buttons[buttons.length-2]).remove();
    $.each($(".day"), function (i, day) {
        if (day.id>elid) {
            day.id = day.id-1;
            $(day).find("h3")[0].innerHTML = "День "+day.id;
            changeexday("#"+day.id, day.id);
        }
    });
    return false;
}

/************************************************************
Изменение номера дня для всех упражнений в этом дне.
************************************************************/
function changeexday(day, num) {
    $.each($(day).find(".excersice"), function (i, ex) {
        $.each($(ex).find("input"), function(j, inp) {
          //var name = inp.name;
          inp.name = inp.name.replace(/(exercise)\[\d+\](\[\d+\].*)/, "$1["+num+"]$2")
          //console.log(name);
        })
    });
}

/************************************************************
Удаление упражнения из дня.
************************************************************/
function removeex(daynum, ex) {
    var day = $(".day")[daynum-1];
    $(ex).parent().parent().remove();
    $.each($(day).find(".excersice"), function (i, ex) {
        $.each($(ex).find("input"), function(j, inp) {
          	inp.name = inp.name.replace(/(exercise\[\d+\])\[\d+\](.*)/, "$1["+(i+1)+"]$2")
        })
    });
    return false;
}
    
/************************************************************
Отображение списка дней.
************************************************************/
function list(ob) {
    $(".righttoolbar").removeClass("active")
    $(ob).addClass("active"); 
    $(".day").attr("style", "display: block");
    isList = true;
}
    
/************************************************************
Отображение только активного дня.
************************************************************/
function hide(ob) {
    $(".righttoolbar").removeClass("active")
    $(ob).addClass("active"); 
    $(".day").attr("style", "display: none");
    var dat = $($(".daybtn.active")[0]).attr("data"); 
    $('#'+dat).attr("style", "display:block");
    isList = false;
}

/************************************************************
Подсветка полей при фокусе. (Дополнительно удаление единицы.)
************************************************************/
function onFocus(ob) {
    $($(ob).prev()[0]).css({"background-color": "#ffa726", "color" : "#fff"});
    if ($(ob).val().length == 1 && $(ob).val() == "1")
        $(ob).val("");
}

/************************************************************
Отмена подсветки при потере фокуса.
************************************************************/
function onBlur(ob) {
    $($(ob).prev()[0]).removeAttr( 'style' );
    if ($(ob).val().length == 0)
        $(ob).val("1");
}

/************************************************************
Инициализация перетаскивания элементов после завершения рендеринга страницы.
************************************************************/
$(function() {
    $('#content').perfectScrollbar();
    $('#rightbox').perfectScrollbar();

    $('.drugel').draggable({ appendTo: 'body', 
      	helper: function () {
        	var el = $(this).clone().css("width", $(this).width());
        	var e = $("<div class='item'>").append(el).append($('</div>'));
        	return e;
      	}, 
      	start: function (event, ui) {
            $($(this)[0].parentElement).css("opacity", 0);
        },
        stop: function (event, ui) {
            $($(this)[0].parentElement).css("opacity", 100);
        }, 
        revert:  function(dropped) {
            var $draggable = $(this),
                hasBeenDroppedBefore = $draggable.data('hasBeenDropped'),
                wasJustDropped = dropped && dropped[0].id == "droppable";
            if(wasJustDropped) {
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
    $('.droparea').droppable(drop);
    hide($("#left .row.box.cap a")[1]);

    $('.exerciseimgwrap').click(function(e) {
    //отменить стандартное действие браузера
    e.preventDefault();
    //присвоить атрибуту scr элемента img модального окна
    //значение атрибута scr изображения, которое обёрнуто
    //вокруг элемента a, на который нажал пользователь
    $('#image-modal .modal-body img').attr('src', $(this).find("img").attr('src'));
    //открыть модальное окно
    $("#image-modal").modal('show');
  });
  //при нажатию на изображение внутри модального окна 
  //закрыть его (модальное окно)
  $('#image-modal .modal-body img').on('click', function() {
    $("#image-modal").modal('hide')
  });
});

/************************************************************
Валидация полей формы перед отправкой.
************************************************************/
function checkvalidity() {
  // Each time the user tries to send the data, we check
  // if the email field is valid.
  var inputs = $("input[day]");
  for(var i = 0; i < inputs.length; i++) {
  	if (!inputs[i].validity.valid) {
  		var daynum = $(inputs[i]).attr("day");
  		changeday($("button[data='"+daynum+"']")[0]);
  		//event.preventDefault();
  		break;
  	}
          //var name = inp.name;
          //inp.name = inp.name.replace(/(exercise)\[\d+\](\[\d+\].*)/, "$1["+num+"]$2")
          //console.log(name);
    }
}