<?php
// tests/AppBundle/Controller/BidControllerTest.php
namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BidControllerTest extends WebTestCase
{
    public $description = 'algo no funciona';
    public function returnjson($success,$message,$data=null)
    {
        $response = new JsonResponse();
        if (is_null($data)){
            $data=array();
        }
        $response->setData(array(
            'success' => $success,
            'message' => $message,
            'data'=> $data,
        ));
        return $response;
    }

    /**
     * Test create a bid from student to college, which has a agreement.
     * Success false because the user has a agreement.
     */
    public function testcreate()
    {
        $client = static::createClient();

        $session = $client->getContainer()->get('session');
        $firewall = 'main';
        $em = $client->getContainer()->get('doctrine')->getManager();
        $user = $em->getRepository('AppBundle:Student')->findOneByUsername('test');

        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
        self::$kernel->getContainer()->get('security.token_storage')->setToken($token);

        $session->set('_security_'.$firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

        $room = $em->getRepository('AppBundle:Room')->findOneByName('test');
        $date_start_school  = date_create('tomorrow');
        $date_end_school  = date_create('tomorrow');
        $date_end_school->modify('+1 month');
        $client->request(
            'POST',
            '/Bid/create/',
            array(
                'room' => $room->getId(),
                'date_start_school' => $date_start_school->format('Y-m-d'),
                'date_end_school' => $date_end_school->format('Y-m-d')
            )
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(
            $this->returnjson(False,'El usuario tiene un contrato en esas fechas.')->getContent(),
            $response
        );
        // Assert a specific 200 status code
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
    }

}
