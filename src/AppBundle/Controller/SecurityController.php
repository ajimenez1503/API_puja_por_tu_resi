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
     *  description="This method allow to a user to sign in the systme.",
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
             $data=json_encode(array(
                 'username' => $user->getUsername(),
                 'password'=>$user->getPassword(),
                 'isvalid'=>$user->getIsActive(),
                 'ROLE'=>$user->getRoles(),
             ));
             $response->setData(array(
                'success' => true,
                'message' => 'USER logged.',
                'data'=> $data,
             ));
        }
        else{
            $authenticationUtils = $this->get('security.authentication_utils');
            // get the login error if there is one
            $message = 'User is not authenticate';
            $error = $authenticationUtils->getLastAuthenticationError();
            if (!is_null($error)){
                $message=$error->getMessageKey();
            }
            $data=json_encode(array());
            $response->setData(array(
                'success' => false,
                'message' => $message,
                'data'=>$data,
            ));
        }
        return $response;
    }
}
