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

        $connector = new WindowsPrintConnector("BP-T3");
        $printer = new Printer($connector);

        date_default_timezone_set('Asia/Dhaka');
        //$order_date = strtotime( "2017-03-17 09:12:11" );
        //$order_date = date( 'd/m/y g:i A', $order_date );

        $invoice_product_details = $_POST['invoice_product_details'];
        $invoiceDiscounts = $_POST['invoiceDiscounts'];

        try {
		
            $printer -> setFont(Printer::FONT_A);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("Service Ordered                       ".$_POST['serviceType']."\n");
            $printer -> selectPrintMode();


            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            http_response_code(200);
            $im = imagecreatefrompng("image/logo.png");
            $foo = new GdEscposImage();
            $foo -> readImageFromGdResource($im);
            $printer -> bitImage($foo);
            $printer -> selectPrintMode();
            $printer -> feed();


            $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setUnderline();
            $printer -> text($_POST['branchContactNumber']);
            $printer -> selectPrintMode();
            $printer -> feed();

            $printer -> setFont(Printer::FONT_B);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("Vat Reg No. 18141115868            Area Code: 180304\n");
            $printer -> selectPrintMode();
            $printer -> feed();


            $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setUnderline();
            $printer -> text("CUSTOMER COPY");
            $printer -> selectPrintMode();
            $printer -> feed();
			
			$printer -> setFont(Printer::FONT_B);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("(TO BE RETAINED BY PRESSTO UPON DELIVERY)\n");
            $printer -> selectPrintMode();
            $printer -> feed();

            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text(columnify('Date:', date('d-m-Y',strtotime($_POST['created_at'])), 22, 22, 4));
            $printer -> text(columnify('Name:', $_POST['customerName'], 22, 22, 4));
            $printer -> text(columnify('CID # :', $_POST['cardNumber'], 22, 22, 4));
            $printer -> text(columnify('Mobile # :', '+880'.$_POST['customerPhone'], 22, 22, 4));
            $printer -> text(columnify('Invoice # :', $_POST['invoice_code'], 22, 22, 4));
            $printer -> text(columnify('Delivery Date :', date('d-m-Y',strtotime($_POST['delivery_datetime'])), 22, 22, 4));
            $printer -> selectPrintMode();
            $printer -> feed();


            $printer -> setEmphasis(true);
            $line = sprintf('%-1.8s %15.18s %10.8s %5.8s %8.8s', 'Qty', 'Article(s)', 'PRCS', 'PKG', 'Tk.');
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
                    // $productTotalCost += $item['cost_after_discount'];
                }
                  //$printer -> text(columnify((string) $productDetail['item_quantity'].' - '.(string) $productDetail['product']['name'], (string) $productDetail['process']['short_name'].' - '.(string) $productDetail['packing_type']['short_name'].' - '.(string) $productTotalCost, 32, 12, 4));

                  $line = str_pad((string) $productDetail['item_quantity'], 8, " ", STR_PAD_RIGHT).
                  str_pad((string) $productDetail['product']['short_name'], 15, " ", STR_PAD_BOTH).
                  str_pad((string) $productDetail['process']['short_name'], 8, " ", STR_PAD_BOTH).
                  str_pad((string) $productDetail['packing_type']['short_name'], 5, " ", STR_PAD_BOTH).
                  str_pad((string) $productTotalCost, 10, " ", STR_PAD_LEFT);

                $printer -> text($line);
                $printer -> feed();
                }
            }
            $printer -> text("\n");
			$printer -> selectPrintMode();
            $printer -> feed();
            

