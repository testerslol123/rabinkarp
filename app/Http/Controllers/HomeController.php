<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Http\UploadedFile;
use File;
use Storage;
use Auth;

use App\Http\Controllers\Pdf2Text;

class HomeController extends Controller
{

    public function index()
    {
        if(Auth::check()) {
            return view('home')->with('userInfo', Auth::user());
        } else {
            return view('home');
        }
    }

    public function aboutus() {
        if(Auth::check()) {
            return view('aboutus/aboutus')->with('userInfo', Auth::user());
        } else {
            return view('aboutus/aboutus');
        }
    }

    public function testdocument() {
        $source = base_path() . '/ext/bab4.pdf';
        echo $source;
        echo $this->read_pdf($source);
    }



    public function submitDocument(Request $request) {
        $this->validate($request, [
            'doc1' => 'required|mimes:doc,docx,pdf,txt',
            'doc2' => 'required|mimes:doc,docx,pdf,txt',
        ]);

        if($request->hasFile('doc1') && $request->hasFile('doc2')) {
            $file1 = $request->file('doc1');
            $file2 = $request->file('doc2');

            $uploadFile1 = $this->uploadFile($file1);
            echo "<br/>";
            $uploadFile2 = $this->uploadFile($file2);

            echo "<br/>";
            $stemming_doc1 = $this->stemming($uploadFile1, $file1);
            // dapat per kalimat


            $stopword_doc1 = $this->stopWord($stemming_doc1);

            $stemming_doc2 = $this->stemming($uploadFile2, $file2);
            // dapat per kalimat

            $stopword_doc2 = $this->stopWord($stemming_doc2);
            // print_r($stopword_doc1);

            $kumpulanKataDoc1 = '';
            foreach($stopword_doc1 as $stopword) {
                $kumpulanKataDoc1 = $kumpulanKataDoc1 . ' ' . $stopword;
            }

            $kumpulanKataDoc2 = '';
            foreach($stopword_doc2 as $stopword) {
                $kumpulanKataDoc2 = $kumpulanKataDoc2 . ' ' . $stopword;
            }

            $rkDoc1 = array();
            $rkDoc2 = array();

            $rkString1 = '';
            $rkString2 = '';

            foreach($stopword_doc1 as $stopword) {
                $hasil = $this->rabinKarp($stopword, $kumpulanKataDoc2);
                if(!empty($hasil)) {
                    array_push($rkDoc1, $hasil);
                    $rkString1 .= ' ' . $hasil;
                }
            }

            foreach($stopword_doc2 as $stopword) {
                $hasil = $this->rabinKarp($stopword, $kumpulanKataDoc1);
                if(!empty($hasil)) {
                    array_push($rkDoc2, $hasil);
                    $rkString2 .= ' ' . $hasil;
                }
            }

            $similarity = $this->DiceMatch($rkString1, $rkString2);

            return view('hasil2')->with([
                'doc1' => $stopword_doc1,
                'doc2' => $stopword_doc2,
                'rkDoc1' => $rkDoc1,
                'rkDoc2' => $rkDoc2,
                'similarity' => $similarity,
            ]);
        } else {
            return redirect('/');
        }
    }


    public function submit2 (Request $request) {
        $file1 = $request->file('doc1');
        $file2 = $request->file('doc2');

        $uploadFile1 = $this->uploadFile($file1);
        $uploadFile2 = $this->uploadFile($file2);

        $stemming_doc1 = $this->stemming($uploadFile1, $file1);
        $stemming_doc2 = $this->stemming($uploadFile2, $file2);

        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
        $stemmer  = $stemmerFactory->createStemmer();

        $tampung_stopword_1 = array();
        foreach($stemming_doc1 as $stem1) {
          array_push($tampung_stopword_1, $this->stopWord($stemmer->stem($stem1)));
        }

        $tampung_stopword_2 = array();
        foreach($stemming_doc2 as $stem2) {
          array_push($tampung_stopword_2, $this->stopWord($stemmer->stem($stem2)));
        }

        $setorKalimat = array();
        $i = 0;
        foreach($stemming_doc1 as $data) {
          foreach($stemming_doc2 as $data2) {
            if($data == $data2) {
              $setorKalimat[$i] = $data;
              $i++;
            }
          }
        }


        $potongKata1 = $tampung_stopword_1;
        $potongKata2 = $tampung_stopword_2;

        $rkDoc1 = array();
        $rkDoc2 = array();

        $rkString1 = "";
        $rkString2 = "";

        $kumpulanKataDoc1 = '';
        foreach($potongKata1 as $kata) {
          foreach($kata as $word) {
            $kumpulanKataDoc1 = $kumpulanKataDoc1 . ' ' . $word;
          }
        }

        $kumpulanKataDoc2 = '';
        foreach($potongKata2 as $kata) {
          foreach($kata as $word) {
            $kumpulanKataDoc2 = $kumpulanKataDoc2 . ' ' . $word;
          }
        }

        foreach($potongKata1 as $stopword) {
          foreach($stopword as $kata) {
            $hasil = $this->rabinKarp($kata, $kumpulanKataDoc2);
            if(!empty($hasil)) {
                array_push($rkDoc1, $hasil);
                $rkString1 .= ' ' . $hasil;
            }
          }
        }

        foreach($potongKata2 as $stopword) {
          foreach($stopword as $kata) {
            $hasil = $this->rabinKarp($kata, $kumpulanKataDoc2);
            if(!empty($hasil)) {
                array_push($rkDoc2, $hasil);
                $rkString2 .= ' ' . $hasil;
            }
          }
        }


        $similarity = 0;
        $similarity = $this->DiceMatch($rkString1, $rkString2);


        return view('hasil')->with([
            'doc1' => $stemming_doc1, // hasilnya per kalimat
            'doc2' => $stemming_doc2,
            'potongKata1' => $potongKata1,
            'potongKata2' => $potongKata2,
            'rkDoc1' => $rkDoc1,
            'rkDoc2' => $rkDoc2,
            'similarity' => $similarity,
            'setorKalimat' => $setorKalimat
        ]);
    }


