<?php
/* @var $this StoresController */
/* @var $model Stores */
$viewName = 'Impostazioni';


//VISIBILI ALL'USER MERCHANT
if (Yii::app()->user->objUser['privilegi'] == 10){
$attributes[] = array(
					'label'=>'Indirizzo token principale',
					'value'=>Wallets::model()->findByPk($model->id_wallet)->wallet_address
				);
}




//VISIBILI ALL'ADMIN
if (Yii::app()->user->objUser['privilegi'] == 20){
		$attributes[] = array(
							'label'=>'Smart Contract POA (token)',
							'value'=>(isset($model->poa_contractAddress) ? $model->poa_contractAddress : '')
						);
		$attributes[] = array(
							'label'=>'Scadenza Invoice',
							'value'=>$model->poa_expiration . ' (min)',
						);


}



#echo '<pre>'.print_r($attributes,true).'</pre>';
#exit;
?>
<div class='section__content section__content--p30'>
<div class='container-fluid'>
	<div class="row">
		<div class="col-lg-12">
			<div class="au-card au-card--no-shadow au-card--no-pad m-b-40 bg-overlay--semitransparent">
				<div class="card-header ">
					<i class="fa fa-gear"></i>
					<span class="card-title"><?php echo $viewName; ?></span>
				</div>
				<div class="card-body">
					<div class="table-responsive table--no-card ">
						<?php $this->widget('zii.widgets.CDetailView', array(
							// 'htmlOptions' => array('class' => 'table table-borderless table-striped '),
							'data'=>$model,
							'attributes'=>$attributes,
						));
						?>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-md-2">
							<div class="overview-wrap">
								<h2 class="title-1">
									<?php
										if (Yii::app()->user->objUser['privilegi'] == 20)
											$modifyURL = Yii::app()->createUrl('settings/update').'&id='.crypt::Encrypt(0);
										else
											$modifyURL = Yii::app()->createUrl('settings/update').'&id='.crypt::Encrypt($model->id_user);

									?>
											<a href="<?php echo $modifyURL;?>">
												<button type="button" class="btn btn-warning">Modifica</button>
											</a>


								</h2>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php echo Logo::footer(); ?>
</div>
</div>
