<?php

namespace App\Controller;


use App\Entity\Product;
use App\Service\PdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
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
           
        ]);
        $pdf = new PdfGenerator();
        $pdf->generatePdf($html); 
        
    }

}
