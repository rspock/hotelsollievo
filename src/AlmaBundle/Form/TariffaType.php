<?php

namespace AlmaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TariffaType extends AbstractType
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
            ->add('tipoCamera','entity',array(
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
            'data_class' => 'AlmaBundle\Entity\Tariffa'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'almabundle_tariffa';
    }
}
