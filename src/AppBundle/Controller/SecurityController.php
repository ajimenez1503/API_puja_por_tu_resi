<?php
// src/AppBundle/Controller/SecurityController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SecurityController extends Controller
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
     *  description="This method allow to a user to login the systme. Can be called by user (College/Student).",
     *  requirements={
     *      {
     *          "name"="_username",
     *          "dataType"="String",
     *          "description"="Username of the user (DNI/CIF)"
     *      },
     *      {
     *          "name"="_password",
     *          "dataType"="String",
     *          "description"="Password of the user"
     *      },
     *  },
     * )
     */
    public function loginAction(Request $request)
    {
        $response = new JsonResponse();
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
             $user=$this->get('security.token_storage')->getToken()->getUser();
             $data=array(
                 'username' => $user->getUsername(),
                 'ROLE'=>$user->getRoles(),
             );
             $response->setData(array(
                'success' => true,
                'message' => 'USER logged.',
                'data'=> $data,
             ));
        }
        else{
            $authenticationUtils = $this->get('security.authentication_utils');
            // get the login error if there is one
            $message = 'User is not registered.';
            $error = $authenticationUtils->getLastAuthenticationError();
            if (!is_null($error)){
                $message=$error->getMessageKey();
            }
            $data=array();
            $response->setData(array(
                'success' => false,
                'message' => $message,
                'data'=>$data,
            ));
        }
        return $response;
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @ApiDoc(
     *  description="This method allow to a user to remmeber the password the systme. Can be called by user (College/Student).",
     *  requirements={
     *      {
     *          "name"="_username",
     *          "dataType"="String",
     *          "description"="Username of the user (DNI/CIF)"
     *      },
     *  },
     * )
     */
    public function remmemberPasswordAction(Request $request)
    {
        $username=$request->request->get('_username');
        $sizePassword=$this->container->getParameter('sizePassword');
        $new_password = $this->generateRandomString($sizePassword);//generate a random password of sizePassword

        //check username
        $user = $this->getDoctrine()->getRepository('AppBundle:Student')->find($username);
        if (!$user) {
            $user = $this->getDoctrine()->getRepository('AppBundle:College')->find($username);
            if (!$user) {
                return $this->returnjson(False,'Usuario no existe.');
            }
        }
        // send new password to the email
        $text= "Tu nueva contraseña de acceso es ".$new_password."\n Puedes acceder al sistema con tu nueva contraseña.\n Te recomendamos que, una vez autenticado, cambies tu contraseña en el apartado 'Perfil'. \n Si sigues sin poder acceder a tu área personal, no dudes en contactar con nosotros escribiendo un email a: pujaporturesi@gmail.com";
        $this->get('app.Email')->send($this->get('mailer'),$this->container->getParameter('mailer_user'),$user->getEmail(),"update password",$text);
        try {
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
        return $this->returnjson(true,'La contraseña se ha enviado a tu correo correctamente.');
    }


    /**
     * @ApiDoc(
     *  description="This method verify if a user (which role) is connected in the system. Can be called by user (College/Student). ",
     * )
     */
    public function checkSesionAction(Request $request)
    {
        $response = new JsonResponse();
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
             $user=$this->get('security.token_storage')->getToken()->getUser();
             $data=array(
                 'username' => $user->getUsername(),
                 'isvalid'=>$user->getIsActive(),
                 'ROLE'=>$user->getRoles(),
             );
             $response->setData(array(
                'success' => true,
                'message' => 'USER  is logged.',
                'data'=> $data,
             ));
        }
        else{
            $message = 'User is not registered.';
            $data=array();
            $data=array();
            $response->setData(array(
                'success' => false,
                'message' => $message,
                'data'=>$data,
            ));
        }
        return $response;
    }
}
