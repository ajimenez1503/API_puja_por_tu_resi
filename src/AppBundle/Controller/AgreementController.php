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

    public function validate_list_agreement_out_date($list_agreement){
        $today=date_create('now');
        for ($i = 0; $i < count($list_agreement); $i++) {
            if ($list_agreement[$i]->getDateSigned()<= $today && $list_agreement[$i]->getDateStartSchool()>= $today){//the current date is ina contract
                return false;
            }
        }
        return true;
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

        if(!$this->validate_list_agreement_out_date($room->getAgreements()->getValues())){
            return $this->returnjson(False,'Habitacion con id '.$room_id.' ya tiene un contrato.');
        }
        $username=$request->request->get('student_username');
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username);
        if (!$student) {
            return $this->returnjson(False,'Estudiente con username '.$username.' no existe.');
        }
        if(!$this->validate_list_agreement_out_date($student->getAgreements()->getValues())){
            return $this->returnjson(False,'Estudiente '.$username.' ya tiene un contrato.');
        }
        if ($student->getRoles()[0]=="ROLE_STUDENT"){
            try {
                $agreement = new Agreement();
                $agreement->setDateStartSchool($room->getDateStartSchool());
                $agreement->setDateEndSchool($room->getDateEndSchool());
                $agreement->setPrice($room->getPrice());
                //TODO generate agreement file
                $agreement->setStudent($student);
                $agreement->setRoom($room);

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

       if($this->validate_list_agreement_out_date($room->getAgreements()->getValues())){
           return $this->returnjson(False,'Habitacion con id '.$room_id.' no tiene un contrato.');
       }
       $username=$request->request->get('student_username');
       $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username);
       if (!$student) {
           return $this->returnjson(False,'Estudiente con username '.$username.' no existe.');
       }
       if($this->validate_list_agreement_out_date($student->getAgreements()->getValues())){
           return $this->returnjson(False,'Estudiente '.$username.' no tiene un contrato.');
       }
       if ($student->getRoles()[0]=="ROLE_STUDENT"){
           $agreement=null;
           $today=date_create('now');
           $list_agreement=$student->getAgreements()->getValues();
           for ($i = 0; $i < count($list_agreement); $i++) {
               if ($list_agreement[$i]->getDateSigned()<= $today && $list_agreement[$i]->getDateStartSchool()>= $today){//the current date is ina contract
                   $agreement=$list_agreement[$i];
               }
           }
           if ($agreement){
               try {
                   $student->removeAgreement($agreement);
                   $room->removeAgreement($agreement);
                   $em = $this->getDoctrine()->getManager();
                   // tells Doctrine you want to (eventually) save the Product (no queries is done)
                   $em->remove($agreement);
                   $em->persist($student);
                   $em->persist($room);
                   // actually executes the queries (i.e. the INSERT query)
                   //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                   $em->flush();
               } catch (\Exception $pdo_ex) {
                   return $this->returnjson(false,'SQL exception.'.$pdo_ex);
               }
               return $this->returnjson(true,'El contrato se ha eliminado correctamente.');
           }else{
               return $this->returnjson(False,'Estudiente '.$username.' no tiene un contrato.');
           }

       }else{
           return $this->returnjson(False,'El usuario (residencia) no puede tener un contrato con una habitacion.');
       }
   }

}
