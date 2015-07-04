<?php

namespace AlmaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OspiteType extends AbstractType
{


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
            ->add('cognome')
            ->add('codiceFiscale',null,array('required'=>false))
            ->add('numeroDocumento',null,array('label'=>"Documento identità",'required'=>false))
            ->add('dataDocumento',null,array('label'=>"Data Documento identità",'required'=>false))
            ->add('dataNascita', 'date' , array('widget' => 'single_text','input' => 'datetime','format' => 'dd/MM/yyyy','required'=>false))
            ->add('indirizzoVia',null,array('required'=>false))
            ->add('indirizzoCivico',null,array('required'=>false))
            ->add('indirizzoCap',null,array('required'=>false))
            ->add('indirizzoCitta',null,array('required'=>false))
            ->add('telefono',null,array('required'=>false))
            ->add('note',null,array('required'=>false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AlmaBundle\Entity\Ospite'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'almabundle_ospite';
    }
}
