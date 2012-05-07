<?php

namespace Isics\GlossaryBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class IsicsGlossaryListener {

    protected $entityManager;
    protected $router;

    public function __construct(ObjectManager $entity_manager, RouterInterface $router)
    {
        $this->entityManager = $entity_manager;
        $this->router = $router;
    }
    
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();
        
        if ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html')
            || 'html' !== $request->getRequestFormat()
        ) {
            return;
        }
        
        $this->injectDefinitionLinks($response);
    }
    
    protected function injectDefinitionLinks(Response $response)
    {
        $terms = $this->entityManager->getRepository('IsicsGlossaryBundle:Term')->findAll();

        $terms2links = array();
        foreach ($terms as $term) {
            $terms2links[$term->getTerm()] = sprintf(
                '<a href="%s#glossary_term%s">%s</a>',
                $this->router->generate('isics_glossary_list'),
                $term->getId(),
                $term->getTerm()
            );
        }
        
        // A revoir car ça déconne dans les input
        // $response->setContent(
        //     str_replace(array_keys($terms2links), $terms2links, $response->getContent())
        // );
    }
}
