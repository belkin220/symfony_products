<?php

namespace App\Controller;


use App\Entity\Product;
use App\Service\PdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfGeneratorController extends AbstractController
{
    
    #[Route('/pdf/generator', name: 'app_pdf_generator')]

    public function index(EntityManagerInterface $entityManager)
    {       
        $data = $entityManager->getRepository(Product::class)->findAll();
        $html =  $this->renderView('pdf_generator/index.html.twig',
         [
            'data' => $data,
            'date' => date("d/m/Y"),
            'host' => $_SERVER['SERVER_NAME']
           
        ]);
        // $html .= '<link type="text/css" href="https://symfonypdf.dev.com/css/pdfgenerator.css" rel="stylesheet" />';
        $pdf = new PdfGenerator();
        $pdf->generatePdf($html); 
        
    }

}
