<?php
// src/AppBundle/Validate.php
namespace AppBundle;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\Length as LengthConstraint;
use Symfony\Component\Validator\Constraints\Url as UrlConstraint;
use Symfony\Component\Validator\Constraints\File as FileValidatorConstraint;


class Validate
{

    public function validateURL($validator,$url)
    {
        $UrlConstraint = new UrlConstraint();
        $UrlConstraint->message = 'URL is not correct.';
        $errors = $validator->validate(
            $url,
            $UrlConstraint
        );
        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }

    public function validateEmail($validator,$email)
    {

        $emailConstraint = new EmailConstraint();
        $emailConstraint->message = 'Email is not correct.';
        $errors = $validator->validate(
            $email,
            $emailConstraint
        );

        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }



    public function validateLenghtInput($validator,$input,$min=1,$max=100)
    {

        $lengthConstraint = new LengthConstraint(array(
        'min'        => $min,
        'max'        => $max,
        'minMessage' => 'Lengh should be >'.$min.'.',
        'maxMessage' => 'Lengh should be <'.$max.'.'));
        $errors = $validator->validate(
            $input,
            $lengthConstraint
        );
        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }


    public function validateOldPassword($validator,$old_password)
    {
        $UserPasswordConstraint = new UserPasswordConstraint();
        $UserPasswordConstraint->message="Password is not valid.";
        $errors = $validator->validate(
            $old_password,
            $UserPasswordConstraint
        );
        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }



    public function validateImageFile($validator,$file)
    {
        $FileValidatorConstraint = new FileValidatorConstraint();
        $FileValidatorConstraint->maxSize='100M';
        $FileValidatorConstraint->maxSizeMessage='size no allow.';
        $FileValidatorConstraint->mimeTypes=array("image/jpeg","image/png");

        $errors = $validator->validate(
            $file,
            $FileValidatorConstraint
        );
        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }




    public function validatePDFFile($validator,$file)
    {
        $FileValidatorConstraint = new FileValidatorConstraint();
        $FileValidatorConstraint->maxSize='100M';
        $FileValidatorConstraint->maxSizeMessage='size no allow.';
        $FileValidatorConstraint->mimeTypes=array("application/pdf");

        $errors = $validator->validate(
            $file,
            $FileValidatorConstraint
        );
        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }
}
