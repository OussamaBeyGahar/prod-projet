<?php

namespace ActivityBundle\Controller;

use ActivityBundle\Entity\Activity;
use ActivityBundle\Entity\Deslikes;
use ActivityBundle\Entity\Likes;
use ActivityBundle\Entity\Rating;
use ActivityBundle\Entity\Reports;
use ActivityBundle\Form\ActivityType;
use ActivityBundle\Form\RatingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ActivityController extends Controller
{
    public function addAction(Request $request){
        // preparer un form
        $activity = new Activity();
        $form = $this->createForm(ActivityType::class, $activity);
        $form = $form->handleRequest($request);

        if ($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $activity->setDatecreation(new \DateTime());
            $activity->setNbvue(0);
            $activity->setIduser($this->getUser());
            $em->persist($activity);
            $em->flush();
            $id=$activity->getId();
            //return $this->render('@Activity/activity/rating.html.twig', array('id'=>$id));
            return $this->redirectToRoute('activity_activity_addrating',array('id'=>$id));
        }
        return $this->render('@Activity/activity/activityadd.html.twig', array('form'=>$form->createView()));

    }

    public function displayAction(Request $request){
        $activities=$this->getDoctrine()->getRepository(Activity::class)->findAll();
        $mostviewed=$this->getDoctrine()->getRepository(Activity::class)->mostviewed();
        $mostliked=$this->getDoctrine()->getRepository(Activity::class)->mostliked();
        $mostrated=$this->getDoctrine()->getRepository(Activity::class)->mostrated();

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $res = $paginator->paginate(
            $activities,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3)


        );


        return $this->render('@Activity/activity/activitydisplay.html.twig', array('list'=>$res,
            'mostviewed'=>$mostviewed,'mostliked'=>$mostliked,'mostrated'=>$mostrated));

    }


    public function singleAction(Request $id){
        $id=$id->get('id');

        $activity=$this->getDoctrine()->getRepository(Activity::class)->find($id);
        if (!$activity) {
            return $this->redirectToRoute('activity_activity_display');
        }
        else {
            $n=$activity->getNbvue();
            $n=$n+1;
            $activity->setNbvue($n);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            $idact=$activity->getId();
            $idu = $this->getUser()->getId();
            $checklikes=$this->getDoctrine()->getRepository(Likes::class)->Findlikes($idu, $idact);
            $checkdes=$this->getDoctrine()->getRepository(Deslikes::class)->Finddeslike($idu, $idact);
            $rating = $this->getDoctrine()->getRepository(Rating::class)->findrating($idact);
            $reports = $this->getDoctrine()->getRepository(Reports::class)->findBy(['idAct' => $id, 'idUser' => $idu]);

            if ( $checklikes ) {
                $likescheck = true;
            }
            else {
                $likescheck = false;
            }
            if ($checkdes) {
                $descheck = true;

            }else{

                $descheck = false;
            }

            if ( $reports ) {
                $reportCheck = true;
            }
            else {
                $reportCheck = false;
            }

            return $this->render('@Activity/activity/single.html.twig', array('activity'=>($activity),
                'likes'=>($likescheck),
                'deslikes'=>($descheck),
                'rating'=>($rating),
                'reportCheck'=>($reportCheck)
            ));
        }


    }
    public function likesAction(Request $request) {
        $idAct = $request->get('idact');
        $activity=$this->getDoctrine()->getRepository(Activity::class)->find($idAct);
        $iduser = $this->getUser();
        $id = $this->getUser()->getId();
        $check=$this->getDoctrine()->getRepository(Likes::class)->Findlikes($id, $idAct);
        if (!$check){
            $likes = new Likes();
            $likes->setIdAct($activity);
            $likes->setInUser($iduser);
            $em=$this->getDoctrine()->getManager();
            $em->persist($likes);
            $em->flush();
            $checkdes=$this->getDoctrine()->getRepository(Deslikes::class)->Finddeslike($id, $idAct);
            if ($checkdes){
                $em=$this->getDoctrine()->getManager();
                foreach ($checkdes as $item) {
                    $deslikesrem = $item;
                    $em->remove($deslikesrem);
                    $em->flush();
                }

            }

            return $this->redirectToRoute('activity_activity_single_display',array('id'=>$idAct));
        }
        else {
            $em=$this->getDoctrine()->getManager();
            foreach ($check as $item) {
                $likesrem = $item;
                $em->remove($likesrem);
            }

            $em->flush();

            return $this->redirectToRoute('activity_activity_single_display',array('id'=>$idAct));
        }

    }
    public function deslikesAction(Request $request) {
        $idAct = $request->get('idact');
        $activity=$this->getDoctrine()->getRepository(Activity::class)->find($idAct);
        $iduser = $this->getUser();
        $id = $this->getUser()->getId();
        $check=$this->getDoctrine()->getRepository(Deslikes::class)->Finddeslike($id, $idAct);
        if (!$check){
            $likes = new Deslikes();
            $likes->setIdAct($activity);
            $likes->setIdUser($iduser);
            $em=$this->getDoctrine()->getManager();
            $em->persist($likes);
            $em->flush();
            $checkdes=$this->getDoctrine()->getRepository(Likes::class)->Findlikes($id, $idAct);
            if ($checkdes){
                $em=$this->getDoctrine()->getManager();
                foreach ($checkdes as $item) {
                    $likesrem = $item;
                    $em->remove($likesrem);
                    $em->flush();
                }

            }

            return $this->redirectToRoute('activity_activity_single_display',array('id'=>$idAct));
        }
        else {
            $em=$this->getDoctrine()->getManager();
            foreach ($check as $item) {
                $likesrem = $item;
                $em->remove($likesrem);
            }
            $em->flush();

            return $this->redirectToRoute('activity_activity_single_display',array('id'=>$idAct));
        }



    }

    public function removeAction(Request $request){
        //creation d'un objet
        $id=$request->get('id');
        $activity=$this->getDoctrine()->getRepository(Activity::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($activity);
        $em->flush();
        return $this->redirectToRoute('activity_activity_display');

    }
    public function addratingAction(Request $request){
        // preparer un form
        $id=$request->get('id');
        $actrating=$this->getDoctrine()->getRepository(Activity::class)->find($id);
        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form = $form->handleRequest($request);

        if ($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $rating->setIdact($actrating);
            $rating->setIduser($this->getUser());
            $em->persist($rating);
            $em->flush();
            return $this->redirectToRoute('activity_activity_display');
        }
        return $this->render('@Activity/activity/rating.html.twig', array('form'=>$form->createView()));

    }

    public function reportsAction(Request $request){
        $idact=$request->get('id');
        $iduser = $this->getUser();
        $activity=$this->getDoctrine()->getRepository(Activity::class)->find($idact);

        $report = new Reports();
        $report->setIdAct($activity);
        $report->setIdUser($iduser);
        $em=$this->getDoctrine()->getManager();
        $em->persist($report);
        $em->flush();
        return $this->redirectToRoute('activity_activity_single_display',array('id'=>$idact));


    }

    public function apidisplayAction(){
        $tasks = $this->getDoctrine()->getRepository(Activity::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);

    }



}
