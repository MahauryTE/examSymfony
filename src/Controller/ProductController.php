<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="product_all")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->getAllPublic();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/products/add", name="product_add")
     */
    public function addProduct( Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }
        return $this->render('product/add.html.twig', [
            'productForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products/{id}/visible", name="product_visible")
     */
    public function visibleProduct(int $id, Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['id' => $id]);

        $product->switchStatus();

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
