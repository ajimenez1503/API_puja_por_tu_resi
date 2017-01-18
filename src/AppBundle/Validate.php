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

    /**
    * Validate URL.
    *
    * @param validator_module  $validator
    * @param string $url    url
    *
    * @return bool
    */
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

    /**
    * Validate cardNumber by the LUHN algorithm
    *
    * @param validator_module  $validator
    * @param string $CardNumber    CardNumber
    *
    * @return bool
    */
    public function validateLuhnCardNumber($validator,$CardNumber)
    {
        $LuhnValidatorConstraint = new LuhnValidatorConstraint();
        $LuhnValidatorConstraint->message = 'CardNumber is not correct.';
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

    /**
    * Validate security code
    *
    * @param string $CardNumber    CardNumber
    * @param string $cvv    cvv
    *
    * @return bool
    */
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


    /**
    * Validate if the expiry date is older or equal than now.
    *
    * @param string  $expiry_month
    * @param string $expiry_year
    *
    * @return bool
    */
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


    /**
    * Validate email
    *
    * @param validator_module  $validator
    * @param string $email    email
    *
    * @return bool
    */
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


    /**
    * Validate length of the input.
    *
    * @param validator_module  $validator
    * @param string $input    input
    * @param int $min    min length
    * @param int $max    max length
    *
    * @return bool
    */
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

    /**
    * Validate int in a range
    *
    * @param int $input   input
    * @param int $min    min price
    * @param int $max    max price
    *
    * @return bool
    */
    public function validateInt($input,$min=1,$max=2000)
    {
        $price=intval($input);
        if ($input>=$min && $input<=$max){
            return true;
        }else{
            return false;
        }
    }


    /**
    * Validate boolean, it should be 0 or 1
    *
    * @param int $input   input
    *
    * @return bool
    */
    public function validateBool($input)
    {
        if ($input=="0" || $input=="1" ){
            return true;
        }else{
            return false;
        }
    }


    /**
    * Validate Date,
    * The both dates must be old than now.
    * The second date must be old than the first
    *
    * @param int $date1   input
    * @param int $date2   input
    *
    * @return bool
    */
    public function validateDate($date1,$date2)
    {
        $yesterday=date_create('yesterday');
        if ($date1>=$yesterday and $date2>=$date1){
            return true;
        }else{
            return false;
        }
    }

    /**
    * Validate length of the input.
    *
    * @param validator_module  $validator
    * @param string $password    password
    * @param string $size       size
    *
    * @return bool
    */
    public function validatePassword($validator,$password,$size)
    {
        $lengthConstraint = new LengthConstraint(array(
        'min'        => $size,
        'max'        => $size,
        'minMessage' => 'Lengh should be >'.$size.'.',
        'maxMessage' => 'Lengh should be <'.$size.'.'));
        $errors = $validator->validate(
            $password,
            $lengthConstraint
        );
        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }

    /**
    * Validate that the old password belong to the user.
    *
    * @param validator_module  $validator
    * @param string $old_password    input old_password
    *
    * @return bool
    */
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


    /**
    * Validate format image file
    *
    * @param validator_module  $validator
    * @param string $file    file structure
    *
    * @return bool
    */
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


    /**
    * Validate format pdf file
    *
    * @param validator_module  $validator
    * @param string $file    file structure
    *
    * @return bool
    */
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
