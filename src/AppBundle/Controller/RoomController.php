<?php
// src/AppBundle/Controller/RoomController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\User;
use AppBundle\Entity\Student;
use AppBundle\Entity\Room;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class RoomController extends Controller
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
     *  description="This method create a room by the user (College)",
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="String",
     *          "description"="Name of the room by the college."
     *      },
     *      {
     *          "name"="price",
     *          "dataType"="float",
     *          "description"="Price of the room per month."
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
     *      {
     *          "name"="date_start_bid",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) can start to bid."
     *      },
     *      {
     *          "name"="date_end_bid",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) can not bid anymore."
     *      },
     *      {
     *          "name"="floor",
     *          "dataType"="integer",
     *          "description"="floor of the room."
     *      },
     *      {
     *          "name"="size",
     *          "dataType"="integer",
     *          "description"="size of the room in m²."
     *      },
     *      {
     *          "name"="picture1",
     *          "dataType"="File",
     *          "description"="Image File of the room."
     *      },
     *      {
     *          "name"="picture2",
     *          "dataType"="File",
     *          "description"="Image File of the room."
     *      },
     *      {
     *          "name"="picture3",
     *          "dataType"="File",
     *          "description"="Image File of the room."
     *      },
     *      {
     *          "name"="tv",
     *          "dataType"="boolean",
     *          "description"="True if the room has a tv."
     *      },
     *      {
     *          "name"="bath",
     *          "dataType"="boolean",
     *          "description"="True if the room has bath."
     *      },
     *      {
     *          "name"="desk",
     *          "dataType"="boolean",
     *          "description"="True if the room has a desk."
     *      },
     *      {
     *          "name"="wardrove",
     *          "dataType"="boolean",
     *          "description"="True if the room has a wardrove."
     *      },
     *  },
     * )
     */
    public function createAction(Request $request)
    {
        $name=$request->request->get('name');
        $price=$request->request->get('price');
        $date_start_school=date_create($request->request->get('date_start_school'));
        $date_end_school=date_create($request->request->get('date_end_school'));
        $date_start_bid=date_create($request->request->get('date_start_bid'));
        $date_end_bid=date_create($request->request->get('date_end_bid'));
        $floor=$request->request->get('floor');
        $size=$request->request->get('size');
        $picture1=$request->files->get('picture1');
        $picture2=$request->files->get('picture2');
        $picture3=$request->files->get('picture3');
        $tv=$request->request->get('tv');
        $bath=$request->request->get('bath');
        $desk=$request->request->get('desk');
        $wardrove=$request->request->get('wardrove');

        //TODO validate everything
        if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$name)){
                return $this->returnjson(false,'Nombre no es valido (size).');
        }
        if (!$this->get('app.validate')->validateInt($price,$mix=1,$max=2000)){
            return $this->returnjson(false,'Precio no es valido (size).');
        }
        if (!$this->get('app.validate')->validateDate($date_start_school,$date_end_school)){
            return $this->returnjson(false,'Fecha de estacia academica no es correcta.');
        }
        if (!$this->get('app.validate')->validateDate($date_start_bid,$date_end_bid)){
            return $this->returnjson(false,'Fecha de las puja no es correcta.');
        }
        if (!$this->get('app.validate')->validateDate($date_end_bid,$date_start_school)){
            return $this->returnjson(false,'Fecha de las puja debe ser menor que la academica.');
        }
        if (!$this->get('app.validate')->validateImageFile($this->get('validator'),$picture1)){
            return $this->returnjson(false,'Archivo no es valido (Image format).');
        }
        if (!$this->get('app.validate')->validateImageFile($this->get('validator'),$picture2)){
            return $this->returnjson(false,'Archivo no es valido (Image format).');
        }
        if (!$this->get('app.validate')->validateImageFile($this->get('validator'),$picture3)){
            return $this->returnjson(false,'Archivo no es valido (Image format).');
        }
        if (!$this->get('app.validate')->validateInt($floor,$mix=1,$max=20)){
            return $this->returnjson(false,'Planta no es valido (size).');
        }
        if (!$this->get('app.validate')->validateInt($size,$mix=1,$max=100)){
            return $this->returnjson(false,'Tamaño no es valido (size).');
        }
        if (!$this->get('app.validate')->validateBool($tv)){
            return $this->returnjson(false,'Tv debe ser true or false.');
        }
        if (!$this->get('app.validate')->validateBool($bath)){
            return $this->returnjson(false,'Baño debe ser true or false.');
        }
        if (!$this->get('app.validate')->validateBool($desk)){
            return $this->returnjson(false,'Escritorio debe ser true or false.');
        }
        if (!$this->get('app.validate')->validateBool($wardrove)){
            return $this->returnjson(false,'Armario debe ser true or false.');
        }
        $filename1=md5(uniqid()).'.'.$picture1->getClientOriginalExtension();
        $picture1->move($this->container->getParameter('storageFiles'),$filename1);
        $filename2=md5(uniqid()).'.'.$picture2->getClientOriginalExtension();
        $picture2->move($this->container->getParameter('storageFiles'),$filename2);
        $filename3=md5(uniqid()).'.'.$picture3->getClientOriginalExtension();
        $picture3->move($this->container->getParameter('storageFiles'),$filename3);

        try {
            $user=$this->get('security.token_storage')->getToken()->getUser();
            if ($user->getRoles()[0]=="ROLE_COLLEGE"){
                $room = new Room();
                $room->setCollege($user);
                $room->setName($name);
                $room->setPrice($price);
                $room->setDateStartSchool($date_start_school);
                $room->setDateEndSchool($date_end_school);
                $room->setDateStartBid($date_start_bid);
                $room->setDateEndBid($date_end_bid);
                $room->setFloor($floor);
                $room->setSize($size);
                $room->setPicture1($filename1);
                $room->setPicture2($filename2);
                $room->setPicture3($filename3);
                $room->setTv($tv);
                $room->setBath($bath);
                $room->setDesk($desk);
                $room->setWardrove($wardrove);

                $user->addRoom($room);

                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($room);
                $em->persist($user);
                // actually executes the queries (i.e. the INSERT query)
                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            }else{
                return $this->returnjson(False,'The user Student cannot create a room.');
            }
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.'.$pdo_ex);
        }
        return $this->returnjson(true,'La habitacion se ha creado correctamente.');
    }



    /**
     * @ApiDoc(
     *  description="Get list of rooms of a user (College). Format JSON.",
     * )
     */
    public function getAllAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRoles()[0]=="ROLE_COLLEGE"){
            $rooms=$user->getRooms()->getValues();

            $output=array();
            for ($i = 0; $i < count($rooms); $i++) {
                array_unshift($output,$rooms[$i]->getJSON());
            }
            return $this->returnjson(true,'Lista de habitaciones.',$output);
        }else{
            return $this->returnjson(False,'The user Student cannot get rooms');
        }
    }

    /**
     * @ApiDoc(
     *  description="Get list of FREE rooms of a user (College). Format JSON.",
     * )
     */
    public function getFREEAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRoles()[0]=="ROLE_COLLEGE"){
            $rooms=$user->getRooms()->getValues();
            $today=date_create('now');
            $output=array();
            for ($i = 0; $i < count($rooms); $i++) {
                if(
                    ($rooms[$i]->getDateStartSchool()>$today && $rooms[$i]->getDateEndBid()<$today) ||//between bid and school
                    ($rooms[$i]->getDateStartSchool()>$today && $rooms[$i]->getDateStartBid()>$today)||//before bid and before school
                    ($rooms[$i]->getDateEndBid()<$today && $rooms[$i]->getDateEndSchool()<$today)  //after bid and after school
                ){
                    array_unshift($output,$rooms[$i]->getJSON());
                }
            }
            return $this->returnjson(true,'Lista de habitaciones sin usar (libres).',$output);
        }else{
            return $this->returnjson(False,'The user Student cannot get rooms');
        }
    }

    /**
     * @ApiDoc(
     *  description="Get list of OFFERED rooms of a user (College). Format JSON.",
     * )
     */
    public function getOFFEREDAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRoles()[0]=="ROLE_COLLEGE"){
            $rooms=$user->getRooms()->getValues();
            $today=date_create('now');
            $output=array();
            for ($i = 0; $i < count($rooms); $i++) {
                if($rooms[$i]->getDateStartBid()<=$today && $rooms[$i]->getDateEndBid()>=$today){
                    array_unshift($output,$rooms[$i]->getJSON());
                }
            }
            return $this->returnjson(true,'Lista de habitaciones para pujar.',$output);
        }else{
            return $this->returnjson(False,'The user Student cannot get rooms');
        }
    }


    /**
     * @ApiDoc(
     *  description="Get room of the id of a user (College). Format JSON.",
     * )
     */
    public function getAction($id)
    {
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($id);
        if (!$room) {
            return $this->returnjson(False,'Habitacion with id '.$id.' doesnt exists.');
        }else {
            return $this->returnjson(False,'Habitacion',$room->getJSON());
        }
    }



    /**
     * @ApiDoc(
     *  description="Remove room of the id of a user (College).",
     * )
     */
    public function removeAction($id)
    {
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find(intval($id));
        if (!$room) {
            return $this->returnjson(False,'Habitacion with id '.$id.' doesnt exists.');
        }else {
            $today=date_create('now');
            if(
                ($room->getDateStartSchool()>$today && $room->getDateEndBid()<$today) ||//between bid and school
                ($room->getDateStartSchool()>$today && $room->getDateStartBid()>$today)||//before bid and before school
                ($room->getDateEndBid()<$today && $room->getDateEndSchool()<$today)  //after bid and after school
            ){
                $em = $this->getDoctrine()->getManager();
                $em->remove($room);
                $em->flush();
                return $this->returnjson(True,'Habitacion with id '.$id.' se ha eleminado.');
            }else{
                return $this->returnjson(False,'Habitacion with id '.$id.' esta en uso (pujas - academico ).');
            }
        }
    }


    /**
     * @ApiDoc(
     *  description="Download pictures rooms.",
     *  requirements={
     *      {
     *          "name"="filename",
     *          "dataType"="String",
     *          "description"="Filename receipt"
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
     *  description="This method update the date bid/school of a room.",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "description"="ID of the room"
     *      },
     *      {
     *          "name"="date_start_school",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) starts to live and pay. (OPTIONAL)"
     *      },
     *      {
     *          "name"="date_end_school",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) stops to live and pay. (OPTIONAL)"
     *      },
     *      {
     *          "name"="date_start_bid",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) can start to bid. (OPTIONAL)"
     *      },
     *      {
     *          "name"="date_end_bid",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) can not bid anymore. (OPTIONAL)"
     *      },
     *  },
     * )
     */
    public function updateDateAction(Request $request)
    {
        $id=$request->request->get('id');

        if (!is_null($request->request->get('date_start_school'))) {
            $date_start_school=date_create($request->request->get('date_start_school'));
        }else{
            $date_start_school=NULL;
        }
        if (!is_null($request->request->get('date_end_school')))  {
            $date_end_school=date_create($request->request->get('date_end_school'));
        }else{
            $date_end_school=NULL;
        }

        if (!is_null($request->request->get('date_start_bid')))  {
            $date_start_bid=date_create($request->request->get('date_start_bid'));
        }else{
            $date_start_bid=NULL;
        }

        if (!is_null($request->request->get('date_end_bid')))  {
            $date_end_bid=date_create($request->request->get('date_end_bid'));
        }else{
            $date_end_bid=NULL;
        }

        if (!is_null($date_start_school) && !is_null($date_end_school)){
            if (!$this->get('app.validate')->validateDate($date_start_school,$date_end_school)){
                return $this->returnjson(false,'Fecha de estacia academica no es correcta.');
            }
        }
        if (!is_null($date_start_bid) && !is_null($date_end_bid)){
            if (!$this->get('app.validate')->validateDate($date_start_bid,$date_end_bid)){
                return $this->returnjson(false,'Fecha de las puja no es correcta.');
            }
        }
        if (!is_null($date_start_school) && !is_null($date_end_school) && !is_null($date_start_bid) && !is_null($date_end_bid)){
            if (!$this->get('app.validate')->validateDate($date_end_bid,$date_start_school)){
                return $this->returnjson(false,'Fecha de las puja debe ser menor que la academica.');
            }
        }
        try {
            $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($id);
            if (!is_null($date_start_school) && !is_null($date_end_school)){
                $room->setDateStartSchool($date_start_school);
                $room->setDateEndSchool($date_end_school);
            }
            if (!is_null($date_start_bid) && !is_null($date_end_bid)){
                $room->setDateStartBid($date_start_bid);
                $room->setDateEndBid($date_end_bid);
            }
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries is done)
            $em->persist($room);
            // actually executes the queries (i.e. the INSERT query)
            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.');
        }
        return $this->returnjson(true,'La habitacion se ha actualizado correctamente con las nuevas fechas.');
    }


    /**
     * @ApiDoc(
     *  description="Get all the colleges with the data of college and all the OFFERED room. This function can be called by User (College/Student). Format JSON.",
     * )
     */
    public function getSearchAllAction(Request $request)
    {
        $colleges = $this->getDoctrine()->getRepository('AppBundle:College')->findAll();
        if (!$colleges) {
            return $this->returnjson(true,'No hay ninguna residencia.');
        }else {
            $output=array();
            for ($i = 0; $i < count($colleges); $i++) {
                //check if the college has OFFERED rooms
                $rooms=$colleges[$i]->getOFFEREDroom();
                if ($rooms){
                    array_unshift($output,array_merge(
                        $colleges[$i]->getJSON(),array('rooms'=>$rooms))
                    );
                }
            }
            return $this->returnjson(true,'Lista de residencias y habitaciones para pujar.',$output);

        }
    }



    /**
     * @ApiDoc(
     *  description="Get all the colleges with the data of college and all the OFFERED room.
     * The college and the room should pass the restrictions: price, equipment, specific_college
     * This function can be called by User (College/Student). Format JSON.",
     *  requirements={
     *      {
     *          "name"="college_company_name",
     *          "dataType"="String",
     *          "description"="Name of the college (companyName)."
     *      },
     *      {
     *          "name"="price_max",
     *          "dataType"="float",
     *          "description"="Price max of the room per month."
     *      },
     *      {
     *          "name"="price_min",
     *          "dataType"="float",
     *          "description"="Price min of the room per month."
     *      },
     *  },
     * )
     */
    public function getSearchAction(Request $request)
    {
        $restrictions_companyName=$request->query->get('college_company_name');
        $restrictions_price_max=intval($request->query->get('price_max'));
        $restrictions_price_min=intval($request->query->get('price_min'));


        $restrictions_wifi=$request->query->get('wifi',$default ='0');

        $restrictions_elevator=$request->query->get('elevator',$default ='0');
        $restrictions_canteen=$request->query->get('canteen',$default ='0');
        $restrictions_hours24=$request->query->get('hours24',$default ='0');
        $restrictions_laundry=$request->query->get('laundry',$default ='0');
        $restrictions_gym=$request->query->get('gym',$default ='0');
        $restrictions_study_room=$request->query->get('study_room',$default ='0');
        $restrictions_heating=$request->query->get('heating',$default ='0');
        $restrictions_tv=$request->query->get('tv',$default ='0');
        $restrictions_bath=$request->query->get('bath',$default ='0');
        $restrictions_desk=$request->query->get('desk',$default ='0');
        $restrictions_wardrove=$request->query->get('wardrove',$default ='0');


        if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$restrictions_companyName)){
                return $this->returnjson(false,'Nombre residencia no es valido (size).');
        }
        if (!$this->get('app.validate')->validateInt($restrictions_price_max,$mix=1,$max=2000)){
            return $this->returnjson(false,'Precio max '.$restrictions_price_max.'no es valido.');
        }
        if (!$this->get('app.validate')->validateInt($restrictions_price_min,$mix=0,$max=1999)){
            return $this->returnjson(false,'Precio min no es valido.');
        }
        $colleges = $this->getDoctrine()->getRepository('AppBundle:College')->findAll();
        if (!$colleges) {
            return $this->returnjson(true,'No hay ninguna residencia.');
        }else {
            $output=array();
            for ($i = 0; $i < count($colleges); $i++) {
                //check if the college has OFFERED rooms
                if ($restrictions_companyName=="TODAS" || $restrictions_companyName==$colleges[$i]->getCompanyName()){
                    if(
                        ($restrictions_wifi=="0" || $colleges[$i]->getWifi()) &&
                        ($restrictions_elevator=="0" || $colleges[$i]->getElevator()) &&
                        ($restrictions_canteen=="0" || $colleges[$i]->getCanteen()) &&
                        ($restrictions_hours24=="0" || $colleges[$i]->getHours24()) &&
                        ($restrictions_laundry=="0" || $colleges[$i]->getLaundry()) &&
                        ($restrictions_gym=="0" || $colleges[$i]->getGym()) &&
                        ($restrictions_study_room=="0" || $colleges[$i]->getStudyRoom()) &&
                        ($restrictions_heating=="0" || $colleges[$i]->getHeating())
                    ){
                        $rooms=$colleges[$i]->getOFFEREDroom();
                        //analyze all the rooms and get the rooms, which pass the restrictions, in a new array
                        $new_rooms=array();
                        for ($j = 0; $j < count($rooms); $j++) {
                            if ($rooms[$j]['price']>=$restrictions_price_min && $rooms[$j]['price']<=$restrictions_price_max){
                                if(
                                    ($restrictions_tv=="0" || $rooms[$j]['tv']) &&
                                    ($restrictions_bath=="0" || $rooms[$j]['bath']) &&
                                    ($restrictions_desk=="0" || $rooms[$j]['desk']) &&
                                    ($restrictions_wardrove=="0" || $rooms[$j]['wardrove'])
                                ){
                                    array_unshift($new_rooms,$rooms[$j]);
                                }
                            }
                        }
                        if ($new_rooms){
                            array_unshift($output,array_merge(
                                $colleges[$i]->getJSON(),array('rooms'=>$new_rooms))
                            );
                        }
                    }
                }

            }
            return $this->returnjson(true,'Lista de residencias y habitaciones para pujar.',$output);

        }
    }


    /**
     * @ApiDoc(
     *  description="Get the companyName of all the colleges. This function can be called by User (College/Student). Format JSON.",
     * )
     */
    public function getAllCompanyNameAction(Request $request)
    {
        $colleges = $this->getDoctrine()->getRepository('AppBundle:College')->findAll();
        if (!$colleges) {
            return $this->returnjson(true,'No hay ninguna residencia.',$output);
        }else {
            $output=array();
            for ($i = 0; $i < count($colleges); $i++) {
                array_unshift($output,$colleges[$i]->getCompanyName());
            }
            return $this->returnjson(true,'Lista de residencias (company name).',$output);

        }
    }


}
