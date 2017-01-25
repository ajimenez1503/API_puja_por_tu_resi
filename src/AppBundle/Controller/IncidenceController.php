<?php
// src/AppBundle/Controller/IncidenceController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\User;
use AppBundle\Entity\Student;
use AppBundle\Entity\Incidence;
use Symfony\Component\Validator\Constraints\Length as LengthConstraint;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\File as FileValidatorConstraint;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class IncidenceController extends Controller
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
     *  description="This method create a incidence. Can be called by user (Student).",
     *  requirements={
     *      {
     *          "name"="description",
     *          "dataType"="String",
     *          "description"="Description of the inicidence"
     *      },
     *      {
     *          "name"="file_name",
     *          "dataType"="File",
     *          "description"="File with the picture. Image format."
     *      },
     *  },
     * )
     */
    public function createAction(Request $request)
    {
        $description=$request->request->get('description');
        $file=$request->files->get('file_name');

        if (!$this->get('app.validate')->validateLenghtInput($this->get('validator'),$description)){
            return $this->returnjson(false,'Descripcion no es valida.');
        }
        if (!$this->get('app.validate')->validateImageFile($this->get('validator'),$file)){
            return $this->returnjson(false,'Imagen no es valida.');
        }
        $filename=md5(uniqid()).'.'.$file->getClientOriginalExtension();
        $file->move($this->container->getParameter('storageFiles'),$filename);
        try {
            $user=$this->get('security.token_storage')->getToken()->getUser();
            $incidence = new Incidence();
            $incidence->setStudent($user);
            $incidence->setDescription($description);
            $incidence->setFileName($filename);

            $user->addIncidence($incidence);
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries is done)
            $em->persist($incidence);
            $em->persist($user);
            // actually executes the queries (i.e. the INSERT query)
            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.');
        }
        return $this->returnjson(true,'La incidencia se ha creado correctamente.');
    }


    /**
     * @ApiDoc(
     *  description="This method update the state of a incidence. Can be called by user (College/Student).",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="Integer",
     *          "description"="ID of the inicidence"
     *      },
     *      {
     *          "name"="state",
     *          "dataType"="String",
     *          "description"="New state of the incidence. state=['OPEN' or 'IN PROGRESS' or 'DONE']"
     *      },
     *  },
     * )
     */
    public function updateStateAction(Request $request)
    {
        $id=$request->request->get('id');
        $state=$request->request->get('state');
        //validate state.
        if (!$this->validateState($state)){
            return $this->returnjson(false,'El estado '.$state.' no es valido.');
        }
        $incidence = $this->getDoctrine()->getRepository('AppBundle:Incidence')->find($id);
        if (!$incidence){
            return $this->returnjson(False,'La inicidencia con id '.$id.' no existe.');
        }else{
            try {

                $incidence->setStatus($state);
                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($incidence);
                // actually executes the queries (i.e. the INSERT query)
                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            } catch (\Exception $pdo_ex) {
                return $this->returnjson(false,'SQL exception.');
            }
            return $this->returnjson(true,'La incidencia se ha actualizado correctamente con el nuevo estado '.$state.'.');
        }

    }



    public function validateState($state)
    {
        if($state=="OPEN" or $state=="IN PROGRESS" or $state=="DONE"){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @ApiDoc(
     *  description="Get list of incident of a user in a JSON format. Can be called by user (Student).",
     * )
     */
    public function getAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRoles()[0]=="ROLE_STUDENT"){
            $incidences=$user->getIncidences()->getValues();

            $output=array();
            for ($i = 0; $i < count($incidences); $i++) {
                array_push($output,$incidences[$i]->getJSON()
                );
            }
            return $this->returnjson(true,'Lista de inicidencias.',$output);
        }else{//TODO get all the user by all the agreement in date
            //return all the incidences of all the user
            return $this->returnjson(False,'College_incidences is not done yet.',$output);

        }
    }



    /**
     * @ApiDoc(
     *  description="Download file of the incidence. Can be called by user (College/Student).",
     *  requirements={
     *      {
     *          "name"="filename",
     *          "dataType"="String",
     *          "description"="filename of the file to download"
     *      },
     *  },
     * )
     */
    public function downloadAction($filename)
    {
        $path = $this->container->getParameter('storageFiles');
        $content = file_get_contents($path.'/'.$filename);
        $response = new Response();
        //set headers
        //$response->headers->set("Access-Control-Expose-Headers", "Content-Disposition");
        $response->headers->add(array('Access-Control-Expose-Headers' =>  'Content-Disposition'));
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);

        $response->setContent($content);
        return $response;
    }


}
