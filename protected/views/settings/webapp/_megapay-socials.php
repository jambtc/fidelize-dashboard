<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'socials-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
));
?>


<div class="col-md-12">
	<div class="au-card  au-card--no-pad bg-overlay--semitransparent">
		<div class="card-body ">

			<div class="card border border-primary">
				<div class="card-header">
					<strong class="card-title">Telegram</strong>
				</div>
				<div class="card-body">
					<div class="form-group">
						<?php echo $form->labelEx($model,'MegapayTelegramBotName'); ?>
						<?php echo $form->textField($model,'MegapayTelegramBotName',array('size'=>50,'maxlength'=>150,'class'=>'form-control')); ?>
						<?php echo $form->error($model,'MegapayTelegramBotName',array('class'=>'alert alert-danger')); ?>
					</div>

					<div class="form-group">
						<?php echo $form->labelEx($model,'MegapayTelegramToken'); ?>
						<?php echo $form->passwordField($model,'MegapayTelegramToken',array('size'=>50,'maxlength'=>150,'class'=>'form-control')); ?>
						<?php echo $form->error($model,'MegapayTelegramToken',array('class'=>'alert alert-danger')); ?>
					</div>
				</div>
			</div>


		</div>
	</div>
</div>
<?php //echo $form->hiddenField($model,'step',array('value'=>4)); ?>
<div class="col-md-12">
	<div class="form-group">
		<br>
		<?php echo CHtml::submitButton('Conferma', array('class' => 'btn btn-primary')); ?>
	</div>
</div>



<?php $this->endWidget(); ?>

</div><!-- form -->
