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
