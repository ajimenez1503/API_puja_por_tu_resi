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



}
