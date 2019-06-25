<?php

namespace FriendBundle\Controller;

use FriendBundle\Entity\Paiement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $pay=$this->getDoctrine()
            ->getRepository(Paiement::class)
            ->findAll();
        return $this->render('@Friend/Default/index.html.twig',
            array('liste'=>$pay));
    }
}
