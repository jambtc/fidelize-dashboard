<?php
$apiGet = Yii::app()->createUrl('settings/apiKeysGet');


$myScript = <<<JS

	var waitMsg = '<img width=20 src="css/images/loading.gif" alt="loading...">';

	var btnApikeysCreate = document.querySelector('#btnApikeysCreate');

  if ($( "#btnApikeysCreate" ).length){
    btnApikeysCreate.addEventListener('click', function(){
      api.get();
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

					$('#Api_key_public').val(js.public);
          $('#Api_key_secret').val(js.secret);
          $('#onechance').show();
				}
			});
		},
  };

JS;
Yii::app()->clientScript->registerScript('myScript', $myScript);
?>
