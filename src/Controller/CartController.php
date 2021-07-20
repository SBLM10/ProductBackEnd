<?php

namespace App\Controller;


use App\Entity\Panier;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index")
     */
    public function afficherPanier(SessionInterface $session, ProductRepository $productRepository, SerializerInterface $serializer): Response
    {
        $list=$this->getDoctrine()->getRepository(Panier::class)->findAll();
        $jsonContent = $serializer->serialize($list,"json" );
        return new Response($jsonContent);
    }

    /**
     * @Route("/panier/add", name="cart_add")
     *
     */
    public function addProductInPanier( Request $request,SerializerInterface $serializer): Response
    {
        $data=$request->getContent();
        $panier = $serializer->deserialize($data, Panier::class, 'json');
        $em=$this->getDoctrine()->getManager();
        $em->persist($panier);
        $em->flush();
        $jsonContent = $serializer->serialize($panier,"json");
        return new Response($jsonContent);
    }


    /**
     * @Route("/panier/remove", name="cart_remove")
     */
    public function removeProductInPanier(SessionInterface $session ,SerializerInterface $serializer): Response
    {

        $product=$this->getDoctrine()->getRepository(Panier::class)->findAll();
        foreach ($product as  $value){
            $em=$this->getDoctrine()->getManager();
            $em->remove($value);
            $em->flush();

        }
  

        $jsonContent = $serializer->serialize($product,"json");
        return new Response($jsonContent);

    }



}
