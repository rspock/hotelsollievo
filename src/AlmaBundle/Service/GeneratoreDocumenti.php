<?php
/**
 * Created by PhpStorm.
 * User: rspock
 * Date: 03/07/15
 * Time: 17:22
 */

namespace AlmaBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlmaBundle\Entity\Conto;

class GeneratoreDocumenti {

    private $container;
    private $twig;
    private $pdfObject;
    private $ragioneSociale;
    private $pathLogo;

    function __construct(ContainerInterface $container, \Twig_Environment $twig)
    {

        $this->container = $container;
        $this->twig=$twig;
        $this->ragioneSociale = $container->getParameter("ragioneSociale");
        $this->pathLogo = $container->getParameter("pathLogo");
        $this->pdfObject = $container->get("white_october.tcpdf")->create();
    }

    function generaRicevutaConto(Conto $conto){
        $docHtml = $this->twig->render("AlmaBundle:Documenti:ricevuta.html.twig",
            array(
                'conto' => $conto,
                'vociSpesa' => $conto->getVociSpesa()
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
        $this->pdfObject->Output('ricevuta.pdf', 'I');

    }
}