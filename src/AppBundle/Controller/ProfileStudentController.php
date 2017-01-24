<?php
// src/AppBundle/Controller/ProfileStudentController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\User;
use AppBundle\Entity\Student;
use Symfony\Component\Validator\Constraints\Length as LengthConstraint;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class ProfileStudentController extends Controller
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
     *  description="Get data of the user (student) : name, username, email, ROLE, date_creation, point.",
     * )
     */
    public function getAction()
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $data=array(
            'name'=>$user->getName(),
            'username' => $user->getUsername(),
            'email'=>$user->getEmail(),
            'isvalid'=>$user->getIsActive(),
            'ROLE'=>$user->getRoles(),
            'date_creation'=>$user->getCreationDate(),
            'point'=>$user->get_point(),
        );
        return $this->returnjson(true,'Data user.',$data);
    }


    /**
     * @ApiDoc(
     *  description="This method update the password of a user (student).",
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
        $old_password=$request->request->get('old_password');
        $new_password=$request->request->get('new_password');
        if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$old_password,8,8) or !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$new_password,8,8)){
            return $this->returnjson(false,'Contraseña invalidad. Longitud 8 caracteres.');
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
            // tells Doctrine you want to (eventually) save the Product (no queries is done)
            $em->persist($user);
            // actually executes the queries (i.e. the INSERT query)
            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.');
        }
        return $this->returnjson(true,'La contraseña se ha cambiado correctamente.');
    }


    /**
     * @ApiDoc(
     *  description="This method update the email of a user (student). ",
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
            // actually executes the queries (i.e. the INSERT query)
            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.');
        }
        return $this->returnjson(true,'El email se ha cambiado correctamente.');
    }

}
