<?php
/**
 * Created by PhpStorm.
 * User: rspock
 * Date: 03/06/15
 * Time: 22:00
 */
namespace AlmaBundle\Controller;

use AlmaBundle\Form\DataClass\OspiteVoceSpesa;
use AlmaBundle\Form\OspiteVoceSpesaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;


class BaseController extends Controller {

    public function addErrore($descrizione){
        $this->get('session')->getFlashBag()->add('error', $descrizione);
    }

    public function addSucces($descrizione){
        $this->get('session')->getFlashBag()->add('success', $descrizione);
    }

    /**
     * @return Session
     */
    protected function getSession() {
        $session = $this->getRequest()->getSession();
        if(!$session->isStarted()){
            $session->start();
        }
        return $session;
    }

    public function isAdminLoged() {
        return $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
    }


    /**
     * Crea un form per aggiungere una voce spesa ad un ospite
     *
     * @param Conto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createOspiteVoceSpesaForm(OspiteVoceSpesa $entity)
    {
        $form = $this->createForm(new OspiteVoceSpesaType($this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('conto_aggiungi_voce_spesa'),
            'method' => 'POST',
        ));

        return $form;
    }


    public function getRefererRoute()
    {
        $request = $this->getRequest();

        //look for the referer route
        $referer = $request->headers->get('referer');
        $lastPath = substr($referer, strpos($referer, $request->getBaseUrl()));
        $lastPath = str_replace($request->getBaseUrl(), '', $lastPath);

        $matcher = $this->get('router')->getMatcher();
        $parameters = $matcher->match($lastPath);
        $route = $parameters['_route'];

        return $route;
    }


    public function returnResponse($request = null){
        if($request == null){
            $request = $this->getRequest();
        }
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}
