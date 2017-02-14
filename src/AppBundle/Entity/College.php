<?php
// src/AppBundle/Entity/College.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;


class College implements AdvancedUserInterface, \Serializable
{
    private $username;
    private $password;
    private $email;
    private $isActive;
    private $companyName;
    private $address;
    private $latitude;
    private $longitude;
    private $telephone;
    private $url;
    private $messages;
    private $rooms;
    private $wifi;
    private $elevator;
    private $canteen;
    private $hours24;
    private $laundry;
    private $gym;
    private $study_room;
    private $heating;
    private $banks;
    private $responsiblePersons;


    public function __construct()
    {
        $this->isActive = true;
        $this->messages = new ArrayCollection();
        $this->banks = new ArrayCollection();
        $this->rooms = new ArrayCollection();
        $this->responsiblePersons = new ArrayCollection();
        $this->wifi = false;
        $this->elevator = false;
        $this->canteen = false;
        $this->hours24 = false;
        $this->laundry = false;
        $this->gym = false;
        $this->study_room = false;
        $this->heating = false;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

    /**
    * @return JSON format of the college
    */
    public function getJSON()
    {
        $output=array(
            'username'=>$this->getUsername(),
            'email' => $this->getEmail(),
            'companyName'=>$this->getCompanyName(),
            'address'=>$this->getAddress(),
            'latitude'=>$this->getLatitude(),
            'longitude'=>$this->getLongitude(),
            'telephone'=>$this->getTelephone(),
            'url'=>$this->geturl(),
            'equipment_college'=> array(
                'wifi' => $this->getWifi(),
                'elevator'=>$this->getElevator(),
                'canteen'=>$this->getCanteen(),
                'hours24'=>$this->getHours24(),
                'laundry'=>$this->getLaundry(),
                'gym'=>$this->getGym(),
                'study_room'=>$this->getStudyRoom(),
                'heating'=>$this->getHeating(),
            )
        );
        return $output;
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


    public function getUsername()
    {
        return $this->username;
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
        return array('ROLE_COLLEGE');
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
    public function setUsername($username)
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
   public function set( $username,$password,$email,$companyName,$address,$telephone,$url)
   {
       $this->username = $username;
       $this->password = $password;
       $this->email = $email;
       $this->isActive = true;
       $this->companyName=$companyName;
       $this->address=$address;
       $this->telephone=$telephone;
       $this->url=$url;
       return $this;
   }

   /**
   * @param string  $wifi,$elevator,$canteen,$hours24,$laundry,$gym,$study_room,$heating
   *
   * @return User
   */
  public function setEquipment( $wifi,$elevator,$canteen,$hours24,$laundry,$gym,$study_room,$heating)
  {
      $this->wifi = $wifi;
      $this->elevator = $elevator;
      $this->canteen = $canteen;
      $this->hours24 = $hours24;
      $this->gym=$gym;
      $this->study_room=$study_room;
      $this->heating=$heating;
  }




    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return College
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return College
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return College
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return College
     */
    public function seturl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function geturl()
    {
        return $this->url;
    }


    /**
     * Add message
     *
     * @param \AppBundle\Entity\Message $message
     *
     * @return College
     */
    public function addMessage(\AppBundle\Entity\Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \AppBundle\Entity\Message $message
     */
    public function removeMessage(\AppBundle\Entity\Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }


    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return College
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return College
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Add room
     *
     * @param \AppBundle\Entity\Room $room
     *
     * @return College
     */
    public function addRoom(\AppBundle\Entity\Room $room)
    {
        $this->rooms[] = $room;

        return $this;
    }

    /**
     * Remove room
     *
     * @param \AppBundle\Entity\Room $room
     */
    public function removeRoom(\AppBundle\Entity\Room $room)
    {
        $this->rooms->removeElement($room);
    }

    /**
     * Get rooms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRooms()
    {
        return $this->rooms;
    }



    /**
     * Get all the student who have a agreemtn with a college
     *
     * @return \Array student
     */
    public function getStudents()
    {
        $rooms=$this->getRooms()->getValues();
        $output=array();
        for ($i = 0; $i < count($rooms); $i++) {
            if($rooms[$i]->getCurrentAgreement()){
                if($rooms[$i]->getCurrentAgreement()->verifyAgreementSigned()){
                    array_unshift($output,$rooms[$i]->getCurrentAgreement()->getStudent());
                }
            }
        }
        return $output;
    }

    /**
     * Set wifi
     *
     * @param boolean $wifi
     *
     * @return College
     */
    public function setWifi($wifi)
    {
        $this->wifi = $wifi;

        return $this;
    }

    /**
     * Get wifi
     *
     * @return boolean
     */
    public function getWifi()
    {
        return $this->wifi;
    }

    /**
     * Set elevator
     *
     * @param boolean $elevator
     *
     * @return College
     */
    public function setElevator($elevator)
    {
        $this->elevator = $elevator;

        return $this;
    }

    /**
     * Get elevator
     *
     * @return boolean
     */
    public function getElevator()
    {
        return $this->elevator;
    }

    /**
     * Set canteen
     *
     * @param boolean $canteen
     *
     * @return College
     */
    public function setCanteen($canteen)
    {
        $this->canteen = $canteen;

        return $this;
    }

    /**
     * Get canteen
     *
     * @return boolean
     */
    public function getCanteen()
    {
        return $this->canteen;
    }

    /**
     * Set hours24
     *
     * @param boolean $hours24
     *
     * @return College
     */
    public function setHours24($hours24)
    {
        $this->hours24 = $hours24;

        return $this;
    }

    /**
     * Get hours24
     *
     * @return boolean
     */
    public function getHours24()
    {
        return $this->hours24;
    }

    /**
     * Set laundry
     *
     * @param boolean $laundry
     *
     * @return College
     */
    public function setLaundry($laundry)
    {
        $this->laundry = $laundry;

        return $this;
    }

    /**
     * Get laundry
     *
     * @return boolean
     */
    public function getLaundry()
    {
        return $this->laundry;
    }

    /**
     * Set gym
     *
     * @param boolean $gym
     *
     * @return College
     */
    public function setGym($gym)
    {
        $this->gym = $gym;

        return $this;
    }

    /**
     * Get gym
     *
     * @return boolean
     */
    public function getGym()
    {
        return $this->gym;
    }

    /**
     * Set studyRoom
     *
     * @param boolean $studyRoom
     *
     * @return College
     */
    public function setStudyRoom($studyRoom)
    {
        $this->study_room = $studyRoom;

        return $this;
    }

    /**
     * Get studyRoom
     *
     * @return boolean
     */
    public function getStudyRoom()
    {
        return $this->study_room;
    }

    /**
     * Set heating
     *
     * @param boolean $heating
     *
     * @return College
     */
    public function setHeating($heating)
    {
        $this->heating = $heating;

        return $this;
    }

    /**
     * Get heating
     *
     * @return boolean
     */
    public function getHeating()
    {
        return $this->heating;
    }

    /**
     * Get OFFERED rooms
     * OFFERED room according to the dates of bid.
     * @return list of room (JSON format)
     */
    public function getOFFEREDroom()
    {
        $rooms=$this->getRooms()->getValues();
        $today=date_create('now')->format('Y-m-d');//year month and day (not hour and minute)
        $output=array();
        for ($i = 0; $i < count($rooms); $i++) {
            if($rooms[$i]->getDateStartBid()->format('Y-m-d')<=$today && $rooms[$i]->getDateEndBid()->format('Y-m-d')>=$today){//TODO use the format year-month-day to compare
                array_unshift($output,$rooms[$i]->getJSON());
            }
        }
        return $output;
    }


    /**
     * Add bank
     *
     * @param \AppBundle\Entity\Bank $bank
     *
     * @return College
     */
    public function addBank(\AppBundle\Entity\Bank $bank)
    {
        $this->banks[] = $bank;

        return $this;
    }

    /**
     * Remove bank
     *
     * @param \AppBundle\Entity\Bank $bank
     */
    public function removeBank(\AppBundle\Entity\Bank $bank)
    {
        $this->banks->removeElement($bank);
    }

    /**
     * Get banks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBanks()
    {
        return $this->banks;
    }

    /**
     * Add responsiblePerson
     *
     * @param \AppBundle\Entity\Responsible_person $responsiblePerson
     *
     * @return College
     */
    public function addResponsiblePerson(\AppBundle\Entity\Responsible_person $responsiblePerson)
    {
        $this->responsible_persons[] = $responsiblePerson;

        return $this;
    }

    /**
     * Remove responsiblePerson
     *
     * @param \AppBundle\Entity\Responsible_person $responsiblePerson
     */
    public function removeResponsiblePerson(\AppBundle\Entity\Responsible_person $responsiblePerson)
    {
        $this->responsible_persons->removeElement($responsiblePerson);
    }

    /**
     * Get responsiblePersons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResponsiblePersons()
    {
        return $this->responsible_persons;
    }
}
