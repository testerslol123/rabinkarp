<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Http\UploadedFile;
use File;
use Storage;

class HomeController extends Controller
{

    public function index()
    {
        return view('home');
    }

    public function testdocument() {
        $source = base_path() . '/ext/bab4.docx';
        echo $source;

        echo $this->read_docx($source);
    }

    public function submitDocument(Request $request) {
        $file = $request->file('doc1');

        //Display File Name
        echo 'File Name: '.$file->getClientOriginalName();
        echo '<br>';

        //Display File Extension
        echo 'File Extension: '.$file->getClientOriginalExtension();
        echo '<br>';

        //Display File Real Path
        echo 'File Real Path: '.$file->getRealPath();
        echo '<br>';

        //Display File Size
        echo 'File Size: '.$file->getSize();
        echo '<br>';

        //Display File Mime Type
        echo 'File Mime Type: '.$file->getMimeType();

        //Move Uploaded File
        $destinationPath = 'uploads';
        $file->move($destinationPath,$file->getClientOriginalName());

        echo "<br/>";
        $fileDummy = base_path() . '/uploads/' . $file->getClientOriginalName();
        $stemming_doc1 = $this->stemming($fileDummy);
        $stopword_doc1 = $this->stopWord($stemming_doc1);
        print_r($stopword_doc1);
    }

    private function stemming($fileDummy) {
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
        $stemmer  = $stemmerFactory->createStemmer();
        $sentence = $this->read_docx($fileDummy);
        $output   = $stemmer->stem($sentence);
        return $output;
    }

    private function stopWord($hasilStemming) {
        $words = explode(" ",strtolower($hasilStemming));
        $numWordsIn = count($words);
        $stopWords = explode("\n", strtolower(asset('ext/stop_words.txt')));

        print_r($stopWords);
        $words = array_diff($words,$stopWords);
        $words = array_values($words);//re-indexes array
        $numWordsOut = count($words);
        return $words;
    }

    private function read_docx($filename){

        $striped_content = '';
        $content = '';

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }

    private function read_doc($filename) {
        $fileHandle = fopen($filename, "r");
        $line = @fread($fileHandle, filesize($this->filename));   
        $lines = explode(chr(0x0D),$line);
        $outtext = "";
        foreach($lines as $thisline)
          {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== FALSE)||(strlen($thisline)==0))
              {
              } else {
                $outtext .= $thisline." ";
              }
          }
         $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
        return $outtext;
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
