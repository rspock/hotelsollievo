<?php

namespace AlmaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManagerInterface;

class OspiteVoceSpesaType extends VoceSpesaType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ospiteId','hidden')
            ->add('tipoVoceSpesa', 'choice', array(
                'choices' => $this->tipiVociSpesa,
                'expanded' =>false,
                'multiple' =>false

            ))
            ->add('descrizione','text',array("required"=>false))
            ->add('sconto','checkbox',array('label' => 'Rappresenta uno sconto?',"required"=>false))
            ->add('importo')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AlmaBundle\Form\DataClass\OspiteVoceSpesa',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'almabundle_ospitevocespesa';
    }
}
