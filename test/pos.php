<?php
/* Change to the correct path if you copy this example! */
require __DIR__ . '/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

        $connector = new WindowsPrintConnector("BP-T3");
        $printer = new Printer($connector);


        $order_date = strtotime( "2017-03-17 09:12:11" );
        $order_date = date( 'd/m/y g:i A', $order_date );
        // selecting logo

        try {

            //TOP TEXT

            $printer -> setFont(Printer::FONT_A);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("Service Ordered                       ".$_GET['serviceType']."\n");
            $printer -> selectPrintMode();

            // TOP LOGO

            // $printer -> setJustification(Printer::JUSTIFY_CENTER);
            // http_response_code(200);
            // $im = imagecreatefrompng("admin_assets/img/logo.png");
            // $foo = new GdEscposImage();
            // $foo -> readImageFromGdResource($im);
            // $printer -> bitImage($foo);
            // $printer -> selectPrintMode();
            // $printer -> feed();

            // TOP BOTTOM

            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> setUnderline();
            // $printer -> text("01884619244");
            // $printer -> selectPrintMode();
            // $printer -> feed();

            // $printer -> setFont(Printer::FONT_B);
            // $printer -> setJustification(Printer::JUSTIFY_CENTER);
            // $printer -> text("Registration No. 123456789            Area Code: 123456\n");
            // $printer -> selectPrintMode();
            // $printer -> feed();

            //BODY TOP

            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> setUnderline();
            // $printer -> text("CUSTOMER COPY");
            // $printer -> selectPrintMode();
            // $printer -> feed();

            // $printer -> setJustification(Printer::JUSTIFY_CENTER);
            // $printer -> text("(TO BE RETAINED BY PRESSTO UPON)\n");
            // $printer -> selectPrintMode();
            // $printer -> feed();

            // BODY CUSTOMER INFO

                // $printer -> setJustification(Printer::JUSTIFY_CENTER);
                // $printer -> text(columnify('Date:', '10/11/2017 7:13:38 PM', 22, 22, 4)."\n");
                // $printer -> text(columnify('Name:', 'RAASIKH MAHMUD', 22, 22, 4)."\n");
                // $printer -> text(columnify('ID # :', '10013296', 22, 22, 4)."\n");
                // $printer -> text(columnify('Mobile # :', '+8801715229663', 22, 22, 4)."\n");
                // $printer -> text(columnify('Invoice # :', 'GL01091', 22, 22, 4)."\n");
                // $printer -> text(columnify('Delivery Date :', '15-Oct-2017 4:30 PM', 22, 22, 4)."\n");
                // $printer -> selectPrintMode();
                // $printer -> feed();

            // BODY PRODUCT INFO

            // $printer -> setEmphasis(true);
            // $line = sprintf('%-1.8s %15.18s %10.8s %5.8s %8.8s', 'Qty', 'Article(s)', 'Process', 'P', 'Tk.');
            // $printer -> setUnderline();
            // $printer -> text($line."\n");
            // $printer -> selectPrintMode();
            // $line = sprintf('%-1.8s %18.18s %8.8s %5.8s %8.8s', '3', 'Emtiaz Zahid', 'LD', 'F', '285');
            // $printer -> setEmphasis(false);
            // $printer -> text($line."\n");
            // $line = sprintf('%-1.8s %18.18s %8.8s %5.8s %8.8s', '3', 'Zahid', 'LD', 'F', '5');
            // $printer -> text($line."\n");;
            // $printer -> feed();

            // $printer -> setEmphasis(true);
            // $printer -> setJustification(Printer::JUSTIFY_LEFT);
            // $printer -> setUnderline();
            // $printer -> text("SUB TOTAL\n");
            // $printer -> selectPrintMode();
            // $printer -> feed();

            // $printer -> setEmphasis(true);
            // $printer -> setJustification(Printer::JUSTIFY_CENTER);
            // $printer -> text(columnify('Total : 5', '535', 22, 22, 20));
            // $printer -> text(columnify('Reward : ', '27', 22, 22, 20));

            // $printer -> setJustification(Printer::JUSTIFY_LEFT);
            // $printer -> setEmphasis(false);
            // $printer -> text("Inclusive VAT\n");
            // $printer -> selectPrintMode();
            // $printer -> feed();

            // $printer -> setEmphasis(true);
            // $printer -> text(columnify('PAID Amount:', '', 22, 22, 20));

            // $printer -> setEmphasis(true);
            // $printer -> text(columnify('Due:', '508', 22, 22, 20));

            //BODY BARCODE

            // $printer -> setJustification(Printer::JUSTIFY_CENTER);
            // $content = 'GL01091';
            // $printer -> setBarcodeHeight(120);
            // $printer -> setBarcodeWidth(70);
            // $type = Printer::BARCODE_CODE39;
            // $printer ->  barcode($content, $type);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> text("\n * ".$content." *\n");
            // $printer -> selectPrintMode();
            // $printer -> feed();


            //FOOTER PART
            // $printer -> setEmphasis(false);
            // $printer -> text("FOR QUICK PICK-UP GIVE US A QUICK HEADS UP! CALL BEFORE HAND\n");
            // $printer -> selectPrintMode();
            // $printer -> feed();

            // $printer -> text("10/11/2017 7:13:38 PM\n");
            $printer -> selectPrintMode();
            $printer -> feed();
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        } finally {
            $printer -> cut();
            $printer -> pulse();
            $printer -> close();
            header("Location: ".$orderReceiveUrl);
        }
