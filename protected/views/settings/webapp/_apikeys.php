<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'settingsApykeys-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
));
include ('_apikeys_js.php');
?>


<div class="col-md-12">
	<div class="au-card  au-card--no-pad bg-overlay--semitransparent">
		<div class="card-body ">
			<div class="form-group">
				<p class="text-primary">Api keys are used to identify your application server with the RULE Engine service. Click on link to generate new keys.<br>
					<div class="form-group">
						<br>
						<?php
						if (empty($model->RuleEngineApiKeySecret))
							echo CHtml::Button('Create', array('class' => 'btn btn-success','id'=>'btnApikeysCreate'));

						?>
					</div>

				</p>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'RuleEngineApiKeyPublic'); ?>
				<?php echo $form->textField($model,'RuleEngineApiKeyPublic',array('size'=>50,'maxlength'=>150,'class'=>'form-control','readonly'=>true)); ?>
				<?php echo $form->error($model,'RuleEngineApiKeyPublic',array('class'=>'alert alert-danger')); ?>
			</div>

			<div class="form-group">
				<?php echo $form->labelEx($model,'RuleEngineApiKeySecret'); ?>
				<?php
					if (empty($model->RuleEngineApiKeySecret)){
						echo $form->textField($model,'RuleEngineApiKeySecret',array('size'=>50,'maxlength'=>150,'class'=>'form-control','readonly'=>true));
						?>
						<p class="text-light bg-info" id="onechance" style="display:none;">This is your only chance to copy the private key, as it will no longer be shown</p>
						<?php
					}
					else
						echo $form->passwordField($model,'RuleEngineApiKeySecret',array('size'=>50,'maxlength'=>150,'class'=>'form-control','readonly'=>true));
					?>
				<?php echo $form->error($model,'RuleEngineApiKeySecret',array('class'=>'alert alert-danger')); ?>
			</div>
		</div>
	</div>
</div>
<?php //echo $form->hiddenField($model,'step',array('value'=>4)); ?>
<div class="col-md-12">
	<div class="form-group">
		<br>
		<?php
		if (empty($model->RuleEngineApiKeySecret))
			echo CHtml::submitButton('Conferma', array('class' => 'btn btn-primary'));
		else{
			?>
			<button type="button" class="btn alert-danger text-light float-right" data-toggle="modal" data-target="#deleteApiKeysModal" style="min-width: 100px; padding:2.5px 10px 2.5px 10px; height:30px;">
				<i class="fa fa-chain"></i> <?php echo Yii::t('lang','delete');?>
			</button>

			<?php
		}
		?>
	</div>
</div>





<?php $this->endWidget(); ?>

</div><!-- form -->
