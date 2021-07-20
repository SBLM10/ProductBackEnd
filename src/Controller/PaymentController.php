<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\Checkout\Session;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */
    public function index(): Response
    {

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PaymentController.php',
        ]);
    }

    /**
     * @Route("/succes", name="succes")
     */
    public function succes(): Response
    {
        return $this->json([
            'message' => 'your payment has been receved with success!',
         
        ]);
    }


    /**
     * @Route("/error", name="error")
     */
    public function error(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PaymentController.php',
        ]);
    }



    /**
     * @Route("/doPayment/{amount} ", name="Do_payment")
     */
    public function chekout($amount): Response
    {
         $fm = $amount * 10 ;
        \Stripe\Stripe::setApiKey("sk_test_51J4YbTJBX9jI6tP5mUrUXlJgp55waqyOIxQOWKxQhaYA5uUsvnXjenCCo6FpLOf6AXClJzROollZOWzx3dHdhFpj00k5zBbjVs");
        $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'T-shirt',
                        ],
                        'unit_amount' =>  $fm,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
    # These placeholder URLs will be replaced in a following step.
                'success_url' => 'http://localhost:4200/success',
                'cancel_url' => $this->generateUrl('error',[],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

          return new JsonResponse([['id' => $session -> url ]]);
        // return $request->withHeader('Location', $session->url)->withStatus(303);

    }

}
