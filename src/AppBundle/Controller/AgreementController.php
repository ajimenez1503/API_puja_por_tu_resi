<?php
// src/AppBundle/Controller/AgreementController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\User;
use AppBundle\Entity\Student;
use AppBundle\Entity\Agreement;
use AppBundle\Entity\Room;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class AgreementController extends Controller
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
     * Create the agreement file by the date of the college, student, room and the current agreement.
     * Using knp_snappy and twig to generate a pdf file.
     */
    public function create_agreement_file($college,$student,$room,$agreement)
    {
        $filename=md5(uniqid()).'.pdf';
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'agreement.html.twig',
                array(
                    'college'  => $college->getJSON(),
                    'student' =>$student->getJSON(),
                    'room' =>$room->getJSON(),
                    'agreement' =>$agreement->getJSON(),
                )
            ),
            $this->container->getParameter('storageFiles')."/".$filename
        );
        return $filename;
    }

     /**
      * @ApiDoc(
      *  description="This method create a Agreement between a student and a room. That fucntion is called by the system automatically.",
      *  requirements={
      *      {
      *          "name"="room_id",
      *          "dataType"="Integer",
      *          "description"="Id of the room."
      *      },
      *      {
      *          "name"="student_username",
      *          "dataType"="String",
      *          "description"="Username of a student"
      *      },
      *  },
      * )
      */
    public function createAction(Request $request)
    {
        $room_id=$request->request->get('room_id');
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($room_id);
        if (!$room) {
            return $this->returnjson(False,'Habitacion con id '.$room_id.' no existe.');
        }

        if($room->getCurrentAgreement()){
            return $this->returnjson(False,'Habitacion con id '.$room_id.' ya tiene un contrato.');
        }
        $username=$request->request->get('student_username');
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username);
        if (!$student) {
            return $this->returnjson(False,'Estudiente con username '.$username.' no existe.');
        }
        if($student->getCurrentAgreement()){
            return $this->returnjson(False,'Estudiente '.$username.' ya tiene un contrato.');
        }
        if ($student->getRoles()[0]=="ROLE_STUDENT"){
            try {
                $agreement = new Agreement();
                $agreement->setDateStartSchool($room->getDateStartSchool());
                $agreement->setDateEndSchool($room->getDateEndSchool());
                $agreement->setPrice($room->getPrice());
                $agreement->setStudent($student);
                $agreement->setRoom($room);
                $file_name=$this->create_agreement_file($room->getCollege(),$student,$room,$agreement);
                $agreement->setFileAgreement($file_name);

                $student->addAgreement($agreement);
                $room->addAgreement($agreement);

                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($agreement);
                $em->persist($student);
                $em->persist($room);
                // actually executes the queries (i.e. the INSERT query)
                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.'.$pdo_ex);
            }
            return $this->returnjson(true,'El contrato se ha creado correctamente.');
        }else{
            return $this->returnjson(False,'El usuario (residencia) no puede tener un contrato con una habitacion.');
        }
    }




    /**
     * @ApiDoc(
     *  description="This method remove a Agreement between a student and a room (the agreement which is valid according to the dates). That fucntion is called by the system automatically.",
     *  requirements={
     *      {
     *          "name"="room_id",
     *          "dataType"="Integer",
     *          "description"="Id of the room."
     *      },
     *      {
     *          "name"="student_username",
     *          "dataType"="String",
     *          "description"="Username of a student"
     *      },
     *  },
     * )
     */
   public function removeAction(Request $request)
   {
       $room_id=$request->request->get('room_id');
       $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($room_id);
       if (!$room) {
           return $this->returnjson(False,'Habitacion con id '.$room_id.' no existe.');
       }
       $agreement_room=$room->getCurrentAgreement();
       if(!$agreement_room){
           return $this->returnjson(False,'Habitacion con id '.$room_id.' no tiene un contrato.');
       }
       $username=$request->request->get('student_username');
       $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username);
       if (!$student) {
           return $this->returnjson(False,'Estudiente con username '.$username.' no existe.');
       }
       $agreement_student=$room->getCurrentAgreement();
       if(!$agreement_student){
           return $this->returnjson(False,'Estudiente '.$username.' no tiene un contrato.');
       }
       if($agreement_student!=$agreement_room){
            return $this->returnjson(False,'Estudiente '.$username.'  y habitacion con id '.$room_id.' tienen distinto contrato.');
       }
       if ($student->getRoles()[0]=="ROLE_STUDENT"){
           try {
               $student->removeAgreement($agreement_student);
               $room->removeAgreement($agreement_student);
               $em = $this->getDoctrine()->getManager();
               // tells Doctrine you want to (eventually) save the Product (no queries is done)
               $em->remove($agreement_student);
               $em->persist($student);
               $em->persist($room);
               // actually executes the queries (i.e. the INSERT query)
               //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
               $em->flush();
           } catch (\Exception $pdo_ex) {
               return $this->returnjson(false,'SQL exception.');
           }
           return $this->returnjson(true,'El contrato se ha eliminado correctamente.');
       }else{
           return $this->returnjson(False,'El usuario (residencia) no puede tener un contrato con una habitacion.');
       }
   }


   /**
    * @ApiDoc(
    *  description="This method accept a Agreement between a student and a room. That fucntion is called by a user (student).",
    *  requirements={
    *      {
    *          "name"="room_id",
    *          "dataType"="Integer",
    *          "description"="Id of the room."
    *      },
    *      {
    *          "name"="file_agreement_signed",
    *          "dataType"="File",
    *          "description"="agreement signed "
    *      },
    *  },
    * )
    */
  public function acceptAction(Request $request)
  {
      $room_id=$request->request->get('room_id');
      $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($room_id);
      if (!$room) {
          return $this->returnjson(False,'Habitacion con id '.$room_id.' no existe.');
      }
      $agreement_room=$room->getCurrentAgreement();
      if(!$agreement_room){
          return $this->returnjson(False,'Habitacion con id '.$room_id.' no tiene un contrato.');
      }
      $student=$this->get('security.token_storage')->getToken()->getUser();
      if ($student->getRoles()[0]!="ROLE_STUDENT"){
          return $this->returnjson(False,'El usuario (residencia) no puede tener un contrato con una habitacion.');
      }
      if(!$student->getCurrentAgreement()){
          return $this->returnjson(False,'Estudiente '.$username.' ya tiene un contrato.');
      }
      $agreement_student=$room->getCurrentAgreement();
      if(!$agreement_student){
          return $this->returnjson(False,'Estudiente '.$username.' no tiene un contrato.');
      }
      if($agreement_student!=$agreement_room){
           return $this->returnjson(False,'Estudiente '.$username.'  y habitacion con id '.$room_id.' tienen distinto contrato.');
      }
      if($agreement_student->verifyAgreementSigned()){
          return $this->returnjson(false,'El contrato con id '.$agreement_student->getId().' ya esta firmado con fecha: '.$agreement_student->getDateSigned()->format('Y-m-d H:i'));
      }
      $file=$request->files->get('file_agreement_signed');
      if (!$this->get('app.validate')->validatePDFFile($this->get('validator'),$file) and !$this->get('app.validate')->validateImageFile($this->get('validator'),$file)){
          return $this->returnjson(false,'Archivo no es valido (PFD- IMG).');
      }
      $filename=md5(uniqid()).'.'.$file->getClientOriginalExtension();
      $file->move($this->container->getParameter('storageFiles'),$filename);
      try {
          $agreement_student->setDateSigned(date_create('now'));
          $agreement_student->setFileAgreementSigned($filename);
          $em = $this->getDoctrine()->getManager();
          //remove all the bid of a room removeBidsRoomAction($id)
          $response = $this->forward('AppBundle:Bid:removeBidsRoom', array(
              'id'  => $room->getId(),
          ));
          if (!json_decode($response->getContent(),true)['success']){
              return $response;
          }

          //TODO generate the the first rent
          // tells Doctrine you want to (eventually) save the Product (no queries is done)
          $em->persist($agreement_student);
          // actually executes the queries (i.e. the INSERT query)
          //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
          $em->flush();
      } catch (\Exception $pdo_ex) {
          return $this->returnjson(false,'SQL exception.'.$pdo_ex);
      }
      return $this->returnjson(true,'El contrato se ha aceptado correctamente.');
  }




}
