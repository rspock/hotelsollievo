<?php

namespace AlmaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dataApertura', 'date' , array('widget' => 'single_text','input' => 'datetime','format' => 'dd/MM/yyyy', 'required'=>false))
            ->add('dataPagamento', 'date' , array('widget' => 'single_text','input' => 'datetime','format' => 'dd/MM/yyyy', 'required'=>false))
            ->add('saldo', null,array('required'=>false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AlmaBundle\Entity\Conto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'almabundle_conto';
    }
}
