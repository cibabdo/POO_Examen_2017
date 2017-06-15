<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller{

    /**
     *
     * @Route("/event/new", name="event_new")
     */
    public function newAction(Request $request) {

        $event = new Event();

        $formBuilder = $this->createFormBuilder($event);

        $formBuilder->add('nom', TextType::class, ['data' => 'nom '])
                ->add('description', TextType::class, ['data' => 'description'])
                ->add('lieu', TextType::class, ['data' => 'lieu'])
                ->add('debut', DateType::class)
                ->add('fin', DateType::class)
                ->add('categorie', EntityType::class, ['class'=>'AppBundle\Entity\Categorie'] )
                ->add('save', SubmitType::class, ['label' => 'Créer']);

        $form = $formBuilder->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            $this->addFlash('notice', 'event créé');

            return $this->redirectToRoute('event_list');
        }

        return $this->render('app/Event/new.html.twig', ['eventForm' => $form->createView()]);
    }

    /**
     *
     * @Route("/event", name="event_list")
     */
    public function listAction() {

        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository('AppBundle:Event');

        $event = $repo->findAll();

        return $this->render('app/Event/list.html.twig', ['event' => $event]);
    }

    /**
     *
     * @Route("/event/{id}", name="event_show")
     */
    public function showAction($id){

        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository('AppBundle:Event');
        $event = $repo->findOneBy(['id'=>$id]);

        return $this->render('app/Event/event_page.html.twig', ['event'=>$event]);
    }

     /**
     * @Route("/event/{id}/update", name="event_update")
     *
     */
    public function updateAction(event $event, Request $request) {
        $form = $this->getEventForm($event, 'update');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_list');
        }
    return $this->render('app/Event/update.html.twig', ['form'=>$form->createView()]);
    }

    private function getEventForm(Event $event, $action="new"){

        $formBuilder = $this->createFormBuilder($event);
        $formBuilder->add('nom', TextType::class);
        $formBuilder->add('description', TextType::class);
        $formBuilder->add('debut', DateType::class);
        $formBuilder->add('fin', DateType::class);
        $formBuilder->add('categorie', EntityType::class, ['class'=>'AppBundle\Entity\Categorie']);
        $formBuilder->add('save', SubmitType::class, array('label'=>$action == 'new' ? 'Créer' : 'Mettre à jour'));

		return $formBuilder->getForm();
    }


}
