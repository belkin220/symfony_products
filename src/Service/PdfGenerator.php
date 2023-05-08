<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator extends Dompdf
{
    public function generatePdf($html)
    {
        $options = new Options(); 
        $this->loadHtml($html); 
        $this->setPaper('A4', 'landscape');
        $options->set([
            'isPhpEnabled'=> true,
            'defaultFont' => 'Arial',
            'isRemoteEnabled'=> true
        ]);
        
        $this->setOptions($options);
        $this->render();
       
        return 
            $this->stream('resume.pdf', [
                "Attachment" => false]);
    }


}