    function DiceMatch($string1, $string2) {
        if (empty($string1) || empty($string2)) return 0;

        if ($string1 == $string2) return 1;

        $strlen1 = strlen($string1);
        $strlen2 = strlen($string2);

        if ($strlen1 < 2 || $strlen2 < 2) return 0;

        $length1 = $strlen1 - 1;
        $length2 = $strlen2 - 1;

        $matches = 0;
        $i = 0;
        $j = 0;

        while ($i < $length1 && $j < $length2)
        {
            $a = substr($string1, $i, 2);
            $b = substr($string2, $j, 2);
            $cmp = strcasecmp($a, $b);

            if ($cmp == 0) {
                $matches += 2;
            }

            ++$i;
            ++$j;
        }
        return $matches / ($length1 + $length2);
    }



    private function rabinKarp ($needle, $haystack) {
        $nlen = strlen($needle);
        $hlen = strlen($haystack);
        $nhash = 0;
        $hhash = 0;

        // Special cases that don't require the rk algo:
        // if needle is longer than haystack, no possible match
        if ($nlen > $hlen) {
            return false;
        }
        // If they're the same size, they must just match
        if ($nlen == $hlen) {
            return ($needle === $haystack);
        }

        // Compute hash of $needle and $haystack[0..needle.length]
        // This is a very primitive hashing method for illustrative purposes
        // only. You'll want to modify each value based on its position in
        // the string as per Gumbo's example above (left shifting)
        for ($i = 0; $i < $nlen; ++$i) {
            $nhash += ord($needle[$i]);
            $hhash += ord($haystack[$i]);
        }

        // Go through each position of needle and see if
        // the hashes match, then do a comparison at that point
        for ($i = 0, $c = $hlen - $nlen; $i <= $c; ++$i) {
                // If the hashes match, there's a good chance the next $nlen characters of $haystack matches $needle
                if ($nhash == $hhash && $needle === substr($haystack, $i, $nlen)) {
                    return $i;
                }
                // If we've reached the end, don't try to update the hash with
                // the code following this if()
                if ($i == $c) {
                    return false;
                }

            // Update hhash to the next position by subtracting the
            // letter we're removing and adding the letter we're adding
            $hhash = ($hhash - ord($haystack[$i])) + ord($haystack[$i + $nlen]);
            }
            return false;
        }


    private function uploadFile ($requestFile) {
        //Display File Name
        // echo 'File Name: '.$requestFile->getClientOriginalName();
        // echo '<br>';

        // //Display File Extension
        // echo 'File Extension: '.$requestFile->getClientOriginalExtension();
        // echo '<br>';

        // //Display File Real Path
        // echo 'File Real Path: '.$requestFile->getRealPath();
        // echo '<br>';

        // //Display File Size
        // echo 'File Size: '.$requestFile->getSize();
        // echo '<br>';

        // //Display File Mime Type
        // echo 'File Mime Type: '.$requestFile->getMimeType();

        //Move Uploaded File
        $destinationPath = 'uploads';
        $requestFile->move($destinationPath,$requestFile->getClientOriginalName());
        return base_path() . '/uploads/' . $requestFile->getClientOriginalName();
    }


