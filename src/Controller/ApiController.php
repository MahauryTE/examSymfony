<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route ("/api/products", name="api_products")
     */
    public function productsAll(ProductRepository $productRepository,PaginatorInterface $paginator, Request $request, SerializerInterface $serializer): Response
    {

        if($request->query->get('page') == null) {
            $products = $productRepository->getAllPublic();
        } else {
            $products = $paginator->paginate(
                $productRepository->getAllPublic(), /* query NOT result */
                $request->query->getInt('page', $request->query->get('page')), /*page number*/
                20 /*limit per page*/
            );
        }
        $json = $serializer->serialize($products, 'json', [
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    return $object->getName();}
            ]
        );

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route ("/api/products/user/{id}/products", name="api_products_user")
     */
    public function productsByUser(int $id, ProductRepository $productRepository, UserRepository $userRepository, Request $request, SerializerInterface $serializer): Response
    {

        $user = $userRepository->findOneBy(['id' => $id]);

        $products = $this->getAllActifs($productRepository->findByUser($user));


        $json = $serializer->serialize($products, 'json', [
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                AbstractObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    return $object->getName();}
            ]
        );

        return new JsonResponse($json, 200, [], true);
    }

    private function getAllActifs($products) {
        $productsActifs = [];
        foreach($products as $product){
            if(!$product->getStatus()) {
                $productsActifs[] = $product;
            }
        }
        return $productsActifs;
    }
}
