function defineKalories()
{
	var growth = $("#growth").val();
    var weight = $("#weight").val();
    var age = $("#age").val();
    var body = $("#body").val();
    var active = $("#activity>option:selected").val();
    //var longArm = $("#longArm").val();
    //var growth = Number($("#growth").val())/100.0;
    var sex = $("input[name=sex]:checked").val();
    var aimeTraining = $("input[name=aimTrain]:checked").val();
    var somatotype = $("input[name=somatotype]:checked").val();

    var VOO = 9.99*weight + 6.25*growth-4.92*age;
    if (sex == 'male')
    	VOO -= 161;
    else
    	VOO += 5;
    VOO = VOO * active;

    var BMR;
    if (sex == 'male')
    	BMR = 88.362 + 13.397*weight + 4.799*growth - 5.677 * age;
    else
    	BMR = 447.593 + (9.247 * weight) + (3.098 * growth) - (4.330 * age);
    BMR = BMR * active;

    var hasWaist = body !== "" && body !== null && typeof body !== "undefined" && !isNaN(parseFloat(body)) && parseFloat(body) > 0;

    var KKAL;
    if (hasWaist) {
        // Талия указана — используем формулу Кетча-Макардла (через % жира)
        var LBM;
        var FAT;
        if (sex == 'male')
        	FAT = 100*(( 4.15 * body)/2.54 - (0.082 * weight)/0.453 - 98.42 ) / (weight/0.453);
        else
        	FAT = 100*(( 4.15 * body)/2.54 - (0.082 * weight)/0.453 - 76.76 ) / (weight/0.453);
        LBM = weight*(100-FAT)/100;
        var KMA = 370 + 21.6 * LBM;
        KMA = KMA * active;

        KKAL = KMA;
    } else {
        // Талия не указана — используем формулу Харриса-Бенедикта
        KKAL = BMR;
    }
    if (aimeTraining == "1")
    	KKAL -= 500;
    else if (aimeTraining == "2")
    	KKAL += 500;
    switch (somatotype) {
    	case "1":
    		var minBel = KKAL * 0.2;
    		var maxBel = KKAL * 0.3;
    		var Bel = ((maxBel + minBel) / 2) * 0.96;
    		var BelGram = Bel / 4;
    		var minFat = KKAL * 0.2;
    		var maxFat = KKAL * 0.3;
    		var Fat = ((maxFat + minFat) / 2) * 0.96;
    		var FatGram = Fat / 9;
    		var minProt = KKAL * 0.5;
    		var maxProt = KKAL * 0.6;
    		var Prot = ((maxProt + minProt) / 2) * 0.96;
    		var ProtGram = Prot / 4;
    		var result = [{kalories: KKAL}, {bel: Bel, belgram: BelGram}, {fat: Fat, fatgram: FatGram}, {prot: Prot, protgram: ProtGram}];
    		return result;
    	case "2":
    		var minBel = KKAL * 0.3;
    		var maxBel = KKAL * 0.4;
    		var Bel = ((maxBel + minBel) / 2) * 0.96;
    		var BelGram = Bel / 4;
    		var minFat = KKAL * 0.1;
    		var maxFat = KKAL * 0.2;
    		var Fat = ((maxFat + minFat) / 2) * 0.96;
    		var FatGram = Fat / 9;
    		var minProt = KKAL * 0.4;
    		var maxProt = KKAL * 0.5;
    		var Prot = ((maxProt + minProt) / 2) * 0.96;
    		var ProtGram = Prot / 4;
    		var result = [{kalories: KKAL}, {bel: Bel, belgram: BelGram}, {fat: Fat, fatgram: FatGram}, {prot: Prot, protgram: ProtGram}];
    		return result;
    	case "3":
    		var minBel = KKAL * 0.4;
    		var maxBel = KKAL * 0.5;
    		var Bel = ((maxBel + minBel) / 2) * 0.96;
    		var BelGram = Bel / 4;
    		var minFat = KKAL * 0.1;
    		var maxFat = KKAL * 0.15;
    		var Fat = ((maxFat + minFat) / 2) * 0.96;
    		var FatGram = Fat / 9;
    		var minProt = KKAL * 0.3;
    		var maxProt = KKAL * 0.4;
    		var Prot = ((maxProt + minProt) / 2) * 0.96;
    		var ProtGram = Prot / 4;
    		var result = [{kalories: KKAL}, {bel: Bel, belgram: BelGram}, {fat: Fat, fatgram: FatGram}, {prot: Prot, protgram: ProtGram}];
    		return result;
    }
}