    private function stemming($pathFile, $file) {
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
        $stemmer  = $stemmerFactory->createStemmer();

        // Put Format of File here 
        // echo $file->getClientOriginalExtension();
        switch ($file->getClientOriginalExtension()) {
            case 'docx' :
                $sentence = $this->read_docx($pathFile);
                break;
            case 'doc' : 
                $sentence = $this->read_doc($pathFile);
                break;
            case 'pdf' :
                $sentence = $this->read_pdf($pathFile);
            break;
            case 'txt' :
                $sentence = $this->read_txt($pathFile);
            break;
            default:
                echo "Wrong format file";
        }

        $array_kalimat = array();
        $i = 0;
        $j = 0;
        $stringPakai = '';
        $tampung_kata = '';


        $outputParagraph = explode("\n", $sentence);
        foreach($outputParagraph as $paragraph) {
          $output = explode(" ", $paragraph);
          foreach($output as $data) {
            $stringPakai .= $data . " ";
            if (strpos($data, ".")){
              $stringPakai = $stringPakai . "_";
              $array_kalimat = explode("_", $stringPakai);
            }            
          }
        }


        return $array_kalimat;
    }

    private function stopWord($hasilStemming) {
        $words = explode(" ", strtolower($hasilStemming));
        $numWordsIn = count($words);

        $stopWords = explode(" ", "ada adalah adanya adapun agak agaknya agar akan akankah akhir akhiri akhirnya aku akulah amat amatlah anda andalah antar antara antaranya apa apaan apabila apakah apalagi apatah artinya asal asalkan atas atau ataukah ataupun awal awalnya bagai bagaikan bagaimana bagaimanakah bagaimanapun bagi bagian bahkan bahwa bahwasanya baik bakal bakalan balik banyak bapak baru bawah beberapa begini beginian beginikah beginilah begitu begitukah begitulah begitupun bekerja belakang belakangan belum belumlah benar benarkah benarlah berada berakhir berakhirlah berakhirnya berapa berapakah berapalah berapapun berarti berawal berbagai berdatangan beri berikan berikut berikutnya berjumlah berkali-kali berkata berkehendak berkeinginan berkenaan berlainan berlalu berlangsung berlebihan bermacam bermacam-macam bermaksud bermula bersama bersama-sama bersiap bersiap-siap bertanya bertanya-tanya berturut berturut-turut bertutur berujar berupa besar betul betulkah biasa biasanya bila bilakah bisa bisakah boleh bolehkah bolehlah buat bukan bukankah bukanlah bukannya bulan bung cara caranya cukup cukupkah cukuplah cuma dahulu dalam dan dapat dari daripada datang dekat demi demikian demikianlah dengan depan di dia diakhiri diakhirinya dialah diantara diantaranya diberi diberikan diberikannya dibuat dibuatnya didapat didatangkan digunakan diibaratkan diibaratkannya diingat diingatkan diinginkan dijawab dijelaskan dijelaskannya dikarenakan dikatakan dikatakannya dikerjakan diketahui diketahuinya dikira dilakukan dilalui dilihat dimaksud dimaksudkan dimaksudkannya dimaksudnya diminta dimintai dimisalkan dimulai dimulailah dimulainya dimungkinkan dini dipastikan diperbuat diperbuatnya dipergunakan diperkirakan diperlihatkan diperlukan diperlukannya dipersoalkan dipertanyakan dipunyai diri dirinya disampaikan disebut disebutkan disebutkannya disini disinilah ditambahkan ditandaskan ditanya ditanyai ditanyakan ditegaskan ditujukan ditunjuk ditunjuki ditunjukkan ditunjukkannya ditunjuknya dituturkan dituturkannya diucapkan diucapkannya diungkapkan dong dua dulu empat enggak enggaknya entah entahlah guna gunakan hal hampir hanya hanyalah hari harus haruslah harusnya hendak hendaklah hendaknya hingga ia ialah ibarat ibaratkan ibaratnya ibu ikut ingat ingat-ingat ingin inginkah inginkan ini inikah inilah itu itukah itulah jadi jadilah jadinya jangan jangankan janganlah jauh jawab jawaban jawabnya jelas jelaskan jelaslah jelasnya jika jikalau juga jumlah jumlahnya justru kala kalau kalaulah kalaupun kalian kami kamilah kamu kamulah kan kapan kapankah kapanpun karena karenanya kasus kata katakan katakanlah katanya ke keadaan kebetulan kecil kedua keduanya keinginan kelamaan kelihatan kelihatannya kelima keluar kembali kemudian kemungkinan kemungkinannya kenapa kepada kepadanya kesampaian keseluruhan keseluruhannya keterlaluan ketika khususnya kini kinilah kira kira-kira kiranya kita kitalah kok kurang lagi lagian lah lain lainnya lalu lama lamanya lanjut lanjutnya lebih lewat lima luar macam maka makanya makin malah malahan mampu mampukah mana manakala manalagi masa masalah masalahnya masih masihkah masing masing-masing mau maupun melainkan melakukan melalui melihat melihatnya memang memastikan memberi memberikan membuat memerlukan memihak meminta memintakan memisalkan memperbuat mempergunakan memperkirakan memperlihatkan mempersiapkan mempersoalkan mempertanyakan mempunyai memulai memungkinkan menaiki menambahkan menandaskan menanti menanti-nanti menantikan menanya menanyai menanyakan mendapat mendapatkan mendatang mendatangi mendatangkan menegaskan mengakhiri mengapa mengatakan mengatakannya mengenai mengerjakan mengetahui menggunakan menghendaki mengibaratkan mengibaratkannya mengingat mengingatkan menginginkan mengira mengucapkan mengucapkannya mengungkapkan menjadi menjawab menjelaskan menuju menunjuk menunjuki menunjukkan menunjuknya menurut menuturkan menyampaikan menyangkut menyatakan menyebutkan menyeluruh menyiapkan merasa mereka merekalah merupakan meski meskipun meyakini meyakinkan minta mirip misal misalkan misalnya mula mulai mulailah mulanya mungkin mungkinkah nah naik namun nanti nantinya nyaris nyatanya oleh olehnya pada padahal padanya pak paling panjang pantas para pasti pastilah penting pentingnya per percuma perlu perlukah perlunya pernah persoalan pertama pertama-tama pertanyaan pertanyakan pihak pihaknya pukul pula pun punya rasa rasanya rata rupanya saat saatnya saja sajalah saling sama sama-sama sambil sampai sampai-sampai sampaikan sana sangat sangatlah satu saya sayalah se sebab sebabnya sebagai sebagaimana sebagainya sebagian sebaik sebaik-baiknya sebaiknya sebaliknya sebanyak sebegini sebegitu sebelum sebelumnya sebenarnya seberapa sebesar sebetulnya sebisanya sebuah sebut sebutlah sebutnya secara secukupnya sedang sedangkan sedemikian sedikit sedikitnya seenaknya segala segalanya segera seharusnya sehingga seingat sejak sejauh sejenak sejumlah sekadar sekadarnya sekali sekali-kali sekalian sekaligus sekalipun sekarang sekarang sekecil seketika sekiranya sekitar sekitarnya sekurang-kurangnya sekurangnya sela selain selaku selalu selama selama-lamanya selamanya selanjutnya seluruh seluruhnya semacam semakin semampu semampunya semasa semasih semata semata-mata semaunya sementara semisal semisalnya sempat semua semuanya semula sendiri sendirian sendirinya seolah seolah-olah seorang sepanjang sepantasnya sepantasnyalah seperlunya seperti sepertinya sepihak sering seringnya serta serupa sesaat sesama sesampai sesegera sesekali seseorang sesuatu sesuatunya sesudah sesudahnya setelah setempat setengah seterusnya setiap setiba setibanya setidak-tidaknya setidaknya setinggi seusai sewaktu siap siapa siapakah siapapun sini sinilah soal soalnya suatu sudah sudahkah sudahlah supaya tadi tadinya tahu tahun tak tambah tambahnya tampak tampaknya tandas tandasnya tanpa tanya tanyakan tanyanya tapi tegas tegasnya telah tempat tengah tentang tentu tentulah tentunya tepat terakhir terasa terbanyak terdahulu terdapat terdiri terhadap terhadapnya teringat teringat-ingat terjadi terjadilah terjadinya terkira terlalu terlebih terlihat termasuk ternyata tersampaikan tersebut tersebutlah tertentu tertuju terus terutama tetap tetapi tiap tiba tiba-tiba tidak tidakkah tidaklah tiga tinggi toh tunjuk turut tutur tuturnya ucap ucapnya ujar ujarnya umum umumnya ungkap ungkapnya untuk usah usai waduh wah wahai waktu waktunya walau walaupun wong yaitu yakin yakni yang");


        $words = array_diff($words,$stopWords);
        $words = array_values($words);//re-indexes array
        $numWordsOut = count($words);
        return $words;
    }

    private function read_pdf ($pathFile) {
        $a = new PDF2Text();
        $a->setFilename($pathFile); 
        $a->decodePDF();
        return $a->output(); 
    }

    private function read_txt($pathFile) {
        return file_get_contents($pathFile);
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
        $line = @fread($fileHandle, filesize($filename));   
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

}
