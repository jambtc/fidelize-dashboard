<div class='section__content section__content--p30'>
	<div class='container-fluid'>
		<div class="row">
			<div class="col-lg-12">
				<div class="au-card au-card--no-shadow au-card--no-pad m-b-40 bg-overlay--semitransparent">
					<div class="card-header ">
						<i class="fas fa-code-branch"></i>
						<span class="card-title">Lista Chiavi API</span>
						<div class="float-right">
							<?php $actionURL = Yii::app()->createUrl('api/create'); ?>
							<a href="<?php echo $actionURL;?>">
								<button class="btn alert-primary text-light img-cir" style="padding:2.5px; width:30px; height:30px;">
									<i class="fa fa-plus"></i></button>
							</a>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive table--no-card ">

							<?php $this->widget('zii.widgets.grid.CGridView', array(
								'htmlOptions' => array('class' => 'table table-wallet'),
							    'dataProvider'=>$dataProvider,
								'columns' => array(
									array(
							      'name'=>'key_description',
										'header'=>'key_description',
										'type'=>'raw',
										'value' => 'CHtml::link(CHtml::encode($data->key_description), Yii::app()->createUrl("api/update")."&id=".CHtml::encode(crypt::Encrypt($data->id_api)))',
							     ),
									array(
							      'name'=>'key_public',
										'header'=>'key_public',
										'type'=>'raw',
										'value' => 'CHtml::link(CHtml::encode($data->key_public), Yii::app()->createUrl("api/update")."&id=".CHtml::encode(crypt::Encrypt($data->id_api)))',
							     ),
									 // 'key_private',
									 array(
									  'name'=>'',
									  'value' => '',
								   ),
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
