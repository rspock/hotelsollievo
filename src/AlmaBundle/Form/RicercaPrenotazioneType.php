<?php

namespace AlmaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManagerInterface;

class RicercaPrenotazioneType extends AbstractType
{

    function __construct()
    {

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dataArrivo', 'date' , array('widget' => 'single_text','input' => 'datetime','format' => 'dd/MM/yyyy'))
            ->add('dataPartenza', 'date' , array('widget' => 'single_text','input' => 'datetime','format' => 'dd/MM/yyyy'))
            ->add('numeroStanza',null,array('required'=>false))
            ->add('tipo','entity',array(
                'class' => 'AlmaBundle:TipoCamera',
                'property' => 'nome',
                'expanded'=>false,
                'multiple'=>false))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AlmaBundle\Form\DataClass\RicercaPrenotazione',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'almabundle_ricercaprenotazione';
    }
}
