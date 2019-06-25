<?php

namespace FriendBundle\Controller;

use FriendBundle\Entity\Comment;
use FriendBundle\Entity\Event;
use FriendBundle\Entity\Paiement;
use FriendBundle\Entity\Participant;
use FriendBundle\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;


class EventController extends Controller
{
    private $session;
    public function __construct()
    {
        $this->session = new Session();
    }


    public function ajoutAction(Request $request, UserInterface $user)
    {

        $event = new Event();
        $form= $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $file = $event->getPhoto();
            $filename= md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $filename);
            $event->setPhoto($filename);
            $event->setEventdate(new \DateTime('now'));
            $event->setCreator($user);
            $event->setNbrVue(0);

            $em->persist($event);
            $flush = $em->flush();

            if ($flush != null){
                $statut = "OK";
                $this->session->getFlashBag ()->add ("status", $statut);
            }

           // $this->addFlash('info', 'Created Successfully !');
            //$em=$this->getDoctrine()->getManager();
            //$event=$em->getRepository(Event::class)->findAll();
            //$paginator  = $this->get('knp_paginator');
            //$pagination = $paginator->paginate(
            //    $event, /* query NOT result */
            //    $request->query->getInt('page', 1), /*page number*/
             //   5 /*limit per page*/
            //);
            //return $this->render('@Friend/Event/afficher.html.twig', array(
             //   "event" =>$pagination
            //));

            $etat = 'false';
            $id=$event->getId ();

            $event=$this->getDoctrine()
                ->getRepository(Event::class)
                ->find($id);

            $comment=$this->getDoctrine()
                ->getRepository(Comment::class)
                ->findCommentById($id);

            $participant=$this->getDoctrine()
                ->getRepository(Participant::class)
                ->findParticipant($id);


            // $nbcomment=$this->getDoctrine()
            //   ->getRepository(Comment::class)->CommentefindAll($id);

            //incrementation
            $nbr=$request->get('nbrVue');
            $event->setNbrVue($nbr+1);
            $em=$this->getDoctrine()->getManager();
            $em->flush();


