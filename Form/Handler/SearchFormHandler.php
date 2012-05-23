<?php

namespace Isics\GlossaryBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handler du Fomulaire de recherche
 */
class SearchFormHandler
{
    /**
     * Constructor
     * 
     * @param Symfony\Component\Form\Form $searchForm
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(Form $searchForm, Request $request)
    {
        $this->searchForm = $searchForm;
        $this->request = $request;
    }
    
    public function process()
    {
        if ('POST' === $this->request->getMethod() && $this->request->request->has($this->searchForm->getName())) {
            $this->searchForm->bindRequest($this->request);
            if ($this->searchForm->isValid()) {
                return true;
            }
        }
        
        return false;
    }
}