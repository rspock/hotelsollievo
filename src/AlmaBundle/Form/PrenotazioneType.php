<?php

namespace AlmaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrenotazioneType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dataInizio','date' , array('widget' => 'single_text','input' => 'datetime','format' => 'dd/MM/yyyy'))
            ->add('dataFine','date' , array('widget' => 'single_text','input' => 'datetime','format' => 'dd/MM/yyyy'))
            ->add('persona','entity',array(
                'class' => 'AlmaBundle:Persona',
                'property' => 'nomeCognome',
                'expanded'=>false,
                'multiple'=>false))
            ->add('letti','entity',array(
                'class' => 'AlmaBundle:Letto',
                'property' => 'descrizione',
                'expanded'=>false,
                'multiple'=>true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AlmaBundle\Entity\Prenotazione'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'almabundle_prenotazione';
    }
}
