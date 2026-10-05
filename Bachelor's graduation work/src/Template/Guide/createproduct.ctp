<!--Подключаем плагин для загрузки файлов -->
<?= $this->Html->css('/bootstrapfileinput/css/fileinput.min.css') ?>
<?= $this->Html->script('/bootstrapfileinput/js/fileinput.min.js') ?>
<?= $this->Html->css('redesign/routine.css') ?>
<?= $this->Html->css('redesign/profile.css') ?>

<style>
.kv-avatar .krajee-default.file-preview-frame,.kv-avatar .krajee-default.file-preview-frame:hover {
    margin: 0;
    padding: 0;
    border: none;
    box-shadow: none;
    text-align: center;
}
.kv-reqd {
    color: red;
    font-family: monospace;
    font-weight: normal;
}
.file-preview {
	min-height: 200px;
}
.file-drop-zone {
	min-height: 200px;
}

            /* CSS */
            .btn-icon {
    padding-top: 0;
    padding-bottom: 0;
    margin-left: 20px;
}        
.btn > .icon {
    position: relative;
    left: -1.3rem;
    display: inline-block;
    padding: .375rem .75rem;
    background: rgba(0, 0, 0, 0.15);
    border-radius: .25rem 0 0 .25rem;
}
.imgpreview {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<?= $this->Flash->render() ?>

<div class = "col-xs-6">
    <div class="row box cap">
        <a href="/admin/food" class="back"><i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i></a>
        <?if (!isset($edit)) {?> <span>Добавление продукта питания</span>
        <? } else { ?>
            <span>Изменение продукта питания</span>
        <? } ?>
    </div>
    <form method="POST" action="<?= isset($edit)?('/redesign/guide/editproduct/' . $food->id):'createproduct'?>" enctype='multipart/form-data'>

    <?if (isset($edit)) { ?> <input type="hidden" name="id" value="<?= $food->id?>" /> <? } ?>
    <div id="step1content">
        <div id="content" class="row box content">
            <div class="form">
              <div class="formelement">
                <label for="name">Название продукта питания</label>
                <input type="text" name="name" id="name" value="<?= isset($food)?$food->name:"" ?>"/>
            </div>

            <div class="formelement">
                <label for="foodcategory">Категория продуктов</label>    
                <select id="foodcategory" type="text" name="foodcategory" required class="form-control" value="<?= isset($food)?$food->foodcategory_id:1 ?>">
                    <? foreach($foodcategories as $foodcategory) {
                        echo "<option value=". $foodcategory->id . ((isset($food) && $food->foodcategory_id == $foodcategory->id)?" selected":"") . ">" . $foodcategory->name . "</option>";
                    }?>
                </select>
            </div>

            <div class="formelement">
                <label for="cnt">Количество белков на 100 грамм</label>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="down(this.parentNode.querySelector('input[type=number]'));"></span>
                    <input type="number" required min="0" max="10000" step=1 id="cnt" class="form-control" name="proteins" value="<?= isset($food)?$food->proteins:'' ?>">
                    <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="up(this.parentNode.querySelector('input[type=number]'));"></span>
                </div>
            </div>

            <div class="formelement">
                <label for="cnt">Количество жиров на 100 грамм</label>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="down(this.parentNode.querySelector('input[type=number]'));"></span>
                    <input type="number" required min="0" max="10000" step=1 id="cnt" class="form-control" name="fats" value="<?= isset($food)?$food->fats:'' ?>">
                    <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="up(this.parentNode.querySelector('input[type=number]'));"></span>
                </div>
            </div>

            <div class="formelement">
                <label for="cnt">Количество белков на 100 грамм</label>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="down(this.parentNode.querySelector('input[type=number]'));"></span>
                    <input type="number" required min="0" max="10000" step=1 id="cnt" class="form-control" name="hidrocarbonats" value="<?= isset($food)?$food->hidrocarbonats:'' ?>">
                    <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="up(this.parentNode.querySelector('input[type=number]'));"></span>
                </div>
            </div>

            <div class="formelement">
                <label for="cnt">Количество калорий на 100 грамм</label>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="down(this.parentNode.querySelector('input[type=number]'));"></span>
                    <input type="number" required min="0" max="10000" step=1 id="cnt" class="form-control" name="colories" value="<?= isset($food)?$food->colories:'' ?>">
                    <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="up(this.parentNode.querySelector('input[type=number]'));"></span>
                </div>
            </div>
            <?
                echo $this->Form->input(__('Save'), array(
                    'type' => 'submit',
                    'class' => 'btn btn-primary', 
                    'onclick' => 'onSubmit();'
                ));
            ?>
        </div>
    </div>
    </form>
</div>

<script src="/js/input.js"></script>
<script>
function onSubmit() {
    $("#txtEditor").val($("#txtEditor").Editor("getText"));
}


var btnCust = '<button type="button" class="btn btn-default" title="Add picture tags" ' + 
    'onclick="alert(\'Call your custom code here.\')">' +
    '<i class="glyphicon glyphicon-tag"></i>' +
    '</button>'; 
$("#avatar-1").fileinput({
	uploadUrl: "http://coachme/admin/exercise/create",
	uploadAsync: false,
    overwriteInitial: true,
    initialPreviewAsData: true,
    maxFileSize: 1500,
    showClose: false,
    showCaption: false,
    browseLabel: '',
    removeLabel: '',
    browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
    removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
    removeTitle: 'Cancel or reset changes',
    elErrorContainer: '#kv-avatar-errors-1',
    msgErrorClass: 'alert alert-block alert-danger',
    //defaultPreviewContent: '<img src="/uploads/default_avatar_male.jpg" alt="Фотография упражнения" style="width:160px">',
    layoutTemplates: {main2: '{preview} {remove} {browse}'},
    allowedFileExtensions: ["jpg", "png", "gif"] ,
    initialPreviewFileType: 'image', // image is the default and can be overridden in config below
    initialPreviewConfig: [
        {caption: "Business-1.jpg", size: 762980, url: "/site/file-delete", key: 8},
        {previewAsData: false, size: 823782, caption: "Business-2.jpg", url: "/site/file-delete", key: 9}, 
        {type: "pdf", size: 8000, caption: "PDF-Sample.pdf", url: "/file-upload-batch/2", key: 10}, 
        {type: "video", size: 375000, filetype: "video/mp4", caption: "KrajeeSample.mp4", url: "/file-upload-batch/2", key: 14},  
    ],
    uploadExtraData: {
        img_key: "1000",
        img_keywords: "happy, nature",
    }
});

$("#avatar-2").fileinput({
	uploadUrl: "/file-upload-batch/1",
    uploadAsync: false,
    overwriteInitial: false,
    initialPreviewAsData: true,
    maxFileSize: 300000,
    showClose: false,
    showCaption: false,
    browseLabel: '',
    removeLabel: '',
    browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
    removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
    removeTitle: 'Cancel or reset changes',
    elErrorContainer: '#kv-avatar-errors-1',
    msgErrorClass: 'alert alert-block alert-danger',
    //defaultPreviewContent: '<img src="/uploads/default_avatar_male.jpg" alt="Видео упражнения" style="width:160px">',
    layoutTemplates: {main2: '{preview} {remove} {browse}'},
    allowedFileExtensions: ["mp4", "mpeg"],
    initialPreviewFileType: 'image', // image is the default and can be overridden in config below
    initialPreviewConfig: [
        {caption: "Business-1.jpg", size: 762980, url: "/site/file-delete", key: 8},
        {previewAsData: false, size: 823782, caption: "Business-2.jpg", url: "/site/file-delete", key: 9}, 
        {type: "pdf", size: 8000, caption: "PDF-Sample.pdf", url: "/file-upload-batch/2", key: 10}, 
        {type: "video", size: 375000, filetype: "video/mp4", caption: "KrajeeSample.mp4", url: "/file-upload-batch/2", key: 14},  
    ],
    uploadExtraData: {
        img_key: "1000",
        img_keywords: "happy, nature",
    }
}).on('filesorted', function(e, params) {
    console.log('File sorted params', params);
}).on('fileuploaded', function(e, params) {
    console.log('File uploaded params', params);
});

function deletefile(exercise, type, obj) {
    var delobj = obj;
    $.post( "/admin/exercise/deletefile", {"id" : exercise, "type" : type}).done(function( data ) {
      var dataobj = $.parseJSON(data);
      if (dataobj.status == "success") {
          $(delobj).remove();
      }
    });
}

</script>