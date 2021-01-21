<div class='section__content section__content--p30'>
	<div class='container-fluid'>
		<div class="row">
			<div class="col-lg-12">
				<div class="au-card au-card--no-shadow au-card--no-pad m-b-40 bg-overlay--semitransparent">
					<div class="card-header ">
						<i class="fas fa-list-alt"></i>
						<span class="card-title">Rules Engine Requests</span>
					</div>
					<div class="card-body">
						<div class="table-responsive table--no-card m-b-40">
							<?php $this->widget('zii.widgets.grid.CGridView', array(
								//'htmlOptions' => array('class' => 'table table-wallet'),
								'dataProvider'=>$modelc->search(),
								'id'=>'log-grid',
								'filter'=>$modelc,

								'columns' => array(
									array(
            				'name'=>'timestamp',
      							'type' => 'raw',
										'value' => 'CHtml::link(WebApp::dateLN($data->timestamp), Yii::app()->createUrl("rulesenginerequests/view",["id"=>$data->id_request]) )',
                  ),
									array(
            			  'name'=>'id_request',
            				'type' => 'raw',
                    'value' => 'crypt::Encrypt($data->id_request)',
                  ),
									array(
							      'name'=>'id_merchant',
										'value'=> '(Merchants::model()->findByPk($data->id_merchant) === null)
														? $data->id_merchant : Merchants::model()->findByPk($data->id_merchant)->denomination',
										'type'=>'raw',
							    ),
									array(
            			  'name'=>'payload',
            				'type' => 'raw',
                    'value' => '$data->payload',
                  ),
									array(
            			  'name'=>'sent',
            				'type' => 'raw',
                    'value' => '$data->sent',
                  ),
									// [
									// 'value'=>''
									// ],
								)
							));
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php echo Logo::footer(); ?>
	</div>
</div>
