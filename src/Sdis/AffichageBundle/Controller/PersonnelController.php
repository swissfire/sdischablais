<?php

namespace Sdis\AffichageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sdis\AffichageBundle\Entity\Personnel;
use Sdis\AffichageBundle\Form\Type\PersonnelType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class PersonnelController extends Controller
{
    /**
    * @Secure(roles="ROLE_ADMIN")
    */
    public function listeAction() {		
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SdisAffichageBundle:Personnel');
        $personnels = $repository->findby(array(), array('nom' => 'ASC'));    
            
        return $this->render('SdisAffichageBundle:Personnel:liste.html.twig', array('personnels' => $personnels));
    }
    /**
    * @Secure(roles="ROLE_USER")
    */
    public function listeJsonAction() {		
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SdisAffichageBundle:Personnel');
        $personnels = $repository->findby(array(), array('nom' => 'ASC'));    
        
        $array = array();
        foreach($personnels as $personnel) {
            $array[$personnel->__toString()] = $personnel->getId();
        }
            
        $response = new JsonResponse();
        $response->setData($array);
        return $response;
    }
    
	/**
    * @Secure(roles="ROLE_ADMIN")
    */
	public function supprimerAction(Personnel $personnel) {
		return $this->render('SdisAffichageBundle:Personnel:supprimer.html.twig', array('personnel' => $personnel));
	}
	/**
	* @Secure(roles="ROLE_ADMIN")
	*/
	public function supprimerOkAction(Personnel $personnel) {		
		$em = $this->getDoctrine()->getManager();
		$em->remove($personnel);
		$em->flush();
		
		return $this->redirect($this->generateUrl('sdis_admin_personnel_liste'));
	}
    /**
	* @Secure(roles="ROLE_ADMIN")
	*/
	public function modifierAction(Personnel $personnel) {

        $form = $this->createForm(new PersonnelType, $personnel);
		
		$request = $this->get('request');
			if($request->getMethod() == 'POST') {
				$form->bind($request);
				if($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
					$em->persist($personnel);
					$em->flush();
                    
                    return $this->redirect($this->generateUrl('sdis_admin_personnel_liste'));
				}
			}
        
        return $this->render('SdisAffichageBundle:Personnel:formulaire.html.twig', array('form' => $form->createView()));
    }
        
    /**
	* @Secure(roles="ROLE_ADMIN")
	*/
	public function nouveauAction() {
		$em = $this->getDoctrine()->getManager();
		$personnel = new Personnel;
        
        $form = $this->createForm(new PersonnelType, $personnel);
		
		$request = $this->get('request');
			if($request->getMethod() == 'POST') {
				$form->bind($request);
				if($form->isValid()) {
					$em->persist($personnel);
					$em->flush();
                    
                    return $this->redirect($this->generateUrl('sdis_admin_personnel_nouveau'));
				}
			}
        
        return $this->render('SdisAffichageBundle:Personnel:formulaire.html.twig', array('form' => $form->createView()));
    }

    public function sectionsAction() {
        $this->denyAccessUnlessGranted('ROLE_OFFICIER', null, 'Vous n\'avez pas les droits nécessaires');
        $em = $this->getDoctrine()->getManager();
        $sectionRepo = $em->getRepository('SdisAffichageBundle:Sections');
        $personnelRepo = $em->getRepository('SdisAffichageBundle:Personnel');
        $sections = $sectionRepo->findAll();

        $personnelSection = array();
        foreach($sections as $section) {
            $personnelSection[$section->getNumero()] = array(
                'off' => $personnelRepo->getFromSection('off', $section),
                'sof' => $personnelRepo->getFromSection('sof', $section),
                'sap' => $personnelRepo->getFromSection('sap', $section)
            );
        }
        $personnelSection['reste'] = $personnelRepo->findBy(array('section' => NULL), array('nom' => 'ASC'));
        return $this->render('SdisAffichageBundle:Personnel:sections.html.twig', array('sections' => $sections, 'personnelSection' => $personnelSection));
    }
}
