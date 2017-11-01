<?php

namespace Omega\NAOBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Omega\NAOBundle\Entity\Observations;
use Omega\NAOBundle\Form\ObservationsType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OmegaNAOBundle:Default:index.html.twig');
    }

    public function addObservationAction(Request $request)
    {
    	$observation = new Observations();
    	$form = $this->get('form.factory')->create(ObservationsType::class, $observation);

    	if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
    	{
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($observation);
    		$em->flush();

    		$this->get('session')->getFlashBag()->add('success', 'Votre observation a bien été ajoutée');

    		return $this->redirectToRoute('omega_nao_add_observation');
    	}

    	return $this->render('OmegaNAOBundle:Observations:add.html.twig', array('form' => $form->createView(),
    	));
    }
}
