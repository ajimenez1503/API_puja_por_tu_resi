<?php
// src/AppBundle/Controller/ResponsiblePersonController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\User;
use AppBundle\Entity\College;
use AppBundle\Entity\ResponsiblePerson;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class ResponsiblePersonController extends Controller
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
     *  description="This method create a ResponsiblePerson for a college. Can be called by user (College). ",
     *  requirements={
     *      {
     *          "name"="DNI",
     *          "dataType"="string",
     *          "description"="DNI of the responsible person."
     *      },
     *      {
     *          "name"="email",
     *          "dataType"="string",
     *          "description"="email of the responsible person."
     *      },
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "description"="name of the responsible person."
     *      },
     *      {
     *          "name"="job_position",
     *          "dataType"="string",
     *          "description"="job_position of the responsible person."
     *      },
     *  }
     * )
     */
    public function createAction(Request $request)
    {

        $DNI=$request->request->get('DNI');
        $email=$request->request->get('email');
        $name=$request->request->get('name');
        $job_position=$request->request->get('job_position');
        if ($DNI=="" || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$DNI,1,10)){
            return $this->returnjson(false,'DNI no es valido.');
        }if ($email=="" || !$this->get('app.validate')->validateEmail($this->get('validator'),$email)){
            return $this->returnjson(false,'email no es valido.');
        }if ($name=="" || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$name,1,30)){
            return $this->returnjson(false,'nombre no es valido.');
        }if ($job_position=="" || !$this->get('app.validate')->validateLenghtInput($this->get('validator'),$job_position,1,30)){
            return $this->returnjson(false,'puesto de trabajo no es valido.');
        }
        $user=$this->get('security.token_storage')->getToken()->getUser();
        try {
            $responsiblePerson = new ResponsiblePerson();
            $responsiblePerson->setDNI($DNI);
            $responsiblePerson->setEmail($email);
            $responsiblePerson->setName($name);
            $responsiblePerson->setJobPosition($job_position);
            $responsiblePerson->setCollege($user);

            $user->addResponsiblePerson($responsiblePerson);
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries is done)
            $em->persist($responsiblePerson);
            $em->persist($user);

            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.'.$pdo_ex);
        }
        return $this->returnjson(true,'El responsable se ha creado correctamente.');
    }

}
