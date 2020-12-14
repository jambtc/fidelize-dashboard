<?php
$apiGet = Yii::app()->createUrl('settings/apiKeysGet');
$apiDelete = Yii::app()->createUrl('settings/apiKeysDelete');

$myScript = <<<JS

	var waitMsg = '<img width=20 src="css/images/loading.gif" alt="loading...">';

	var btnApikeysCreate = document.querySelector('#btnApikeysCreate');
  var btnApikeysDelete = document.querySelector('#btnApikeysDelete');

  if ($( "#btnApikeysCreate" ).length){
    btnApikeysCreate.addEventListener('click', function(){
      api.get();
    });
  }
  if ($( "#btnApikeysDelete" ).length){
    btnApikeysDelete.addEventListener('click', function(){
  		api.delete();
  	});
  }


	var api = {
		get: function(func){
			$.ajax({
				url: '{$apiGet}',
				type: 'GET',
				dataType: "json",
				complete: function (json) {
					js = json.responseJSON;

					$('#SettingsWebappForm_RulesEngineApiKeyPublic').val(js.public);
          $('#SettingsWebappForm_RulesEngineApiKeySecret').val(js.secret);
          $('#onechance').show();
				}
			});
		},
    delete: function(func){
			$.ajax({
				url: '{$apiDelete}',
				type: 'GET',
				dataType: "json",
				complete: function (json) {
					js = json.responseJSON;
          //console.log('Response apikeys:',js)
					$('#SettingsWebappForm_RulesEngineApiKeyPublic').val(js.public);
          $('#SettingsWebappForm_RulesEngineApiKeySecret').val(js.secret);
          window.location.href = window.location.href;
				}
			});
		},

	};

JS;
Yii::app()->clientScript->registerScript('myScript', $myScript);
?>
