<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PdfGeneratorController extends AbstractController
{
    
    #[Route('/pdf/generator', name: 'app_pdf_generator')]

    public function index(EntityManagerInterface $entityManager): Response
    {
        $pdf = new Dompdf();
        $canvas = $pdf->getCanvas();
        $options = new \Dompdf\Options();
              
        $data = $entityManager->getRepository(Product::class)->findAll();
        $html =  $this->renderView('pdf_generator/index.html.twig',
         [
            'data' => $data,
            'date' => date("d/m/Y"),
            'page' => $canvas->get_page_number(),
            'page_count' => $canvas->get_page_count(),
            
        ]);
              
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont','Arial');
        $options->set('isRemoteEnabled', true);
        $pdf->setOptions($options);
        $pdf->render();
        return new Response (
            $pdf->stream('resume.pdf', [
                "Attachment" => false,]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']);
    }

}
