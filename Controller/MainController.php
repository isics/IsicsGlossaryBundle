<?php

namespace Isics\GlossaryBundle\Controller;

use Isics\GlossaryBundle\Form\Handler\SearchFormHandler;
use Isics\GlossaryBundle\Form\Handler\TermFormHandler;
use Isics\GlossaryBundle\Form\Model\Search;
use Isics\GlossaryBundle\Form\Type\TermType;
use Isics\GlossaryBundle\Form\Type\SearchType;
use Isics\GlossaryBundle\Manager\TermManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /** 
     * Shows glossary.
     * Creates / updates terms.
     *
     * @param integer $id       Edited term id (optional)
     * @param Request $request  The request
     */
    public function listAction($id = null, Request $request)
    {
        $termManager = new TermManager($this->getDoctrine()->getEntityManager());
        $repository  = $termManager->getRepository();

        $searchForm = $this->createForm(new SearchType(), new Search());
        $searchFormHandler = new SearchFormHandler($searchForm, $request);
        if ($searchFormHandler->process()) {
            $terms = $repository->findByKeywordsAndOrderedByTerm($searchForm->getData()->getKeywords());
        } else {
            $terms = $repository->findAllOrderedByTerm();
        }
        
        // Grouping terms by first letter
        $letters = array_fill_keys(range('A', 'Z'), array());
        foreach ($terms as $term) {
            $letter = strtoupper(substr($term, 0, 1));           
            $letters[$letter][] = $term;
        }
        
        $term = (null !== $id) ? $repository->find($id) : $termManager->create();

        $form        = $this->createForm(new TermType(), $term);
        $formHandler = new TermFormHandler($form, $request, $termManager);
        if ($formHandler->process()) {
            return $this->redirect($this->generateUrl('isics_glossary_list'));
        }

        return $this->render('IsicsGlossaryBundle:Main:list.html.twig', array(
            'id'         => $id,
            'form'       => $form->createView(),
            'searchForm' => $searchForm->createView(),
            'letters'    => $letters
        ));
    }

    /**
     * Deletes a term.
     *
     * @param integer $id  Term id
     */
    public function deleteAction($id)
    {
        $termManager = new TermManager($this->getDoctrine()->getEntityManager());
        $repository  = $termManager->getRepository();

        $term = $repository->find($id);

        $termManager->delete($term);

        return $this->redirect($this->generateUrl('isics_glossary_list'));
    }

}
