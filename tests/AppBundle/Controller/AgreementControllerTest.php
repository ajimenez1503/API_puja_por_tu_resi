<?php
// tests/AppBundle/Controller/AgreementControllerTest.php
namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AgreementControllerTest extends WebTestCase
{
    public $username_student = 'test';
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
     * Test verify a agreement between a student and a room.
     */
    public function testroomVerifyUnsigned()
    {
        $client = static::createClient();

        $session = $client->getContainer()->get('session');
        $firewall = 'main';
        $em = $client->getContainer()->get('doctrine')->getManager();
        $user = $em->getRepository('AppBundle:College')->findOneByUsername('test');

        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
        self::$kernel->getContainer()->get('security.token_storage')->setToken($token);

        $session->set('_security_'.$firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);


        $room = $em->getRepository('AppBundle:Room')->findOneByName('test');
        $agreement = $em->getRepository('AppBundle:Agreement')->findOneBy(
            array('room' => $room->getId(), "student"=>$this->username_student)
        );
        $client->request(
            'GET',
            '/Agreement/roomVerifyUnsigned/'.$room->getId()
        );
        $output=array(
             'student'=>$agreement->getStudent()->getJSON(),
             'agreement'=>$agreement->getJSON(),
             'agreement_signed'=>$agreement->verifyAgreementSigned(),
        );
        $response = $client->getResponse()->getContent();
        $this->assertEquals(
            $this->returnjson(True,'Room '.$room->getId().' tiene un contrato.',$output)->getContent(),
            $response
        );
    }


}
