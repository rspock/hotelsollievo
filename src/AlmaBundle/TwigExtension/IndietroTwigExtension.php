<?php
/**
 * Created by PhpStorm.
 * User: rspock
 * Date: 04/06/15
 * Time: 22:36
 */

namespace AlmaBundle\TwigExtension;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IndietroTwigExtension extends \Twig_Extension {

    private $templating;
    private $requestStack;

    private $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function getName()
    {
        return 'almabundle_pulsante_indietro';
    }

    public function pulsanteIndietro($route = null){
        if($route==null) {
            $request = $this->container->get("request_stack")->getCurrentRequest();
            $referer = $request->headers->get('referer');
            return $this->container->get("templating")->render("AlmaBundle:Base:pulsanteIndietro.html.twig", array("url" => $referer));
        }else{
            return $this->container->get("templating")->render("AlmaBundle:Base:pulsanteIndietro.html.twig", array("url" => $route));
        }
    }

    public function getFunctions()
    {
        return array(
            'pulsante_indietro' => new \Twig_Function_Method($this, 'pulsanteIndietro', array('is_safe' => array('html'))),
        );
    }
}