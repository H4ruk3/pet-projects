var curdate = null;
var calendars = {};
var curtraining = null;
var globaltarget = null
var curtrainingday = null;
var oldexercises = null;
var days = ["воскресенье", "понедельник", "вторник", "среда", "четверг", "пятница", "суббота"];
var hellopreloader = document.getElementById("hellopreloader_preload");

/************************************************************
Функция отображения прелоадера пока не загрузится календарь
************************************************************/
function fadeOutnojquery(el) {
    el.style.opacity = 1;
    var interhellopreloader = setInterval(function() {
        el.style.opacity = el.style.opacity - 0.05;
        if (el.style.opacity <= 0.05) {
            clearInterval(interhellopreloader);
            hellopreloader.style.display = "none";
        }
    }, 16);
}

/************************************************************
Добавление тренировки на выбранный день.
ob - указатель на элемент добавить день в разметке.
У ob обязательно задано свойство date.
************************************************************/
function addDay(ob) {
    var month = '' + (ob.date.getMonth() + 1),
        day = '' + ob.date.getDate(),
        year = ob.date.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    curdate = [year, month, day].join('-');
    $.post("/diary/addDay", { date: curdate }, function(data) {
            try {
                obj = $.parseJSON(data);
                if (obj.status == "success") {
                    for (var key in obj.diaryday)
                        diary[key] = obj.diaryday[key];
                    calendars.clndr1.addEvents([{ startDate: obj.date, endDate: obj.date, title: 'Тренировка', id: obj.id }]);
                    $(".calendar-day-" + obj.date).click();
                    $(".create-event").css("display", "none");
                } else {
                    $("#alertmain .message").html("Не удалось добавить тренировку на указанный день.");
                    $("#alertmain").fadeTo(500, 1);
                    window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
                }
            } catch (e) {
                $("#alertmain .message").html("Не удалось добавить тренировку на указанный день.");
                $("#alertmain").fadeTo(500, 1);
                window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
            }

        })
        .fail(function() {
            $("#alertmain .message").html("Не удалось добавить тренировку на указанный день.");
            $("#alertmain").fadeTo(500, 1);
            window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
        });
}

/************************************************************
Удалить тренировку с выбранного дня.
************************************************************/
function deleteday(id, date) {
    curdate = date;
    $.post("/diary/deleteday/" + id, function(data) {
            try {
                obj = $.parseJSON(data);
                if (obj.status == "success") {
                    for (var key in obj.diaryday)
                        diary[key] = obj.diaryday[key];
                    calendars.clndr1.removeEvents(function(event) {
                        return event.id == id;
                    });
                    $(".calendar-day-" + curdate).click();
                    if (!userdiary)
                        $(".create-event").css("display", "flex");
                    else
                        $(".create-event").css("display", "none");
                } else {
                    $("#alertmain .message").html("Не удалось удалить тренировку.");
                    $("#alertmain").fadeTo(500, 1);
                    window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
                }
            } catch (e) {
                $("#alertmain .message").html("Не удалось удалить тренировку.");
                $("#alertmain").fadeTo(500, 1);
                window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
            }
        })
        .fail(function() {
            $("#alertmain .message").html("Не удалось удалить тренировку.");
            $("#alertmain").fadeTo(500, 1);
            window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
        });
}

