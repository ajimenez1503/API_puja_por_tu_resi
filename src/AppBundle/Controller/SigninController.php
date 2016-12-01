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

    public function validateEmail($email)
    {
        $emailConstraint = new EmailConstraint();
        $emailConstraint->message = 'Email is not correct.';
        $errors = $this->get('validator')->validate(
            $email,
            $emailConstraint
        );
        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }


    public function validateURL($url)
    {
        $UrlConstraint = new UrlConstraint();
        $UrlConstraint->message = 'URL is not correct.';
        $errors = $this->get('validator')->validate(
            $url,
            $UrlConstraint
        );
        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }

    public function validateLenghtInput($input,$min=1,$max=100)
    {

        $lengthConstraint = new LengthConstraint(array(
        'min'        => $min,
        'max'        => $max,
        'minMessage' => 'Lengh should be >'.$min.'.',
        'maxMessage' => 'Lengh should be <'.$max.'.'));
        $errors = $this->get('validator')->validate(
            $input,
            $lengthConstraint
        );
        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }

    public function validateRegiterCollege($password,$username,$email,$companyName,$address,$telephone,$url)
    {
        $message="Errors: ";

        if (is_null($password) || !$this->validateLenghtInput($password,1,8)){
                $message=$message.' Password lenght [1,8].';
        }
        if (is_null($username) || !$this->validateLenghtInput($username,1,10)){
                $message=$message.' Username lenght [1,10].';
        }
        if (is_null($email) || !$this->validateEmail($email)){
            $message=$message.' Email is not correct.';
        }
        if (is_null($companyName) || !$this->validateLenghtInput($companyName,1)){
                $message=$message.' company_name lenght [1,max].';
        }
        if (is_null($address) || !$this->validateLenghtInput($address,1)){
                $message=$message.' address lenght [1,8].';
        }
        if (is_null($telephone) || !$this->validateLenghtInput($telephone,1)){
                $message=$message.' telephone lenght [1,8].';
        }
        if (is_null($url) || !$this->validateURL($url)){
            $message=$message.' url is not correct.';
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
     *  description="This method sign up a user (College) in the system. ",
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
     *          "description"="Email of the user (DNI)"
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
     *          "description"="Telephone of the user (DNI)"
     *      },
     *      {
     *          "name"="url",
     *          "dataType"="String",
     *          "description"="Url name of the college."
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
        $telephone=$request->request->get('telephone');
        $url=$request->request->get('url');

        $validate=$this->validateRegiterCollege($password,$username,$email,$companyName,$address,$telephone,$url);
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
                //This method is a shortcut to get the doctrine service
                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($college);
                // actually executes the queries (i.e. the INSERT query)
                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
            $data=array(
             'username' => $college->getUsername(),
            );
            return $this->returnjson(true,'USER register',$data);
        }else{
            return $this->returnjson(false,'Username is already used');
        }
    }


    public function validateRegiterStudent($password,$username,$email,$name)
    {
        $message="Errors: ";

        if (is_null($password) ||!$this->validateLenghtInput($password,1,8)){
                $message=$message.' Password lenght [1,8].';
        }
        if (is_null($username) ||!$this->validateLenghtInput($username,1,10)){
                $message=$message.' Username lenght [1,10].';
        }
        if (is_null($name) ||!$this->validateLenghtInput($name,1,100)){
                $message=$message.' Name lenght [1,8].';
        }
        if (is_null($email) ||!$this->validateEmail($email)){
            $message=$message.' Email is not correct.';
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
     *  description="This method sign up a user (Student) in the system. ",
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
     *          "description"="Email of the user (DNI)"
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
                // actually executes the queries (i.e. the INSERT query)
                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
            $data=array(
             'username' => $Student->getUsername(),
            );
            return $this->returnjson(true,'Student register',$data);
        }else{
            return $this->returnjson(false,'Username is already used');
        }
    }

}
