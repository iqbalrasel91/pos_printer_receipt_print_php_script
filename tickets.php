<?php
  for ($i=0; $i <=1 ; $i++) { 
            $printer -> setJustification(Printer::JUSTIFY_CENTER);

            $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setUnderline();
            $printer -> setEmphasis(true);
            $printer->text("FACTORY COPY\n");
            $printer -> feed();
		   
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> setEmphasis(true);
            // $printer -> setTextSize(4,4);
            // $printer->text("QTY: ".$_POST['product_quantity']);
            // $printer -> feed();

              // $printer -> selectPrintMode();
              // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
              // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
              // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
              // $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
              // $printer -> setJustification(Printer::JUSTIFY_RIGHT);
              // $printer -> setEmphasis(true);
              // $printer -> text($_POST['invoice_code']."\n");
              // $printer -> feed();

            // $printer -> selectPrintMode();
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            // $printer -> setJustification(Printer::JUSTIFY_CENTER);
            // $printer -> text($_POST['serviceType']."\n");
            // $printer -> feed();

            // $printer -> selectPrintMode();
            // $printer -> setJustification(Printer::JUSTIFY_CENTER);
            // $printer -> text("Delivery Date:       ".$_POST['delivery_datetime']."\n");
            // $printer -> feed();

            // $printer -> selectPrintMode();
            // $printer -> setJustification(Printer::JUSTIFY_CENTER);
            // $printer -> text("Customer ID:                ".$_POST['cardNumber']."\n");
            // $printer -> feed();

            // $printer -> selectPrintMode();
            // $printer -> setJustification(Printer::JUSTIFY_CENTER);
            // $printer -> text("Date:                ".$_POST['created_at']."\n");
            // $printer -> feed();


            // $printer -> setEmphasis(true);
            // $line = str_pad('Qty', 8, " ", STR_PAD_RIGHT).str_pad('Item', 15, " ", STR_PAD_BOTH).str_pad('Process', 8, " ", STR_PAD_BOTH).str_pad('SL', 5, " ", STR_PAD_BOTH);
            // $printer -> setUnderline();
            // $printer -> text($line."\n");
            // $printer -> feed();
            // $printer -> selectPrintMode();


            // $printer -> setEmphasis(false);
           // if (count($invoice_product_details) > 0) {
              // foreach ($invoice_product_details as $key => $productDetail) {
                // $productTotalCost = 0.00;
                // $product_items = $productDetail['product_items'];
                // foreach ($product_items as $k => $item) {
                    // $productTotalCost += $item['cost_after_discount'];
                // }
              // $line = str_pad((string) $productDetail['item_quantity'], 8, " ", STR_PAD_RIGHT).str_pad((string) $productDetail['product']['name'], 15, " ", STR_PAD_BOTH).str_pad((string) $productDetail['process']['short_name'], 8, " ", STR_PAD_BOTH).str_pad((string) $productDetail['packing_type']['short_name'], 5, " ", STR_PAD_BOTH);
                // $printer -> text($line);
                // $printer -> feed();
                // }
            // }
                // $printer -> text("\n");
                // $printer -> feed();


            // $printer -> setJustification(Printer::JUSTIFY_CENTER);
            // $content = $_POST['invoice_code'];
            // $printer -> setBarcodeHeight(120);
            // $printer -> setBarcodeWidth(70);
            // $type = Printer::BARCODE_CODE39;
            // $printer ->  barcode($content, $type);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> text("* ".$content." *\n");
            // $printer -> selectPrintMode();
            // $printer -> feed();


            // $printer -> selectPrintMode();
            // $printer -> setJustification(Printer::JUSTIFY_RIGHT);
            // $printer -> text(date('Y-m-d H:i:s')."\n");
            // $printer -> feed();
            $printer->cut();
		}
		
?>