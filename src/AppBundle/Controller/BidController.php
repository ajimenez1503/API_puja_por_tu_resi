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
     *  description="This method create a bid by the user (Student). An user cannot bid more than 5 times neither bid twice for the same room. ",
     *  requirements={
     *      {
     *          "name"="room_id",
     *          "dataType"="String",
     *          "description"="Id of the room ."
     *      },
     *  }
     * )
     */
    public function createAction(Request $request)
    {
        $room_id=$request->request->get('room');
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($room_id);
        if (!$room) {
            return $this->returnjson(False,'Habitacion con id '.$room_id.' no existe.');
        }else {
            $user=$this->get('security.token_storage')->getToken()->getUser();
            if ($user->getRoles()[0]=="ROLE_STUDENT"){
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
                        $user->addBid($bid);
                        $em = $this->getDoctrine()->getManager();
                        // tells Doctrine you want to (eventually) save the Product (no queries is done)
                        $em->persist($bid);
                        $em->persist($user);
                        // actually executes the queries (i.e. the INSERT query)
                        //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                        $em->flush();
                    } catch (\Exception $pdo_ex) {
                        return $this->returnjson(false,'SQL exception.');
                    }
                    return $this->returnjson(true,'El apuesta se ha creado correctamente.');
                }else{
                    return $this->returnjson(False,'El usuario ya ha pujada por 5 habitaciones.');
                }
            }else{
                return $this->returnjson(False,'The user College cannot bid for a room.');
            }
        }
    }


    /**
     * @ApiDoc(
     *  description="Get all the bid of a room by its id. Can be called by user (College/Student). Format JSON.",
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
                array_unshift($output,$list_bids[$i]->getJSON());
            }
            return $this->returnjson(true,'Lista de pujas.',$output);
        }
    }


    /**
     * @ApiDoc(
     *  description="Get data of a bid. Can be called by user (College/Student). Format JSON.",
     * )
     */
    public function getAction($id)
    {
        $bid = $this->getDoctrine()->getRepository('AppBundle:Bid')->find($id);
        if (!$bid) {
            return $this->returnjson(False,'Puja con id '.$id.' no existe.');
        }else {
            return $this->returnjson(true,'Puja.',$bid->getJSON());
        }
    }


    /**
     * @ApiDoc(
     *  description="Remove a bid. Can be called by user (College/Student).",
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
     *  description="Remove all the bid of a room by its id. Can be called by user (College).",
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
     *  description="Remove the bid of a user (student) above a room by its id. Can be called by user (Student).",
     * )
     */
    public function removeBidRoomStudentAction($id)
    {
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($id);
        if (!$room) {
            return $this->returnjson(False,'Habitacion con id '.$id.' no existe.');
        }else {
            $user=$this->get('security.token_storage')->getToken()->getUser();
            if ($user->getRoles()[0]=="ROLE_STUDENT"){
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
            }else{
                return $this->returnjson(False,'The user College cannot remove that bid for a room.');
            }
        }
    }





}
