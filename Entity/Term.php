<?php

namespace Isics\GlossaryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Isics\GlossaryBundle\Entity\Term
 *
 * @ORM\Table(name="term")
 * @ORM\Entity(repositoryClass="Isics\GlossaryBundle\Entity\TermRepository")
 */
class Term
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $term
     *
     * @ORM\Column(name="term", type="string", length=255)
     */
    private $term;

    /**
     * @var text $definition
     *
     * @ORM\Column(name="definition", type="text")
     */
    private $definition;


    public function __toString()
    {
        return $this->term;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set term
     *
     * @param string $term
     */
    public function setTerm($term)
    {
        $this->term = $term;
    }

    /**
     * Get term
     *
     * @return string 
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set definition
     *
     * @param text $definition
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    }

    /**
     * Get definition
     *
     * @return text 
     */
    public function getDefinition()
    {
        return $this->definition;
    }
}