//            $printer -> setEmphasis(true);
//            $printer -> setJustification(Printer::JUSTIFY_LEFT);
//            $printer -> setUnderline();
//            $printer -> text("SUB TOTAL\n");
//            $printer -> selectPrintMode();
//            $printer -> feed();

            $line = str_pad('SUB TOTAL:', 12, " ", STR_PAD_RIGHT).
                str_pad('', 10, " ", STR_PAD_BOTH).
                str_pad('', 8, " ", STR_PAD_BOTH).
                str_pad('', 5, " ", STR_PAD_BOTH).
                str_pad($_POST['without_discount_amount'], 10, " ", STR_PAD_LEFT);
            $printer -> text($line);
            $printer -> selectPrintMode();
            $printer -> feed();

           // $printer -> setEmphasis(false);
            //$printer -> setJustification(Printer::JUSTIFY_CENTER);
           // $printer -> text(columnify('Total : '.$_POST['product_item_quantity'], $_POST['without_discount_amount'], 22, 22, 4));

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
            $printer -> selectPrintMode();
			$printer -> feed();

            $line = str_pad('Total:'.$_POST['product_item_quantity'], 12, " ", STR_PAD_RIGHT).
                str_pad('', 10, " ", STR_PAD_BOTH).
                str_pad('', 8, " ", STR_PAD_BOTH).
                str_pad('', 5, " ", STR_PAD_BOTH).
                str_pad(($_POST['without_discount_amount']-$totalInvoiceDiscount), 10, " ", STR_PAD_LEFT);
            $printer -> text($line);
            $printer -> selectPrintMode();
            $printer -> feed();


//            $printer -> setJustification(Printer::JUSTIFY_LEFT);
//            $printer -> setEmphasis(false);
//            $printer -> text("Inclusive VAT\n");
//            $printer -> selectPrintMode();
//            $printer -> feed();

            $line = str_pad('Inclusive VAT', 12, " ", STR_PAD_RIGHT).
                str_pad('', 10, " ", STR_PAD_BOTH).
                str_pad('', 8, " ", STR_PAD_BOTH).
                str_pad('', 5, " ", STR_PAD_BOTH).
                str_pad('', 10, " ", STR_PAD_LEFT);
            $printer -> text($line);
            $printer -> selectPrintMode();
            $printer -> feed();


