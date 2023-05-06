<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Flasher\Prime\Flasher;
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

    public function index(
        EntityManagerInterface $entityManager,
        Request $request,
        FileUploader $fileUploader,
        HandleMessages $message
    ) {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $product->setImage($imageFileName);
            }
            $entityManager->persist($product);
            if ($entityManager->flush() == null) {
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
    public function update(
        EntityManagerInterface $entityManager,
        int $id,
        Request $request,
        HandleMessages $message
    ) {
        // Obtenemos todo el request de la petición http
        $data = $request->request->all();
        // Obtenemos el registro que nos interesa por su id
        $product = $entityManager->getRepository(Product::class)->find($id);
        // Asignamos los nuevos valores que vayamos a actualizar
        $product->setName($data['edit_product']['name']);
        $product->setDescription($data['edit_product']['description']);
        // El campo price cambiamos el separador de miles y el de decimales para que los acepte MySql
        $product->setPrice(str_replace(',', '.', $data['edit_product']['price']));
        if ($entityManager->flush() == null) {
           $message->messageUpdate($product->getName());
        }
        // Por último redireccionamos a la ruta muestra.     
        return $this->redirectToRoute('app_muestra');
    }

    #[Route('/product/image-show/{id}', name: 'app_product_image_show')]

    public function modifyImage(
        EntityManagerInterface $entityManager,
        Request $request,
        FileUploader $fileUploader,
        HandleMessages $message,
        $id
    ) {
        $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
        $form = $this->createForm(EditImageType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $product->setImage($imageFileName);
            }
            $entityManager->persist($product);

            if ($entityManager->flush() == null) {
                $message->messageUpdateImage($product->getName());
            }
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
    public function delete(
        EntityManagerInterface $entityManager,
        HandleMessages $message,
        $id
    ) {
        $product = $entityManager->getRepository(Product::class)->find($id);       
        $entityManager->remove($product);
        $entityManager->flush();
        $message->messageRemove($product->getName());
        
        return $this->redirectToRoute('app_muestra', [
            'id' => $id
        ]);
    }
}
