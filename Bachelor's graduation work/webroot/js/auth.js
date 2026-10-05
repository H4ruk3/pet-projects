/*********************************
Отправка зпроса на регистрацию нового пользователя
*********************************/
function register() {
	if ($("#register")[0].checkValidity()) {
		$.post( "/auth/reg", $("#register").serialize()).done(function( data ) {
    		try {
			    obj = $.parseJSON(data);
			    if (obj.status == "success")
			    	$(location).attr("href", "/profile/create");
			    else {
			    	$("#registration #message").html(obj.message);
			    	$("#registration .alert").fadeTo(500, 1);
			    	window.setTimeout(function() {$("#registration .alert").fadeTo(500, 0).slideUp(500, function() {});}, 4000);
			    }
			} catch (e) {
				$("#registration #message").html("Введены неверные данные");
			    $("#registration .alert").fadeTo(500, 1);
			    window.setTimeout(function() {$("#registration .alert").fadeTo(500, 0).slideUp(500, function() {});}, 4000);
			}
  		});
	}
	return false;
}

/************************************************************
Валидация полей формы
*************************************************************/
function checkValidity(elem) {
	$($(elem)[0]).find('input, select').each(function() {
	    if (!($(this)[0].checkValidity())) {
	        var formGroup = $(this).parents('.form-group');
	        $(formGroup).addClass('has-error');
	        $(formGroup).removeClass('has-success');  
	        var glyphicon = formGroup.find('.form-control-feedback');
	        glyphicon.addClass('glyphicon-remove').removeClass('glyphicon-ok');
	    } else {
	        var formGroup = $(this).parents('.form-group');
	        $(formGroup).removeClass('has-error');
	        $(formGroup).addClass('has-success');  
	        var glyphicon = formGroup.find('.form-control-feedback');
	        glyphicon.removeClass('glyphicon-remove').addClass('glyphicon-ok');
	    }
    });
}

/************************************************************
Восстановление пароля
************************************************************/
function restorepassword() {
	$.post( "/auth/password", $("#restoreform").serialize()).done(function( data ) {
		try {
	    	obj = $.parseJSON(data);
	    	if (obj.status == "success")
	    		$(location).attr("href", "/auth/");
	    	else {
	    		grecaptcha.reset(widgetId2);
	    		$("#restore #message").html(obj.message);
	    		$("#restore .alert").fadeTo(500, 1);
	    		window.setTimeout(function() {$("#restore .alert").fadeTo(500, 0).slideUp(500, function() {/*$(this).remove()*/});}, 4000);			
	    	}
	    } catch (e) {
	    	grecaptcha.reset(widgetId2);
			$("#restore #message").html(obj.message);
	    	$("#restore .alert").fadeTo(500, 1);
	    	window.setTimeout(function() {$("#restore .alert").fadeTo(500, 0).slideUp(500, function() {/*$(this).remove()*/});}, 4000);
		}
	});
	return false;
}

/************************************************************
Запрос авторизации
************************************************************/
function login() {
	if ($("#loginform")[0].checkValidity()) {
		$.post( "/auth/login", $("#loginform").serialize()).done(function( data ) {
			try {
	    		obj = $.parseJSON(data);
	    		if (obj.status == "success")
			    	if ($('#accept').prop( "checked" ))
			    		$(location).attr("href", "/auth/postlogin/remember");
			    	else
			    		$(location).attr("href", "/auth/postlogin");
	    		else {
			    	$("#login #message").html(obj.message);
			    	$("#login .alert").fadeTo(500, 1);
			    	window.setTimeout(function() {$("#login .alert").fadeTo(500, 0).slideUp(500, function() {/*$(this).remove()*/});}, 4000);
			    }
			} catch (e) {
				$("#login #message").html("Введены неверные данные	");
			    	$("#login .alert").fadeTo(500, 1);
			    	window.setTimeout(function() {$("#login .alert").fadeTo(500, 0).slideUp(500, function() {/*$(this).remove()*/});}, 4000);
			}
		});
	} 
	return false;
}

/************************************************************
Открытие интерфейса регистрации нового пользователя
************************************************************/
function registration() {
	/*$("#login #message1").html("В настоящее время регистрация новых пользователей недоступна.");
	$("#login .alert1").fadeTo(500, 1);
	window.setTimeout(function() {$("#login .alert1").fadeTo(500, 0).slideUp(500, function() {/*$(this).remove()*//*});}, 4000);*/
	$("#registration").css("display", "block");
	$("#login").css("display", "none");
	$("#restore").css("display", "none");
	return true;
}

/************************************************************
Открытие окна восстановления пароля
************************************************************/
function restore() {
	$("#registration").css("display", "none");
	$("#login").css("display", "none");
	$("#restore").css("display", "block");
	return true;
}

/************************************************************
Открытие окна авторизации
************************************************************/
function loginform() {
	$("#registration").css("display", "none");
	$("#login").css("display", "block");
	$("#restore").css("display", "none");
	return true;
}

/************************************************************
Отображение сокрытие пароля
************************************************************/
function togglepassw(obj, passfield) {
	if ($(passfield).get(0).type == "password") {
		$(passfield).get(0).type = 'text';
		$(obj).addClass('glyphicon-eye-close');
		$(obj).removeClass(' glyphicon-eye-open ');
	}
	else {
		$(passfield).get(0).type = 'password';
		$(obj).removeClass('glyphicon-eye-close');
		$(obj).addClass(' glyphicon-eye-open ');
	}
}

var widgetId2, widgetId1;
var onloadCallback = function() {
	widgetId1 = grecaptcha.render('captcha1', {
          'sitekey' : '6Ldj9jcUAAAAAHKxOe6K49dlC_-MqhFzX4GraZZO',
          'theme' : 'light'
        });
	widgetId2 = grecaptcha.render('captcha2', {
          'sitekey' : '6Ldj9jcUAAAAAHKxOe6K49dlC_-MqhFzX4GraZZO',
          'theme' : 'light'
        });
}