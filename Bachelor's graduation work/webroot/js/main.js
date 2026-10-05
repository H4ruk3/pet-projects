/*Скрипт с базовыми функциями программы*/

Main = {};
/*Базовый метод валидации формы*/
Main.validate = function () {
	var st = true;
	$('input, select').each(function() {
      if (!($(this)[0].checkValidity()))
      {
        var formGroup = $(this).parents('.form-group');
        $(formGroup).addClass('has-error');
        $(formGroup).removeClass('has-success');  
        var glyphicon = formGroup.find('.form-control-feedback');
        glyphicon.addClass('glyphicon-remove').removeClass('glyphicon-ok');
        st = false;
      } else {
        var formGroup = $(this).parents('.form-group');
        $(formGroup).removeClass('has-error');
        $(formGroup).addClass('has-success');  
        var glyphicon = formGroup.find('.form-control-feedback');
        glyphicon.removeClass('glyphicon-remove').addClass('glyphicon-ok');
      }
    });
    return st;
}