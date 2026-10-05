
function defineSomatotype()
{
	var lenLeg = $("#lenLeg").val();
    var widthShoulder = $("#widthShoulder").val();
    var girthWrist = $("#girthWrist").val();
    var widthPlev = $("#widthPlev").val();
    var longArm = $("#longArm").val();
    var growth = Number($("#growth").val())/100.0;

    if (lenLeg == '' || widthShoulder == '' || girthWrist == '' || widthPlev == '' || longArm == '' || growth == '')
        return -1;

    var sum = 0;

    if (lenLeg / growth > 54)
    	sum += 1;
    else
    if (lenLeg / growth < 50)
    	sum += 3;
    else
    	sum += 2;

    if (widthShoulder / growth > 24.5)
    	sum += 3;
    else
    if (widthShoulder / growth < 21.5)
    	sum += 1;
    else
    	sum += 2;

    if (widthPlev / growth > 17.5)
    	sum += 3;
    else
    if (widthPlev / growth < 16)
    	sum += 1;
    else
    	sum += 2;

    if (longArm / growth > 46.5)
    	sum += 1;
    else
    if (longArm / growth < 42.5)
    	sum += 3;
    else
    	sum += 2;

    var sex = $("input[name=sex]:checked").val();

    if (sex == 'male') {
    if (girthWrist > 20)
    	sum += 3;
    else
    if (girthWrist < 17.5)
    	sum += 1;
    else
    	sum += 2;
    } else {
        if (girthWrist > 17)
        sum += 3;
    else
    if (girthWrist < 15)
        sum += 1;
    else
        sum += 2;
    }

    var type = -1;

    if (sum <= 7)
    	type = 1;
    else
    if (sum <= 12)
    	type = 2;
    else
    	type = 3;

 /*  $("#soma :selected").removeAttr("selected"); 
   $("#soma :nth-child("+(type+1)+")").attr('selected', 'selected');
   $("#soma :nth-child("+(type+1)+")").prop('selected', true);
   */
   return type;
/*   $("#somatotype:checked").removeAttr("checked");
   //$("#somatotypes:nth-child("+(type*2)+")").attr('checked', 'checked');
   //$("#somatotypes:nth-child("+(type*2)+")").prop('checked', true);
   $($("#somatotypes")[0].children[type*2]).attr('checked', 'checked');
   $($("#somatotypes")[0].children[type*2]).prop('checked', true);

   console.log(sum);*/
   //console.log(sum);
   //console.log(lenLeg / growth);
   //console.log(widthShoulder / growth);
   //console.log(widthPlev / growth);
   //console.log(longArm / growth);

    //var sex = $("input[name=sex]:checked").val();
}