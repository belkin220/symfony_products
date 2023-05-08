<?php

namespace App\Controller;

use App;
use App\Entity\Product;
use App\Service\PdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfGeneratorController extends AbstractController
{
    
    #[Route('/pdf/generator', name: 'app_pdf_generator')]

    public function index(EntityManagerInterface $entityManager, Request $request, PdfGenerator $pdf)
    {       
        $data = $entityManager->getRepository(Product::class)->findAll();
        $html =  $this->renderView('pdf_generator/index.html.twig',
         [
            'data' => $data,
            'date' => date("d/m/Y"),
            'host' => $request->getSchemeAndHttpHost(),         
        ]);

        return $pdf->generatePdf($html);         
    }

}
