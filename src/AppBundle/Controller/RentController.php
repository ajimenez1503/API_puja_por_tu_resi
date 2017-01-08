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
     *  description="This method create a rent of a moth without pay, yet ",
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
        $username_student=$request->request->get('student');
        $username_college=$request->request->get('college');
        if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$username_student,1,10)){
            return $this->returnjson(false,'Username student no es valido.');
        }
        if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$username_college,1,10)){
            return $this->returnjson(false,'Username student  no es valido.');
        }
        try {
            $user_student = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username_student);
            $user_college = $this->getDoctrine()->getRepository('AppBundle:College')->find($username_college);
            //TODO get price of agrement between college and user
            $rent = new Rent();
            $rent->setStudent($user_student);
            $rent->setCollege($user_college);
            $rent->setPrice(100);//TODO $agreement->getPrice();

            $user_college->addRent($rent);
            $user_student->addRent($rent);

            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries is done)
            $em->persist($rent);
            $em->persist($user_student);
            $em->persist($user_college);
            // actually executes the queries (i.e. the INSERT query)
            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception. '.$pdo_ex);
        }
        return $this->returnjson(true,'La mensualidad se ha creado correctamente.');
    }


    /**
     * @ApiDoc(
     *  description="get list of rents of a user(Student || College)",
     * )
     */
    public function getAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $messages=$user->getRents()->getValues();

        $output=array();
        for ($i = 0; $i < count($messages); $i++) {
            array_unshift($output,$messages[$i]->getJSON()
            );
        }
        return $this->returnjson(true,'List of rents',$output);
    }

    /**
     * @ApiDoc(
     *  description="get list of rents without pay of a user(Student )",
     * )
     */
    public function getUnpaidAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $messages=$user->getRents()->getValues();

        $output=array();
        for ($i = 0; $i < count($messages); $i++) {
            if (!$messages[$i]->getStatusPaid()){
                array_unshift($output,$messages[$i]->getJSON());
            }
        }
        return $this->returnjson(true,'List of rents unpaid',$output);
    }

    public function crete_receipt(/*$college_data,*/$student_data,$rent_data)
    {
        $filename=md5(uniqid()).'.pdf';
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'payment_receipt.html.twig',
                array(
                    'rent'  => $rent_data->getJSON(),
                    'student' =>$student_data->getJSON(),
                    //TODO add college information
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
     *  update the date_paid and set the cardNumber and cardHolder",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "description"="ID of the message"
     *      },
     *      {
     *          "name"="cardNumber",
     *          "dataType"="string",
     *          "description"="number of the card of the paid"
     *      },
     *      {
     *          "name"="cardHolder",
     *          "dataType"="string",
     *          "description"="name of the holder of the card of the paid"
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
            return $this->returnjson(false,'cardHolder no es valido.');
        }
        if (!$this->get('app.validate')->validateLuhnCardNumber($this->get('validator'),$cardNumber)){
            return $this->returnjson(false,'cardNumber no es valido. Luhn.');
        }if (!$this->get('app.validate')->validateCVV($cardNumber,$cvv)){
            return $this->returnjson(false,'CVV no es valido.');
        }if (!$this->get('app.validate')->validateExpiryDate($expiry_month,$expiry_year)){
            return $this->returnjson(false,'Fecha expiracion erronea.');
        }

        try {
            $rent = $this->getDoctrine()->getRepository('AppBundle:Rent')->find($id);
            $user=$this->get('security.token_storage')->getToken()->getUser();
            //TODO get college by the agreement
            $rent->setStatusPaid(true);
            $rent->setCardHolder($cardHolder);
            $rent->setCardNumber($cardNumber);
            $rent->setDatePaid(date_create('now'));
            $file_name=$this->crete_receipt(/*$college_data*/$user,$rent);//TODO add college information
            $rent->setFileReceipt($file_name);
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries is done)
            $em->persist($rent);
            // actually executes the queries (i.e. the INSERT query)
            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.'.$pdo_ex);
        }
        return $this->returnjson(true,'La mensualidad se ha pagado correctamente.');
    }

    /**
     * @ApiDoc(
     *  description="download file",
     *  requirements={
     *      {
     *          "name"="filename",
     *          "dataType"="String",
     *          "description"="filename receipt"
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
