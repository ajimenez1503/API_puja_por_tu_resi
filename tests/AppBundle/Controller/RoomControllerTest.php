<?php
// tests/AppBundle/Controller/RoomControllerTest.php
namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

class RoomControllerTest extends WebTestCase
{
    public $name_room = 'test123';
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
     * Test create a room from college
     */
    public function testcreate()
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


        $file1 = tempnam(sys_get_temp_dir(), 'upl1'); // create file
        imagepng(imagecreatetruecolor(10, 10), $file1); // create and write image/png to it
        $image1 = new UploadedFile(
            $file1,
            'new_image1.png',
            'image/jpeg'
        );

        $file2 = tempnam(sys_get_temp_dir(), 'upl2'); // create file
        imagepng(imagecreatetruecolor(10, 10), $file2); // create and write image/png to it
        $image2 = new UploadedFile(
            $file2,
            'new_image2.png',
            'image/jpeg'
        );

        $file3 = tempnam(sys_get_temp_dir(), 'upl3'); // create file
        imagepng(imagecreatetruecolor(10, 10), $file3); // create and write image/png to it
        $image3 = new UploadedFile(
            $file3,
            'new_image3.png',
            'image/jpeg'
        );


        //TEST CREATE

        $client->request(
            'POST',
            '/Room/create/',
            array(
                'name' => $this->name_room,
                'price' => 100,
                'floor' =>1,
                'size'=> 1,
                'tv'=>1,
                'bath'=>1,
                'desk'=>1,
                'wardrove'=>1
            ),
            array(
                'picture1' => $image1,
                'picture2' => $image2,
                'picture3' => $image3
            )
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(
            $this->returnjson(true,'La habitacion se ha creado correctamente.')->getContent(),
            $response
        );
        // Assert a specific 200 status code
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
    }

    /**
     * Test update a inicidence  from college to student, which has a agreement
     */
    public function testremove()
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


        //TEST UPDATE STATE
        $em = $client->getContainer()->get('doctrine')->getManager();
        $room = $em->getRepository('AppBundle:Room')->findOneBy(
            array('name' => $this->name_room)
        );
        $id=$room->getId();
        $client->request(
            'POST',
            '/Room/remove/'.$id
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(
            $this->returnjson(true,'Habitacion with id '.$id.' se ha eleminado.')->getContent(),
            $response
        );
        // Assert a specific 200 status code
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
    }
}
