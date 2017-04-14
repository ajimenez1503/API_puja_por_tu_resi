<?php
// tests/AppBundle/Controller/MessageControllerTest.php
namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MessageControllerTest extends WebTestCase
{
    public $message = 'message';
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
     * Test create a message from student to college, which has a agreement
     */
    public function testcreateStudent()
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

        $file = tempnam(sys_get_temp_dir(), 'upl'); // create file
        imagepng(imagecreatetruecolor(10, 10), $file); // create and write image/png to it
        $image = new UploadedFile(
            $file,
            'new_image.png',
            'image/jpeg'
        );


        //TEST CREATE a message from student to college, which has a agreement
        $client->request(
            'POST',
            '/Message/create/',
            array('message' => $this->message),
            array('file_attached' => $image)
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(
            $this->returnjson(true,'El mensaje se ha creado correctamente.')->getContent(),
            $response
        );
    }

    /**
     * Test create a message from college to student, which has a agreement
     * Test is not a valid DNI, so it is success false.
     */
    public function testcreateCollege()
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

        $file = tempnam(sys_get_temp_dir(), 'upl'); // create file
        imagepng(imagecreatetruecolor(10, 10), $file); // create and write image/png to it
        $image = new UploadedFile(
            $file,
            'new_image.png',
            'image/jpeg'
        );


        //TEST CREATE a message from student to college, which has a agreement
        $client->request(
            'POST',
            '/Message/create/',
            array('message' => $this->message, 'username_student'=>$this->username_student),
            array('file_attached' => $image)
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(
            $this->returnjson(False,'Username '.$this->username_student.' no es valido.')->getContent(),
            $response
        );
    }
}
