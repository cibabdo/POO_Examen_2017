<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Categorie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class CategorieController extends Controller{

     /**
     *
     * @Route("/categorie/new", name="categorie_new")
     */
    public function newAction(Request $request) {

        $categorie = new Categorie();

        $formBuilder = $this->createFormBuilder($categorie);

        $formBuilder->add('nom', TextType::class, ['data' => 'nom'])
                ->add('save', SubmitType::class, ['label' => 'Créer']);

        $form = $formBuilder->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('notice', 'categorie créée');

            return $this->redirectToRoute('categorie_list');
        }

        return $this->render('app/Categorie/new.html.twig', ['categorieForm' => $form->createView()]);
    }

      /**
     *
     * @Route("/categorie", name="categorie_list")
     */
    public function listAction() {

        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository('AppBundle:Categorie');

        $categorie = $repo->findAll();

        return $this->render('app/Categorie/list.html.twig', ['categorie' => $categorie]);
    }

    /**
     *
     * @Route("/categorie/{id}", name="categorie_show")
     */
    public function showAction($id){

        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository('AppBundle:Event');
        $categorie = $repo->findOneBy(['id'=>$id]);

        return $this->render('app/Categorie/categorie_page.html.twig', ['categorie'=>$categorie]);
    }

}
