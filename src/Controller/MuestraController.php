<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MuestraController extends AbstractController
{
    #[Route('/muestra', name: 'app_muestra')]
    public function index(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $query = $productRepository->productPaginator();

        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1), 5);

        return $this->render('muestra/index.html.twig', [
            'date' =>  date("d/m/Y"),
            'pagination' => $pagination
        ]);
    }

    
    
    

}
