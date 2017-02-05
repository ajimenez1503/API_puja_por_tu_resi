<?php
// src/AppBundle/Controller/LoggingController.php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\College;
use AppBundle\Entity\Student;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\Length as LengthConstraint;
use Symfony\Component\Validator\Constraints\Url as UrlConstraint;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SigninController extends Controller
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
    * Validate input of the register of the student
    *
    * @param string  $password
    * @param string $username
    * @param string  $email
    * @param string $companyName
    * @param string  $telephone
    * @param string $url
    *
    * @return {'success': bool, 'message': String}
    */
    public function validateRegiterCollege($password,$username,$email,$companyName,$address,$telephone,$url,$lat,$lng,$wifi,$elevator,$canteen,$hours24,$laundry,$gym,$study_room,$heating)
    {
        $message="Errors: ";
        $sizePassword=$this->container->getParameter('sizePassword');
        if (is_null($password) || !$this->get('app.validate')->validatePassword($this->get('validator'),$password,$sizePassword)){
                $message=$message.' Tamaño contraseña['.strval($sizePassword).','.strval($sizePassword).'].';
        }
        if (is_null($username) || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$username,9,9)){
                $message=$message.' CIF erroneo [9,9].';
        }
        if (is_null($email) || !$this->get('app.validate')->validateEmail($this->get('validator'),$email)){
            $message=$message.' Email no es correcto.';
        }
        if (is_null($companyName) || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$companyName,1)){
                $message=$message.' Nombre comapañia no es correcto [1,max].';
        }
        if (is_null($address) || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$address,1,30)){
                $message=$message.' Direecion no es correcta  [1,30].';
        }
        if (is_null($telephone) || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$telephone,1,15)){
                $message=$message.' Telefono no es correcto [1,15].';
        }
        if (is_null($url) || !$this->get('app.validate')->validateURL($this->get('validator'),$url)){
            $message=$message.' url no es correcta .';
        }
        if (is_null($lat)){
            $message=$message.' Latitude no es correcta .';
        }
        if (is_null($lat)){
            $message=$message.' Longitude no es correcta .';
        }
        if (is_null($wifi) || !$this->get('app.validate')->validateBool($wifi)){
            $message=$message.'wifi debe ser true or false.';
        }
        if (is_null($canteen) || !$this->get('app.validate')->validateBool($canteen)){
            $message=$message.'canteen debe ser true or false.';
        }
        if (is_null($elevator) || !$this->get('app.validate')->validateBool($elevator)){
            $message=$message.'elevator debe ser true or false.';
        }
        if (is_null($hours24) || !$this->get('app.validate')->validateBool($hours24)){
            $message=$message.'hours24 debe ser true or false.';
        }
        if (is_null($laundry) || !$this->get('app.validate')->validateBool($laundry)){
            $message=$message.'laundry debe ser true or false.';
        }
        if (is_null($gym) || !$this->get('app.validate')->validateBool($gym)){
            $message=$message.'gym debe ser true or false.';
        }
        if (is_null($study_room) || !$this->get('app.validate')->validateBool($study_room)){
            $message=$message.'study_room debe ser true or false.';
        }
        if (is_null($heating) || !$this->get('app.validate')->validateBool($heating)){
            $message=$message.'$heating debe ser true or false.';
        }
        if ($message=="Errors: "){
            return array(
                'success' => True,
                'message' => 'Validation correct');
        }else{
            return array(
                'success' => false,
                'message' => $message);
        }
    }

    /**
     * @ApiDoc(
     *  description="This method sign up a user (College) in the system. It is not necessary to be authenticate.",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="String",
     *          "description"="Username of the user (CIF)"
     *      },
     *      {
     *          "name"="password",
     *          "dataType"="String",
     *          "description"="Password of the user"
     *      },
     *      {
     *          "name"="email",
     *          "dataType"="String",
     *          "description"="Email of the user"
     *      },
     *      {
     *          "name"="companyName",
     *          "dataType"="String",
     *          "description"="Company name of the college."
     *      },
     *      {
     *          "name"="address",
     *          "dataType"="String",
     *          "description"="Address of the College"
     *      },
     *      {
     *          "name"="telephone",
     *          "dataType"="String",
     *          "description"="Telephone of the user"
     *      },
     *      {
     *          "name"="url",
     *          "dataType"="String",
     *          "description"="Url name of the college."
     *      },
     *      {
     *          "name"="wifi",
     *          "dataType"="boolean",
     *          "description"="True if the college has wifi."
     *      },
     *      {
     *          "name"="elevator",
     *          "dataType"="boolean",
     *          "description"="True if the college has elevator."
     *      },
     *      {
     *          "name"="canteen",
     *          "dataType"="boolean",
     *          "description"="True if the college has canteen."
     *      },
     *      {
     *          "name"="hours24",
     *          "dataType"="boolean",
     *          "description"="True if the college has hours24 receptions."
     *      },
     *      {
     *          "name"="laundry",
     *          "dataType"="boolean",
     *          "description"="True if the college has laundry."
     *      },
     *      {
     *          "name"="gym",
     *          "dataType"="boolean",
     *          "description"="True if the college has gym."
     *      },
     *      {
     *          "name"="study_room",
     *          "dataType"="boolean",
     *          "description"="True if the college has study_room."
     *      },
     *      {
     *          "name"="heating",
     *          "dataType"="boolean",
     *          "description"="True if the college has heating."
     *      },
     *  },
     * )
     */
    public function collegeAction(Request $request)
    {
        $password=$request->request->get('password');
        $username=$request->request->get('username');
        $email=$request->request->get('email');
        $companyName=$request->request->get('companyName');
        $address=$request->request->get('address');
        $lat=$request->request->get('lat');
        $lng=$request->request->get('lng');
        $telephone=$request->request->get('telephone');
        $url=$request->request->get('url');
        $wifi =$request->request->get('wifi');
        $elevator =$request->request->get('elevator');
        $canteen=$request->request->get('canteen');
        $hours24=$request->request->get('hours24');
        $laundry =$request->request->get('laundry');
        $gym =$request->request->get('gym');
        $study_room =$request->request->get('study_room');
        $heating =$request->request->get('heating');

        $validate=$this->validateRegiterCollege($password,$username,$email,$companyName,$address,$telephone,$url,$lat,$lng,$wifi,$elevator,$canteen,$hours24,$laundry,$gym,$study_room,$heating);
        if (!$validate['success']){
            //get message problem
            return $this->returnjson(false,$validate['message']);
        }

        //get password
        $college = new College();
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($college, $password);

        //check if the username exists
        $repository = $this->getDoctrine()->getRepository('AppBundle:College');
        $college_exist = $repository->findOneByUsername($username);
        if (!$college_exist){//if not exists, create it
            try {
                $college->set( $username,$encoded,$email,$companyName,$address,$telephone,$url);
                $college->setLatitude($lat);
                $college->setLongitude($lng);
                $college->setEquipment($wifi,$elevator,$canteen,$hours24,$laundry,$gym,$study_room,$heating);
                //This method is a shortcut to get the doctrine service
                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($college);

                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.'.$pdo_ex);
            }
            $data=array(
             'username' => $college->getUsername(),
            );
            return $this->returnjson(true,'Usuario registrado',$data);
        }else{
            return $this->returnjson(false,'Username ya existe.');
        }
    }

    /**
    * Validate input of the register of the student
    *
    * @param string  $password
    * @param string $username
    * @param string  $email
    * @param string $name
    *
    * @return {'success': bool, 'message': String}
    */
    public function validateRegiterStudent($password,$username,$email,$name)
    {
        $message="Errors: ";
        $sizePassword=$this->container->getParameter('sizePassword');
        if (is_null($password) || !$this->get('app.validate')->validatePassword($this->get('validator'),$password,$sizePassword)){
                $message=$message.' Tamaño contraseña ['.strval($sizePassword).','.strval($sizePassword).'].';
        }
        if (is_null($username) ||!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$username,1,10)){
                $message=$message.' DNI no es correcto  [1,10].';
        }
        if (is_null($name) ||!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$name,1,30)){
                $message=$message.' Nombre no es correcto [1,30].';
        }
        if (is_null($email) || !$this->get('app.validate')->validateEmail($this->get('validator'),$email)){
            $message=$message.' Email no es correcto .';
        }
        if ($message=="Errors: "){
            return array(
                'success' => True,
                'message' => 'Validation correct');
        }else{
            return array(
                'success' => false,
                'message' => $message);
        }

    }

    /**
     * @ApiDoc(
     *  description="This method sign up a user (Student) in the system. It is not necessary to be authenticate.",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="String",
     *          "description"="Username of the user (DNI)"
     *      },
     *      {
     *          "name"="password",
     *          "dataType"="String",
     *          "description"="Password of the user"
     *      },
     *      {
     *          "name"="email",
     *          "dataType"="String",
     *          "description"="Email of the user "
     *      },
     *      {
     *          "name"="name",
     *          "dataType"="String",
     *          "description"="Complete name of the user"
     *      },
     *  },
     * )
     */
    public function studentAction(Request $request)
    {

        $password=$request->request->get('password');
        $username=$request->request->get('username');
        $email=$request->request->get('email');
        $name=$request->request->get('name');

        $validate=$this->validateRegiterStudent($password,$username,$email,$name);
        if (!$validate['success']){
            //get message problem
            return $this->returnjson(false,$validate['message']);
        }

        //get password
        $Student = new Student();
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($Student, $password);

        //check if the username exists
        $repository = $this->getDoctrine()->getRepository('AppBundle:Student');
        $Student_exist = $repository->findOneByUsername($username);
        if (!$Student_exist){//if not exists, create it
            try {
                $Student->set( $username,$encoded,$email,$name);
                //This method is a shortcut to get the doctrine service
                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($Student);

                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
            $data=array(
             'username' => $Student->getUsername(),
            );
            return $this->returnjson(true,'Usuario registrado',$data);
        }else{
            return $this->returnjson(false,'Username ya existe.');
        }
    }

}
