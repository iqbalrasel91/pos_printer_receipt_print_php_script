<?php
    header('Access-Control-Allow-Origin: *');
	$config = include('config.php');
	if($config['APP_ENV'] == 'PRODUCTION'){
		if ((isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))) {
			if (strtolower(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)) == strtolower($_SERVER['HTTP_HOST'])) {
				echo '<h1>The Printing Request Must be came from pressto web app</h1>';
				return 'server error';
				die();
			}
		}
	}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
</html>

<?php
/* Change to the correct path if you copy this example! */
require __DIR__ . '/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\GdEscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

        // selecting logo

       if (isset($_POST['serviceType'])) {

		date_default_timezone_set('Asia/Dhaka');
	   
        $connector = new WindowsPrintConnector("BP-T3");
        $printer = new Printer($connector);


        $order_date = strtotime( "2017-03-17 09:12:11" );
        $order_date = date( 'd/m/y g:i A', $order_date );
		
		$invoice_product_details = $_POST['invoice_product_details'];
        $invoiceDiscounts = $_POST['invoiceDiscounts'];
		
        try {


            // TOP LOGO

            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            http_response_code(200);
            $im = imagecreatefrompng("image/logo.png");
            $foo = new GdEscposImage();
            $foo -> readImageFromGdResource($im);
            $printer -> bitImage($foo);
            $printer -> selectPrintMode();
            $printer -> feed();

            // TOP BOTTOM

            $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setUnderline();
            $printer -> text($_POST['branchContactNumber']);
            $printer -> selectPrintMode();
            $printer -> feed();

            $printer -> setFont(Printer::FONT_B);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
		    // $printer -> text("Registration No. ".$_POST['branchRegNumber']."            Area Code: ".$_POST['branchAreaCodeNumber']."\n");
		    $printer -> text("Vat Reg No. 18141115868            Area Code: 180304\n");
            $printer -> selectPrintMode();
            $printer -> feed();

            //BODY TOP

            $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setUnderline();
            $printer -> text("MONEY RECEIPT");
            $printer -> selectPrintMode();
            $printer -> feed();

            // BODY CUSTOMER INFO
          
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text(columnify('Date:', $_POST['created_at'], 22, 22, 4)."\n");
            $printer -> text(columnify('Name:', $_POST['customerName'], 22, 22, 4)."\n");
            $printer -> text(columnify('CID # :', $_POST['cardNumber'], 22, 22, 4)."\n");
            $printer -> text(columnify('Mobile # :', '+880'.$_POST['customerPhone'], 22, 22, 4)."\n");
            $printer -> text(columnify('Invoice # :', $_POST['invoice_code'], 22, 22, 4)."\n");
            $printer -> text(columnify('Delivery Date :', $_POST['delivery_datetime'], 22, 22, 4)."\n");
            $printer -> selectPrintMode();
            $printer -> feed();

                       // BODY PRODUCT INFO

            $printer -> setEmphasis(true);
            $line = sprintf('%-1.8s %15.18s %10.8s %5.8s %8.8s', 'Qty', 'Article(s)', 'Process', 'P', 'Tk.');
            $printer -> setUnderline();
            $printer -> text($line."\n");
            $printer -> feed();
            $printer -> selectPrintMode();
                
            $printer -> setEmphasis(false);
            if (count($invoice_product_details) > 0) {
              foreach ($invoice_product_details as $key => $productDetail) {
                $productTotalCost = 0.00;
                $product_items = $productDetail['product_items'];
                foreach ($product_items as $k => $item) {
                    $productTotalCost += $item['cost'];
                }
              $line = str_pad((string) $productDetail['item_quantity'], 8, " ", STR_PAD_RIGHT)
                  .str_pad((string) $productDetail['product']['short_name'], 15, " ", STR_PAD_BOTH)
                  .str_pad((string) $productDetail['process']['short_name'], 8, " ", STR_PAD_BOTH)
                  .str_pad((string) $productDetail['packing_type']['short_name'], 5, " ", STR_PAD_BOTH)
                  .str_pad((string) $productTotalCost, 10, " ", STR_PAD_LEFT);
                $printer -> text($line);
                $printer -> feed();
                }
            }
            $printer -> text("\n");
            $printer -> feed();
            

//            $printer -> setEmphasis(true);
//            $printer -> setJustification(Printer::JUSTIFY_LEFT);
//            $printer -> setUnderline();
//            $printer -> text("SUB TOTAL\n");
//            $printer -> selectPrintMode();
//            $printer -> feed();

            $printer -> setEmphasis(true);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text(columnify('Total : '.$_POST['product_item_quantity'], $_POST['without_discount_amount'], 22, 22, 20));

//            foreach ($invoiceDiscounts as $key => $invoiceDiscount) {
//                if ($invoiceDiscount['discount_type'] == 1) {$dType = 'Vip Discount';}
//                elseif ($invoiceDiscount['discount_type'] == 2) {$dType = 'Gift Card Discount';}
//                elseif ($invoiceDiscount['discount_type'] == 2) {$dType = 'Reward Discount';}
//			    elseif ($invoiceDiscount['discount_type'] == 4) {$dType = 'EIC Discount';} //Exceeding Invoice Cost
//                elseif ($invoiceDiscount['discount_type'] == 5) {$dType = 'EPQ Discount';} //Exceeding Product Quantity
//
//                $printer -> text(columnify($dType.' : ', $invoiceDiscount['amount'], 22, 22, 4)."\n");
//                $printer -> feed();
//            }
//
            $totalInvoiceDiscount=0;
            foreach ($invoiceDiscounts as $key => $invoiceDiscount) {

                $totalInvoiceDiscount+=$invoiceDiscount['amount'];
                if ($invoiceDiscount['discount_type'] == 1) {$dType = 'VIP Discount';}
                elseif ($invoiceDiscount['discount_type'] == 2) {$dType = 'GC Discount';}//Gift Card Discount
                elseif ($invoiceDiscount['discount_type'] == 3) {$dType = 'Reward Discount';}
                elseif ($invoiceDiscount['discount_type'] == 4) {$dType = 'EIC Discount';} //Exceeding Invoice Cost
                elseif ($invoiceDiscount['discount_type'] == 5) {$dType = 'EPQ Discount';} //Exceeding Product Quantity

                //$printer -> text(columnify($dType.' : ', $invoiceDiscount['amount'], 22, 22, 4));
                $line = str_pad($dType.':', 15, " ", STR_PAD_RIGHT).
                    str_pad('', 7, " ", STR_PAD_BOTH).
                    str_pad('', 8, " ", STR_PAD_BOTH).
                    str_pad('', 5, " ", STR_PAD_BOTH).
                    str_pad( round($invoiceDiscount['amount']), 10, " ", STR_PAD_LEFT);
                $printer ->text($line."\n");



            }
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer -> setEmphasis(false);
            $printer -> text("Inclusive VAT\n");
            $printer -> selectPrintMode();
            $printer -> feed();

            $printer -> setEmphasis(true);
            $printer -> text(columnify('PAID Amount:', $_POST['received_amount'], 22, 22, 20));

            $printer -> setEmphasis(true);
            $printer -> text(columnify('Due:', $_POST['due_amount'], 22, 22, 20));




           // FOOTER PART
             $printer -> setJustification(Printer::JUSTIFY_CENTER);
             $printer -> setEmphasis(false);
             $printer -> text("Thank You\n");
             $printer -> selectPrintMode();
             $printer -> feed();

		    $printer -> text(date('Y-m-d H:i:s')."\n");

            $printer -> selectPrintMode();
            $printer -> feed();
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        } finally {
            $printer -> cut();
            $printer -> pulse();
            $printer -> close();
        }
   }


function columnify($leftCol, $rightCol, $leftWidth, $rightWidth, $space = 4)
{
    $leftWrapped = wordwrap($leftCol, $leftWidth, "\n", true);
    $rightWrapped = wordwrap($rightCol, $rightWidth, "\n", true);

    $leftLines = explode("\n", $leftWrapped);
    $rightLines = explode("\n", $rightWrapped);
    $allLines = array();
    for ($i = 0; $i < max(count($leftLines), count($rightLines)); $i ++) {
        $leftPart = str_pad(isset($leftLines[$i]) ? $leftLines[$i] : "", $leftWidth, " ");
        $rightPart = str_pad(isset($rightLines[$i]) ? $rightLines[$i] : "", $rightWidth, " ");
        $allLines[] = $leftPart . str_repeat(" ", $space) . $rightPart;
    }
    return implode($allLines, "\n") . "\n";
}
