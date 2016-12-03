<?php
namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $event->getResponse()->headers->add(array('Access-Control-Allow-Origin' =>  'http://127.0.0.1'));
        $event->getResponse()->headers->add(array('Access-Control-Allow-Credentials' =>  'true'));
    }
}