            return $this->render('@Friend/Event/detail.html.twig', array(
                'titre'=>$event->getTitre(),
                'eventdate'=>$event->getEventdate(),
                'photo'=>$event->getPhoto(),
                'nbParticipants'=>$event->getNbParticipants(),
                'descripion'=>$event->getDescription(),
                'maxparticipants'=>$event->getMaxparticipants(),
                'lieu'=>$event->getLieu(),
                'events'=>$event,
                'comments'=>$comment,
                'id'=>$event->getId(),
                'participants'=>$participant,

                'etat'=>$etat
            ));

        }
        return $this->render('@Friend/Event/ajout.html.twig', array(
            "Form"=> $form->createView()
        ));
    }

    public function afficherAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        //$event=$em->getRepository(Event::class)->findAll();
        $dql = "SELECT e FROM FriendBundle:Event e";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('@Friend/Event/afficher.html.twig', array(
            "event" =>$pagination
        ));

    }

    public function updateAction(Request $request)
    {
        $id=$request->get('id');
        $p=$this->getDoctrine()->getManager()
            ->getRepository(Event::class)
            ->find($id);

        $form=$this->createForm(EventType::class,$p);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $file = $p->getPhoto();
            $filename= md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $filename);
            $p->setPhoto($filename);
            $p->setEventdate(new \DateTime('now'));
            $em= $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
            return $this->redirectToRoute('event_afficher');

        }
        return $this->render('@Friend/Event/update.html.twig', array(
            "Form"=> $form->createView()
        ));
    }

    public function detailAction(Request $request){
        $etat = 'false';
        $id=$request->get('id');

        $event=$this->getDoctrine()
            ->getRepository(Event::class)
            ->find($id);

        $comment=$this->getDoctrine()
            ->getRepository(Comment::class)
            ->findCommentById($id);

        $participant=$this->getDoctrine()
            ->getRepository(Participant::class)
            ->findParticipant($id);


       // $nbcomment=$this->getDoctrine()
         //   ->getRepository(Comment::class)->CommentefindAll($id);

        //incrementation
        $nbr=$request->get('nbrVue');
        $event->setNbrVue($nbr+1);
        $em=$this->getDoctrine()->getManager();
        $em->flush();


        return $this->render('@Friend/Event/detail.html.twig', array(
            'titre'=>$event->getTitre(),
            'eventdate'=>$event->getEventdate(),
            'photo'=>$event->getPhoto(),
            'nbParticipants'=>$event->getNbParticipants(),
            'descripion'=>$event->getDescription(),
            'maxparticipants'=>$event->getMaxparticipants(),
            'lieu'=>$event->getLieu(),
            'events'=>$event,
            'comments'=>$comment,
            'id'=>$event->getId(),
            'participants'=>$participant,

            'etat'=>$etat
        ));
    }

    public function deleteAction(Request $request){

        $id=$request->get('id');
        $event=$this->getDoctrine()
            ->getRepository(Event::class)
            ->find($id);

        $em=$this->getDoctrine()->getManager();
        $em->remove($event);
        $comment=$this->getDoctrine()
            ->getRepository(Comment::class)
            ->findCommentById($id);
        foreach ($comment as $c)
        {
           $em->remove($c);
        }

        $participant=$this->getDoctrine()
            ->getRepository(Participant::class)
            ->findParticipant($id);
        foreach ($participant as $p)
        {
            $em->remove($p);
        }

        $em->flush();
        return $this->redirectToRoute('event_afficher');

    }

    public function chercherAction(Request $request){
        $event=$this->getDoctrine()->getManager ()
            ->getRepository(Event::class)
            ->findAll();
        $titre=$request->get('titre');
        if (isset($titre)){
            $event=$this->getDoctrine()
                ->getRepository(Event::class)->myfindAll($titre);
            return $this->render('@Friend/Event/chercher.html.twig',
                array('event'=>$event));
        }
        return $this->render('@Friend/Event/afficher.html.twig');
    }

    public function chercherlieuAction(Request $request){
        $event=$this->getDoctrine()->getManager ()
            ->getRepository(Event::class)
            ->findAll();
        $lieu=$request->get('lieu');
        if (isset($lieu)){
            $event=$this->getDoctrine()
                ->getRepository(Event::class)->myfindlieu($lieu);
            return $this->render('@Friend/Event/chercher.html.twig',
                array('event'=>$event));
        }
        return $this->render('@Friend/Event/afficher.html.twig');
    }

    public function addCommentAction(Request $request, UserInterface $user)
    {


        $ref = $request->headers->get('referer');
        $id=$request->get('id');

        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findEventByid($id);

        $comment = new Comment();

        $comment->setUser($user);
        $comment->setEvent($event);
        $comment->setDateComment(new \DateTime('now'));
        $comment->setContent($request->request->get('comment'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();



        return $this->redirect($ref);

    }

    public function deleteCommentAction(Request $request)
    {
        $ref = $request->headers->get('referer');

        $id = $request->get('id');
        $em= $this->getDoctrine()->getManager();
        $comment=$em->getRepository('FriendBundle:Comment')->find($id);
        $em->remove($comment);
        $em->flush();
        return $this->redirect($ref);
    }

    public function addParticipantAction(Request $request, UserInterface $user)
    {
        $ref = $request->headers->get('referer');
        $id=$request->get('id');

        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findEventByid($id);

        $participant = new Participant();

        $participant->setUser($user);
        $participant->setEvent($event);
        $nbr=$request->get('nbParticipants');
        $event->setNbParticipants($nbr+1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($participant);
        $em->flush();

        return $this->redirect($ref);
    }

    public function  AnnulerParticipationAction(Request $request){

        $ref = $request->headers->get('referer');

        $id=$request->get('id');

        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findEventByid($id);

        $idp=$request->get('idp');
        $partipant =$this->getDoctrine()
            ->getRepository(Participant::class)
            ->find($idp);

        $nbr=$request->get('nbParticipants');
        $event->setNbParticipants($nbr-1);
        $em=$this->getDoctrine()->getManager();
        $em->remove($partipant);
        $em->flush();
        return $this->redirect($ref);

    }

    public function paiementAction(Request $request)
    {
        $id=$request->get('id');;
        $event=$this->getDoctrine()
            ->getRepository(Event::class)
            ->find($id);

        \Stripe\Stripe::setApiKey("sk_test_F1HUneWebRV5hqCsBIdsnCL600nqntoH41");

        \Stripe\Charge::create([
            "amount" => 200,
            "currency" => "eur",
            "source" => "tok_mastercard", // obtained with Stripe.js
            "description" => "paiement : ". $event->getTitre()
        ]);

        $pay = new Paiement();

        $pay->setEvent($event);
        $pay->setPrix (200);
        $em = $this->getDoctrine()->getManager();
        $em->persist($pay);
        $em->flush();

        return $this->render('@Friend/Event/paiement.html.twig');
    }

    public function allAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('FriendBundle:Event')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }

    public function findAction($id)
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('FriendBundle:Event')
            ->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $task = new Event();
        $task->setTitre($request->get('titre'));
        $task->setLieu($request->get('lieu'));
        $task->setPhoto($request->get('photo'));

        $em->persist($task);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);
    }


}
