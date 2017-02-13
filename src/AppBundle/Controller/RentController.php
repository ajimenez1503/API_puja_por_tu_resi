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
use AppBundle\Entity\Rent;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class RentController extends Controller
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
     *  description="This method create all the rents of a user during his agreement. One per moth. It is called  automatically (ROLE_ADMIN)",
     *  requirements={
     *      {
     *          "name"="username_student",
     *          "dataType"="String",
     *          "description"="Username of the user Student"
     *      },
     *  },
     * )
     */
    public function createAllAction($username_student)
    {
        if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$username_student,1,10)){
            return $this->returnjson(false,'DNI usurio no es valido.');
        }
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username_student);
        if(!$student){
            return $this->returnjson(true,'No existe un estudiante con DNI '.$username_student.'.');
        }else{
            $agreement=$student->getCurrentAgreement();
            if($agreement){
                if($agreement->verifyAgreementSigned()){
                    $interval=date_diff($agreement->getDateStartSchool(), $agreement->getDateEndSchool());
                    $number_month=$interval->m + ($interval->y * 12)+ ($interval->d / 30);
                    $month=$agreement->getDateStartSchool();
                    for( $c=0;$c<$number_month;$c++){
                        try {
                            $rent = new Rent();
                            $rent->setStudent($student);
                            $rent->setPrice($agreement->getPrice());
                            $rent->setDate($month);
                            $student->addRent($rent);

                            $em = $this->getDoctrine()->getManager();
                            // tells Doctrine you want to (eventually) save the Product (no queries is done)
                            $em->persist($rent);
                            $em->persist($student);

                            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                            $em->flush();
                        } catch (\Exception $pdo_ex) {
                            return $this->returnjson(false,'SQL exception. '.$pdo_ex);
                        }
                        $month=$month->add(new \DateInterval('P1M'));
                    }
                    return $this->returnjson(true,'Las mensualidades se ha creado correctamente.',$number_month);

                }else{
                    return $this->returnjson(false,'El estudiante '.$student->getUsername().' tiene un contrato pero sin firmar.');
                }
            }else{
                return $this->returnjson(false,'El estudiante '.$student->getUsername().' no tiene contrato con ninguna residencai.');
            }
        }
    }


    /**
     * @ApiDoc(
     *  description="Get list of rents of a user. Format JSON. Can be called by user (Student/College).",
     * )
     */
    public function getAction(Request $request)
    {
        $output=array();
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRoles()[0]=="ROLE_STUDENT"){
            $rents=$user->getRents()->getValues();
            for ($i = 0; $i < count($rents); $i++) {
                array_push($output,$rents[$i]->getJSON());
            }
            return $this->returnjson(true,'Lista de pagos.',$output);
        }elseif ($user->getRoles()[0]=="ROLE_COLLEGE") {
            $list_students=$user->getStudents();// get all the user by all the agreement in date
            for($j=0;$j< count($list_students);$j++){
                $rents=$list_students[$j]->getRents()->getValues();
                for ($i = 0; $i < count($rents); $i++) {
                    array_push($output,$rents[$i]->getJSON());
                }
            }
            return $this->returnjson(true,'Lista de pagos.',$output);//return all the rents of all the user
        }else{
            return $this->returnjson(False,'Unknow roles ');
        }
    }


    /**
     * @ApiDoc(
     *  description="Get list of rent of a user (Student). In JSON format. This function can be called by User (College).",
     *  requirements={
     *      {
     *          "name"="username_student",
     *          "dataType"="String",
     *          "description"="Username of the student  "
     *      },
     *  },
     * )
     */
    public function getStudentAction($username_student)
    {
        $output=array();
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
                    $rents=$student->getRents()->getValues();
                    for ($i = 0; $i < count($rents); $i++) {
                        array_push($output,$rents[$i]->getJSON());
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
        return $this->returnjson(true,'Lista de pagos.',$output);
    }


    /**
     * @ApiDoc(
     *  description="Get list of rents without pay of a user (Student). Can be called by user (Student).",
     * )
     */
    public function getUnpaidAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $messages=$user->getRents()->getValues();
        $output=array();
        for ($i = 0; $i < count($messages); $i++) {
            if (!$messages[$i]->getStatusPaid()){
                array_push($output,$messages[$i]->getJSON());
            }
        }
        return $this->returnjson(true,'Lista de mensualidades sin pagar.',$output);
    }


    /**
     * Create the receipt file by the date of the college, student, and the current rent.
     * Using knp_snappy and twig to generate a pdf file.
     * The name of the user is random.
     */
    public function create_receipt($college_data,$student_data,$rent_data)
    {
        $filename=md5(uniqid()).'.pdf';
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'payment_receipt.html.twig',
                array(
                    'rent'  => $rent_data->getJSON(),
                    'student' =>$student_data->getJSON(),
                    'college' =>$college_data->getJSON(),
                )
            ),
            $this->container->getParameter('storageFiles')."/".$filename
        );
        return $filename;
    }

    /**
     * @ApiDoc(
     *  description="Pay the rent.
     *  This method generate file_receipt.
     *  update the date_paid and set the cardNumber and cardHolder. Can be called by user (Student).",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "description"="ID of the message"
     *      },
     *      {
     *          "name"="cardNumber",
     *          "dataType"="string",
     *          "description"="Number of the card of the paid"
     *      },
     *      {
     *          "name"="cardHolder",
     *          "dataType"="string",
     *          "description"="Name of the holder of the card of the paid"
     *      },
     *      {
     *          "name"="cvv",
     *          "dataType"="string",
     *          "description"="Security number of the card of the paid"
     *      },
     *      {
     *          "name"="expiry_year",
     *          "dataType"="string",
     *          "description"="4 digit of the year"
     *      },
     *      {
     *          "name"="cvv",
     *          "dataType"="expiry_month",
     *          "description"="2 digit of the month."
     *      },
     *  },
     * )
     */
    public function payAction(Request $request)
    {
        $id=$request->request->get('id');
        $cardHolder=$request->request->get('cardHolder');
        $cvv=$request->request->get('cvv');
        $expiry_year=$request->request->get('expiry_year');
        $expiry_month=$request->request->get('expiry_month');
        $cardNumber=$request->request->get('cardNumber');
        $cardNumber=preg_replace("/\D/", "", $cardNumber);//Delete everthing that is not a digit

        if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$cardHolder,1,20)){
            return $this->returnjson(false,'Propietario de la tarjeta no es valido.');
        }if (!$this->get('app.validate')->validateLuhnCardNumber($this->get('validator'),$cardNumber)){
            return $this->returnjson(false,'Numero de tarjeta no es valido.');
        }if (!$this->get('app.validate')->validateCVV($cardNumber,$cvv)){
            return $this->returnjson(false,'CVV no es valido.');
        }if (!$this->get('app.validate')->validateExpiryDate($expiry_month,$expiry_year)){
            return $this->returnjson(false,'Fecha expiracion erronea.');
        }
        $rent = $this->getDoctrine()->getRepository('AppBundle:Rent')->find($id);
        if(!$rent){
            return $this->returnjson(false,'La mensualidad con id '.$id.' no existe.');
        }else{
            $user=$this->get('security.token_storage')->getToken()->getUser();
            $agreement=$user->getCurrentAgreement();
            if($agreement){
                if($agreement->verifyAgreementSigned()){
                    try {
                        //TODO get college by the agreement
                        $rent->setStatusPaid(true);
                        $rent->setCardHolder($cardHolder);
                        $rent->setCardNumber($cardNumber);
                        $rent->setDatePaid(date_create('now'));
                        $college_data=$agreement->getRoom()->getCollege();
                        $file_name=$this->create_receipt($college_data,$user,$rent);
                        $rent->setFileReceipt($file_name);
                        $em = $this->getDoctrine()->getManager();
                        // tells Doctrine you want to (eventually) save the Product (no queries is done)
                        $em->persist($rent);

                        //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                        $em->flush();
                    } catch (\Exception $pdo_ex) {
                        return $this->returnjson(false,'SQL exception.'.$pdo_ex);
                    }
                    return $this->returnjson(true,'La mensualidad se ha pagado correctamente.');
                }else{
                    return $this->returnjson(false,'El estudiante '.$student->getUsername().' tiene un contrato pero sin firmar.');
                }
            }else{
                return $this->returnjson(false,'El estudiante '.$student->getUsername().' no tiene contrato con ninguna residencai.');
            }
        }
    }


    /**
     * @ApiDoc(
     *  description="Download receipt file. Can be called by user (Student/College).",
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

}
