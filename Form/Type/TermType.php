<?php

namespace Isics\GlossaryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TermType extends AbstractType
{
    /**
     * @see Symfony\Component\Form\AbstractType
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('term', 'text');
        $builder->add('definition', 'textarea');
    }
    
    /**
     * @see Symfony\Component\Form\AbstractType
     */
    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Isics\GlossaryBundle\Entity\Term');
    }
    
    /**
     * @see Symfony\Component\Form\AbstractType
     */    
    public function getName()
    {
        return 'isics_glossary_term';
    }
}
