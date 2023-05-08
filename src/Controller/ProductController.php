<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\EditImageType;
use App\Form\EditProductType;
use App\Service\FileUploader;
use App\Service\HandleMessages;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductController extends AbstractController
{
    #[Route('/producto', name: 'app_user_type')]

    public function index(EntityManagerInterface $em, Request $request, FileUploader $fileUploader,
                          HandleMessages $message) 
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $product->setImage($imageFileName);
            }
            $em->persist($product);
            if ($em->flush() == null) {
                $message->messageNew($product->getName());
            }

            return $this->redirectToRoute('app_muestra');
        }
        return $this->render('user_type/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/product/show/{id}', name: 'app_product_show')]

    public function show(ProductRepository $productRepository,  $id)
    {
        $product = $productRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(EditProductType::class, $product);
        // Retornamos la vista con los parámetros necesarios
        return $this->render('edit/index.html.twig', [
            'data' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/edit/{id}', name: 'app_product_edit')]

    public function update(EntityManagerInterface $em, int $id, Request $request, 
                           HandleMessages $message) 
    {
        $data = $request->request->all();
        $product = $em->getRepository(Product::class)->find($id);
        $product->setName($data['edit_product']['name']);
        $product->setDescription($data['edit_product']['description']);
        $product->setPrice(str_replace(',', '.', $data['edit_product']['price']));
        if ($em->flush() == null) {
           $message->messageUpdate($product->getName());
        }
        return $this->redirectToRoute('app_muestra');
    }

    #[Route('/product/image-show/{id}', name: 'app_product_image_show')]

    public function modifyImage(EntityManagerInterface $em,Request $request,FileUploader $fileUploader,
                                HandleMessages $message, $id) 
    {

        $product = $em->getRepository(Product::class)->find( $id);
        $form = $this->createForm(EditImageType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $product->setImage($imageFileName);
            }
            $em->persist($product);
            $em->flush();
            $message->messageUpdateImage($product->getName());
            
            return $this->redirectToRoute('app_product_show', [
                'id' => $id
            ]);
        }
        return $this->render('edit-image/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'productName' => $product->getName()
        ]);

    }

    #[Route('/product/remove/{id}', name: 'app_product_remove')]
    public function delete(EntityManagerInterface $em,HandleMessages $message,$id)
    {

        $product = $em->getRepository(Product::class)->find($id);       
        $em->remove($product);
        $em->flush();
        $message->messageRemove($product->getName());
        
        return $this->redirectToRoute('app_muestra', [
            'id' => $id
        ]);
    }
}
