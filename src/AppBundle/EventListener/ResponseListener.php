<?php
namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{

    /**
    * For every response of the API, add to the headers, allow access 'http://127.0.0.1' (localhost)
    */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $event->getResponse()->headers->add(array('Access-Control-Allow-Origin' =>  'http://127.0.0.1'));
        $event->getResponse()->headers->add(array('Access-Control-Allow-Credentials' =>  'true'));
    }
}
