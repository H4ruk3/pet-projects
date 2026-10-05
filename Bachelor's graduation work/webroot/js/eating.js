/***************************************
Класс, описывающий один продукт.
****************************************/
class Food {
  constructor(food) {
    this.basefood = food;
    this.name = food.name;
    this.cnt = 100;
    this.fats = food.fats;
    this.hidrocarbonats = food.hidrocarbonats;
    this.proteins = food.proteins;
    this.colories = food.colories;
    this.productid = food.id;
    this.daynum = food.day;
    this.eating = food.eating;
  }

  changecnt(cnt) {
    this.cnt = cnt;
    var deltas = {};
    deltas.fats = this.basefood.fats * (this.cnt / 100.0) - this.fats;
    deltas.hidrocarbonats = this.basefood.hidrocarbonats * (this.cnt / 100.0) - this.hidrocarbonats;
    deltas.proteins = this.basefood.proteins * (this.cnt / 100.0) - this.proteins;
    deltas.colories = this.basefood.colories * (this.cnt / 100.0) - this.colories;
    this.fats = this.basefood.fats * (this.cnt / 100.0);
    this.hidrocarbonats = this.basefood.hidrocarbonats * (this.cnt / 100.0);
    this.proteins = this.basefood.proteins * (this.cnt / 100.0);
    this.colories = this.basefood.colories * (this.cnt / 100.0);
    
    $(this.cntHTML)[0].value = this.cnt;
    $(this.fatsHTML)[0].innerHTML = this.fats.toFixed(2);
    $(this.hidrocarbonatsHTML)[0].innerHTML = this.hidrocarbonats.toFixed(2);
    $(this.proteinsHTML)[0].innerHTML = this.proteins.toFixed(2);
    $(this.coloriesHTML)[0].innerHTML = this.colories.toFixed(2);
    this.updatetotal(deltas);
  }

  delete() {
    $(this.foodHTML).remove();
    this.deletefood(this.num);
  }

  updatenum(num) {
    this.num = num;
    $(this.cntHTML[0]).attr("name", "foods[" + this.daynum + "]["+ this.eating +"]["+ this.num +"][1]");
    $(this.idHTML[0]).attr("name", "foods[" + this.daynum + "]["+ this.eating +"]["+ this.num +"][0]");

  }

  setEating(id) {
    this.eating = id;
    $(this.cntHTML[0]).attr("name", "foods[" + this.daynum + "]["+ this.eating +"]["+ this.num +"][1]");
    $(this.idHTML[0]).attr("name", "foods[" + this.daynum + "]["+ this.eating +"]["+ this.num +"][0]");
  }

  render(ob) {
    var myTemplate = $.templates("#onefoodtmp");
    var obj = {name: this.name, fats : this.fats.toFixed(2), hidrocarbonats: this.hidrocarbonats.toFixed(2), proteins : this.proteins.toFixed(2), colories: this.colories.toFixed(2), day: this.daynum, eating: this.eating, number: this.num, productid : this.productid, cnt: this.cnt };
    var html = myTemplate.render(obj);
    this.foodHTML = $(html);
    $(ob).append(this.foodHTML);
    this.cntHTML = $($(this.foodHTML).find("#cnt")[0]);
    this.idHTML = $($(this.foodHTML).find("#id")[0]);
    this.fatsHTML = $(this.foodHTML).find("#fats")[0];
    this.hidrocarbonatsHTML = $(this.foodHTML).find("#hidrocarbonats")[0];
    this.proteinsHTML = $(this.foodHTML).find("#proteins")[0];
    this.coloriesHTML = $(this.foodHTML).find("#colories")[0];
    this.deleteHTML = $(this.foodHTML).find("#delete")[0];
    var obj = this;
    $(this.cntHTML[0]).change(function(){
      obj.changecnt(this.value);
    })
    $(this.cntHTML[0]).on('keypress', function(e) {
      if (e.which == 13) {
        this.blur();
      }
      return e.which !== 13;
    });
    $(this.deleteHTML)[0].onclick = function() {
      obj.delete();
    }
  }
}

/************************************************************
Класс, описывающий один приём пищи.
************************************************************/
class Eating {
  constructor(id) {
    this.foods = [];
    this.fats = 0;
    this.hidrocarbonats = 0;
    this.proteins = 0;
    this.colories = 0;
    this.id = id;
  }

  setId(id) {
    this.id = id;
    $($(this.tablehtml)[0]).attr("id", ''+this.daynum + this.id);
    for(var i = 0; i<this.foods.length; i++){
      this.foods[i].setEating(id);
    }
  }

