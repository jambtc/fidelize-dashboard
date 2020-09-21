<?php
$tabList['GDPR']   = array('id'=>'gdpr','content'=>$this->renderPartial('webapp/_gdpr',array('model'=>$model),TRUE));
$tabList['Server host']   = array('id'=>'serverhost','content'=>$this->renderPartial('webapp/_serverhost',array('model'=>$model),TRUE));
$tabList['POA & Token']   = array('id'=>'poa','content'=>$this->renderPartial('webapp/_poa',array('model'=>$model),TRUE));

$tabList['Socials']   = array('id'=>'socials','content'=>$this->renderPartial('webapp/_socials',array('model'=>$model),TRUE));
$tabList['Vapid Push']   = array('id'=>'vapid','content'=>$this->renderPartial('webapp/_vapid',array('model'=>$model, ),TRUE));
$tabList['reCaptcha2']   = array('id'=>'recaptcha','content'=>$this->renderPartial('webapp/_recaptcha',array('model'=>$model, ),TRUE));
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
