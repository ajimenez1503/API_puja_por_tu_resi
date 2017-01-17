<?php
// src/AppBundle/Controller/MessageController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\User;
use AppBundle\Entity\Student;
use AppBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class MessageController extends Controller
{

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
     * @ApiDoc(
     *  description="This method create a message by the user (Student)",
     *  requirements={
     *      {
     *          "name"="message",
     *          "dataType"="String",
     *          "description"="Text of the message, max 5000 character."
     *      },
     *      {
     *          "name"="file_attached",
     *          "dataType"="File",
     *          "description"="File of the attachedfile file. It is optional. It can be image or pdf format. "
     *      },
     *  },
     * )
     */
    public function createAction(Request $request)
    {
        $message_text=$request->request->get('message');
        $file=$request->files->get('file_attached');
        if (count($file) == 0){
            $file=null;
        }
        if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$message_text,1,5000)){
            return $this->returnjson(false,'Texto no es valido.');
        }
        if (!is_null($file)){
            if (!$this->get('app.validate')->validatePDFFile($this->get('validator'),$file) and !$this->get('app.validate')->validateImageFile($this->get('validator'),$file)){
                return $this->returnjson(false,'Archivo no es valido.');
            }
            $filename=md5(uniqid()).'.'.$file->getClientOriginalExtension();
            $file->move($this->container->getParameter('storageFiles'),$filename);
        }

        try {
            $user=$this->get('security.token_storage')->getToken()->getUser();
            //TODO If ROLE_STUDENT get the college by the agreement
            //TODO elese, get the username_student in the request
            $message = new Message();
            $message->setStudent($user);
            $message->setSenderType($user->getRoles()[0]);
            $message->setMessage($message_text);
            if (!is_null($file)){
                $message->setFileAttached($filename);
            }
            $user->addMessage($message);
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries is done)
            $em->persist($message);
            $em->persist($user);
            // actually executes the queries (i.e. the INSERT query)
            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.');
        }
        return $this->returnjson(true,'El mensaje se ha creado correctamente.');
    }


    /**
     * @ApiDoc(
     *  description="This methodo oepn a message by the 'id'.",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "description"="ID of the message"
     *      },
     *  },
     * )
     */
    public function openAction(Request $request)
    {
        $id=$request->request->get('id');
        try {
            $message = $this->getDoctrine()->getRepository('AppBundle:Message')->find($id);
            $message->setOpen(true);
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries is done)
            $em->persist($message);
            // actually executes the queries (i.e. the INSERT query)
            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.');
        }
        return $this->returnjson(true,'El mensaje se ha leido correctamente.');
    }


    /**
     * @ApiDoc(
     *  description="Get list of messages of a user (Student || College). In JSON format.",
     * )
     */
    public function getAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $messages=$user->getMessages()->getValues();
        $output=array();
        for ($i = 0; $i < count($messages); $i++) {
            array_unshift($output,$messages[$i]->getJSON());
        }
        return $this->returnjson(true,'Lista de mensajes.',$output);
    }



    /**
     * @ApiDoc(
     *  description="Get number of unread message of a user (Student || College).",
     * )
     */
    public function countUnreadAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $messages=$user->getMessages()->getValues();

        $count=0;
        for ($i = 0; $i < count($messages); $i++) {
            if ($user->getRoles()[0]=="ROLE_STUDENT"){
                if (!$messages[$i]->getReadByStudent()){
                    $count+=1;
                }
            }elseif ($user->getRoles()[0]=="ROLE_COLLEGE"){
                if (!$messages[$i]->getReadByCollege()){
                    $count+=1;
                }
            }
        }
        return $this->returnjson(true,'Numero de mensajes sin leer.',$count);
    }


    /**
     * @ApiDoc(
     *  description="Download attached file of the message.",
     *  requirements={
     *      {
     *          "name"="filename",
     *          "dataType"="String",
     *          "description"="Filename of the file to download"
     *      },
     *  },
     * )
     */
    public function downloadAction($filename)
    {
        $path = $this->container->getParameter('storageFiles');
        $content = file_get_contents($path.'/'.$filename);
        $response = new Response();
        //set headers
        //$response->headers->set("Access-Control-Expose-Headers", "Content-Disposition");
        $response->headers->add(array('Access-Control-Expose-Headers' =>  'Content-Disposition'));
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);

        $response->setContent($content);
        return $response;
    }



    /**
     * @ApiDoc(
     *  description="This method set ReadBy=true of a all the messages of user (Student/College).",
     * )
     */
    public function openAllAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $messages=$user->getMessages()->getValues();
        for ($i = 0; $i < count($messages); $i++) {
            try {
                if ($user->getRoles()[0]=="ROLE_STUDENT"){
                    $messages[$i]->setReadByStudent(true);
                }elseif ($user->getRoles()[0]=="ROLE_COLLEGE"){
                    $messages[$i]->setReadByCollege(true);
                }
                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($messages[$i]);
                // actually executes the queries (i.e. the INSERT query)
                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
        }
        return $this->returnjson(true,'Todos mensajes se ha leido correctamente.');
    }


}