/************************************************************
Инициализация календаря.
************************************************************/
$(document).ready(function() {
    var thisMonth = moment().format('YYYY-MM');
    calendars.clndr1 = $('.calendar').clndr({
        events: eventArray,
        classes: {
            past: "past",
            today: "today",
            event: "event",
            inactive: "inactive",
            lastMonth: "last-month",
            nextMonth: "next-month",
            adjacentMonth: "adjacent-month"
        },
        doneRendering: function() {
            console.log('this would be a fine place to attach custom event handlers.');
            if (curdate == null)
                $(".day.today").click();
            else
                $(".calendar-day-" + curdate).click();
            $(".calendar-left").css("display", "block");
            fadeOutnojquery(hellopreloader);
        },
        clickEvents: {
            click: function(target) {
                console.log(target);
                globaltarget = target;
                $(".day.selected").removeClass('selected');
                $(target.element).addClass("selected");
                if ($(target.element).hasClass('inactive')) {
                    console.log('not a valid datepicker date.');
                } else {
                    console.log('VALID datepicker date.');
                }
                var month = '' + (target.date._a[1] + 1),
                    day = '' + target.date._a[2],
                    year = target.date._a[0];
                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;
                curdate = [year, month, day].join('-');
                showeating(curdate);
                $(".calendar-left>.num-date")[0].innerHTML = target.date._a[2];
                $(".calendar-left>.dayleft")[0].innerHTML = days[target.date._d.getDay()];
                $(".add-event")[0].date = target.date._d;
                if (target.events.length > 0) {
                    $(".calendar-left>.current-events>ul>li")[0].innerHTML = target.events[0].title + '<div id="buttons" class="row">' +
                        '<div class="btn" onclick="show(' + target.events[0].id + ');"><span class="glyphicon glyphicon-eye-open"></span></div>' +
                        '<div class="btn" onclick="deleteday(' + target.events[0].id + ', \'' + curdate + '\');"><span class="glyphicon glyphicon-trash"></span></div>' +
                        '</div>';
                    curtrainingday = diary[target.events[0].id].trainingprogramday_id;
                    oldexercises = diary[target.events[0].id].dayexersices;
                    show(target.events[0].id, target.date);
                    $(".create-event").css("display", "none");
                } else {
                    $(".calendar-left>.current-events>ul>li")[0].innerHTML = "На этот день не запланировано событий";
                    $('#progname')[0].innerHTML = "Дата: " + curdate + " В этот день тренировки нет";
                    $("#infoblock").empty();
                    if ($($("#tab1")[0].firstChild).is("form"))
                       $("#tab1")[0].innerHTML = $("#tab1")[0].firstChild.innerHTML; 
                    var header = $('#infoblock')[0].parentNode.previousElementSibling;
                    var save = $(header).find("#progname").detach();
                    $(header).empty().append(save);
                    if (typeof curtraining != "undefined" && curtraining != null && diary[curtraining] != null) {
                        diary[curtraining].trainingprogramday_id = curtrainingday;
                        diary[curtraining].dayexersices = oldexercises;
                    }
                    if (!userdiary)
                        $(".create-event").css("display", "flex");
                    else
                        $(".create-event").css("display", "none");
                }
            },
            nextMonth: function() {
                console.log('next month.');
            },
            previousMonth: function() {
                console.log('previous month.');
            },
            onMonthChange: function() {
                console.log('month changed.');
            },
            nextYear: function() {
                console.log('next year.');
            },
            previousYear: function() {
                console.log('previous year.');
            },
            onYearChange: function() {
                console.log('year changed.');
            }
        },
        multiDayEvents: {
            startDate: 'startDate',
            endDate: 'endDate',
            singleDay: 'date'
        },
        dateParameter: 'date',
        showAdjacentMonths: true,
        adjacentDaysChangeMonth: false
    });

    // bind both clndrs to the left and right arrow keys
    $(document).keydown(function(e) {
        if (e.keyCode == 37) {
            // left arrow
            calendars.clndr1.back();
        }
        if (e.keyCode == 39) {
            // right arrow
            calendars.clndr1.forward();
        }
    });
});

/************************************************************
Функция показа подробного описания результатов тренировки
************************************************************/
function show(id, date) {
    curtraining = id;
    var myTemplate = $.templates("#daytmp");
    var info = $("#infoblock");
    info.empty();
    if ($($("#tab1")[0].firstChild).is("form"))
       $("#tab1")[0].innerHTML = $("#tab1")[0].firstChild.innerHTML; 
    var header = $('#infoblock')[0].parentNode.previousElementSibling;
    var save = $(header).find("#progname").detach();
    $(header).empty().append(save);
    for (var i = 0; i < diary[id].dayexersices.length; i++) {
        var html = myTemplate.render(diary[id].dayexersices[i]);
        info.append(html);
        info.append("<input type='hidden' name='excersice[" + diary[id].dayexersices[i].exercise.id + "][trainingexercise]' value='" + (diary[id].dayexersices[i].plan != null ? diary[id].dayexersices[i].plan.id : "") + "'/>")
    }
    var now = new Date();
    if (date._d <= now)
        info.append('<input type="button" id="startedit" value="Заполнить" onclick="changetraining(this, ' + id + ')"/>');

    
    $("div[id*='menu-']").css("height", "100px");
    $("#menu-1").dotdotdot();
    $('.contentleft').removeClass("active");
    $('#left').find(".header").removeClass("active");
    $('#' + id).parent().addClass("active");
    $('#progname')[0].innerHTML = "Дата: " + diary[id].date + " День " + diary[id].trainingprogramday["number"] + " Оценка: " +
        //"<input id='input-3' name='mark' size= 'xs', value='" + (diary[id].mark != null ? diary[id].mark : 0) + "' class='rating' min='1' max='5' step='1'>";
        "<div id='star'></div>";
    $('#star').raty({
        //
        score:     diary[id].mark != null ? diary[id].mark : 0,
        readOnly:  true,
        path: "/img/",
        hints: ['Очень плохо', 'Плохо', 'Нормально', 'Хорошо', 'Очень хорошо'],
    });
    //oldexercises = diary[id].dayexersices;

    //$('#input-3').rating({displayOnly: true, step: 0.5});
    /*var $input = $('#input-3');
    if ($input.length > 0) {
        $input.rating();
    }*/
    //$('#input-3').rating('update', 3);
    //$('#input-3').rating('refresh', { disabled: true, showClear: false, showCaption: true, size: "xs", showCaption: false, step: 1, min: 1, max: 5 });

}

