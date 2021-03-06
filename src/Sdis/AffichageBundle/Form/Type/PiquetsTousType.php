<?php

namespace Sdis\AffichageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PiquetsTousType extends AbstractType {
    /**
    * @param FormBuilderInterface $builder
    * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('debut', 'datetime', array(
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'date_format' => 'dd/MM/yyyy',
                    'label' => 'De'
                ))
                ->add('fin', 'datetime', array(
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'date_format' => 'dd/MM/yyyy',
                    'label' => 'A'
                ))
                ->add('sectionService', 'entity', array('class' => 'SdisAffichageBundle:Sections', 'property' => 'numero'))
                ->add('sectionRenfort', 'entity', array('class' => 'SdisAffichageBundle:Sections', 'property' => 'numero'))
                ->add('chefIntervention', 'entity', array('class' => 'SdisAffichageBundle:Personnel'))
                ->add('chefGroupe', 'entity', array('class' => 'SdisAffichageBundle:Personnel'))
                ->add('chauffeur', 'entity', array('class' => 'SdisAffichageBundle:Personnel'))
                ->add('intervenant', 'entity', array('class' => 'SdisAffichageBundle:Personnel'));
    }
    
    /**
    * @param OptionsResolverInterface $resolver
    */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array('data_class' => 'Sdis\AffichageBundle\Entity\Piquets'));
    }
    
    /**
    * @return string
    */
    public function getName() {
        return 'sdis_affichagebundle_piquets';
    }
}
?>