  dropcallbacks() {
    var currentobject = this;
    return {
    over: function(event, ui) { 
      //var el = $(this).parent().prev().find("div[data="+ui.draggable[0].id+"]");
      //if (el.length > 0) {
      if (!currentobject.checkfood(ui.draggable[0].id)) {
        $(this).css("background", "#ff0000") 
        $(this).css("color", "#ffffff")
        this.innerHTML = "Данный продукт уже добавлен в текущий приём пищи."
      }
      else {  
        $(this).css("background", "#ff9800");
       this.innerHTML = "" 
        }
         },
    out: function(event, ui) { $(this).css("background", "#ffffff");
                                $(this).css("color", "#333")
                              this.innerHTML = "Перетащите сюда продукты, что бы добавить их на этот день"; },
    drop: function(event, ui) {
      //var myTemplate = $.templates("#onefoodtmp");
      if (currentobject.checkfood(ui.draggable[0].id)) {
        var obj = foods[ui.draggable[0].id];
        obj.eating = currentobject.id;
        obj.day = currentobject.daynum;
        var food = new Food(obj);
        currentobject.addfood(food);
        //var html = myTemplate.render(obj);
        var ob = $(this).parent().prev().find("tbody")[0];
        food.render(ob);
      }
      //
      $(this).css("background", "#ffffff");
      $(this).css("color", "#333");
      this.innerHTML = "Перетащите сюда продукты, что бы добавить их на этот день.";
    }
  }
  }

  render(ob) {
    var myTemplate = $.templates("#eatingtmp");
    var html = myTemplate.render([{eating : (this.num + 1) + ' приём пищи', tableid : ''+this.daynum + this.id}]);
    this.eatingHTML = $(html);
    $(ob).find(".exersicesblock").append(this.eatingHTML);
    this.tablehtml = $($(this.eatingHTML)[2]);
    this.totalfatshtml = $($(this.eatingHTML).find(".total #fats")[0]);
    this.totalproteinshtml = $($(this.eatingHTML).find(".total #proteins")[0]);
    this.totalhidrocarbonatshtml = $($(this.eatingHTML).find(".total #hidrocarbonats")[0]);
    this.totalcolorieshtml = $($(this.eatingHTML).find(".total #colories")[0]);
    $($(this.eatingHTML).find(".droparea")[0]).droppable(this.dropcallbacks());
  }

  addcustomfood(obj) {
    obj.food.eating = this.id;
    obj.food.day = this.daynum;
    obj.food.fats = (100.0 / obj.cnt) * obj.food.fats;
    obj.food.proteins = (100.0 / obj.cnt) * obj.food.proteins;
    obj.food.hidrocarbonats = (100.0 / obj.cnt) * obj.food.hidrocarbonats;
    obj.food.colories = (100.0 / obj.cnt) * obj.food.colories;
    var food = new Food(obj.food);
    this.addfood(food)
    var ob = $("#"+this.daynum + this.id).find("tbody")[0];
    food.render(ob);
    food.changecnt(obj.cnt);
  }

  delete() {
    this.eatingHTML.remove();
  }

  checkfood(id){
    for (var i = 0; i<this.foods.length; i++)
      if (this.foods[i].productid == id)
        return false;
    return true;
  }

  addfood(food) {
    this.foods[this.foods.length] = food;
    food.num = this.foods.length-1;
    var obj = this;
    food.updatetotal = function(ob) {
        obj.updatetotal(ob);  
    }
    food.deletefood = function(ob) {
      obj.deletefood(ob);
    }
    
    this.fats += food.fats;
    this.hidrocarbonats += food.hidrocarbonats;
    this.proteins += food.proteins;
    this.colories += food.colories;
    this.totalfatshtml[0].innerHTML = this.fats;
    this.totalhidrocarbonatshtml[0].innerHTML = this.hidrocarbonats;
    this.totalproteinshtml[0].innerHTML = this.proteins;
    this.totalcolorieshtml[0].innerHTML = this.colories;
    this.updatedaytotal();
  }

  updatetotal(deltas) {
    this.fats += deltas.fats;
    this.hidrocarbonats += deltas.hidrocarbonats;
    this.proteins += deltas.proteins;
    this.colories += deltas.colories;
    this.totalfatshtml[0].innerHTML = this.fats.toFixed(2);
    this.totalhidrocarbonatshtml[0].innerHTML = this.hidrocarbonats.toFixed(2);
    this.totalproteinshtml[0].innerHTML = this.proteins.toFixed(2);
    this.totalcolorieshtml[0].innerHTML = this.colories.toFixed(2);
    this.updatedaytotal();
  }

  deletefood(num) {
    for (var i = num+1; i<this.foods.length; i++)
      this.foods[i].updatenum(i-1);
    var deltas = {};
    deltas.fats = -this.foods[num].fats;
    deltas.hidrocarbonats = -this.foods[num].hidrocarbonats;
    deltas.proteins = -this.foods[num].proteins;
    deltas.colories = -this.foods[num].colories;
    this.updatetotal(deltas);
    this.foods.splice(num,1);
  }
}

