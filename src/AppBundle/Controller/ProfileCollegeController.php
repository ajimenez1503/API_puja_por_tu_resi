<?php
// src/AppBundle/Controller/ProfileCollegeController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\User;
use AppBundle\Entity\College;
use Symfony\Component\Validator\Constraints\Length as LengthConstraint;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class ProfileCollegeController extends Controller
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
     *  description="Get data of the user (College ). Can be called by user (Collge).",
     * )
     */
    public function getAction()
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        return $this->returnjson(true,'Data user.',$user->getJSON());
    }


    /**
     * @ApiDoc(
     *  description="Get all the student of a college. Can be called by user (College ).",
     * )
     */
    public function getStudentsAction()
    {

        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRoles()[0]=="ROLE_COLLEGE"){
            $student=$user->getStudents();
            $output=array();
            for ($i = 0; $i < count($student); $i++) {
                array_unshift($output,$student[$i]->getJSON());
            }
            return $this->returnjson(true,'Lista de estudiantes',$output);
        }else{
            return $this->returnjson(false,'Esta function solo puede ser llamada por el rol RESIDENCIA');
        }
    }



        /**
         * @ApiDoc(
         *  description="This method update the password of a user (College). Can be called by user (College).",
         *  requirements={
         *      {
         *          "name"="old_password",
         *          "dataType"="String",
         *          "description"="Old password of the user"
         *      },
         *      {
         *          "name"="new_password",
         *          "dataType"="String",
         *          "description"="New Password of the user"
         *      },
         *  },
         * )
         */
        public function updatePasswordAction(Request $request)
        {
            $sizePassword=$this->container->getParameter('sizePassword');
            $old_password=$request->request->get('old_password');
            $new_password=$request->request->get('new_password');
            if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$old_password,$sizePassword,$sizePassword) or !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$new_password,$sizePassword,$sizePassword)){
                return $this->returnjson(false,'Contraseña invalidad. Longitud '.$sizePassword.' caracteres.');
            }
            if(!$this->get('app.validate')->validateOldPassword($this->get('validator'),$old_password)){
                return $this->returnjson(false,'La contraseña actual no es correcta.');
            }
            try {
                $user=$this->get('security.token_storage')->getToken()->getUser();
                $encoder = $this->container->get('security.password_encoder');
                $new_passwor_encoded = $encoder->encodePassword($user, $new_password);

                $user->setPassword($new_passwor_encoded);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);

                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
            return $this->returnjson(true,'La contraseña se ha cambiado correctamente.');
        }


        /**
         * @ApiDoc(
         *  description="This method update the email of a user (College). Can be called by user (College).",
         *  requirements={
         *      {
         *          "name"="email",
         *          "dataType"="String",
         *          "description"="New email of the user"
         *      },
         *  },
         * )
         */
        public function updateEmailAction(Request $request)
        {
            $email=$request->request->get('email');

            if (!$this->get('app.validate')->validateEmail($this->get('validator'),$email) ){
                return $this->returnjson(false,'El email no es valido.');
            }
            try {
                $user=$this->get('security.token_storage')->getToken()->getUser();
                $user->setEmail($email);
                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($user);

                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
            return $this->returnjson(true,'El email se ha cambiado correctamente.');
        }



        /**
         * @ApiDoc(
         *  description="This method update the address of a user (College). Can be called by user (College).",
         *  requirements={
         *      {
         *          "name"="address",
         *          "dataType"="String",
         *          "description"="Address of the College"
         *      },
         *      {
         *          "name"="lat",
         *          "dataType"="float",
         *          "description"="Latitude of the Address  of the College"
         *      },
         *      {
         *          "name"="lng",
         *          "dataType"="float",
         *          "description"="Longitude of the Address  of the College"
         *      },
         *  },
         * )
         */
        public function updateAddressAction(Request $request)
        {
            $address=$request->request->get('address');
            $lat=$request->request->get('lat');
            $lng=$request->request->get('lng');
            if (is_null($address) || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$address,1,200)){
                return $this->returnjson(false,' Direecion no es correcta  [1,200].');
            }
            if (is_null($lat)){
                return $this->returnjson(false,' Latitude no es correcta .');
            }
            if (is_null($lat)){
                return $this->returnjson(false,' Longitude no es correcta .');
            }
            try {
                $user=$this->get('security.token_storage')->getToken()->getUser();
                $user->setAddress($address);
                $user->setLatitude($lat);
                $user->setLongitude($lng);
                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($user);

                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
            return $this->returnjson(true,'La dirreccion se ha cambiado correctamente.');
        }


        /**
         * @ApiDoc(
         *  description="This method update the telephone of a user (College). Can be called by user (College).",
         *  requirements={
         *      {
         *          "name"="telephone",
         *          "dataType"="String",
         *          "description"="Telephone of the user"
         *      },
         *  },
         * )
         */
        public function updateTelephoneAction(Request $request)
        {
            $telephone=$request->request->get('telephone');
            if (is_null($telephone) || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$telephone,1,15)){
                return $this->returnjson(false,' Telefono no es correcto [1,15].');
            }
            try {
                $user=$this->get('security.token_storage')->getToken()->getUser();
                $user->setTelephone($telephone);
                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($user);

                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
            return $this->returnjson(true,'El telefono se ha cambiado correctamente.');
        }


        /**
         * @ApiDoc(
         *  description="This method update the URL of a user (College). Can be called by user (College).",
         *  requirements={
         *      {
         *          "name"="URL",
         *          "dataType"="String",
         *          "description"="URL of the user"
         *      },
         *  },
         * )
         */
        public function updateURLAction(Request $request)
        {
            $url=$request->request->get('URL');
            if (is_null($url) || !$this->get('app.validate')->validateURL($this->get('validator'),$url)){
                return $this->returnjson(false,' La url no es correcta.');
            }
            try {
                $user=$this->get('security.token_storage')->getToken()->getUser();
                $user->seturl($url);
                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($user);

                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
            return $this->returnjson(true,'La url se ha cambiado correctamente.');
        }






        /**
         * @ApiDoc(
         *  description="This method update the equipment of a user (College). Can be called by user (College).",
         *  requirements={
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
        public function updateEquipmentAction(Request $request)
        {
            $wifi =$request->request->get('wifi');
            $elevator =$request->request->get('elevator');
            $canteen=$request->request->get('canteen');
            $hours24=$request->request->get('hours24');
            $laundry =$request->request->get('laundry');
            $gym =$request->request->get('gym');
            $study_room =$request->request->get('study_room');
            $heating =$request->request->get('heating');

            if (is_null($wifi) || !$this->get('app.validate')->validateBool($wifi)){
                return $this->returnjson(false,'wifi debe ser true or false.');
            }
            if (is_null($canteen) || !$this->get('app.validate')->validateBool($canteen)){
                return $this->returnjson(false,'canteen debe ser true or false.');
            }
            if (is_null($elevator) || !$this->get('app.validate')->validateBool($elevator)){
                return $this->returnjson(false,'elevator debe ser true or false.');
            }
            if (is_null($hours24) || !$this->get('app.validate')->validateBool($hours24)){
                return $this->returnjson(false,'hours24 debe ser true or false.');
            }
            if (is_null($laundry) || !$this->get('app.validate')->validateBool($laundry)){
                return $this->returnjson(false,'laundry debe ser true or false.');
            }
            if (is_null($gym) || !$this->get('app.validate')->validateBool($gym)){
                return $this->returnjson(false,'gym debe ser true or false.');
            }
            if (is_null($study_room) || !$this->get('app.validate')->validateBool($study_room)){
                return $this->returnjson(false,'study_room debe ser true or false.');
            }
            if (is_null($heating) || !$this->get('app.validate')->validateBool($heating)){
                return $this->returnjson(false,'$heating debe ser true or false.');
            }
            try {
                $user=$this->get('security.token_storage')->getToken()->getUser();
                $user->setEquipment($wifi,$elevator,$canteen,$hours24,$laundry,$gym,$study_room,$heating);
                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($user);

                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
            return $this->returnjson(true,'El equipamiento se ha cambiado correctamente.');
        }



        /**
         * @ApiDoc(
         *  description="Get all the student of a college. For every student get: list_rents, room, agreement, student_data. Can be called by user (College ).",
         * )
         */
        public function getStudentsCompleteAction()
        {

            $user=$this->get('security.token_storage')->getToken()->getUser();
            if ($user->getRoles()[0]=="ROLE_COLLEGE"){
                $student=$user->getStudents();
                $output=array();
                for ($i = 0; $i < count($student); $i++) {
                    $rents_student=array();
                    $rents=$student[$i]->getRents()->getValues();
                    for ($j = 0; $j < count($rents); $j++) {
                        array_push($rents_student,$rents[$j]->getJSON());
                    }
                    array_push($output,
                        array(
                            "student"=>$student[$i]->getJSON(),
                            "room"=>$student[$i]-> getCurrentAgreement()->getRoom()->getJSON(),
                            "agreement"=>$student[$i]-> getCurrentAgreement()->getJSON(),
                            "rents"=>$rents_student,
                        )
                    );
                }
                return $this->returnjson(true,'Informacion completa de todos los estudiantes.',$output);
            }else{
                return $this->returnjson(false,'Esta function solo puede ser llamada por el rol RESIDENCIA');
            }
        }



        /**
         * @ApiDoc(
         *  description="Get student information get: list_rents, room, agreement, student_data. Can be called by user (College ).",
         *  requirements={
         *      {
         *          "name"="username_student",
         *          "dataType"="String",
         *          "description"="Username of the student  "
         *      },
         *  },
         * )
         */
        public function getStudentCompleteAction($username_student)
        {
            $user=$this->get('security.token_storage')->getToken()->getUser();
            if ($user->getRoles()[0]=="ROLE_COLLEGE"){//college
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
                            $rents_student=array();
                            $rents=$student->getRents()->getValues();
                            for ($j = 0; $j < count($rents); $j++) {
                                array_push($rents_student,$rents[$j]->getJSON());
                            }
                            $output=array(
                                "student"=>$student->getJSON(),
                                "room"=>$student-> getCurrentAgreement()->getRoom()->getJSON(),
                                "agreement"=>$student-> getCurrentAgreement()->getJSON(),
                                "rents"=>$rents_student,
                            );
                        }else{
                            return $this->returnjson(false,'El estudiante '.$student->getUsername().' tiene un contrato pero no con esta residencia '.$user->getUsername().'');
                        }
                    }else{
                        return $this->returnjson(false,'El estudiante '.$student->getUsername().' tiene un contrato pero sin firmar.');
                    }
                }else{
                    return $this->returnjson(false,'El estudiante '.$student->getUsername().' no tiene contrato con ninguna residencai.');
                }
                return $this->returnjson(true,'Informacion completa del estudiente.',$output);
            }else{
                return $this->returnjson(False,'ROLE unknow');
            }
        }

}
