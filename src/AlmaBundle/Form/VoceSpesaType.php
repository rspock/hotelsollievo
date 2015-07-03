<?php

namespace AlmaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManagerInterface;

class VoceSpesaType extends AbstractType
{
    protected $tipiVociSpesa;

    function __construct(EntityManagerInterface $entityManager)
    {
        $tipiVociSpesa = $entityManager->getRepository("AlmaBundle:TipoVoceSpesa")->findAll();
        foreach($tipiVociSpesa as $tipoVoceSpesa){
            $label = $tipoVoceSpesa->getDescrizione();
            if(strpos($label,"Caparra prenotazione") !== false ){
                continue;
            }
            $this->tipiVociSpesa[$tipoVoceSpesa->getId()] = $label ;
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('importo')
            ->add('tipo', 'choice', array(
                'choices' => $this->tipiVociSpesa,
                'expanded' =>false,
                'multiple' =>false

            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AlmaBundle\Entity\VoceSpesa'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'almabundle_vocespesa';
    }
}
