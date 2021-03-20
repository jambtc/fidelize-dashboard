<?php
/* @var $this StoresController */
/* @var $model Stores */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'apiconnections-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
));
// $criteria=new CDbCriteria();
// $criteria->compare('deleted',0,false);

if (Yii::app()->user->objUser['privilegi'] == 20 && $model->isNewRecord){
	$merchants=Merchants::model()->findAll($criteria);
	$listaMerchants = CHtml::listData( $merchants, 'id_merchant' , 'denomination');
	$listaMerchants[0] = ' ';
	ksort($listaMerchants); // ordino per key in modo tale che [0] è il prmio della lista
}else{
	$merchants=Merchants::model()->findByAttributes(array('id_user'=>$model->id_user,'deleted'=>0));
	//$criteria->compare('id_merchant',$merchants->id_merchant,false);
	$listaMerchants = array($merchants->id_merchant => $merchants->denomination);
}
// echo '<pre>'.print_r($listaMerchants,true).'</pre>';
// exit;

$disabled = 'disabled';
if ($model->isNewRecord){
	$disabled = '';
}


include ('_apikeys_js.php');

echo $form->hiddenField($model,'id_user',array('value'=>Yii::app()->user->objUser['id_user']));

if (empty($model->key_secret) || empty($model->key_public) || empty($model->key_description)){
	$model->key_secret = '';
	$model->key_public = '';
	$model->key_description = '';
}
?>
	<?php echo $form->errorSummary($model, '', '', array('class' => 'alert alert-danger')); ?>

	<?php if (Yii::app()->user->objUser['privilegi'] == 20){ ?>
		<div class="form-group">
			<?php echo $form->labelEx($model,'Seleziona il Commerciante'); ?>
			<?php echo $form->dropDownList($model,'id_merchant',$listaMerchants,array("disabled" => $disabled,'class'=>'form-control'));	?>
			<?php echo $form->error($model,'id_merchant',array('class'=>'alert alert-danger')); ?>
		</div>

	<?php }?>

	<div class="form-group">
		<p class="text-primary">Api keys are used to identify your shopping-cart application with the Fidelize Dashboard service. <?php if (empty($model->key_secret) || empty($model->key_public) || empty($model->key_description)){ ?>Click on button to generate new keys.<?php }else{ ?>Click on DELETE button to delete these API Keys. <?php } ?><br>
			<div class="form-group">
				<br>
				<?php
				if (empty($model->key_secret) || empty($model->key_public) || empty($model->key_description))
					echo CHtml::Button('Create', array('class' => 'btn btn-success','id'=>'btnApikeysCreate'));
				?>
			</div>
		</p>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'key_description'); ?>
		<?php echo $form->textField($model,'key_description',array('size'=>50,'maxlength'=>200,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'key_description',array('class'=>'alert alert-danger')); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'key_public'); ?>
		<?php echo $form->textField($model,'key_public',array('size'=>50,'maxlength'=>150,'class'=>'form-control','readonly'=>true)); ?>
		<?php echo $form->error($model,'key_public',array('class'=>'alert alert-danger')); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'key_secret'); ?>
		<?php
			if (empty($model->key_secret) || empty($model->key_public) || empty($model->key_description)){
				echo $form->textField($model,'key_secret',array('size'=>50,'maxlength'=>200,'class'=>'form-control','readonly'=>true));
				?>
				<p class="text-light bg-info" id="onechance" style="display:none;">This is your only chance to copy the private key, as it will no longer be shown</p>
				<?php
			}
			else
				echo $form->passwordField($model,'key_secret',array('size'=>50,'maxlength'=>200,'class'=>'form-control','readonly'=>true));
			?>
		<?php echo $form->error($model,'key_secret',array('class'=>'alert alert-danger')); ?>
	</div>


	<div class="form-group">
		<?php
		if (empty($model->key_secret) || empty($model->key_public) || empty($model->key_description)){
			echo CHtml::submitButton('Conferma', array('class' => 'btn btn-primary'));
		}else{
			?>
			<button type="button" class="btn alert-danger text-light float-right" data-toggle="modal" data-target="#deleteApiKeysModal" style="min-width: 100px; padding:2.5px 10px 2.5px 10px; height:30px;">
				<i class="fa fa-chain"></i> <?php echo Yii::t('lang','delete');?>
			</button>

			<?php
		}
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
