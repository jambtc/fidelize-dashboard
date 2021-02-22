<?php
$tabList['GDPR']   = array('id'=>'gdpr','content'=>$this->renderPartial('webapp/_gdpr',array('model'=>$model),TRUE));
$tabList['Server host']   = array('id'=>'serverhost','content'=>$this->renderPartial('webapp/_serverhost',array('model'=>$model),TRUE));
$tabList['POA & Token']   = array('id'=>'poa','content'=>$this->renderPartial('webapp/_poa',array('model'=>$model),TRUE));

$tabList['Bolt Socials oAuth']   = array('id'=>'socials','content'=>$this->renderPartial('webapp/_bolt-socials',array('model'=>$model),TRUE));
$tabList['MegaPay Socials oAuth']  = array('id'=>'megapay','content'=>$this->renderPartial('webapp/_megapay-socials',array('model'=>$model),TRUE));
$tabList['Vapid Push']   = array('id'=>'vapid','content'=>$this->renderPartial('webapp/_vapid',array('model'=>$model, ),TRUE));
$tabList['Google reCaptcha2']   = array('id'=>'recaptcha','content'=>$this->renderPartial('webapp/_recaptcha',array('model'=>$model, ),TRUE));

$tabList['Rule Engine Api Keys']   = array('id'=>'apykeys','content'=>$this->renderPartial('webapp/_apikeys',array('model'=>$model, ),TRUE));
?>
<div class='section__content section__content--p30'>
	<div class='container-fluid'>
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<h2 class='title-1 m-b-25'><small>Impostazioni applicazione</small></h2>
					</div>
					<div class="card-body card-block">

						<?php $this->widget('zii.widgets.jui.CJuiTabs',array(

							'tabs' => $tabList,
							'options'=>array(
								'collapsible'=>true,
							),
							'id'=>'MyTab-Menu',
						));
						?>
					</div>
				</div>
			</div>
		</div>
		<?php echo Logo::footer(); ?>
	</div>
</div>

<!-- DELETE API KEYS -->
<div class="modal fade" id="deleteApiKeysModal" tabindex="-1" role="dialog" aria-labelledby="deleteApiKeysModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteApiKeysModalLabel"><?php echo Yii::t('lang','Delete');?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
        <p><?php echo Yii::t('lang','This operation delete connection with RULES ENGINE Service.');?></p>
				<p>
					<?php echo Yii::t('lang','Are you sure to continue?');?>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn alert-secondary text-light" data-dismiss="modal" style="min-width: 100px; padding:2.5px 10px 2.5px 10px; height:30px;">
					<i class="fa fa-backward"></i> <?php echo Yii::t('lang','back');?>
				</button>
				<button type="button" class="btn alert-danger text-light" data-dismiss="modal" id="btnApikeysDelete" style="min-width: 100px; padding:2.5px 10px 2.5px 10px; height:30px;">
					<i class="fa fa-thumbs-up"></i> <?php echo Yii::t('lang','confirm');?>
				</button>
			</div>
		</div>
	</div>
</div>
