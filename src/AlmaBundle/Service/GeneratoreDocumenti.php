<?php
/**
 * Created by PhpStorm.
 * User: rspock
 * Date: 03/07/15
 * Time: 17:22
 */

namespace AlmaBundle\Service;

use AlmaBundle\Entity\Ricevuta;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlmaBundle\Entity\Conto;

class GeneratoreDocumenti {

    private $container;
    private $twig;
    private $pdfObject;
    private $ragioneSociale;
    private $pathLogo;
    private $em;

    function __construct(ContainerInterface $container, \Twig_Environment $twig)
    {

        $this->container = $container;
        $this->twig=$twig;
        $this->ragioneSociale = $container->getParameter("ragioneSociale");
        $this->pathLogo = $container->getParameter("pathLogo");
        $this->pdfObject = $container->get("white_october.tcpdf")->create();
        $this->em = $container->get('doctrine')->getEntityManager();
    }

    function generaRicevutaConto(Conto $conto){

        if($conto->getRicevuta() != null){
            throw new \Exception("Ricevuta già generata per il conto indicato");
        }

        $prossimoNumero = $this->em->getRepository('AlmaBundle:Ricevuta')->getProssimoNumero();

        $ricevuta = new Ricevuta();
        $ricevuta->setConto($conto);
        $ricevuta->setNumero($prossimoNumero);
        $ricevuta->setData(new \DateTime());
        $ricevuta->setMime("application/pdf");
        $ricevuta->setNome("Ricevuta Nr ".$prossimoNumero." - ".$ricevuta->getData()->format("d-m-Y").".pdf");
        $ricevuta->setPercorso($this->container->getParameter("pathDocumenti").$ricevuta->getNome());

        $conto->setRicevuta($ricevuta);

        $this->em->persist($ricevuta);
        $this->em->persist($conto);
        $this->em->flush();

        $docHtml = $this->twig->render("AlmaBundle:Documenti:ricevuta.html.twig",
            array(
                'conto' => $conto,
                'vociSpesa' => $conto->getVociSpesa(),
                'numero' => $prossimoNumero
            )
        );

        $this->pdfObject->SetCreator(PDF_CREATOR);
        $this->pdfObject->SetAuthor('Alma Consulting');
        $this->pdfObject->SetTitle("Ricevuta fiscale nr ".$conto->getId());
        $this->pdfObject->SetSubject("Ricevuta fiscale nr ".$conto->getId());

        $this->pdfObject->SetHeaderData($this->pathLogo, 50, "Ricevuta Fiscale nr ".$conto->getId(), $this->ragioneSociale);
        $this->pdfObject->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->pdfObject->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $this->pdfObject->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $this->pdfObject->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdfObject->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdfObject->SetFooterMargin(PDF_MARGIN_FOOTER);

        $this->pdfObject->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $this->pdfObject->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $this->pdfObject->SetFont('helvetica', '', 10);

        $this->pdfObject->AddPage();

        $this->pdfObject->writeHTML($docHtml, true, 0, true, 0);

        $this->pdfObject->lastPage();
        $this->pdfObject->Output($ricevuta->getPercorso(), 'FD');

    }
}