function changetraining(ob, id) {
    $("#tab1").wrapInner("<form id='editform'></form>");
    var options = "<select name='baseday' onchange='changeplanday("+id+", this)'>";
    //for (var i = 0; i<diary[id].alternativedays.length; i++){
    var i = 1;
    for(var propt in diary[id].alternativedays){
        options+="<option value='"+propt+"' "+(propt == diary[id].trainingprogramday_id?"selected":"")+">День "+i+"</option>";
        i++;
    }
    options+="</select>"
    $("#editform>.row.box.cap.orange").append("<div class='changedayblock'>"+options+"</div>");
    var arr = $("div[data='repeats']");
    for (var i = 0; i < arr.length; i++) {
        var data = arr[i].innerHTML;
        arr[i].innerHTML = "<input class='diaryinput' name='excersice[" + arr[i].getAttribute("exid") + "][sets][" + (i + 1) + "][repeats]' value='" + data + "' />"
    }
    var arr = $("div[data='weight']");
    for (var i = 0; i < arr.length; i++) {
        var data = arr[i].innerHTML;
        arr[i].innerHTML = "<input class='diaryinput' name='excersice[" + arr[i].getAttribute("exid") + "][sets][" + (i + 1) + "][weight]' value='" + data + "' />"
    }
    var arr = $("div[data='planrepeats']");
    for (var i = 0; i < arr.length; i++) {
        var data = arr[i].innerHTML;
        $(arr[i]).append("<input type='hidden' name='excersice[" + arr[i].getAttribute("exid") + "][sets][" + (i + 1) + "][plan_repeat]' value='" + data + "' />")
    }
    var arr = $("div[data='planweight']");
    for (var i = 0; i < arr.length; i++) {
        var data = arr[i].innerHTML;
        $(arr[i]).append("<input type='hidden' name='excersice[" + arr[i].getAttribute("exid") + "][sets][" + (i + 1) + "][plan_weight]' value='" + data + "' />")
    }
    var arr = $("div.c1");
    for (var i = 0; i < arr.length; i++) {
        $(arr[i]).append("<div class='buttons'><input type='button' value='Добавить подход' onclick='previous(this);'/><input type='button' value='Удалить подход' onclick='removeset(this);' /></div>")
    }
    var parent = ob.parentElement;
    //.rating('refresh', { disabled: false, showClear: false, showCaption: true, size: "xs", showCaption: false });
    $(ob).css("display", "none");
    $(parent).append("<div class='buttons'><input type='button' data-target='#addExerciseDialog' data-toggle='modal' value='добавить упражнение'/></div>");
    $(parent).append("<div id='editbuttons' class='buttons'><input type='button' value='отмена' onclick='back2("+id+")'/><input type='button' value='сохранить' onclick='save(" + id + ")'/></div>");
    //oldexercises = diary[id].dayexersices;
}

function back2(id) {
    diary[id].trainingprogramday_id = curtrainingday;
    diary[id].dayexersices = oldexercises;//diary[id].alternativedays[curtrainingday];
    back();
    show(id, globaltarget.date);
}

function changeplanday(id, ob) {
    
    //curtrainingprogramday = diary[id].trainingprogramday_id
    diary[id].trainingprogramday_id = ob.value;
    diary[id].dayexersices = diary[id].alternativedays[ob.value];

    back();
    
    /*var myTemplate = $.templates("#daytmp");
    var info = $("#infoblock");
    info.empty();
    //$("#tab1>.row.box.cap.orange>.changedayblock").remove();
    for (var i = 0; i < diary[id].dayexersices.length; i++) {
        var html = myTemplate.render(diary[id].dayexersices[i]);
        info.append(html);
        info.append("<input type='hidden' name='excersice[" + diary[id].dayexersices[i].exercise.id + "][trainingexercise]' value='" + (diary[id].dayexersices[i].plan != null ? diary[id].dayexersices[i].plan.id : "") + "'/>")
    }
    var button = $('<input type="button" id="startedit" value="Заполнить" onclick="changetraining(this, ' + id + ')"/>');
    info.append(button);*/
    show(id, globaltarget.date);

    changetraining($("#infoblock #startedit")[0], id);    


}

function removeset(ob) {
    if (ob.parentNode.parentNode.children.length > 3)
        $(ob.parentNode).prev().remove();
}

