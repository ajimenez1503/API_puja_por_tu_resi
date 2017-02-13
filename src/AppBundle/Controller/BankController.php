<?php
// src/AppBundle/Controller/BankController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\User;
use AppBundle\Entity\College;
use AppBundle\Entity\Bank;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class BankController extends Controller
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
     *  description="This method create a bank account for a college. Can be called by user (College). ",
     *  requirements={
     *      {
     *          "name"="IBAN",
     *          "dataType"="string",
     *          "description"="IBAN of the bank account."
     *      },
     *      {
     *          "name"="SWIFT",
     *          "dataType"="string",
     *          "description"="SWIFT of the bank account."
     *      },
     *      {
     *          "name"="account_holder",
     *          "dataType"="string",
     *          "description"="account_holder of the bank account."
     *      },
     *  }
     * )
     */
    public function createAction(Request $request)
    {
        $IBAN=$request->request->get('IBAN');
        $SWIFT=$request->request->get('SWIFT');
        $account_holder=$request->request->get('account_holder');

        $user=$this->get('security.token_storage')->getToken()->getUser();
        try {
            $bank = new Bank();
            $bank->setIBAN($IBAN);
            $bank->setSWIFT($SWIFT);
            $bank->setAccountHolder($account_holder);
            $bank->setCollege($user);
            $user->addBank($bank);
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries is done)
            $em->persist($bank);
            $em->persist($user);

            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.'.$pdo_ex);
        }
        return $this->returnjson(true,'La cuenta bancaria se ha creado correctamente.');
    }

}
