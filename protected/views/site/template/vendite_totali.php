<div class="col col-lg-12">
	<div class="statistic__item statistic__item--darkgreen">
		<div class="overview__inner" id='vendite'>
			<div class="overview-box clearfix">
				<div class="icon">
					<i class="fa fa-shopping-cart"></i>
				</div>
				<div class="text" style="margin-right:100px;">
					<h2 id='vendite-totali'>0</h2>
					<span>VENDITE</span>
				</div>
			</div>
			<div class="overview-chart">
				<canvas id="widgetChart1"></canvas>
			</div>
		</div>
	</div>
</div>
<?php


//INIZIALIZZO LE VARIABILI
$labels = [];
$labelsToken = [];
$datas=[];
$datasToken=[];
$total_VENDITE=0;
$tokentotal=0;


//INIZIO L'ITERAZIONE SUL CDataProviderTokens
// questi dati vengono utilizzati anche da js_token_incassati
$iteratorToken = new CDataProviderIterator($dataProviderTokens);
foreach($iteratorToken as $item) {
	#echo "<pre>".print_r($item->status,true)."</pre>";
	if ($item->token_price >0 && $item->status == 'complete'){
		$labels[] = date("d M Y",$item->invoice_timestamp);
		$datas[] = $item->token_price;
		$tokentotal += $item->token_price;
		$total_VENDITE++;

		//per il js di token
		$labelsToken[] = date("d M Y",$item->invoice_timestamp);
		$datasToken[] = $item->token_price;
	}
}
include (dirname(__FILE__).'/../js/vendite_totali.php');

$total_EURO = $total_VENDITE * 1; // prezzo token 1:1


?>
