<?php

namespace Controller;

use Library\Controller;
use Model\Form\FeedbackForm;
use Model\FeedbackRepository;
use Model\Entity\Feedback;
use Library\Request;
use Library\Cookie;

class CartController extends Controller
{
    public function indexAction()
    {
        $cart = $this->get('cart_service')->getCart();
        
        $ids = array_values(array_unique($cart));
        $cartAmounts = array_count_values($cart);
        $cart = $this->get('repository')->getRepository('Book')->findByIds($ids);
    
        // ids: [1,2,3,4,5]  <--  select * from books where id in (1,2,3,4,5) 
        
        
        
        return $this->render('index.phtml', ['cart' => $cart, 'cartAmounts' => $cartAmounts]);
    }
    
    public function addAction(Request $request)
    {
        $id = $request->get('id');
        $cart = $this->get('cart_service')->getCart();
        
        /*
            todo:
            
            
            cart = [
                0 => [
                    id: 123
                    price: 657
                    amount: 3
                ],
                1 => [
                    id: 456
                    price: 657
                    amount: 1
                ],
                ...
                
            ]
        
        */
        
        $cart[] = $id;
        $cartCookie = serialize($cart);
        Cookie::set('cart', $cartCookie);
        
        //todo: save prevoius URL to session or to GET param
        $this->get('router')->redirect('/books');
    }
}