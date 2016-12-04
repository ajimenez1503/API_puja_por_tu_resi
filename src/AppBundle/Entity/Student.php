<?php
// src/AppBundle/Entity/Student.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Student implements AdvancedUserInterface, \Serializable
{
    private $username;
    private $password;
    private $email;
    private $isActive;
    private $name;
    private $creationDate;
    private $incidences;

    public function getUsername()
    {
        return $this->username;
    }

    public function __construct()
    {
        $this->isActive = true;
        $this->creationDate=date_create('now');
        $this->$incidences = new ArrayCollection();
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

    public function isAccountNonExpired()
   {
       return true;
   }

   public function isAccountNonLocked()
   {
       return true;
   }

   public function isCredentialsNonExpired()
   {
       return true;
   }

   public function isEnabled()
   {
       return $this->isActive;
   }


    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array('ROLE_STUDENT');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->username,
            $this->password,
            $this->isActive,
            $this->name,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->username,
            $this->password,
            $this->isActive,
            $this->name,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setusername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }



    /**
    * @param string  $username,$password,$email
    *
    * @return User
    */
   public function set( $username,$password,$email,$name)
   {
       $this->username = $username;
       $this->password = $password;
       $this->email = $email;
       $this->isActive = true;
       $this->name=$name;
       return $this;
   }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Student
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Student
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Add incidence
     *
     * @param \AppBundle\Entity\Incidence $incidence
     *
     * @return Student
     */
    public function addIncidence(\AppBundle\Entity\Incidence $incidence)
    {
        $this->incidences[] = $incidence;

        return $this;
    }

    /**
     * Remove incidence
     *
     * @param \AppBundle\Entity\Incidence $incidence
     */
    public function removeIncidence(\AppBundle\Entity\Incidence $incidence)
    {
        $this->incidences->removeElement($incidence);
    }

    /**
     * Get incidences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncidences()
    {
        return $this->incidences;
    }
}
