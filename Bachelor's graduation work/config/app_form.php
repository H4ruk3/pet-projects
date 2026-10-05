<?php
// in config/app_form.php
return [
    'inputwitherror' => '<div class="form-control">{{content}}</div>',
    
    'inputContainer' => '<div id="namegroup" class="form-group has-feedback" style="margin: 0px">
      <label htmlFor="{{id}}">{{label}}</label>
      <div class="col-xs-12" style="padding: 0px">
        <div class="input-group">
          <input ref="name" type="{{type}}" onchange="{{onchange}}" name="{{name}}" class="form-control" id="{{id}}" style="border-radius: 4px" required value = "{{value}}"/>
        </div>
        <span id="glyphicon" class="glyphicon form-control-feedback error"></span>
      </div>
    </div>',
    
    'inputtitleContainer' => '<div id="namegroup" class="form-group has-feedback" style="margin: 0px">
      <label htmlFor="{{id}}">{{label}}</label>
      <div class="col-xs-12" style="padding: 0px">
        <div class="input-group">
          <input ref="name" type="{{typef}}" onchange="{{onchange}}" name="{{name}}" class="form-control" id="{{id}}" required min={{min}} max={{max}} step={{step}} value = "{{value}}"/>
          <span class="input-group-addon">{{title}}</span>
        </div>
      </div>
    </div>',
    
    'timeContainer' => ' <div id="namegroup" class="form-group has-feedback" style="margin: 0px">
      <label htmlFor="{{id}}">{{label}}</label>
      <div class="col-xs-12" style="padding-left: 0px">
    <div class="input-group clockpicker">
    <input type="text" class="form-control" required value="{{value}}" name="{{name}}" id="{{id}}">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
</div>
    </div>
<script type="text/javascript">
$(".clockpicker").clockpicker({
                    donetext: "Установить"});
</script>',
  
'dateContainer' => '
    <div class="form-group">
    <label htmlFor="{{id}}">{{label}}</label>
  <div class="input-group date" id="datetimepicker{{id}}">
    <input type="text" class="form-control" name={{name}} placeholder={{placeholder}} value="{{value}}" />
    <span class="input-group-addon">
      <span class="glyphicon glyphicon-calendar"></span>
    </span>
  
  </div>
</div>

<script type="text/javascript">
  $(function () {
    $("#datetimepicker{{id}}").datetimepicker(
      {pickTime: false, language: "ru"}
    );
  });
</script>
  ',

  'textareaContainer' => '<div class="form-group">
    <div class="col-xs-12" style="padding: 0px; float: none">
    <label htmlFor="{{id}}">{{label}}</label>
  
    <textarea id={{id}} class="form-control textarea" name={{name}} placeholder={{placeholder}} value="{{value}}" rows={{rows}} cols={{cols}} ></textarea>
    </div>
    </div>',

    'fileuploadContainer' => '
    <div class="col-xs-12" style="padding: 0px; float:none">
    <label htmlFor="{{id}}">{{label}}</label>
        <div style="border:1px solid #cfcfcf; padding: 10px 0;">
            <div class="kv-avatar center-block text-center" style="width:400px">
                <input id={{id}} name={{name}} type="file" class="file-loading" {{required}}>
                <div class="help-block"><small>Select file < 1500 KB</small></div>
                {{oldimagecontainer}}
            </div>
        </div>
    </div>
  '

];