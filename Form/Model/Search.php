<?php

namespace Isics\GlossaryBundle\Form\Model;

/**
* Model pour le formulaire de rercher
*/
class Search
{
    /**
     * @var string $keywords
     */
    protected $keywords;

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }
}