/************************************************************
Класс, описывающий один день программы питания.
************************************************************/
class Day {
  constructor(id) {
    this.eatings = [];
    this.routinedayid = 0;
    var curroutine = routines[$("#routine")[0].value];
    if (curroutine[0].eatCount == 0)
      curroutine[0].eatCount = curroutine[0].eating.length;
    for(var i=0; i<curroutine[0].eatCount; i++) {
      this.addeating(new Eating(curroutine[0].eating[i].id));
    }
    
  }

  render() {
    var myTemplate = $.templates("#daytmp");
    var html = myTemplate.render([{id : this.num+1, num : this.num+1}]);
    this.dayHTML = $(html);
    
    var ob = this;
    $(this.dayHTML).find("#delbtn")[0].onclick = function() {
      ob.delete(ob.num);
    }

    this.daytype = this.dayHTML.find("#daytype")[0];
    var changeroutineday = this.changeroutineday;
    var ob = this;
    $(this.daytype).change( function() {
      var optionSelected = $(this).find("option:selected");
     var valueSelected  = optionSelected.val();
      changeroutineday(ob, valueSelected);
    }); 

    $(".days").append(this.dayHTML);
    //this.dayname = $(html).find("#dayname")[0];
    for (var eating of this.eatings) {
      eating.render(this.dayHTML);
    }
    var myTemplate = $.templates("#totaltmp");
    var html = myTemplate.render([{tableid : ''+this.daynum}]);
    this.eatingHTML = $(html);
    $(this.dayHTML).append(this.eatingHTML);
    this.tablehtml = $($(this.eatingHTML)[2]);
    this.totalfatshtml = $($(this.eatingHTML).find(".total #fats")[0]);
    this.totalproteinshtml = $($(this.eatingHTML).find(".total #proteins")[0]);
    this.totalhidrocarbonatshtml = $($(this.eatingHTML).find(".total #hidrocarbonats")[0]);
    this.totalcolorieshtml = $($(this.eatingHTML).find(".total #colories")[0]);
  }

  updatetotal() {
    var totals = {'totalfats': 0, 'totalproteins': 0, 'totalhidrocarbonats': 0, 'totalcolories': 0}
    for (var eating of this.eatings) {
      totals.totalfats += eating.fats;
      totals.totalproteins += eating.proteins;
      totals.totalhidrocarbonats += eating.hidrocarbonats;
      totals.totalcolories += eating.colories;
    }
    this.totalfatshtml[0].innerHTML = totals.totalfats.toFixed(2);
    this.totalhidrocarbonatshtml[0].innerHTML = totals.totalhidrocarbonats.toFixed(2);
    this.totalproteinshtml[0].innerHTML = totals.totalproteins.toFixed(2);
    this.totalcolorieshtml[0].innerHTML = totals.totalcolories.toFixed(2);
  }

  addfood(eating, food) {
    this.eatings[eating].addcustomfood(food)
  }

  addeating(eating) {
    this.eatings[this.eatings.length] = eating;
    eating.num = this.eatings.length-1;
    eating.daynum = this.num;
    var ob = this;
    eating.updatedaytotal = function() {
      ob.updatetotal();
    }
    //eating.delete = this.deleteeating;
    //eating.render(this.dayHTML);
  }

  deleteeating(num) {
    this.eatings[num].delete();
    this.eatings.splice(num,1);
  }

  updateroutine(id) {
    var curroutine = routines[id];
    if (curroutine[this.routinedayid].eatCount < this.eatings.length) {
      for (var i = curroutine[this.routinedayid].eatCount; i<this.eatings.length; i++){
        this.deleteeating(i);
      }
    }
    if (curroutine[this.routinedayid].eatCount > this.eatings.length) {
      for (var i = this.eatings.length; i<curroutine[this.routinedayid].eatCount; i++){
        var eating = new Eating(curroutine[this.routinedayid].eating[i].id)
        this.addeating(eating);
        eating.render(this.dayHTML);
      }
    }
    for (var i = 0; i<curroutine[this.routinedayid].eatCount; i++){
      this.eatings[i].setId(curroutine[this.routinedayid].eating[i].id);
    }
  }
  changeroutineday(ob, id) {
    if (ob.routinedayid != id) {
    ob.routinedayid = id;
    var curroutine = routines[$("#routine")[0].value];
    if (curroutine[ob.routinedayid].eatCount < ob.eatings.length) {
      for (var i = curroutine[ob.routinedayid].eatCount; i<ob.eatings.length;){
        ob.deleteeating(i);
      }
    }
    if (curroutine[ob.routinedayid].eatCount > ob.eatings.length) {
      for (var i = ob.eatings.length; i<curroutine[ob.routinedayid].eatCount; i++){
        var eating = new Eating(curroutine[ob.routinedayid].eating[i].id)
        ob.addeating(eating);
        eating.render(ob.dayHTML);
      }
    }
    for(var i = 0; i < ob.eatings.length; i++) {
      ob.eatings[i].setId(curroutine[ob.routinedayid].eating[i].id);
    }
    $(ob.daytype).find('option[value="'+id+'"]').attr("selected", "selected");
    
  }
  }

