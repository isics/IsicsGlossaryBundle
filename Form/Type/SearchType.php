<?php

namespace Isics\GlossaryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class SearchType extends AbstractType
{
    /**
     * @see Symfony\Component\Form\AbstractType
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('keywords', 'text', array('required' => false));
    }
    
    /**
     * @see Symfony\Component\Form\AbstractType
     */
    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Isics\GlossaryBundle\Form\Model\Search');
    }
    
    /**
     * @see Symfony\Component\Form\AbstractType
     */    
    public function getName()
    {
        return 'isics_glossary_search';
    }
}
