<?php

namespace App\Controller;

use App\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/car")
 */
class EvaluationController extends AbstractController {
    /**
     * @Route("/new", name="new_car")
     */
    public function newAction(Request $request) {


        $form = $this->createFormBuilder()
            ->add('color', TextType::class)
            ->add('brand', TextType::class)
            ->add('model', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Post'))
            ->getForm();

        $form->handleRequest($request);

        $car = new Car();
        $car->setColor($form->get('color')->getData());
        $car->setBrand($form->get('brand')->getData());
        $car->setModel($form->get('model')->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();

            $this->addFlash(
                'notice',

                'Car added successfully!'
            );

            return $this->redirectToRoute('list_car');
        }

        return $this->render('newcar.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function removeAction() {

    }

    public function updateAction() {

    }

    /**
     * @Route("/list", name="list_car")
     */
    public function getAction() {
        $repository = $this->getDoctrine()
            ->getRepository(Car::class);

        $cars = $repository->findAll();

        return $this->render('evaluation/listcar.html.twig', array('cars' => $cars));
    }
}
