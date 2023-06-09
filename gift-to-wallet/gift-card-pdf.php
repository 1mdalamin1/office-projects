<?php
	
	require 'vendor/autoload.php';
	require 'barcode/vendor/autoload.php';
	
	// reference the Dompdf namespace
	use Dompdf\Dompdf;
	use Dompdf\Options;

	// instantiate and use the dompdf class
	$options 	  = new Options();
	$options->set('defaultFont', 'Courier');
	$options->set('isRemoteEnabled', true);
	$options->setIsHtml5ParserEnabled(true);
	$dompdf 	  	  = new Dompdf($options);
	$custome_image	  = wc_get_order_item_meta( $orderLineItemId, 'photo_url', true);
	$sendDate		  = wc_get_order_item_meta( $orderLineItemId, 'Dagsetning', true);
	$expiry_dateString= wc_get_order_item_meta( $orderLineItemId, 'expiry_date', true);

	$sDate = DateTime::createFromFormat("d/m/Y", $sendDate);
	$eDate = DateTime::createFromFormat("d-m-Y", $expiry_dateString);
 
	$dateFormate  = get_option('pdf_date_formate') ? get_option('pdf_date_formate') : "d/M/Y";
	$expiry_date  = $eDate->format($dateFormate);
	$Date		  = $sDate->format($dateFormate);
	
	$recipientName= wc_get_order_item_meta( $orderLineItemId, 'recipient_name', true)[0];
	$Message	  = wc_get_order_item_meta( $orderLineItemId, 'Message', true);
	$Message	  = nl2br($Message);
	$pass_link	  = wc_get_order_item_meta( $orderLineItemId, 'pass_link', true);
	$giftcard_no  = wc_get_order_item_meta( $orderLineItemId, 'giftcard_no', true);
	$pass__identity= wc_get_order_item_meta( $orderLineItemId, 'pass__identity', true);
	$item 		  = new WC_Order_Item_Product($orderLineItemId);
	$total		  = $item->get_total();

	$qr_url		  = plugin_dir_url( __FILE__ ).'qr.php?link='.$pass_link;

	if($pass__identity!=null && !empty($pass__identity)){
		$qr_url	  = 'https://veski.leikbreytir.is/en/passinstance/showpasslinkqrcode?passInstance[__identity]='.$pass__identity;
	}

	$generator 	  = new Picqer\Barcode\BarcodeGeneratorPNG();


	$topLogo = '<img src="'.LOGO.'">';
	$mainImg = '<img style="width:720px;" src="'.$custome_image[0].'" alt="" />';
	$barCode = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($giftcard_no, $generator::TYPE_CODE_128)) . '">';
	$qrCode  = '<img style="width:130px;" src="'.$qr_url.'" alt="qr-code" />';

	$defaulPDFhtml = '
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
		<tr>
			<td style="width:100%;background-color:#ffffff; padding:50px 50px 50px 50px;" valign="top">
				<table cellspacing="0" cellpadding="0" width="100%" align="center" style="margin-bottom:30px">
					<tr>
						<td align="center">{logo}</td>
					</tr>
				</table>

				<table cellpadding="0" cellspacing="0" align="center" width="100%">
					<tr>
						<td style="text-align:center;">
							{gift_card_img}
						</td>
					</tr>
				</table>


				<table cellpadding="0" cellspacing="0" width="100%" align="center" style="margin-top:10px;">
					<tr>
						<td width="100%" style="background-color:#ffffff; padding:50px;border: 2px solid #014189;" valign="top">
							<div style="width:100%;">
								<table cellpadding="0" cellspacing="0" width="100%" style="margin-top: -44px">
									<tr>
										<td style="width:60%;" valign="top">
											<p style="font-size: 16px;">Dagsetning: {gift_card_send_date}</p>
											<p style="font-size: 16px;">Expiry Date: {gift_card_expiry_date}</p>
											<table style="width:100%;">
												<tr>
													<td>
														<div style="min-height:200px;">
															<p style="font-size: 16px;margin-top:-10px;">{gift_card_message}</p>
														</div>
														<div style="text-align:center;">
															{bar_code}
															<p style="font-size: 16px;margin-top:0px;">Gjafakorts númer: {gift_card_no}</p>
														</div>
													</td>
												</tr> 
											</table>
										</td>
										<td style="width:40%;vertical-align:top;" align="right">
											<p style="text-align: right; font-size:25px; font-weight: 700; color: #2C2E35;">{gift_card_amount}</p>
											{qr_code}
										</td>
									</tr>
								</table>
								
								
							</div>
						</td>
					</tr>               
				</table>

				<table width="100%" align="center" style="border-top:2px solid #43454B;margin-top:10px;">
					<tr>
						<td><p>{buttom_text}</p></td>		
					</tr>
				</table>
						
			</td>
		</tr>
	</table>
	';

	$htmlPDFcheck = get_option('pdfhtml');

	$htmlPDF 	  = $htmlPDFcheck ? stripslashes($htmlPDFcheck) : $defaulPDFhtml;

	$search 	  = array("{logo}", "{gift_card_img}", "{gift_card_send_date}", "{gift_card_message}", "{gift_card_amount}", "{bar_code}", "{gift_card_no}", "{qr_code}", "{buttom_text}", "{gift_card_expiry_date}");
	$replace 	  = array($topLogo, $mainImg, $Date, stripslashes($Message), wc_price($total), $barCode, $giftcard_no, $qrCode, BOTTOM_TEXT, $expiry_date );

	$pdfBody 	  = str_replace($search, $replace, $htmlPDF);



	$html		  ='
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Document</title>
			<style>
				html { margin: 0px}
				@page { margin: 0px; }
				body { margin: 0px; }
			</style>
		</head>
		<body>
			'.$pdfBody.'
		</body>
	</html>';
	
	//BOTTOM_TEXT
	// echo $html;

	
	$dompdf->loadHtml($html);

	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('legal', 'portrait');

	// Render the HTML as PDF
	$dompdf->render();

	if(!isset($output)){
		// Output the generated PDF to Browser
		$dompdf->stream('gjafakort.pdf');

		exit();
	}else{
		$attachment=$dompdf->output();
		$pdf_file_name=$orderLineItemId.'-gjafakort.pdf';
		file_put_contents($pdf_file_name, $attachment);
		
	}

	


	