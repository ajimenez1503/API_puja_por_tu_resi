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
     *  description="This method create a message by the user (Student/COLLEGE). Can be called by user (College/Student).",
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
     *      {
     *          "name"="username_student",
     *          "dataType"="String",
     *          "description"="Optianl atribute. If the college create the message, need the student. . "
     *      },
     *  },
     * )
     */
    public function createAction(Request $request)
    {
        $message_text=$request->request->get('message');
        $username_student=$request->request->get('username_student');
        $file=$request->files->get('file_attached');
        if (count($file) == 0){
            $file=null;
        }
        if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$message_text,1,5000)){
            return $this->returnjson(false,'Texto no es valido (tamaño).');
        }
        if (!is_null($file)){
            if (!$this->get('app.validate')->validatePDFFile($this->get('validator'),$file) and !$this->get('app.validate')->validateImageFile($this->get('validator'),$file)){
                return $this->returnjson(false,'Archivo no es valido (PFD- IMG).');
            }
            $filename=md5(uniqid()).'.'.$file->getClientOriginalExtension();
            $file->move($this->container->getParameter('storageFiles'),$filename);
        }
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRoles()[0]=="ROLE_STUDENT"){//student
            $agreement=$user->getCurrentAgreement();
            if($agreement){
                if($agreement->verifyAgreementSigned()){
                    try {
                        $college=$agreement->getCollege();
                        $message = new Message();
                        $message->setStudent($user);
                        $message->setCollege($college);
                        $message->setSenderType($user->getRoles()[0]);
                        $message->setMessage($message_text);
                        if (!is_null($file)){
                            $message->setFileAttached($filename);
                        }
                        $message->setReadByStudent(true);
                        $college->addMessage($message);
                        $user->addMessage($message);
                        $em = $this->getDoctrine()->getManager();
                        // tells Doctrine you want to (eventually) save the Product (no queries is done)
                        $em->persist($message);
                        $em->persist($user);
                        $em->persist($college);

                        //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                        $em->flush();
                    } catch (\Exception $pdo_ex) {
                        return $this->returnjson(false,'SQL exception.');
                    }
                }else{
                    return $this->returnjson(false,'El estudiante tiene un contrato pero sin firmar.');
                }
            }else{
                return $this->returnjson(false,'El estudiante no tiene contrato con ninguna residencai.');
            }
        }elseif ($user->getRoles()[0]=="ROLE_COLLEGE"){//college
            $username_student=$request->request->get('username_student');
            //validate $username
            if (is_null($username_student) || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$username_student,9,9)){
                    return $this->returnjson(False,'Username '.$username_student.' no es valido.');
            }
            $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username_student);
            if (!$student) {
                return $this->returnjson(False,'estudiante con username '.$username_student.' no existe.');
            }
            //verify signed agreement and college
            $agreement=$student->getCurrentAgreement();
            if($agreement){
                if($agreement->verifyAgreementSigned()){
                    if ($agreement->getCollege()==$user){
                        try {
                            $message = new Message();
                            $message->setStudent($student);
                            $message->setCollege($user);
                            $message->setSenderType($user->getRoles()[0]);
                            $message->setMessage($message_text);
                            if (!is_null($file)){
                                $message->setFileAttached($filename);
                            }
                            $message->setReadByCollege(true);
                            $student->addMessage($message);
                            $user->addMessage($message);
                            $em = $this->getDoctrine()->getManager();
                            // tells Doctrine you want to (eventually) save the Product (no queries is done)
                            $em->persist($message);
                            $em->persist($user);
                            $em->persist($student);

                            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                            $em->flush();
                        } catch (\Exception $pdo_ex) {
                            return $this->returnjson(false,'SQL exception.');
                        }
                    }else{
                        return $this->returnjson(false,'El estudiante '.$student->getUsername().' tiene un contrato pero no con esta residencia '.$user->getUsername().'');
                    }
                }else{
                    return $this->returnjson(false,'El estudiante '.$student->getUsername().' tiene un contrato pero sin firmar.');
                }
            }else{
                return $this->returnjson(false,'El estudiante '.$student->getUsername().' no tiene contrato con ninguna residencai.');
            }
        }else{
            return $this->returnjson(False,'ROLE unknown.');
        }
        return $this->returnjson(true,'El mensaje se ha creado correctamente.');
    }


    /**
     * @ApiDoc(
     *  description="Get list of messages of a user (Student || College). Can be called by user (College/Student).",
     *  requirements={
     *      {
     *          "name"="username_student",
     *          "dataType"="String",
     *          "description"="Optianl atribute. If the college create the message, need the student. . "
     *      },
     *  },
     * )
     */
    public function getAction(Request $request)
    {
        $output=array();
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRoles()[0]=="ROLE_COLLEGE"){//college
            $username_student=$request->query->get('username_student');
            //validate $username
            if (is_null($username_student) || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$username_student,9,9)){
                    return $this->returnjson(False,'Username '.$username_student.' no es valido.');
            }
            $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username_student);
            if (!$student) {
                return $this->returnjson(False,'estudiante con username '.$username_student.' no existe.');
            }
            //verify signed agreement and college
            $agreement=$student->getCurrentAgreement();
            if($agreement){
                if($agreement->verifyAgreementSigned()){
                    if ($agreement->getCollege()==$user){
                        $messages=$user->getMessages()->getValues();
                        for ($i = 0; $i < count($messages); $i++) {
                            if($messages[$i]->getStudent()==$student){
                                array_unshift($output,$messages[$i]->getJSON());
                            }
                        }
                    }else{
                        return $this->returnjson(false,'El estudiante '.$student->getUsername().' tiene un contrato pero no con esta residencia '.$user->getUsername().'');
                    }
                }else{
                    return $this->returnjson(false,'El estudiante '.$student->getUsername().' tiene un contrato pero sin firmar.');
                }
            }else{
                return $this->returnjson(false,'El estudiante '.$student->getUsername().' no tiene contrato con ninguna residencai.');
            }

        }elseif ($user->getRoles()[0]=="ROLE_STUDENT"){//college
            $messages=$user->getMessages()->getValues();
            for ($i = 0; $i < count($messages); $i++) {
                array_unshift($output,$messages[$i]->getJSON());
            }
        }else{
            return $this->returnjson(False,'ROLE unknow');
        }
        return $this->returnjson(true,'Lista de mensajes.',$output);
    }


    /**
     * @ApiDoc(
     *  description="Get number of unread message of a user. Can be called by user (College/Student).",
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
     *  description="Get number of unread message from all the student of a college. Can be called by user (College).",
     * )
     */
    public function countUnreadStudentAction()
    {
        $output=array();

        $user=$this->get('security.token_storage')->getToken()->getUser();
        $messages=$user->getMessages()->getValues();
        $student=$user->getStudents();
        for ($i = 0; $i < count($student); $i++) {
            $count=0;
            for ($j = 0; $j < count($messages); $j++) {
                if($messages[$j]->getStudent()==$student[$i] && !$messages[$j]->getReadByCollege()){
                        $count+=1;
                }
            }
            array_unshift($output,
                array(
                    'unread'=>$count,
                    'student'=>$student[$i]->getJSON(),
                )
            );
        }
        return $this->returnjson(true,'Lista studiantes',$output);
    }


    /**
     * @ApiDoc(
     *  description="This method set ReadBy=true of a all the messages of user. Can be called by user (College/Student).",
     * )
     */
    public function openAllAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $messages=$user->getMessages()->getValues();
        for ($i = 0; $i < count($messages); $i++) {
            try {
                if ($user->getRoles()[0]=="ROLE_STUDENT" &&  !$messages[$i]->getReadByStudent()){
                    $messages[$i]->setReadByStudent(true);

                    $em = $this->getDoctrine()->getManager();
                    // tells Doctrine you want to (eventually) save the Product (no queries is done)
                    $em->persist($messages[$i]);

                    //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                    $em->flush();
                }elseif ($user->getRoles()[0]=="ROLE_COLLEGE" &&  !$messages[$i]->getReadByCollege()){
                    $messages[$i]->setReadByCollege(true);

                    $em = $this->getDoctrine()->getManager();
                    // tells Doctrine you want to (eventually) save the Product (no queries is done)
                    $em->persist($messages[$i]);

                    //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                    $em->flush();
                }

            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
        }
        return $this->returnjson(true,'Todos mensajes se ha leido correctamente.');
    }


    /**
     * @ApiDoc(
     *  description="This method set ReadByCollege=true of a all the messages of user with a specific student. Can be called by user (College).",
     * )
     */
    public function openStudentAction($username_student)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        //validate $username
        if (is_null($username_student) || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$username_student,9,9)){
                return $this->returnjson(False,'Username '.$username_student.' no es valido.');
        }
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username_student);
        if (!$student) {
            return $this->returnjson(False,'estudiante con username '.$username_student.' no existe.');
        }
        //verify signed agreement and college
        $agreement=$student->getCurrentAgreement();
        if($agreement){
            if($agreement->verifyAgreementSigned()){
                if ($agreement->getCollege()==$user){
                    $messages=$user->getMessages()->getValues();
                    for ($i = 0; $i < count($messages); $i++) {
                        if( !$messages[$i]->getReadByCollege()){
                            try {
                                $messages[$i]->setReadByCollege(true);
                                $em = $this->getDoctrine()->getManager();
                                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                                $em->persist($messages[$i]);

                                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                                $em->flush();
                            } catch (\Exception $pdo_ex) {
                                return $this->returnjson(false,'SQL exception.');
                            }
                        }
                    }
                }else{
                    return $this->returnjson(false,'El estudiante '.$student->getUsername().' tiene un contrato pero no con esta residencia '.$user->getUsername().'');
                }
            }else{
                return $this->returnjson(false,'El estudiante '.$student->getUsername().' tiene un contrato pero sin firmar.');
            }
        }else{
            return $this->returnjson(false,'El estudiante '.$student->getUsername().' no tiene contrato con ninguna residencai.');
        }
        return $this->returnjson(true,'Todos mensajes se ha leido correctamente.');
    }


    /**
     * @ApiDoc(
     *  description="Download attached file of the message. Can be called by user (College/Student).",
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
}
