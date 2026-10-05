/**************************************
 * Фикс кропера в Edge
 */
if (!HTMLCanvasElement.prototype.toBlob) {
    Object.defineProperty(HTMLCanvasElement.prototype, 'toBlob', {
        value: function(callback, type, quality) {
            var canvas = this;
            setTimeout(function() {

                var binStr = atob(canvas.toDataURL(type, quality).split(',')[1]),
                    len = binStr.length,
                    arr = new Uint8Array(len);

                for (var i = 0; i < len; i++) {
                    arr[i] = binStr.charCodeAt(i);
                }

                callback(new Blob([arr], { type: type || 'image/png' }));

            });
        }
    });
}

/***************************************
 * Переключение между формами вычисления соматотипа 
 */
function hideel1() {
    $('#form1').collapse('hide');
}

function hideel2() {
    $('#form2').collapse('hide');
    var type = defineSomatotype();
    if (type > 0) {
    $("#somatotype:checked").removeAttr("checked");
    //$("#somatotypes:nth-child("+(type*2)+")").attr('checked', 'checked');
    //$("#somatotypes:nth-child("+(type*2)+")").prop('checked', true);
    $($("#somatotypes")[0].children[type * 2]).attr('checked', 'checked');
    $($("#somatotypes")[0].children[type * 2]).prop('checked', true);
    }
}

/**
 * Перемещение подсказки при скролле 
 */
function onScroll(e) {
    $(".helpblock").css("top", window.scrollY);
}
document.addEventListener('scroll', onScroll);

/**
 * Открытие диалога загрузки файла
 */
function openFileOption() {
    document.getElementById("file_upload").click();
}

/**
 * Вспомогательная функция для работы с файлами. Считывает данные о загруженном файле. Инициализирует кропер 
 */
function readURL(input) {
    if (input.files && input.files[0]) {
        console.log(input.files[0]);
        if (input.files[0].size > 5000000) {
            $('#image_error')[0].innerHTML='Привышен максимальный допустимый размер файла в 5 мб.'
            //input.files.splice(0,1);
            return false;
        }
        expr = /image/;
        if (input.files[0].type.search(expr) == -1) {
            $('#image_error')[0].innerHTML='Загруженный файл не является изображением.'
            //input.files.splice(0,1);
            return false;
        }
        $('#image_error')[0].innerHTML=""
        $("#file_info")[0].value = input.files[0].name;
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#blah').attr('src', e.target.result)
        };
        reader.readAsDataURL(input.files[0]);
        setTimeout(initCropper, 1000);
    }
}
/**
 * Инициализация кропера
 */
var cropper;

function initCropper() {
    //console.log("Came here")
    var image = document.getElementById('blah');
    cropper = new Cropper(image, {
        aspectRatio: 1 / 1,
        crop: function(e) {
            console.log(e.detail.x);
            console.log(e.detail.y);
        }
    });
}

/**
 * Валидация отдельных полей формы
 */
function validate() {
    $("div.radiogroup").each(function() {
        if ($(this).find("input:radio:checked").length == 0) {
            $(this).find("input:radio")[0].setCustomValidity('Не выбрано значение');
        } else {
            $(this).find("input:radio")[0].setCustomValidity('');
        }
    });

    //Проверка даты
    var time1 = new Date()
    var correct = true;
    var valid = true
    if ($('#date1')[0].value.match(/\d\d.\d\d.\d\d\d\d/) == null) {
        valid = false;
        correct = false;
    }
    if (!valid) {
        $('#date1')[0].setCustomValidity('Введено неверное значение даты');
        $('#date1')[0].validationMessage = "Введено неверное значение даты. Формат времени dd.MM.yyyy";
    } else
        $('#date1')[0].setCustomValidity('');

}

/**
 * Отправка запроса на сохранение профиля
 */
function saveprofile(url) {
    if (!$("form")[0].checkValidity()) {
        return false;
    } else {
        if (isCreate && cropper != undefined)
            saveprofilewithimage();
        else
            saveprofilewithoutimage(url);
    }
    return false;
}

/**
 * Сохранение профиля без картинки
 */
