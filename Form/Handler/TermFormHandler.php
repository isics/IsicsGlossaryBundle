<?php

namespace Isics\GlossaryBundle\Form\Handler;

use Isics\GlossaryBundle\Entity\Term;
use Isics\GlossaryBundle\Manager\TermManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class TermFormHandler
{
    protected $form;	
    protected $request;
    protected $termManager;

    public function __construct(Form $form, Request $request, TermManager $termManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->termManager = $termManager;
    }

    public function process()
    {
        if ('POST' === $this->request->getMethod()) {
            $this->form->bindRequest($this->request);
            if ($this->form->isValid()) {
                $this->doOnSuccess($this->form->getData());

                return true;
            }
        }

        return false;
    }

    protected function doOnSuccess(Term $term)
    {
        $this->termManager->save($term);
    }
}
