var x, i, j, selElmnt, a, b, c;
/*look for any elements with the class "custom-select":*/
x = document.getElementsByClassName("custom-select");
for (i = 0; i < x.length; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  /*for each element, create a new DIV that will act as the selected item:*/
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");

  s = $("<input id='select' class='search' type='text' value='' />")[0];
  /*s.addEventListener("click", function(e) {
    e.stopPropagation();
  });*/
  //a.innerHTML = "<input class='search' type='text' value='"+selElmnt.options[selElmnt.selectedIndex].innerHTML+"' />" + selElmnt.options[selElmnt.selectedIndex].innerHTML;
  //newdiv.appendChild
  a.appendChild(s);
  a.innerHTML += "<span class='value'>" + (selElmnt.selectedIndex!=-1?selElmnt.options[selElmnt.selectedIndex].innerHTML:"") + "</span>";
  x[i].appendChild(a);
  
  $(a).find("#select")[0].addEventListener("click", function(e) {
    e.stopPropagation();
  });
  $(a).find("#select")[0].addEventListener("keyup", function(e) {
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(), count = 0;
 
        // Loop through the comment list
        //$(".item").each(function(){
          $(".select-items>div:not(.group)").each(function(){
 
            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).fadeOut();
 
            // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show();
                count++;
            }

        });
  });
  /*for each element, create a new DIV that will contain the option list:*/
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 0; j < selElmnt.length; j++) {
    /*for each option in the original select element,
    create a new DIV that will act as an option item:*/
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    if (selElmnt.options[j].hasAttribute("group"))
      c.className += "group";
    else
    c.addEventListener("click", function(e) {
        /*when an item is clicked, update the original select box,
        and the selected item:*/
        var y, i, k, s, h;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        h = $(this.parentNode.previousSibling).find(".value")[0];
        //h = document.getElementById("value");
        for (i = 0; i < s.length; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            for (k = 0; k < y.length; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.parentNode.click();
    });
    b.appendChild(c);
  }
  $(b).perfectScrollbar();
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
      /*when the select box is clicked, close any other select boxes,
      and open/close the current select box:*/
      e.stopPropagation();
      if (!this.parentNode.parentNode.getElementsByTagName("select")[0].hasAttribute("disabled")) {
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
      }
  });
}

function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  for (i = 0; i < y.length; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < x.length; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);