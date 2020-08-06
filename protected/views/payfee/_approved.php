

			<div class="col-md-12">
				<div class="card border border-primary bg-teal">
					<div class="card-header text-primary">
						<strong class="card-title">Paypal: pagamento effettuato correttamente!</strong>
					</div>
					<div class="card-body">
						<div class="alert alert-success">
							<p>La tua iscrizione annuale è stata rinnovata.</p>
						</div>
						<p class="text-primary">
							Puoi scaricare la ricevuta cliccando sul pulsante <strong>'STAMPA'</strong>.
						</p>
					</div>
					<div class="card-footer">

						<?php
							$printURL = Yii::app()->createUrl('pagamenti/print').'&id='.crypt::Encrypt($model->id_pagamento);
						?>
						
						<a href="<?php echo $printURL;?>" target="_blank">
							<button type="button" class="btn btn-secondary">Stampa</button>
						</a>
					</div>

				</div>
			</div>

		<div class="row">
			<div class="col-lg-12">
				<h2 class='title-1 m-b-25'></h2>
				<div class="table-responsive table--no-card m-b-40">
					<?php
					// echo "<pre>".print_r($model,true)."</pre>";
					// exit;
					//
					$this->widget('zii.widgets.CDetailView', array(
						'htmlOptions' => array('class' => 'table table-borderless table-striped '),
						'data'=>$model,
						'attributes'=>array(
							'id_invoice_bps',
							array(
								'label'=>'Ricevuta',
								'value'=>$model->progressivo.'/'.$model->anno,
							),
							array(
								'label'=>'Tipo di iscrizione',
								'value'=>Quote::model()->findByPk($model->id_quota)->description,
							),
							array(
								'type'=>'raw',
								'name'=>'status',
								'value'=>WebApp::walletStatus($model->status),
							),
							'importo',
							//'id_tipo_pagamento',
							array(
								'label'=>'Tipo di pagamento',
								'value'=>TipoPagamenti::model()->findByPk($model->id_tipo_pagamento)->description,
							),
							array(
								'type'=>'raw',
								'label'=>'Transazione Paypal',
								'value'=>$model->paypal_txn_id,
								'visible'=>($model->id_tipo_pagamento == 2 ? true:false),
							),
							array(
								'label'=>'Data Registrazione',
								'value'=>WebApp::data_it($model->data_registrazione),
							),
							array(
								'label'=>'Data Inizio',
								'value'=>WebApp::data_it($model->data_inizio),
							),
							array(
								'label'=>'Data Scadenza',
								'value'=>WebApp::data_it($model->data_scadenza),
							),
						),
					));
					?>
				</div>
			</div>
		</div>
