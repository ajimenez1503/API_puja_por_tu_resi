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
     *  description="This method create a message ",
     *  requirements={
     *      {
     *          "name"="message",
     *          "dataType"="String",
     *          "description"="text of the message"
     *      },
     *      {
     *          "name"="file_attached",
     *          "dataType"="String",
     *          "description"="file_name of the attachedfile file "
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
            return $this->returnjson(false,'Texto no es valida.');
        }
        if (!is_null($file)){
            if (!$this->get('app.validate')->validatePDFFile($this->get('validator'),$file)){
                return $this->returnjson(false,'Archivo no es valida.');
            }
            $filename=md5(uniqid()).'.'.$file->getClientOriginalExtension();
            $file->move($this->container->getParameter('storageFiles'),$filename);
        }

        try {
            $user=$this->get('security.token_storage')->getToken()->getUser();
            $message = new Message();
            $message->setStudent($user);
            $message->setMessage($message_text);
            if (!is_null($file)){
                $message->setFileAttached($filename);
            }
            //$user->addMessage($message);
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
     *  description="This method set open=true of a message.",
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
        return $this->returnjson(true,'El messnage se ha leido correctamente.');
    }


    /**
     * @ApiDoc(
     *  description="get list of message of a user(Student || College)",
     * )
     */
    public function getAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $messages=$user->getMessages()->getValues();

        $output=array();
        for ($i = 0; $i < count($messages); $i++) {
            array_push($output,$messages[$i]->getJSON()
            );
        }
        return $this->returnjson(true,'List of messages',$output);
    }

}
