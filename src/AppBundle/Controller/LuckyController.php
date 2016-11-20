<?php
// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class LuckyController extends Controller
{
    public function numberAction($min = 1)
    {
        $number = mt_rand($min, 100);
        $url = $this->generateUrl('numberLucky', array('min' => 5), UrlGeneratorInterface::ABSOLUTE_URL);
        return new Response(
            '<html><body>Min: '.$min.' Lucky number: '.$number.'<br>
            URL: '.$url.'
            </body></html>'
        );
    }

    public function hellooAction($name)
    {
        return new Response(
            '<html><body>Hello how are you'.$name.'</body></html>'
        );
    }

    public function testAction($name)
    {
        return new Response(
            '<html><body>test'.$name.'</body></html>'
        );
    }

}
