<?php
$viewName = 'Negozio';
$visible = false;

$posURL = Yii::app()->createUrl('pos/create');
$posizioneMenu = (WebApp::isMobileDevice() ? 'in alto a destra' : 'alla tua sinistra');

// controller di modifica impostazioni store
$general_settings = Yii::app()->createUrl('stores/general',['id'=>crypt::Encrypt($model->id_store)]);
$deleteURL = Yii::app()->createUrl('stores/delete',['id'=>crypt::Encrypt($model->id_store)]);
$exchange = Yii::app()->createUrl('stores/exchange',['id'=>crypt::Encrypt($model->id_store)]);
$checkout = Yii::app()->createUrl('stores/checkout',['id'=>crypt::Encrypt($model->id_store)]);
$checkoutLogo = Yii::app()->createUrl('stores/checkoutLogo',['id'=>crypt::Encrypt($model->id_store)]);
$checkoutCss = Yii::app()->createUrl('stores/checkoutCss',['id'=>crypt::Encrypt($model->id_store)]);
$buttonLoadLogo = '<a class="float-right" href="'.$checkoutLogo.'"><button class="btn btn-info" style="padding: 0px 15px 0px 15px;">Carica</button></a>';
$buttonLoadCss = '<a class="float-right" href="'.$checkoutCss.'"><button class="btn btn-info" style="padding: 0px 15px 0px 15px;">Carica</button></a>';
?>
<div class='section__content section__content--p30'>
	<div class='container-fluid'>
		<div class="row">
			<div class="col-lg-12">
				<div class="au-card au-card--no-shadow au-card--no-pad m-b-40 bg-overlay--semitransparent">
					<div class="card-header ">
						<i class="fas fa-shopping-cart"></i>
						<span class="card-title">Dettagli <?php echo $viewName;?></span>
					</div>
					<div class="card-body">
						<div class="table-responsive table--no-card ">
							<?php $this->widget('zii.widgets.CDetailView', array(
								//'htmlOptions' => array('class' => 'table table-borderless table-striped '),
								'data'=>$model,
								'attributes'=>array(
									'denomination',
									'address',
										'cap',
									array(
										'label'=>'Città',
										'value'=>ComuniItaliani::model()->findByAttributes(array('id_comune'=>$model->city))->citta
									),
									'county',
									array(
										'label'=>'ID Negozio',
										'value'=>$model->bps_storeid
									)

									//'nation',

								),
							));
							?>
						</div>
					</div>
					<div class="card-footer">
						<?php if (Yii::app()->user->objUser['privilegi'] > 5){ ?>
										<?php
											$modifyURL = Yii::app()->createUrl('stores/update').'&id='.crypt::Encrypt($model->id_store);
											$deleteURL = Yii::app()->createUrl('stores/delete').'&id='.crypt::Encrypt($model->id_store);
										?>
										<a href="<?php echo $modifyURL;?>">
											<button type="button" class="btn btn-warning">Modifica</button>
										</a>
										<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#mediumModal">Elimina</button>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo Logo::footer(); ?>
	</div>
</div>

<?php if (Yii::app()->user->objUser['privilegi'] > 5){ ?>
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="mediumModalLabel">Conferma Cancellazione</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Sei sicuro di voler cancellare questo <?php echo $viewName;?>?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<a href="<?php echo $deleteURL;?>">
					<button type="button" class="btn btn-primary btn-danger">Conferma</button>
				</a>
			</div>
		</div>
	</div>
</div>
<?php } ?>
