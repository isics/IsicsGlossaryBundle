<?php

namespace Isics\GlossaryBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class GlossaryListener {

    protected $entityManager;
    protected $router;

    public function __construct(ObjectManager $entity_manager, RouterInterface $router)
    {
        $this->entityManager = $entity_manager;
        $this->router = $router;
    }
    
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $response = $event->getResponse();
        $request  = $event->getRequest();
        
        if ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html')
            || 'html' !== $request->getRequestFormat()
        ) {
            return;
        }

        $this->injectGlossaryLinks($response);
    }
    
    protected function injectGlossaryLinks(Response $response)
    {
        $terms = $this->entityManager->getRepository('IsicsGlossaryBundle:Term')->findAllOrderedByTerm();

        if (0 === count($terms)) {
            return;
        }

        $urls    = array();
        $pattern = array();
        $baseUrl = $this->router->generate('isics_glossary_list');
        foreach ($terms as $term) {
            $termNorm        = preg_replace('/\s+/', ' ', strtoupper(trim($term->getTerm())));
            $pattern[]       = preg_replace('/ /', '\\s+', preg_quote($termNorm));
            $urls[$termNorm] = sprintf('%s#glossary_term%s', $baseUrl, $term->getId());
        }

        $pattern = '/\b(' . implode('|', $pattern) . ')\b/i';

        $document = new \DOMDocument;
        // skip warnings cause HTML5 tags are not supported
        @$document->loadHTML($response->getContent());
        $xpath = new \DOMXPath($document);
        $nodes = $xpath->query('body//text()[not(ancestor::a)]');
        foreach ($nodes as $node) {
            $text     = $node->nodeValue;
            $hitCount = preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);

            if (0 === $hitCount) {
                continue;
            }

            $offset  = 0;
            $parent  = $node->parentNode;
            $refNode = $node->nextSibling;

            $parent->removeChild($node);

            foreach ($matches[0] as $i => $match) {
                  $termTxt  = $match[0];
                  $termPos  = $match[1];
                  $termNorm = preg_replace('/\s+/', ' ', strtoupper($termTxt));

                  // insert any text before the term instance
                  $prefix = substr($text, $offset, $termPos - $offset);
                  $parent->insertBefore($document->createTextNode($prefix), $refNode);

                  // insert the actual term instance as a link
                  $link = $document->createElement('a', $termTxt);
                  $link->setAttribute('href', $urls[$termNorm]);
                  $parent->insertBefore($link, $refNode);

                  $offset = $termPos + strlen($termTxt);

                  if ($i === $hitCount - 1) {  // last match, append remaining text
                        $suffix = substr($text, $offset);
                        $parent->insertBefore($document->createTextNode($suffix), $refNode);
                  }    
            }        
        }

        $response->setContent($document->saveHTML());
    }

}
