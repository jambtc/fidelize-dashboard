<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
<table style="font-family:Roboto; border-spacing:0px;padding: 20px; background-color: #F8F9FA; border-collapse:separate;" summary="o_mail_notification" width="100%" cellpadding="0" border="0" bgcolor="#F8F9FA">
<!-- HEADER -->
		<tr>
			<td style="min-width: 590px;" align="center">

				<table style="border-spacing:0px;width:590px;background:inherit;color:inherit" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="padding:10px 10px 10px 0px;font-size: 14px" width="200" valign="center">
							<img src="<?php echo $logo; ?>" style="padding: 20px; margin: 0px; height: auto; max-width:200px;" alt="<?php echo Yii::app()->params['nomeAssociazione'];?>" data-original-title="" title="">
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>

	  <!-- CONTENT -->
  		<tr>
			<td style="min-width: 590px;" align="center">
				<table style="border-spacing:0px;min-width: 590px; background-color: rgb(255, 255, 255); padding: 20px; border-collapse:separate; border-width:1px; border-style:solid; border-color:#e8eaed;" width="590" cellpadding="0" bgcolor="#ffffff">
					<tbody>
						<tr>
							<td style="font-family:Roboto,Tahoma,Verdana,Segoe,sans-serif; color: #555; font-size: 14px;" valign="top">
								<div>
									<p style="font-size:34px;color:rgb(57,150,220)">Avviso</p>
								</div>
								<div>
									<p style="margin-top: 10px;font-size: 14px;">Ciao <strong><?php echo $name; ?></strong>,</p>
								</div>
                				<div>
									<p style="margin-top: 10px;font-size: 14px;">l'associazione <strong><?php echo Yii::app()->params['nomeAssociazione']; ?></strong>
										è felice di invitarti all'evento <b><?php echo $post['subject']; ?></b>
										che si terrà il giorno <b><?php echo $post['data']; ?></b> alle ore <b><?php echo $post['time']; ?></b> in <?php echo $post['place']; ?></p>
								</div>


								<p style="margin-top: 10px;font-size: 14px;">
									<?php echo nl2br($post['body']); ?>
								</p>

								<p style="margin-top: 10px;font-size: 14px;">
									La tua partecipazione è importante, ti aspettiamo.
								</p>

								<div>
									<p style="margin-top: 10px;font-size: 14px;">A presto,<br><strong><?php echo Yii::app()->params['adminName']; ?> Team</strong></p>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
		</td>
	</tr>
	<!-- FOOTER -->
	<?php
	$settings = Settings::load();

	 ?>

		<tr>
			<td style="min-width: 590px;" align="center">
				<table style="border-spacing:0px;min-width: 590px; background-color: rgb(248,249,250); padding: 20px; border-collapse:separate;" width="590" cellpadding="0" border="0" bgcolor="#F8F9FA">
					<tbody>
						<tr>
							<td style="color: #6c737f; padding-top: 10px; padding-bottom: 10px;" valign="middle" align="left">
								<div>
									<p style="font-size: 14px;">
										<strong><?php echo Yii::app()->params['nomeAssociazione']; ?></strong>
										<br><?php echo $settings->gdpr_address; ?>
										<br><?php echo $settings->gdpr_cap.' - '. ComuniItaliani::model()->findByPk($settings->gdpr_city)->citta .' ('. ComuniItaliani::model()->findByPk($settings->gdpr_city)->sigla .')' ; ?>
										<br>Tel. <?php echo $settings->gdpr_telefono . ' | Fax '. $settings->gdpr_fax; ?>
										<br><?php echo Yii::app()->params['adminEmail'] .' | www.napoliblockchain.it'; ?>
									</p>
								</div>
								<div>
									<p style="font-size: 10px;">Ricevi questa email perché ti sei registrato sul nostro sito e/o hai usufruito dei nostri servizi ed hai dato il consenso a ricevere comunicazioni email da parte nostra.
									</p>
								</div>
								<div>
									<p style="font-size: 10px;">---<br><strong>Riservatezza e sicurezza del messaggio</strong><br>Il contenuto della e-mail è riservato ed è rivolto esclusivamente al/ai destinatario/i identificato/i. Pertanto è proibito leggerlo, copiarlo, divulgarlo o utilizzarlo da parte di chiunque salvo il/i destinatario/i. Se non siete il destinatario, vi invitiamo a cancellare il messaggio ed eventuali allegati dandocene immediatamente comunicazione scritta a mezzo posta elettronica. Sebbene il mittente si impegni ad adottare le misure più idonee per assicurare l'assenza di virus all'interno di eventuali allegati alla presente comunicazione e-mail, tali misure non costituiscono una garanzia assoluta e pertanto vi invitiamo a porre in essere i vostri controlli antivirus prima di aprire qualsiasi allegato. Il mittente non si assume quindi alcuna responsabilità per eventuali danni che potreste subire a causa di virus contenuti nei messaggi.
									</p>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
<?php
// exit;
?>
