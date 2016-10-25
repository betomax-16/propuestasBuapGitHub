<?php

namespace RCMPropuestasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PropuestaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('descripcion','textarea', array('attr' => array('cols' => 90,'rows' => 10)))
            ->add('tipo')
            ->add('visibilidad', 'choice', array('choices' => array('PUBLIC' => 'Publico', 'PRIVATE' => 'Privado')))
            //->add('usuario', 'entity', array('class' => 'RCMPropuestasBundle:Usuario', 'choice_label' => 'getNombreCompleto'))
            //->add('usuario', 'hidden')
            ->add('file')
            ->add('save', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RCMPropuestasBundle\Entity\Propuesta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'propuesta';
    }
}