function previous(ob) {
    var node;
    if (ob.parentNode.parentNode.children.length > 3) {
        node = $(ob.parentNode).prev().clone();
        var exid = ob.parentNode.parentNode.getAttribute("exid");
        var numnode = $(node).find("div[data='setnum']")[0];
        var setnum = Number(numnode.innerHTML);
        numnode.innerHTML = (setnum + 1);
        var el1 = $(node).find("div[data='repeats']>input")[0];
        var name = $(el1).attr("name");
        name = name.replace(/(excersice\[\d+\]\[sets\])\[\d+\](\[repeats\])/, '$1[' + (setnum + 1) + ']$2');
        $(el1).attr("name", name);
        $(el1).val(0);
        var el1 = $(node).find("div[data='weight']>input")[0];
        var name = $(el1).attr("name");
        name = name.replace(/(excersice\[\d+\]\[sets\])\[\d+\](\[weight\])/, '$1[' + (setnum + 1) + ']$2');
        $(el1).attr("name", name);
        $(el1).val(0);
        var numnode = $(node).find("div[data='planrepeats']")[0];
        var name = $($(numnode).find("input")[0]).attr("name");
        name = name.replace(/(excersice\[\d+\]\[sets\])\[\d+\](\[plan_repeat\])/, '$1[' + (setnum + 1) + ']$2');
        $($(numnode).find('input')[0]).attr("name", name);

        var isexists = -1;
        for (var i = 0; i < diary[curtraining].dayexersices.length; i++) {
            if (diary[curtraining].dayexersices[i].exercise.id == exid)
                if (diary[curtraining].dayexersices[i].sets.length >= setnum+1) {
                    isexists = i;
                    break;
                }
        }
        var value = 0;
        if (isexists != -1) 
            value = diary[curtraining].dayexersices[isexists].sets[setnum].planrepeats;
        else
            value = 0;
        //numnode.innerHTML+= value;
        $(numnode).html($(numnode).html().replace($(numnode).text(), value));
        $($(numnode).find('input')[0]).val(value);
        
        var numnode = $(node).find("div[data='planweight']")[0];
        var name = $($(numnode).find("input")[0]).attr("name");
        name = name.replace(/(excersice\[\d+\]\[sets\])\[\d+\](\[plan_weight\])/, '$1[' + (setnum + 1) + ']$2');
        $($(numnode).find('input')[0]).attr("name", name);

        if (isexists != -1) 
            value = diary[curtraining].dayexersices[isexists].sets[setnum].planweight;
        else
            value = 0;
        $(numnode).html($(numnode).html().replace($(numnode).text(), value));
        $($(numnode).find('input')[0]).val(value);
        
    } else {
        var exid = ob.parentNode.parentNode.getAttribute("exid");
        var isexists = -1;
        for (var i = 0; i < diary[curtraining].dayexersices.length; i++) {
            if (diary[curtraining].dayexersices[i].exercise.id == exid)
                if (diary[curtraining].dayexersices[i].sets.length > 0) {
                    isexists = i;
                    break;
                }
        }
        var html = '<div class="row rowelement"> \
    <div class="mycol-2 element center" data="setnum">1</div> \
    <div class="mycol-2 element center" data="repeats" exid="' + exid + '"> \
    <input class="diaryinput" name="excersice[' + exid + '][sets][1][repeats]" value="0" /> \
    </div> \
    <div class="mycol-2 element center" data="planrepeats">'+((isexists != -1)?diary[curtraining].dayexersices[isexists].sets[0].planrepeats:0)
    + '<input type="hidden" name="excersice[' + exid + '][sets][1][plan_repeat]" value="'+((isexists != -1)?diary[curtraining].dayexersices[isexists].sets[0].planrepeats:0)+'">'
    +'</div> \
    <div class="mycol-2 element center" data="weight" exid="' + exid + '"> \
    <input class="diaryinput" name="excersice[' + exid + '][sets][1][weight]" value="0" /> \
    </div> \
    <div class="mycol-2 element center" data="planweight">'+((isexists != -1)?diary[curtraining].dayexersices[isexists].sets[0].planweight:0)
    + '<input type="hidden" name="excersice[' + exid + '][sets][1][plan_weight]" value="'+((isexists != -1)?diary[curtraining].dayexersices[isexists].sets[0].planrweight:0)+'">'
    +'</div> \
  </div>';
        node = $(html);
    }

    //"excersice[1][sets][5][repeats]".
    $(ob.parentNode).before(node);

}

