/************************************************************
Функция переключения между окнами в мобильной версии
!!!! Переключение происходит при ширине меньше 768 пикселей !!!
************************************************************/
function togglewindows() {
  if (width <= 768) {
    $(document).scrollTop(0);
    $('#left').toggle("slide", {direction: "left" }, 1000);
    $(".routineinfo").toggle("slide", {direction: "right" }, 1000);
  }
}

/************************************************************
Переход из модального окна с проверкой флага.
Если фоаг установлен то создаём на основе шаблона иначе нет.
************************************************************/
function submit(id) {
  if ($("#mycheck")[0].checked)
    document.location.href = "/user/createuserroutinebytmp/"+id+"/" + $("#tmpprogs")[0].selectedOptions[0].value;
  else 
    document.location.href = "/user/createuserroutine/"+id;
}

/************************************************************
Показываем выбранный распорядок дня
************************************************************/
function show(id) {
  var routineinfo = "";
  if (routinesinfo[id].length > 0) {
    routineinfo+="<div class='header day blue'><h2>Тренировочный день</h2></div><div class='form'>"
    var dates = [];
    dates[0]={};
    dates[0]["date"] = new Date(routinesinfo[id][0].wakeupTime);
    dates[0]["title"] = "Подъём";
    dates[1]={};
    dates[1]["date"] = new Date(routinesinfo[id][0].trainTime);
    dates[1]["title"] = "Время тренировки";
    dates[2]={};
    dates[2]["date"] = new Date(routinesinfo[id][0].sleepTime);
    dates[2]["title"] = "Сон";

    for (var j=0; j<routinesinfo[id][0].eating.length; j++) {
      dates[j+3]={};
      dates[j+3]["date"] = new Date(routinesinfo[id][0].eating[j].time);
      dates[j+3]["title"] = "" + (j+1) + " приём пищи";
    }
    dates.sort(function(a,b){
      //return b.date<a.date; 
      if (a.date < b.date) return -1
        else
          if (a.date>b.date) return 1
            else return 0;
    });
    $.each(dates, function( key, value ) {
      routineinfo+= "<p> <span>" + ((''+value.date.getHours()).length<2 ? '0' :'') + value.date.getHours() + ':' +
        ((''+value.date.getMinutes()).length<2 ? '0' :'') + value.date.getMinutes() + "</span> " + value.title + "</p>";
    });

    routineinfo+= "</div><div class='header day blue'><h2>День отдыха</h2></div><div class='form'>";
    //День 2
    var dates2 = [];
    dates2[0]={};
    dates2[0]["date"] = new Date(routinesinfo[id][1].wakeupTime);
    dates2[0]["title"] = "Подъём";
    dates2[1]={};
    dates2[1]["date"] = new Date(routinesinfo[id][1].sleepTime);
    dates2[1]["title"] = "Сон";
    for (var j=0; j<routinesinfo[id][1].eating.length; j++) {
      dates2[j+2]={};
      dates2[j+2]["date"] = new Date(routinesinfo[id][1].eating[j].time);
      dates2[j+2]["title"] = "" + (j+1) + " приём пищи";
    }
    dates2.sort(function(a,b){
      if (a.date < b.date) return -1
        else
          if (a.date>b.date) return 1
            else return 0;
    });
    $.each(dates2, function( key, value ) {
      routineinfo+= "<p> <span>" + ((''+value.date.getHours()).length<2 ? '0' :'') + value.date.getHours() + ':' +
        ((''+value.date.getMinutes()).length<2 ? '0' :'') + value.date.getMinutes() + "</span> " + value.title + "</p>";
    });
  }
  routineinfo+="</div>";
  $("#contentblock").empty();
  $("#contentblock").append(routineinfo);
  $('.header').removeClass("active");
  $('#'+id).parent().addClass("active");

  $($('#routinename')[0].parentNode).addClass("orange");
  $('#routinename')[0].textContent = $('#'+id)[0].textContent;

  //скроллим страницу на значение равное позиции элемента
  $(document).scrollTop(0);
  togglewindows();
}

/************************************************************
Отслеживаем изменение ширины для перестроения интерфейса
************************************************************/
$( window ).resize(function() { 
  width = window.innerWidth;
  if (width> 768)
    $(".routineinfo").css("display", "block");
  else
    $(".routineinfo").css("display", "none");
});

/************************************************************
Увеличение значения в поле на 1
************************************************************/
function up(input0) {

  'use strict';

  if (typeof input0.stepUp === 'function') {
      try{
     input0.stepUp();
      }catch(ex){
      var step=Number(input0.step);
          input0.value = Number(input0.value) + step;
      }
  }

}

/************************************************************
Уменьшение значения в поле на 1
************************************************************/
function down(input0) {

  'use strict';

  if (typeof input0.stepDown === 'function') {
      try{
     input0.stepDown();
      }catch(ex){
      var step=Number(input0.step);
          input0.value = Number(input0.value) - step;
      }
  }

}