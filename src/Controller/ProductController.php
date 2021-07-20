<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Form;

use App\Repository\ProductRepository;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/listproducts", name="listproducts")
     */
    public function getAllProducts(SerializerInterface $serializer): Response
    {
        $list=$this->getDoctrine()->getRepository(Product::class)->findAll();
        $jsonContent = $serializer->serialize($list,"json" );
        return new Response($jsonContent);
    }

    /**
     * @Route("/api/addProduct", name="addProduct")
     */
    public function addProduct(Request $request, SerializerInterface $serializer) : Response
    {
        $data=$request->getContent();
        $product = $serializer->deserialize($data, Product::class, 'json');
        $em=$this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        $jsonContent = $serializer->serialize($product,"json");
        return new Response($jsonContent);
    }

    /**
     * @Route("/api/updateProduct/{id}" , name="updateProduct")
     */
    public function updateProduct(string $id, Request $request, SerializerInterface $serializer) : Response
    {
        $data = $request->getContent(); // Get json from request

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        if (!$product) {
            $product = new Product();
        }

        $product = $serializer->deserialize($data,Product::class, 'json', ['object_to_populate' => $product] );
        $em=$this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        $jsonContent = $serializer->serialize($product,"json");
        return new Response($jsonContent);
    }

    /**
     * @Route("/api/deleteProduct/{id}", name="deleteProduct")
     */
    public function deleteProduct($id, Request $request, SerializerInterface $serializer) : Response
    {
         // Get json from request

       // $data=$request->getContent();
        //$product = $serializer->deserialize($data, Product::class)->find($id);
        $product=$this->getDoctrine()->getRepository(Product::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        $jsonContent = $serializer->serialize($product,"json");
        return new Response($jsonContent);
    }



}
