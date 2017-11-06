<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Http\UploadedFile;
use File;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function testdocument() {
        // Read contents
// $name = basename(__FILE__, '.php');
// $source = __DIR__ . "/resources/{$name}.docx";
// echo date('H:i:s'), " Reading contents from `{$source}`", EOL;
// $phpWord = \PhpOffice\PhpWord\IOFactory::load($source);
// // Save file
// echo write($phpWord, basename(__FILE__, '.php'), $writers);

        $source = base_path() . '/ext/bab4.docx';
        echo $source;

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $document = $phpWord->loadTemplate($source);
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");
        // $phpWord = IOFactory::createReader('Word2007')->load($source);

        // foreach($phpWord->getSections() as $section) {
        //     foreach($section->getElements() as $element) {
        //         if(method_exists($element,'getText')) {
        //             echo($element->getText() . "<br>");
        //         }
        //     }
        // }

        // $phpWord = \PhpOffice\PhpWord\IOFactory::load($source);
        // echo $phpWord;
        // return view('checkDocs');
    }

    public function testdocument2 () {
            // Creating the new document...
            $phpWord = new \PhpOffice\PhpWord\PhpWord();

            /* Note: any element you append to a document must reside inside of a Section. */

            // Adding an empty Section to the document...
            $section = $phpWord->addSection();
            // Adding Text element to the Section having font styled by default...
            $section->addText(
                '"Learn from yesterday, live for today, hope for tomorrow. '
                    . 'The important thing is not to stop questioning." '
                    . '(Albert Einstein)'
            );

            /*
             * Note: it's possible to customize font style of the Text element you add in three ways:
             * - inline;
             * - using named font style (new font style object will be implicitly created);
             * - using explicitly created font style object.
             */

            // Adding Text element with font customized inline...
            $section->addText(
                '"Great achievement is usually born of great sacrifice, '
                    . 'and is never the result of selfishness." '
                    . '(Napoleon Hill)',
                array('name' => 'Tahoma', 'size' => 10)
            );

            // Adding Text element with font customized using named font style...
            $fontStyleName = 'oneUserDefinedStyle';
            $phpWord->addFontStyle(
                $fontStyleName,
                array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
            );
            $section->addText(
                '"The greatest accomplishment is not in never falling, '
                    . 'but in rising again after you fall." '
                    . '(Vince Lombardi)',
                $fontStyleName
            );

            // Adding Text element with font customized using explicitly created font style object...
            $fontStyle = new \PhpOffice\PhpWord\Style\Font();
            $fontStyle->setBold(true);
            $fontStyle->setName('Tahoma');
            $fontStyle->setSize(13);
            $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
            $myTextElement->setFontStyle($fontStyle);

            // Saving the document as OOXML file...
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save('helloWorld.docx');

            // Saving the document as ODF file...
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
            $objWriter->save('helloWorld.odt');

            // Saving the document as HTML file...
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
            $objWriter->save('helloWorld.html');

            /* Note: we skip RTF, because it's not XML-based and requires a different example. */
            /* Note: we skip PDF, because "HTML-to-PDF" approach is used to create PDF documents. */
    }






    public function submitDocument(Request $request) {
        if($request->hasFile('doc1')) {
            print_r($request->doc1);
        }

        if($request->hasFile('doc2')) {
            print_r($request->doc2);
        }
    }

    public function testdocument3 () {
        $content = File::get(base_path() .'/ext/file.txt');
        print_r($content);
        // foreach($content as $line) {
        // //use $line
        // echo $line; 
        // }

    }

}
