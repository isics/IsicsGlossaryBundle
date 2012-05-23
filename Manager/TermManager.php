<?php

namespace Isics\GlossaryBundle\Manager;

use Doctrine\ORM\EntityManager;
use Isics\GlossaryBundle\Entity\Term;

class TermManager
{
    protected $entityManager;
    
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Creates a new term.
     *
     * @return Term
     */
    public function create()
    {
        $term = new Term();
 
        return $term;
    }
    
    /**
     * Saves a term.
     *
     * @param Term $term  Term
     */
    public function save(Term $term)
    {
        $this->entityManager->persist($term);
        $this->entityManager->flush();
    }

    /**
     * Deletes a term.
     *
     * @param Term $term  Term
     */
    public function delete(Term $term)
    {
        $this->entityManager->remove($term);
        $this->entityManager->flush();
    }    

    /**
     * Returns Term repository.
     *
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->entityManager->getRepository('IsicsGlossaryBundle:Term');
    }
}