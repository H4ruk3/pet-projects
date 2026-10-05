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


$("document").ready(function() {
    //$("div[id*='menu-']").hide();
    $("div.alert").remove();
    /*setTimeout(function() {
        $("div.alert").remove();
    }, 5000);*/ // 5 secs

});

function unsubscribe() {
    $.post("/profile/unsubscribe", {}).done(function(data) {
        //alert(data);
        var dataobj = $.parseJSON(data);
        $("#usergroup").css("display", "none");
    });
}

function openFileOption() {
    document.getElementById("file_upload").click();
}

function readURL(input) {
    if (input.files && input.files[0]) {
        console.log(input.files[0]);
        $("#file_info")[0].value = input.files[0].name;
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#blah').attr('src', e.target.result)
        };
        reader.readAsDataURL(input.files[0]);
        setTimeout(initCropper, 1000);
    }
}
var cropper;

function initCropper() {
    console.log("Came here")
    var image = document.getElementById('blah');
    cropper = new Cropper(image, {
        aspectRatio: 1 / 1,
        crop: function(e) {
            console.log(e.detail.x);
            console.log(e.detail.y);
        }
    });
}

$(function() {
    $("div[id*='menu-']").dotdotdot();
});

function toggle(objName, button) {
    var obj = $(objName),
    blocks = $("div[id*='menu-']");
    if (obj.css("height")!='100px') {
  //blocks.trigger('destroy');
        obj.animate({ height: '100px' }, 500, "linear", function() {
           $(objName).dotdotdot();
           $(button)[0].innerText = "подробнее";
        }); 
    }
    else {
        obj.trigger('destroy');
        var clone = obj.clone();
        clone.css('height', '100%');
        clone.css('visibility', 'hidden');

        obj.before(clone);
        //$(button.parentNode).append(clone);

        var height = clone.height();
        clone.remove();
        //console.log(obj[0].clientHeight);
        obj.animate({ height: height+"px", translation: '2s'}, 500, "linear", function() {
           //$(objName).dotdotdot();
           $(button)[0].innerText = "скрыть";
        }); 
    }
    return false;
    /*var obj = $(objName),
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
    return false;*/

}

function updateavatar(id) {
    cropper.getCroppedCanvas().toBlob(function(blob) {
        var formData = new FormData();
        formData.append('avatar', blob);
        $.ajax('/profile/updateavatar/' + id, {
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                //alert(data);
                try {
                    obj = $.parseJSON(data);
                    if (obj.status == "success")
                        $("#avatar")[0].src = obj.url;
                    //$(location).attr("href", "/redesign/profile"); 
                    else {
                        $("#message").html("Не удалось загрузить файл.");
                        $(".alert").fadeTo(500, 1);
                        window.setTimeout(function() { $(".alert").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
                    }
                    $('#myModalBox').modal('hide');
                } catch (e) {
                    $("#message").html("Не удалось загрузить файл.");
                    $(".alert").fadeTo(500, 1);
                    window.setTimeout(function() { $(".alert").fadeTo(500, 0).slideUp(500, function() { /*$(this).remove()*/ }); }, 4000);
                    $('#myModalBox').modal('hide');
                }
            },
            error: function(data) {
                //alert(data);
                obj = $.parseJSON(data);
                if (obj.status == "success")
                //$(location).attr("href", "/redesign/profile");
                    $("#avatar")[0].src = obj.url;
                else {
                    alert(data);
                }
                console.log('Upload error');
            }
        });
    });
}