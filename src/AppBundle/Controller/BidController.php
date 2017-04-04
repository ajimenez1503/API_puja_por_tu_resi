<?php
// src/AppBundle/Controller/BidController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\User;
use AppBundle\Entity\Student;
use AppBundle\Entity\Bid;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class BidController extends Controller
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
     *  description="This method create a bid by the user (Student). An user cannot bid more than 5 times neither bid twice for the same room. Can be called by user (Student). ",
     *  requirements={
     *      {
     *          "name"="room_id",
     *          "dataType"="Integer",
     *          "description"="Id of the room ."
     *      },
     *      {
     *          "name"="date_start_school",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) starts to live and pay."
     *      },
     *      {
     *          "name"="date_end_school",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) stops to live and pay."
     *      },
     *  }
     * )
     */
    public function createAction(Request $request)
    {
        $room_id=$request->request->get('room');
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($room_id);
        $date_start_school=$request->request->get('date_start_school');
        $date_end_school=$request->request->get('date_end_school');
        if(is_null($date_start_school) || is_null($date_end_school) ){
            return $this->returnjson(false,'Deben introducirse la Fecha de estacia académica.');
        }
        $date_start_school=date_create($date_start_school);
        $date_end_school=date_create($date_end_school);
        if (!$this->get('app.validate')->validateDate($date_start_school,$date_end_school)){
            return $this->returnjson(false,'Fecha de estacia academica no es correcta.');
        }
        if (!$room) {
            return $this->returnjson(False,'Habitacion con id '.$room_id.' no existe.');
        }else {
            $user=$this->get('security.token_storage')->getToken()->getUser();
            //check thaat the user has not a current agreement
            if($user->getCurrentAgreement()!=null){
                //Check that the user has not more that 5 bid already.
                $list_bids=$user->getBids();
                if (count($list_bids)<5){
                    //check if the user already have bid for that room_id
                    for ($i = 0; $i < count($list_bids); $i++) {
                        if ($list_bids[$i]->getRoom()==$room){
                            return $this->returnjson(False,'El usuario ya ha pujada por esta habitacion.');
                        }
                    }
                    try {
                        $bid = new Bid();
                        $bid->setStudent($user);
                        $bid->setRoom($room);
                        $bid->setPoint($user->get_point());
                        $bid->setDateStartSchool($date_start_school);
                        $bid->setDateEndSchool($date_end_school);
                        $user->addBid($bid);
                        $em = $this->getDoctrine()->getManager();
                        // tells Doctrine you want to (eventually) save the Product (no queries is done)
                        $em->persist($bid);
                        $em->persist($user);

                        //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                        $em->flush();
                    } catch (\Exception $pdo_ex) {
                        return $this->returnjson(false,'SQL exception.');
                    }
                    return $this->returnjson(true,'La apuesta se ha creado correctamente.');
                }else{
                    return $this->returnjson(False,'El usuario ya ha pujada por 5 habitaciones.');
                }
            }else{
                return $this->returnjson(False,'El usuario ya tiene un contrato.');
            }
        }
    }


    /**
     * @ApiDoc(
     *  description="Get all the bid of a room by its id. Can be called by user (College/Student/ADMIN).",
     * )
     */
    public function getBidsRoomAction($id)
    {
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($id);
        if (!$room) {
            return $this->returnjson(False,'Habitacion con id '.$id.' no existe.');
        }else {
            $list_bids=$room->getBids();
            $output=array();
            for ($i = 0; $i < count($list_bids); $i++) {
                //TODO sort the list of bigger to smaller
                array_unshift($output,$list_bids[$i]->getJSON());
            }
            $this->sort_array_of_array($output, 'point');//sort the array by point
            return $this->returnjson(true,'Lista de pujas.',$output);
        }
    }


    /**
     * Sort a array which is composed by dict
     */
    function sort_array_of_array(&$array, $subfield)
    {
        $sortarray = array();
        foreach ($array as $key => $row)
        {
            $sortarray[$key] = $row[$subfield];
        }
        array_multisort($sortarray, SORT_DESC, $array);
    }


    /**
     * @ApiDoc(
     *  description="Get data of a bid. Can be called by user (College/Student/ADMIN).",
     * )
     */
    public function getAction($id)
    {
        $bid = $this->getDoctrine()->getRepository('AppBundle:Bid')->find($id);
        if (!$bid) {
            return $this->returnjson(False,'Puja con id '.$id.' no existe.');
        }else {
            return $this->returnjson(true,'Informacion sobre la puja.',$bid->getJSON());
        }
    }


    /**
     * @ApiDoc(
     *  description="Remove a bid. Can be called by user (College/Student/ADMIN).",
     * )
     */
    public function removeAction($id)
    {
        $bid = $this->getDoctrine()->getRepository('AppBundle:Bid')->find($id);
        if (!$bid) {
            return $this->returnjson(False,'Puja con id '.$id.' no existe.');
        }else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bid);
            $em->flush();
            return $this->returnjson(True,'Puja con id '.$id.' se ha eleminado.');
        }
    }


    /**
     * @ApiDoc(
     *  description="Remove all the bids of a room by its id. Can be called by user (College/ADMIN).",
     * )
     */
    public function removeBidsRoomAction($id)
    {
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($id);
        if (!$room) {
            return $this->returnjson(False,'Habitacion con id '.$id.' no existe.');
        }else {
            $list_bids=$room->getBids()->getValues();
            $number_of_bid=count($list_bids);
            for ($i = 0; $i < count($list_bids); $i++) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($list_bids[$i]);
                $em->flush();
            }
            return $this->returnjson(true,'Se han eliminado '.$number_of_bid.' pujas.');
        }
    }


    /**
     * @ApiDoc(
     *  description="Remove all the bids of a student by username. Can be called by user (ADMIN) automatically.",
     * )
     */
    public function removeBidsStudentAction($username)
    {
        if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$username,1,10)){
            return $this->returnjson(false,'DNI usurio no es valido.');
        }
        $user_student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username);
        if(!$user_student){
            return $this->returnjson(true,'No existe un estudiante con DNI '.$username.'.');
        }else{
            $list_bids=$user_student->getBids()->getValues();
            $number_of_bid=count($list_bids);
            for ($i = 0; $i < count($list_bids); $i++) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($list_bids[$i]);
                $em->flush();
            }
            return $this->returnjson(true,'Se han eliminado '.$number_of_bid.' pujas.');
        }
    }


    /**
     * @ApiDoc(
     *  description="Remove the bid of a user (student) above a room by its id. Can be called by user (Student).",
     *  requirements={
     *      {
     *          "name"="room_id",
     *          "dataType"="String",
     *          "description"="Id of the room ."
     *      },
     *  }
     * )
     */
    public function removeBidRoomStudentAction(Request $request)
    {
        $room_id=$request->request->get('room');
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($room_id);
        if (!$room) {
            return $this->returnjson(False,'Habitacion con id '.$id.' no existe.');
        }else {
            $user=$this->get('security.token_storage')->getToken()->getUser();
            $list_bids=$room->getBids()->getValues();
            for ($i = 0; $i < count($list_bids); $i++) {
                if ($list_bids[$i]->getStudent()==$user){
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($list_bids[$i]);
                    $em->flush();
                    return $this->returnjson(true,'Se han eliminado la puja.');
                }
            }
            return $this->returnjson(true,'El usuario no tiene pujas en esta habitacion.');
        }
    }
}