//            $printer -> setEmphasis(true);
//            $printer -> text(columnify('PAID Amount:', $_POST['received_amount'], 22, 22, 4));
//
//            $printer -> setEmphasis(true);
//            $printer -> text(columnify('Due:', (int) $_POST['due_amount'], 22, 22, 4));

            $line = str_pad('PAID Amount:', 12, " ", STR_PAD_RIGHT).
                str_pad('', 10, " ", STR_PAD_BOTH).
                str_pad('', 8, " ", STR_PAD_BOTH).
                str_pad('', 5, " ", STR_PAD_BOTH).
                str_pad($_POST['received_amount'], 10, " ", STR_PAD_LEFT);
            $printer -> text($line);
            $printer -> selectPrintMode();
            $printer -> feed();


            $line = str_pad('Due:', 12, " ", STR_PAD_RIGHT).
                str_pad('', 10, " ", STR_PAD_BOTH).
                str_pad('', 8, " ", STR_PAD_BOTH).
                str_pad('', 5, " ", STR_PAD_BOTH).
                str_pad((int) $_POST['due_amount'], 10, " ", STR_PAD_LEFT);
            $printer -> text($line);
            $printer -> selectPrintMode();
            $printer -> feed();


            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $content = $_POST['invoice_code'];
            $printer -> setBarcodeHeight(120);
            $printer -> setBarcodeWidth(70);
            $type = Printer::BARCODE_CODE39;
            $printer ->  barcode($content, $type);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text("\n * ".$content." *\n");
            $printer -> selectPrintMode();
            $printer -> feed();

			
            $printer -> setEmphasis(false);
            $printer -> text("FOR QUICK PICK-UP GIVE US A QUICK HEADS UP! CALL BEFORE HAND\n");
            $printer -> selectPrintMode();
            $printer -> feed();

            if ($_POST['remarks'] != null) {
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text($_POST['remarks']."\n");
                $printer -> selectPrintMode();
                $printer -> feed();
            }

            date_default_timezone_set('Asia/Dhaka');
            $printer -> text(date('d-m-Y h:i a')."\n");
            $printer -> selectPrintMode();
            $printer -> feed();
			
			$printer -> cut();
			
		// printing tickets	
         for ($i=0; $i <=1 ; $i++) { 
            $printer -> setJustification(Printer::JUSTIFY_CENTER);

            $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setUnderline();
            $printer -> setEmphasis(true);
            $printer->text("FACTORY COPY\n");
            $printer -> feed();
		   
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setEmphasis(true);
            $printer -> setTextSize(4,4);
            $printer->text("QTY: ".$_POST['product_item_quantity']);
            $printer -> feed();

              $printer -> selectPrintMode();
              $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
              $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
              $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
              $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
              $printer -> setJustification(Printer::JUSTIFY_RIGHT);
              $printer -> setEmphasis(true);
              $printer -> text($_POST['invoice_code']."\n");
              $printer -> feed();

            $printer -> selectPrintMode();
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text($_POST['serviceType']."\n");
            $printer -> feed();

//            $printer -> selectPrintMode();
//            $printer -> setJustification(Printer::JUSTIFY_CENTER);
//            $printer -> text("Delivery Date:       ".date('d-m-Y',strtotime($_POST['delivery_datetime']))."\n");
//            $printer -> feed();
//
//            $printer -> selectPrintMode();
//            $printer -> setJustification(Printer::JUSTIFY_CENTER);
//            $printer -> text("Customer ID:                ".$_POST['cardNumber']."\n");
//            $printer -> feed();
//
//            $printer -> selectPrintMode();
//            $printer -> setJustification(Printer::JUSTIFY_CENTER);
//            $printer -> text("Customer Name:                ".$_POST['customerName']."\n");
//            $printer -> feed();
//
//            $printer -> selectPrintMode();
//            $printer -> setJustification(Printer::JUSTIFY_CENTER);
//            $printer -> text("Date:                ".date('d-m-Y',strtotime($_POST['created_at']))."\n");
//            $printer -> feed();

             $printer -> selectPrintMode();
             $printer -> setJustification(Printer::JUSTIFY_CENTER);
             $printer -> text(columnify('Delivery Date :', date('d-m-Y',strtotime($_POST['delivery_datetime'])), 22, 22, 4));
             $printer -> text(columnify('CID:', $_POST['cardNumber'], 22, 22, 4));
             $printer -> text(columnify('Name:', $_POST['customerName'], 22, 22, 4));
             $printer -> text(columnify('Date:', date('d-m-Y',strtotime($_POST['created_at'])), 22, 22, 4));
             $printer -> selectPrintMode();
             $printer -> feed();

            $printer -> setEmphasis(true);
            $line = str_pad('Qty', 8, " ", STR_PAD_RIGHT).str_pad('Item', 15, " ", STR_PAD_BOTH).str_pad('Process', 8, " ", STR_PAD_BOTH).str_pad('SL', 5, " ", STR_PAD_BOTH);
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
                    $productTotalCost += $item['cost_after_discount'];
                }
              $line = str_pad((string) $productDetail['item_quantity'], 8, " ", STR_PAD_RIGHT).str_pad((string) $productDetail['product']['short_name'], 15, " ", STR_PAD_BOTH).str_pad((string) $productDetail['process']['short_name'], 8, " ", STR_PAD_BOTH).str_pad((string) $productDetail['packing_type']['short_name'], 5, " ", STR_PAD_BOTH);
                $printer -> text($line);
                $printer -> feed();
                }
            }
                $printer -> text("\n");
                $printer -> feed();

             if ($_POST['remarks'] != null) {
                 $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
                 $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                 $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                 $printer -> text($_POST['remarks']."\n");
                 $printer -> selectPrintMode();
                 $printer -> feed();
             }

            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $content = $_POST['invoice_code'];
            $printer -> setBarcodeHeight(120);
            $printer -> setBarcodeWidth(70);
            $type = Printer::BARCODE_CODE39;
            $printer ->  barcode($content, $type);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text("* ".$content." *\n");
            $printer -> selectPrintMode();
            $printer -> feed();


            $printer -> selectPrintMode();
            $printer -> setJustification(Printer::JUSTIFY_RIGHT);
            date_default_timezone_set('Asia/Dhaka');
            $printer -> text(date('d-m-Y h:i a')."\n");
            $printer -> feed();
            $printer->cut();
		 }	
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
			return 'server error';
        } finally {
            $printer -> pulse();	
            $printer -> close();
			return 'success';
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
