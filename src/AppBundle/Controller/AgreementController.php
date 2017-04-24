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
      *  description="This method create a Agreement between a student and a room. Can be called by user(ADMIN) automatically.",
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
      *      {
      *          "name"="bid_id",
      *          "dataType"="String",
      *          "description"="bid_id with the date_start_school and date_end_school"
      *      },
      *  },
      * )
      */
    public function createAction($room_id,$username,$bid_id)
    {
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($room_id);
        if (!$room) {
            return $this->returnjson(False,'Habitacion con id '.$room_id.' no existe.');
        }

        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username);
        if (!$student) {
            return $this->returnjson(False,'Estudiente con username '.$username.' no existe.');
        }
        if(!$student->checkAvailability($bid->getDateStartSchool(),$bid->getDateEndSchool())){
            return $this->returnjson(False,'Estudiente '.$username.' ya tiene un contrato.');
        }
        $bid = $this->getDoctrine()->getRepository('AppBundle:Bid')->find($bid_id);
        if (!$bid) {
            return $this->returnjson(False,'Apuesta con id '.$bid_id.' no existe.');
        }
        if ($student->getRoles()[0]=="ROLE_STUDENT"){
            try {
                $agreement = new Agreement();
                $agreement->setDateStartSchool($bid->getDateStartSchool());
                $agreement->setDateEndSchool($bid->getDateEndSchool());
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
     *  description="This method remove a Agreement between a student and a room (the agreement which is valid according to the dates). Can be called by user (Student).",
     *  requirements={
     *      {
     *          "name"="room_id",
     *          "dataType"="Integer",
     *          "description"="Id of the room."
     *      },
     *      {
     *          "name"="agreement_id",
     *          "dataType"="Integer",
     *          "description"="agreement id "
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
       $student=$this->get('security.token_storage')->getToken()->getUser();
       $agreement_id=$request->request->get('agreement_id');
       $agreement_student = $this->getDoctrine()->getRepository('AppBundle:Agreement')->find($agreement_id);
       if (!$agreement_student) {
           return $this->returnjson(False,'contrato con id '.$room_id.' no existe.');
       }
       try {
           $student->removeAgreement($agreement_student);
           $room->removeAgreement($agreement_student);
           $em = $this->getDoctrine()->getManager();
           // tells Doctrine you want to (eventually) save the Product (no queries is done)
           $em->remove($agreement_student);
           $em->persist($student);
           $em->persist($room);

           //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
           $em->flush();
       } catch (\Exception $pdo_ex) {
           return $this->returnjson(false,'SQL exception.');
       }
       return $this->returnjson(true,'El contrato se ha eliminado correctamente.');
    }


    /**
    * @ApiDoc(
    *  description="This method accept a Agreement between a student and a room. Can be called by user  (student).",
    *  requirements={
    *      {
    *          "name"="room_id",
    *          "dataType"="Integer",
    *          "description"="Id of the room."
    *      },
    *      {
    *          "name"="file_agreement_signed",
    *          "dataType"="File",
    *          "description"="file agreement signed "
    *      },
    *      {
    *          "name"="agreement_id",
    *          "dataType"="Integer",
    *          "description"="agreement id "
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
      $agreement_id=$request->request->get('agreement_id');
      $agreement_student = $this->getDoctrine()->getRepository('AppBundle:Agreement')->find($agreement_id);
      if (!$agreement_student) {
          return $this->returnjson(False,'contrato con id '.$room_id.' no existe.');
      }
      $student=$this->get('security.token_storage')->getToken()->getUser();
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

          // generate all the rents of the student in the agreement
          $response = $this->forward('AppBundle:Rent:createAll', array(
              'username_student'  => $student->getUsername(),
          ));
          if (!json_decode($response->getContent(),true)['success']){
              return $response;
          }

          // tells Doctrine you want to (eventually) save the Product (no queries is done)
          $em->persist($agreement_student);

          //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
          $em->flush();
      } catch (\Exception $pdo_ex) {
          return $this->returnjson(false,'SQL exception.'.$pdo_ex);
      }
      return $this->returnjson(true,'El contrato se ha aceptado correctamente.');
    }


    /**
    * @ApiDoc(
    *  description="This method assigned offered room to a student (with more point) the day of the bid. Can be called by user (ADMIN) automatically.",
    * )
    */
    public function assignedRoomsAction(Request $request)
    {
        //get the list of colleges
        //get the list of room
        //get the list of bids of every Room
            //get the student with more point:
                //in the case the the student has not a agreement and the dates of the school are availability
                    //create the contract and assigned the room during the specific time.
                    //when assigned a student a room, remove all the bid of a student
                //else remove the bid
        $colleges = $this->getDoctrine()->getRepository('AppBundle:College')->findAll();
        if (!$colleges) {
            return $this->returnjson(false,'No hay ninguna residencia.');
        }else {
            $output=array();
            $today=date_create('now')->format('Y-m-d');//year month and day (not hour and minute)
            for ($i = 0; $i < count($colleges); $i++) {
                //check if the college has OFFERED rooms
                $rooms=$colleges[$i]->getRooms();
                for ($j = 0; $j < count($rooms); $j++) {
                    //get list of bids of the room.
                    $bids=$rooms[$j]->getBids();
                    while (count($bids)>0){//while there are bids
                        $student=$bids[0]->getStudent();
                        $bid=$bids[0];
                        for($c=1; $c < count($bids); $c++) {//get the student with more point bid in a room
                            if($bids[$c]->getStudent()->getPoint()>$student->getPoint()){
                                $student=$bids[$c]->getStudent();
                                $bid=$bids[$c];
                            }
                        }
                        if ($rooms[$j]->checkAvailability($bid->getDateStartSchool(),$bid->getDateEndSchool())
                            &&
                            $student->checkAvailability($bid->getDateStartSchool(),$bid->getDateEndSchool())
                        ){
                            //$rooms[$j]  $student
                            array_unshift($output,array(
                                'room' => $rooms[$j]->getId(),
                                'student_username' => $student->getUsername(),
                                )
                            );

                            //create a agreement between a room and a user (student)
                            $response = $this->forward('AppBundle:Agreement:create', array(
                                'username'  =>  $student->getUsername(),
                                'room_id' => $rooms[$j]->getId(),
                                'bid_id' => $bid->getId(),
                            ));
                            if (!json_decode($response->getContent(),true)['success']){
                                return $response;
                            }
                            //remove all the bids of a student
                            $response = $this->forward('AppBundle:Bid:removeBidsStudent', array(
                                'username'  =>  $student->getUsername(),
                            ));
                            continue;

                        }
                        //remove the bid of the student
                        $response = $this->forward('AppBundle:Bid:remove', array(
                            'id'  =>  $bid->getId(),
                        ));
                        $bids=$rooms[$j]->getBids();
                    }
                }
            }
            return $this->returnjson(true,'List de habitaciones asignadas.',$output );
        }
    }


    /**
    * @ApiDoc(
    *  description="Download agreement file. Can be called by user (Student/College/ADMIN).",
    *  requirements={
    *      {
    *          "name"="filename",
    *          "dataType"="String",
    *          "description"="Filename agreement"
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
   *  description="Get the current agreement signed. That fucntion is called by a user (student).",
   * )
   */
   public function getCurrentSignedAction(Request $request)
   {
        $student=$this->get('security.token_storage')->getToken()->getUser();
        $agreement=$student->getCurrentAgreement();
        if($agreement){
            if ($agreement->verifyAgreementSigned()) {
                $output=array(
                    'room' => $agreement->getRoom()->getJSON(),
                    'college' => $agreement->getCollege()->getJSON(),
                    'agreement'=>$agreement->getJSON(),
                );
                return $this->returnjson(True,'Estudiante '.$student->getUsername().' tiene un contrato.',$output);
            }
        }
        return $this->returnjson(False,'Estudiante '.$student->getUsername().' no tiene contrato .');
   }

   /**
    * @ApiDoc(
    *  description="Get the current agreement signed. That fucntion is called by a user (student).",
    * )
    */
    public function getListAction(Request $request)
    {
         $student=$this->get('security.token_storage')->getToken()->getUser();
         $list_agreement=$student->getAgreements()->getValues();
         $output=array();
         for ($i = 0; $i < count($list_agreement); $i++) {
             array_unshift($output,array(
                 'room' => $list_agreement[$i]->getRoom()->getJSON(),
                 'college' => $list_agreement[$i]->getCollege()->getJSON(),
                 'agreement'=>$list_agreement[$i]->getJSON(),
                 'agreement_signed'=>$list_agreement[$i]->verifyAgreementSigned(),
                )
             );
         }
         return $this->returnjson(true,'Lista contratos .',$output);
    }


   /**
    * @ApiDoc(
    *  description="Verify if a room has a agreement without signed. Return the agreemnt. Can be called by user (College).",
    * )
    */
    public function roomVerifyUnsignedAction($room_id)
    {
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($room_id);
        if (!$room) {
            return $this->returnjson(False,'Habitacion con id '.$room_id.' no existe.');
        }
        $agreement=$room->getCurrentAgreement();
        if($agreement){
            $output=array(
                 'student'=>$agreement->getStudent()->getJSON(),
                 'agreement'=>$agreement->getJSON(),
                 'agreement_signed'=>$agreement->verifyAgreementSigned(),
            );
            return $this->returnjson(True,'Room '.$room_id.' tiene un contrato.',$output);
        }else{
            return $this->returnjson(False,'Room '.$room_id.' no tiene contrato .');
        }
    }


    /**
     * @ApiDoc(
     *  description="This method remove a Agreement which is not signed for one week. Can be called by user (ADMIN).",
     * )
     */
    public function removeUnsignedAction(Request $request)
    {
        $output=array();
        $today=date_create('now');
        $agreements = $this->getDoctrine()->getRepository('AppBundle:Agreement')->findAll();
        for ($i = 0; $i < count($agreements); $i++) {
            if(!$agreements[$i]->verifyAgreementSigned()){
                $interval=date_diff($agreements[$i]->getDateSigned(),$today);
                if ($interval->y >= 1 || $interval->m >= 1 || $interval->d >= 7){
                    $student=$agreements[$i]->getStudent();
                    $room=$agreements[$i]->getRoom();
                    array_unshift($output,array(
                        'room_id'=>$room->getId(),
                        'student_username'=> $student->getUsername(),
                        )
                    );
                    try {
                        $student->removeAgreement($agreements[$i]);
                        $room->removeAgreement($agreements[$i]);
                        $em = $this->getDoctrine()->getManager();
                        // tells Doctrine you want to (eventually) save the Product (no queries is done)
                        $em->remove($agreements[$i]);
                        $em->persist($student);
                        $em->persist($room);

                        //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                        $em->flush();
                    } catch (\Exception $pdo_ex) {
                        return $this->returnjson(false,'SQL exception.');
                    }
                }
            }
        }
        return $this->returnjson(true,'Los contrato se ha eliminado correctamente.',$output);
    }
}
