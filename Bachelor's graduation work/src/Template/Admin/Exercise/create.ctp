<!--Подключаем плагин для загрузки файлов -->
<?= $this->Html->css('/bootstrapfileinput/css/fileinput.min.css') ?>
<?= $this->Html->script('/bootstrapfileinput/js/fileinput.min.js') ?>
<?= $this->Html->css('editor.css') ?>
<?= $this->Html->script('editor.js') ?>
<?= $this->Html->css('redesign/routine.css') ?>
<?= $this->Html->css('redesign/profile.css') ?>

<script>
			$(document).ready(function() {
                $("#txtEditor").Editor();
                <? if (isset($exercise)) { ?>
                $("#txtEditor").Editor("setText", `<?= $exercise->description ?>`);
            <? } ?>
			});
		</script>

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
    background: none;
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



<!--<h4 class = "head">Создание нового распорядка</h4>-->
<?= $this->Flash->render() ?>

<div class = "col-xs-6">
<div class="row box cap">
                <a href="/admin/exercise" class="back"><i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i></a>
                <?if (isset($edit)) {?> <span>Добавление упражнения</span>
                <? } else { ?>
                    <span>Изменение упражнения</span>
                <? } ?>
            </div>
<form method="POST" action="<?= isset($edit)?'/admin/exercise/edit':'/admin/exercise/create'?>" enctype='multipart/form-data'>

<?if (isset($edit)) { ?> <input type="hidden" name="id" value="<?= $exercise->id?>" /> <? } ?>
<div id="step1content">
<div id="content" class="row box content">
        <div class="form">
          <div class="formelement">
            <label for="name">Название упражнения</label>
            <input type="text" name="name" id="name" value="<?= isset($exercise)?$exercise->name:"" ?>"/>
          </div>


    <div class="formelement">
    <?php
    echo $this->Form->input('description', [
      'type' => 'textarea',
      'class' => 'form-control',
      'templateVars' => ["id"=>"txtEditor", "label"=>"Описание", "type"=>"textarea", "name"=>"description", "value"=>isset($exercise)?$exercise->description:"", "rows"=>10, "cols" => 10]
    ]); ?>
    </div>

    <div class="formelement">
    <?php
	echo $this->Form->input('description', [
      'type' => 'fileupload',
      'class' => 'form-control',
      'templateVars' => ["id"=>"avatar-1", "label"=>"Фотография упражнения", "type"=>"textarea", "name"=>"img", "value"=>"", "rows"=>10, "cols" => 10, "required" => "required",
      'oldimagecontainer' => (isset($exercise) && $exercise->img != null)?'<div class="imgpreview" id="imgprev">
      <img style="max-height: 150px;"src="/img/excersices/'.$exercise->img.'">

<button type="button" class="btn btn-secondary btn-icon" onclick="deletefile('.$exercise->id.',\'img\',\'#imgprev\')">
<span class="icon"><i class="glyphicon glyphicon-trash"></i></span>Удалить
</button>
  </div>':''
      ]
    ]);?>
    </div>

    <div class="formelement">
    <?php
	echo $this->Form->input('description', [
      'type' => 'fileupload',
      'class' => 'form-control',
      'templateVars' => ["id"=>"avatar-2", "label"=>"Видео упражнения", "type"=>"textarea", "name"=>"video", "value"=>"", "rows"=>10, "cols" => 10],
      'oldimagecontainer' => (isset($exercise) && $exercise->video != null)?'<div class="imgpreview" id="videoprev">
      <img style="max-height: 150px;"src="/img/excersices/'.$exercise->video.'">

<button type="button" class="btn btn-secondary btn-icon" onclick="deletefile('.$exercise->id.',\'video\',\'#videoprev\')">
<span class="icon"><i class="glyphicon glyphicon-trash"></i></span>Удалить
</button>
  </div>':''
    ]);

    ?>
    </div>

	<!--<div class="col-xs-12" style="float:none">
            <div class="kv-avatar center-block text-center" style="width:400px">
                <input id="avatar-1" name="avatar-1" type="file" class="file-loading" required>
                <div class="help-block"><small>Select file < 1500 KB</small></div>
            </div>
    </div>

    <div class="col-xs-12" style="float:none">
            <div class="kv-avatar center-block text-center" style="width:400px">
                <input id="avatar-2" name="avatar-1" type="file" class="file-loading" required>
                <div class="help-block"><small>Select file < 1500 KB</small></div>
            </div>
    </div>
-->
<div class="formelement">
            <label for="musculgroup">Группа мышц</label>    
            <select id="musculgroup" type="text" name="musculgroup" required class="form-control" value="<?= isset($exercise)?$exercise->musculgroups[0]->id:1 ?>">
            <? foreach($musculgroups as $musculgroup) {
            echo "<option value=". $musculgroup->id . ">" . $musculgroup->name . "</option>";
            }?>
            <!--<option value=1>Бицепс</option>
            <option value=1>Спина</option>-->
            </select>
          </div>

<?

    echo $this->Form->input(__('Save'), array(
      'type' => 'submit',
      'class' => 'btn btn-primary', 
      'onclick' => 'onSubmit();'
    ));
?>
</form>
</div>
</div>

<div id="kv-avatar-errors-1" class="center-block" style="width:800px;display:none"></div>

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