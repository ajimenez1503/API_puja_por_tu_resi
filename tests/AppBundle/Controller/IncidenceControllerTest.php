<?php
// tests/AppBundle/Controller/IncidenceControllerTest.php
namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class IncidenceControllerTest extends WebTestCase
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
     * Test create a Incidence from student to college, which has a agreement
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

        $file = tempnam(sys_get_temp_dir(), 'upl'); // create file
        imagepng(imagecreatetruecolor(10, 10), $file); // create and write image/png to it
        $image = new UploadedFile(
            $file,
            'new_image.png',
            'image/jpeg'
        );


        //TEST CREATE

        $client->request(
            'POST',
            '/Incidence/create/',
            array('description' => $this->description),
            array('file_name' => $image)
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(
            $this->returnjson(true,'La incidencia se ha creado correctamente.')->getContent(),
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
    public function testupdate()
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
        $new_status="IN PROGRESS";
        $em = $client->getContainer()->get('doctrine')->getManager();
        $incidence = $em->getRepository('AppBundle:Incidence')->findOneBy(
            array('description' => $this->description, 'status' => "OPEN", "student"=>$user->getUsername())
        );
        $client->request(
            'POST',
            '/Incidence/updateState/',
            array(
                'id' => $incidence->getId(),
                'status' => $new_status
            )
        );

        $response = $client->getResponse()->getContent();
        $this->assertEquals(
            $this->returnjson(true,'La incidencia se ha actualizado correctamente con el nuevo estado '.$new_status.'.')->getContent(),
            $response
        );
        // Assert a specific 200 status code
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
    }
}
