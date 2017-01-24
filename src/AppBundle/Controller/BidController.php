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


}