  delete() {
    $(this.dayHTML).remove();
    var buttons = $("#buttons").children();
    $(buttons[buttons.length-2]).remove();
    this.parent.deleteday(this.num);
    //this.trigger("deleteday");
  }

  updateid(id) {
    //$(this.dayname).html("gggggg");
    $(this.dayHTML).find("#dayname").html("День " + (id + 1));
    this.num = id; 
  }
  updateitingnum() {
    for (var eating of this.eatings) {
      eating.daynum = this.num;
    }
  }
}

/************************************************************
Класс, описывающий программу питания.
************************************************************/
class Nutrition {
  constructor() {
    this.days=[];
    var day = new Day(1);
    this.addday(day);
    //this.days[] = day;
    //day.num = 0;
  };

  setname(name) {
    $("#name")[0].value = name;
  }

  addfood(day, eating, food) {
    if (!(this.days.length >= day)) {
      var dayob = new Day(day+1);
      this.addday(dayob);
    }
    this.days[day].addfood(eating, food);
  }

  changeroutineday(daynum, id) {
    this.days[daynum].changeroutineday(this.days[daynum], id);
  }

  updateroutine(id) {
    for(var i=0; i<this.days.length; i++) {
      this.days[i].updateroutine(id);
    }
  }

  addday(day) {
    this.days[this.days.length] = day;
    day.num = this.days.length-1;
    day.updateitingnum();
    //day.deleteday = this.deleteday;
    day.parent = this;
    day.render();
  }

  deleteday(num) {
    //this.days[num].delete();
    for (var i = num+1; i<this.days.length; i++)
      this.days[i].updateid(i-1);
    this.days.splice(num,1);

  }
}

/************************************************************
Добавление дня в программу питания
************************************************************/
function addday(el) {
  var button = "<button type='button' class=' btn btn-default daybtn' onclick='changeday(this)' data='" + $(el)[0].parentNode.children.length +"'>День " + $(el)[0].parentNode.children.length + "</button>";
  $(el).before(button);
  //$("#buttons button").removeClass("active");
  //changeday(button);
  //$(el.previousSibling).addClass("active");
  var day = new Day();
  nutrition.addday(day)
  changeday(el.previousSibling);
}

/************************************************************
Удаление дня из программы питания.
************************************************************/
 function removeday(id) {
      var elid = $("#"+id)[0].id;
      nutrition.deleteday(id);
      //$(id).remove();
      var buttons = $("#buttons").children();
      $(buttons[buttons.length-2]).remove();
        
        $.each($(".day"), function (i, day) {
            if (day.id>elid) {
              day.id = day.id-1;
              $(day).find("h3")[0].innerHTML = "День "+day.id;
              //changeexday("#"+day.id, day.id);
            }
        });
      return false;
    }

function changeroutine(obj) {
  nutrition.updateroutine(obj.value);
}

/************************************************************
Выводить дни списком
************************************************************/
function list(ob) {
  $(".righttoolbar").removeClass("active")
  $(ob).addClass("active"); 
  $(".day").attr("style", "display: block");
  isList = true;
  //$("#buttongroup").attr("style", "display: none");
}

/************************************************************
Выводить дни по одному
************************************************************/
function hide(ob) {
  $(".righttoolbar").removeClass("active")
  $(ob).addClass("active"); 
  $(".day").attr("style", "display: none");
  var dat = $($(".daybtn.active")[0]).attr("data"); 
  $('#'+dat).attr("style", "display:block");
  isList = false;
  //$("#buttongroup").attr("style", "display: block");
}

/************************************************************
Отображение выбранного дня
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

var nutrition;
var isList = true;

/************************************************************
Инициализация начальных параметров.
************************************************************/
function init() {
nutrition = new Nutrition();

  $('form input').on('keypress', function(e) {
      return e.which !== 13;
    });

    $('.drugel').draggable({ appendTo: 'body', /*helper: 'clone',*/
      helper: function () {
        return $(this).clone().css({width: $(this).width(), backgroundColor: '#dae3ea'});
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

    $("#filter").keyup(function(){
        var searchString = $(this).val();
        //userslist.search(searchString);
        $("tr.drugel").each(function(){
 
            // If the list item does not contain the text phrase fade it out
            if ($(this.children[0]).text().search(new RegExp(searchString, "i")) < 0) {
                $(this).fadeOut();
 
            // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show();
                //count++;
            }
        });
    });

    hide($("#left .row.box.cap a")[1]);
}