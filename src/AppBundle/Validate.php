<?php
// src/AppBundle/Validate.php
namespace AppBundle;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\Length as LengthConstraint;
use Symfony\Component\Validator\Constraints\Url as UrlConstraint;
use Symfony\Component\Validator\Constraints\File as FileValidatorConstraint;
use Symfony\Component\Validator\Constraints\Luhn as LuhnValidatorConstraint;



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

    public function validateLuhnCardNumber($validator,$CardNumber)
    {
        $LuhnValidatorConstraint = new LuhnValidatorConstraint();
        $LuhnValidatorConstraint->message = 'CardNumber is not correct. Luhn.';
        $errors = $validator->validate(
            $CardNumber,
            $LuhnValidatorConstraint
        );
        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }


    public function validateCVV($cardNumber,$cvv){
        // Get the first number of the credit card so we know how many digits to look for
        $firstnumber = intval(substr($cardNumber,0, 1));
        if ($firstnumber == 3)
        {
            if (!ereg('([0-9]{4})', $cvv)) {
                return false;
            }
        }
        // else mastercard or visa
        elseif (!ereg('([0-9]{3})', $cvv))  {
            return false;
        }
        return true;
    }



    public function validateExpiryDate($expiry_month,$expiry_year)
    {
        $date=date_create('now');
        $expiry_year=intval($expiry_year);
        $expiry_month=intval($expiry_month);
        $month = date_format($date,"m");
        $year = date_format($date,"Y");
        if ($year> $expiry_year || ($year == $expiry_year && $month >= $expiry_month)){
            return false;
        }else{
            return true;
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