function addexercise() {
    var option = $("#tmpprogs")[0].options[$("#tmpprogs")[0].selectedIndex];
    var exercisename = option.innerHTML;
    var exerciseid = option.value;
    var musculgroup = option.getAttribute("musculgroup");
    var existingexercises = $("#infoblock>.c1").filter(function(key, elem) {
        return elem.getAttribute("exid") == exerciseid;
    });
    if (existingexercises.length == 0) {
        var ob = {};
        ob.exercise = {}
        ob.exercise.name = exercisename;
        ob.exercise.id = exerciseid;
        ob.exercise.musculgroups = [{ name: musculgroup }];
        ob.sets = [];
        var myTemplate = $.templates("#daytmp");
        var html = myTemplate.render(ob);
        var startEdit = $("#startedit")[0];
        var ob = $(html).append("<div class='buttons'><input type='button' value='Добавить подход' onclick='previous(this);'/><input type='button' value='Удалить подход' onclick='removeset(this);' /></div>");
        $(startEdit).before(ob);
        $(startEdit).before("<input type='hidden' name='excersice[" + exerciseid + "][trainingexercise]' value=''/>");
        $('#addExerciseDialog').modal('hide');
    } else {
        $("#alertadd .message").html("Такое упражнение уже есть.");
        $("#alertadd").fadeTo(500, 1);
        window.setTimeout(function() { $("#alertadd").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
    }
    //info.append(html);
    //info.append("<input type='hidden' name='excersice[" + diary[id].dayexersices[i].exercise.id + "][trainingexercise]' value='" + diary[id].dayexersices[i].plan.id + "'/>")
}

function serializeForm($form) {
    return _.object(_.map($form.serializeArray(), function(item) { return [item.name, item.value]; }));
}

function save(id) {
    $('#RatingDialog #rate').raty({
        score:     0,
        hints: ['Очень плохо', 'Плохо', 'Нормально', 'Хорошо', 'Очень хорошо'],
        click: function(score) {
            $("#RatingDialog").modal("hide");
            $('#star').raty({
                score: score,
                readOnly:  true,
                hints: ['Очень плохо', 'Плохо', 'Нормально', 'Хорошо', 'Очень хорошо']
            });
            savewithrating(id, score)
            //alert('score: ' + score);
        }
    })
    $("#RatingDialog").modal("show");
}

function savewithrating(id, score) {
    var formData = serializeForm($("#editform"));
    formData["mark"] = score;
    formData.id = id;
    //console.log(formData);
    $.post("/diary/savetraining", formData).done(function(data) {
        try {
            //alert(data);
            obj = $.parseJSON(data);
            if (obj.status == "success") {
                //$(location).attr("href", "/redesign/auth/postlogin");
                //diary[obj.id] = obj.day;

               for (var key in obj.day)
                        diary[key] = obj.day[key];
                
                back();
                show(obj.id, globaltarget.date);
            } else {
                $("#alertmain .message").html("Не удалось сохранить результаты тренировки.");
                $("#alertmain").fadeTo(500, 1);
                window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
            }
        } catch (e) {
            $("#alertmain .message").html("Не удалось сохранить результаты тренировки.");
            $("#alertmain").fadeTo(500, 1);
            window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
        }
    });
}

function back() {
    //$("#tab1").unwrap();
    $("#tab1")[0].innerHTML = $("#tab1")[0].firstChild.innerHTML;
    var arr = $("div[data='repeats']");
    for (var i = 0; i < arr.length; i++) {
        var data = $(arr[i]).find("input")[0].value;
        arr[i].innerHTML = data;
    }
    var arr = $("div[data='weight']");
    for (var i = 0; i < arr.length; i++) {
        var data = $(arr[i]).find("input")[0].value;
        arr[i].innerHTML = data
    }
    //var parent = ob.parentElement;
    //$('#input-3').rating('refresh', { disabled: true, showClear: false, showCaption: true, size: "xs", showCaption: false });
    var ob = $("#editbuttons");
    $(ob[0].previousSibling.previousSibling).css("display", "block");
    //$(ob).remove();
    $("div.buttons").remove();
    $("#tab1>.row.box.cap.orange>.changedayblock").remove();
    //$(parent).append("<input type='button' value='отмена' oncklick='back()'/>");
}
var foods = {};
var cureating = undefined;
/************************************************************
Функция показа подробного описания результатов питания.
************************************************************/
function showeating(date) {
    curdate = date;
    var info = $("#tab2");
    info.empty();
    info.append("<div id='preloader'></div>");
    $.post(baseurl + "/getEatingdiary/" + date + user_id, function(data) {
            try {
                obj = $.parseJSON(data);
                if (obj.status == "success") {
                    var myTemplate = $.templates("#eatingdaytmp");
                    var info = $("#tab2");
                    info.empty();
                    info.append("<input type='hidden' name='date' value='"+ curdate + "' />");
                    info.append("<input type='hidden' name='eatingprogram_id' value='"+ obj.data.id + "' />");
                    info.append("<input type='hidden' name='day_number' value='"+ obj.data.days[0].day_number + "' />");
                    /*for (var i = 0; i<routinesinfo[id].trainingprogramday.length; i++) {
                      var html = myTemplate.render(routinesinfo[id].trainingprogramday[i]);
                      info.append(html);
                    }*/
                    obj.data.date = curdate;
                    foods = obj.data.foods;
                    cureating = obj.data;
                    var html = myTemplate.render(obj.data);
                    info.append(html);
                    if (new Date(curdate) <= new Date())
                    info.append('<input type="button" id="startedit" value="Заполнить" onclick="changeeating(this, ' + obj.data.id + ')"/>');

                    /*$("div[id*='menu-']").css("height", "100px");
                    $("#menu-1").dotdotdot();
                    $('.contentleft').removeClass("active");
                    $('#left').find(".header").removeClass("active");*/
                    /*$('#'+id).parent().addClass("active");
                    $('#progname')[0].innerHTML = eatingprograms[id].name;*/
                    /*for (var key in obj.diaryday)
                        diary[key] = obj.diaryday[key];
                    calendars.clndr1.removeEvents(function(event) {
                        return event.id == id;
                    });
                    $(".calendar-day-" + curdate).click();
                    $(".create-event").css("display", "flex");*/
                } else {
                    if (obj.message != undefined) {
                        var info = $("#tab2");
                        info.empty();
                        info[0].innerHTML = "<div class='illegaldata'><H4>" + obj.message + "</H4></div>";
                    } else {
                        $("#alertmain .message").html("Не удалось загрузить информацию о питании.");
                        $("#alertmain").fadeTo(500, 1);
                        window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
                    }
                }
            } catch (e) {
                $("#alertmain .message").html("Не удалось загрузить информацию о питании.");
                $("#alertmain").fadeTo(500, 1);
                window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
            }
        })
        .fail(function() {
            $("#alertmain .message").html("Не удалось загрузить информацию о питании.");
            $("#alertmain").fadeTo(500, 1);
            window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
        });


    /*if (eatingprograms[id].active)
      $($('#progname')[0].parentNode).addClass("current")
    else 
      $($('#progname')[0].parentNode).removeClass("current")*/
    //console.log(html);
}
var totals = [];

function updateeating(obj) {
    var myTemplate = $.templates("#eatingdaytmp");
                    var info = $("#tab2");
                    info.empty();
                    info.append("<input type='hidden' name='date' value='"+ curdate + "' />");
                    info.append("<input type='hidden' name='eatingprogram_id' value='"+ obj.id + "' />");
                    info.append("<input type='hidden' name='day_number' value='"+ obj.days[0].day_number + "' />");
                    /*for (var i = 0; i<routinesinfo[id].trainingprogramday.length; i++) {
                      var html = myTemplate.render(routinesinfo[id].trainingprogramday[i]);
                      info.append(html);
                    }*/
                    obj.date = curdate;
                    foods = obj.foods;
                    cureating = obj;
                    var html = myTemplate.render(obj);
                    info.append(html);
                    if (new Date(curdate) <= new Date())
                        var btn = $('<input type="button" id="startedit" value="Заполнить" onclick="changeeating(this, ' + obj.id + ')"/>');
                    info.append(btn);
                changeeating(btn[0], obj.id);
}

function changeplaneating(id, ob) {
    if (globaltarget.events.length > 0)
        updateeating(trainingeatings[ob.value]);
    else
        updateeating(freeeatings[ob.value]);
    
}

function changeeating(ob, id) {
    $("#tab2").wrapInner("<form id='editfoodform'></form>");
    var options = "<select name='baseday' onchange='changeplaneating("+id+", this)'>";
    //for (var i = 0; i<diary[id].alternativedays.length; i++){
    //var i = 1;
    //for(var propt in diary[id].alternativedays){
    var eatingsalternative = null;
    if (globaltarget.events.length > 0)
        eatingsalternative = trainingeatings;
    else
        eatingsalternative = freeeatings;
    for (var i=0; i<eatingsalternative.length; i++){
        options+="<option value='"+i+"' "+(eatingsalternative[i].days[0].day_number == cureating.days[0].day_number?"selected":"")+">День "+(eatingsalternative[i].days[0].day_number + 1)+"</option>";
        //i++;
    }
    options+="</select>"
    $("#editfoodform>.day>.header.active.day.blue").append("<div class='changedayblock'>"+options+"</div>");
    
    var arr = $("td[data='cnt']");
    for (var i = 0; i < arr.length; i++) {
        var data = arr[i].innerHTML;
        arr[i].innerHTML = "<input type='number' class='diaryinput' \
            name='food[" + arr[i].parentElement.parentElement.parentElement.getAttribute("eating_id") + "][" + arr[i].getAttribute("foodid") + "][cnt]' value='" + data + "' \
            eating_id='"+arr[i].parentElement.parentElement.parentElement.getAttribute("eating_id")+"' \
            food_id='"+ arr[i].getAttribute("foodid") +"' \
            onchange='changeeatingcnt(this, " + arr[i].getAttribute("foodid") +")' \
            required \
        />"
        key = arr[i].parentElement.parentElement.parentElement.getAttribute("eating_id");
        totals[totals.length] = key;
        /*if (!(key in totals))
            totals[key] = {proteins : 0, fats: 0, hidrocarbonats: 0, colories: 0};
        totals.proteins += */
    }
    var arr = $("table.nutritiontable");
    arr.after("<div class='buttons'><input type='button' value='удалить продукт' onclick='deletefood(this)'/><input type='button' value='добавить продукт' onclick='openfoodmodal(this)'/></div>")
    /*for (var i = 0; i < arr.length; i++) {
        $(arr[i]).
    }*/
    /*var arr = $("td[data='proteins']");
    for (var i = 0; i < arr.length; i++) {
        var data = arr[i].innerHTML;
        arr[i].innerHTML = "<input class='diaryinput' name='excersice[" + arr[i].getAttribute("exid") + "][sets][" + (i + 1) + "][proteins]' value='" + data + "' />"
    }
    var arr = $("td[data='fats']");
    for (var i = 0; i < arr.length; i++) {
        var data = arr[i].innerHTML;
        arr[i].innerHTML = "<input class='diaryinput' name='excersice[" + arr[i].getAttribute("exid") + "][sets][" + (i + 1) + "][fats]' value='" + data + "' />"
    }
    var arr = $("td[data='hidrocarbonats']");
    for (var i = 0; i < arr.length; i++) {
        var data = arr[i].innerHTML;
        arr[i].innerHTML = "<input class='diaryinput' name='excersice[" + arr[i].getAttribute("exid") + "][sets][" + (i + 1) + "][hidrocarbonats]' value='" + data + "' />"
    }
    var arr = $("td[data='colories']");
    for (var i = 0; i < arr.length; i++) {
        var data = arr[i].innerHTML;
        arr[i].innerHTML = "<input class='diaryinput' name='excersice[" + arr[i].getAttribute("exid") + "][sets][" + (i + 1) + "][colories]' value='" + data + "' />"
    }*/
    /*var arr = $("div.c1");
    for (var i = 0; i < arr.length; i++) {
        $(arr[i]).append("<div class='buttons'><input type='button' value='Добавить подход' onclick='previous(this);'/><input type='button' value='Удалить подход' onclick='removeset(this);' /></div>")
    }*/
    var parent = ob.parentElement;
    $(ob).css("display", "none");
    //$(parent).append("<div class='buttons'><input type='button' data-target='#addExerciseDialog' data-toggle='modal' value='добавить упражнение'/></div>");
    $(parent).append("<div id='editbuttons' class='buttons'><input type='button' value='отмена' onclick='backeating(false)'/><input type='button' value='сохранить' onclick='saveeating(" + id + ")'/></div>");
}

function deletefood(ob) {
    var table = ob.parentElement.previousSibling;
    var tbody = $(table).find("tbody")[0];
    if (tbody.children.length>0) 
        $(tbody.children[tbody.children.length-1]).remove();

}

function openfoodmodal(ob) {
    //data-target='#addProductDialog' data-toggle='modal'
    $("#addProductDialog").modal("show");
    var table = ob.parentElement.previousSibling;
    var eating_id = table.getAttribute("eating_id");
    $("#addProductDialog .ok")[0].setAttribute("eating_id", eating_id);
}

function saveeating(id) {
    if ($("form")[0].checkValidity() != false) {
    var formData = serializeForm($("#editfoodform"));
    //console.log(formData);
    $.post("/diary/saveeating", formData).done(function(data) {
        try {
            //alert(data);
            obj = $.parseJSON(data);
            if (obj.status == "success") {
                //$(location).attr("href", "/redesign/auth/postlogin");
                diary[obj.id] = obj.day;
                backeating(true);
            } else {
                $("#alertmain .message").html("Не удалось сохранить результаты питания.");
                $("#alertmain").fadeTo(500, 1);
                window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
            }
        } catch (e) {
            $("#alertmain .message").html("Не удалось сохранить результаты питания.");
            $("#alertmain").fadeTo(500, 1);
            window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
        }
    });
    } else {
        $("#alertmain .message").html("Не все поля заполнены.");
        $("#alertmain").fadeTo(500, 1);
        window.setTimeout(function() { $("#alertmain").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
        location.href = "#";
location.href = "#";
    }
}

function addfood(ob) {
    var option = $("#tmpfood")[0].options[$("#tmpfood")[0].selectedIndex];
    var id = option.getAttribute("id");
    if (!(id in this.foods))
        this.foods[id] = { id: id, name: option.innerHTML, colories: parseFloat(option.getAttribute("colories")), hidrocarbonats: parseFloat(option.getAttribute("hidrocarbonats")), fats: parseFloat(option.getAttribute("fats")), proteins: parseFloat(option.getAttribute("proteins"))};
    var eating_id = ob.getAttribute("eating_id");
    var foodsel = $("table[eating_id='"+eating_id+"'] td[foodid='"+id+"']");
    if (foodsel.length > 0) {
        $("#alertaddfood .message").html("Такой продукт уже есть.");
        $("#alertaddfood").fadeTo(500, 1);
        window.setTimeout(function() { $("#alertaddfood").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
    } else {
        var body = $("table[eating_id='"+eating_id+"']>tbody");
        var foodrecord = '<tr> \
                <td style="width:25%">' + this.foods[id].name + '</td> \
                <td style="width:12%">0</td> \
                <td style="width:13%" data="cnt" foodid = "';
                foodrecord+=id;
                foodrecord+='"> \
                <input class="diaryinput" \
            name="food[' + eating_id + '][' + id + '][cnt]"" value=0 \
            eating_id="'+eating_id+'" \
            food_id="'+ id +'" \
            onchange="changeeatingcnt(this, ' + id + ')" \
            required \
                /> \
                </td> \
                <td id="proteins" style="width:5%">0</td> \
                <td style="width:5%" data="proteins" foodid = "';
                foodrecord+=id;
                foodrecord+='">0</td>';
                foodrecord+='<td id="fats" style="width:10%">0</td> \
                <td style="width:5%" data="fats" >0</td> \
                <td id="hidrocarbonats" style="width:5%">0</td> \
                <td style="width:5%" data="hidrocarbonats" >0</td> \
                <td id="colories" style="width:7%">0</td> \
                <td style="width:8%" data="colories" >0</td> \
              </tr>';
        $(body).append(foodrecord);
        $("#addProductDialog").modal("hide");
    }
}

function backeating(flag) {
    //$("#tab2").unwrap();
    if (flag) {
        var arr = $("td[data='cnt']");
        for (var i = 0; i < arr.length; i++) {
            var data = $(arr[i]).find("input")[0].value;
        
            arr[i].innerHTML = data;
        }
        var ob = $("#editbuttons");
        $(ob[0].previousSibling).css("display", "block");
        //$(ob).remove();
        $("div.buttons").remove();
        $("#editfoodform>.day>.header.active.day.blue>.changedayblock").remove();
        $("#tab2")[0].innerHTML = $("#tab2")[0].firstChild.innerHTML;
    } else {
        var myTemplate = $.templates("#eatingdaytmp");
        var info = $("#tab2");
        info.empty();
        info.append("<input type='hidden' name='date' value='"+ cureating.date + "' />");
        info.append("<input type='hidden' name='eatingprogram_id' value='"+ obj.data.id + "' />");
        info.append("<input type='hidden' name='day_number' value='"+ obj.data.days[0].day_number + "' />");
        var html = myTemplate.render(obj.data);
        info.append(html);
        info.append('<input type="button" id="startedit" value="Заполнить" onclick="changeeating(this, ' + obj.data.id + ')"/>');
    }
}

function changeeatingcnt(ob, foodid) {
    row = ob.parentElement.parentElement;
    $(row).find("td[data='proteins']")[0].innerHTML = ob.value/100 * foods[foodid].proteins;
    $(row).find("td[data='hidrocarbonats']")[0].innerHTML = ob.value/100 * foods[foodid].hidrocarbonats;
    $(row).find("td[data='fats']")[0].innerHTML = ob.value/100 * foods[foodid].fats;
    $(row).find("td[data='colories']")[0].innerHTML = ob.value/100 * foods[foodid].colories;
    var eating_id = ob.getAttribute("eating_id");
    var arr = $("input.diaryinput[eating_id='"+eating_id+"']");
    var summ = {proteins: 0, fats: 0, hidrocarbonats: 0, colories: 0};
    for (var i = 0; i < arr.length; i++) {
        var foodid = arr[i].getAttribute("food_id");
        summ.proteins += arr[i].value/100 * foods[foodid].proteins;
        summ.fats += arr[i].value/100 * foods[foodid].fats;
        summ.hidrocarbonats += arr[i].value/100 * foods[foodid].hidrocarbonats;
        summ.colories += arr[i].value/100 * foods[foodid].colories;
    }
    var totalline = $("tr[eating_id='"+eating_id+"']");
    $(totalline).find("#proteins")[0].innerHTML = summ.proteins.toFixed(2);
    $(totalline).find("#fats")[0].innerHTML = summ.fats.toFixed(2);
    $(totalline).find("#hidrocarbonats")[0].innerHTML = summ.hidrocarbonats.toFixed(2);
    $(totalline).find("#colories")[0].innerHTML = summ.colories.toFixed(2);
}