function saveprofilewithoutimage(url) {
    var formData = new FormData();
    jQuery.each(jQuery('input[type="text"], input[type="hidden"], input[type="number"]'), function(i, input) {
        formData.append($(input).attr("name"), $(input)[0].value);
    });
    jQuery.each(jQuery('input:checked'), function(i, input) {
        formData.append($(input).attr("name"), $(input)[0].value);
    });
    formData.append("activity", $('#activity').val());
    var options = {
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            //alert(data);
            try {
                obj = $.parseJSON(data);
                if (obj.status == "success")
                    $(location).attr("href", isAssign != undefined && isAssign == true ? "/user/userinfo/" + $("#user1id")[0].value : "/profile");
                else {
                    $("#message").html(obj.message);
                    $(".alert").fadeTo(500, 1);
                    window.setTimeout(function() { $(".alert").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
                }
            } catch (e) {
                $("#message").html("Введены неверные данные ");
                $(".alert").fadeTo(500, 1);
                window.setTimeout(function() { $(".alert").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
            }
            //console.log('Upload success');
        },
        error: function(data) {
            //alert(data);
            try {
                obj = $.parseJSON(data);
                if (obj.status == "success")
                    $(location).attr("href", isAssign != undefined && isAssign == true ? "/user/userinfo/" + $("#user1")[0].id : "/profile");

                else {
                    $("#message").html(obj.message);
                    $(".alert").fadeTo(500, 1);
                    window.setTimeout(function() { $(".alert").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
                }
            } catch (e) {
                $("#message").html("Введены неверные данные ");
                $(".alert").fadeTo(500, 1);
                window.setTimeout(function() { $(".alert").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
            }
            //console.log('Upload error');
        }
    };
    if (url != "")
        options.url = url;
    $.ajax('', options);
}

/**
 * Сохранение профиля с аватаром 
 */
function saveprofilewithimage(url) {
    cropper.getCroppedCanvas().toBlob(function(blob) {
        var formData = new FormData();
        formData.append('avatar', blob);
        jQuery.each(jQuery('input[type="text"], input[type="number"'), function(i, input) {
            formData.append($(input).attr("name"), $(input)[0].value);
        });
        jQuery.each(jQuery('input:checked'), function(i, input) {
            formData.append($(input).attr("name"), $(input)[0].value);
        });
        // Use `jQuery.ajax` method
        $.ajax('', {
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                //alert(data);
                try {
                    obj = $.parseJSON(data);
                    if (obj.status == "success")
                        $(location).attr("href", "/profile");
                    else {
                        //alert(data);
                        $("#message").html(obj.message);
                        $(".alert").fadeTo(500, 1);
                        window.setTimeout(function() { $(".alert").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
                    }
                } catch (e) {
                    $("#message").html("Введены неверные данные ");
                    $(".alert").fadeTo(500, 1);
                    window.setTimeout(function() { $(".alert").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
                }
                //alert(data);
                //}
                //console.log('Upload success');
            },
            error: function(data) {
                //alert(data);
                obj = $.parseJSON(data);
                if (obj.status == "success")
                    $(location).attr("href", "/profile");

                else {
                    alert(data);
                }
                console.log('Upload error');
            }
        });
    });
}

/************************************************************
Показ подсказок
************************************************************/   
function showhelp(block, ob) {
    var title = $('.edithelpblock>div>H2')[0];
    var content = $('.edithelpblock .form')[0];    
    if (block == "personinfo") {
        $(title).html("Личные данные");
        $(content).html("<p>Запоните свои личные данные для учёта индивидуальных особенностей при составлении программы тренировок и питания.</p>");
    } else if (block == "somatype") {
        $(title).html("Определение телосложения");
        $(content).html("<p>Существует три типа различного телосложения тела человека:</p> \
<ul> \
<li>Эктоморф, энергичный, худой, быстрый;</li> \
<li>Эндоморф, полный, широкий и медлительный;</li> \
<li>Мезоморф, достаточно мускулистый, средний.</li> \
 \
<p>Все эти типы телосложения сильно отличаются друг от друга, скоростью обменных процессов в организме.</p> \
 \
<p>В организме эктоморфа процессы протекают стремительно. Лишний вес не грозит человеку подобного телосложения. У него длинные кости, худое тело, маленький запас жира и очень тощие мускулы. Набор мышечной массы дается ему с трудом, но если ему это удается он выглядит очень стройно, эстетично, благодаря полному отсутствию жира и узким костям.</p> \
 \
<p>Эндоморф, напротив, с легкостью набирает большой вес. Метаболизм – обменные процессы, то есть «сгорание» жиров, белков и углеводов в организме у него происходят медленно. К тому же, энергетически потребности тоже небольшие. Поэтому тело у них мягкое, рыхлое, на лицо, избыток жировой массы. Люди такого типа телосложения обычно быстро набирают мышечную массу. Но, к сожалению, она мягкая и рыхлая. Основная проблема для данного типа людей – лишняя жировая масса, от которой им крайне трудно избавиться.</p> \
 \
<p>Мезоморфа отличает от остальных, развитая мускулатура. Мышцы объемные, кости толстые и широкие. Люди такого типа, без особых проблем, набирают мышечную массу, порой даже не замечая, того, что выглядят слишком квадратными и крепкими.</p> \
 \
<p>Вышеизложенная информация очень важна, при составлении индивидуального эффективного плана питания. Каждый тип телосложения по-разному реагирует на состав и режим питания. </p>");
    }
    $('.header').removeClass('active');
    if (ob != null) {
        $(ob.parentElement.parentElement).addClass("active");
    }
}
/**
 * Инициализация календаря
 */
$(function() {
    showhelp("personinfo");
    $("#datetimepicker1").datetimepicker({ pickTime: false, language: "ru" });
    $("input[date]").keypress(function(e) {
        var key = e.keyCode || e.which;
        if ((key >= 48 && key < 58 || (($(this)[0].value.trim().length == 2 || $(this)[0].value.trim().length == 5) && key == 46)) && $(this)[0].value.trim().length < 10) {
            if ($(this)[0].value.trim().length == 0 && (key < 48 || key > 51))
                return false;
            if ($(this)[0].value.trim().length == 1) {
                var time = $(this)[0].value + String.fromCharCode(e.which);
                if (parseInt(time) > 31)
                    return false;
            }
            if ($(this)[0].value.trim().length == 3 && (key < 48 || key > 49))
                return false;
            if ($(this)[0].value.trim().length == 4) {
                var time = $(this)[0].value[3] + String.fromCharCode(e.which);
                if (parseInt(time) > 12 || parseInt(time) < 1)
                    return false;
            }
            if ($(this)[0].value.trim().length == 6 && (key < 49 || key > 51))
                return false;
            if (($(this)[0].value.trim().length == 2 || $(this)[0].value.trim().length == 5) && key != 46) {
                if ($(this)[0].value.trim().length == 5 && (key < 49 || key > 51))
                    return false;
                if ($(this)[0].value.trim().length == 2 && (key < 48 || key > 49))
                    return false;
                $(this)[0].value += '.';
            }
        } else
            return false;
    });
});