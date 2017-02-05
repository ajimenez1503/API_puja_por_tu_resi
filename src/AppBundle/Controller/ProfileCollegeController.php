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



}
