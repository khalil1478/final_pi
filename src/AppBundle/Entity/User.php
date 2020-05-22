<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;






    public function __construct()
    {
        parent::__construct();
        // your own logic
    }




    /**
     * @ORM\Column(type="integer", length=8)
     * @Assert\NotBlank

     */
    private $telephone;



   /**
     * @ORM\Column(type="integer", length=255)
     * @Assert\NotBlank
         * @Assert\GreaterThan( value = 15)
         * @Assert\LessThan( value = 100)
   
     */
    private $age;



    /**
     * @ORM\Column(name="photo", type="string", length=5000)
     * @Assert\File(maxSize="500k", mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/GIF"})
     * @Assert\NotBlank
     */
    private $photouser;

    /**
     * @return mixed
     */
    public function getPhotouser()
    {
        return $this->photouser;
    }

    /**
     * @param mixed $photouser
     */
    public function setPhotouser($photouser)
    {
        $this->photouser = $photouser;
    }









    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank

     */
    private $firstname;

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }
















    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }



    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $telephone
     */
    public function setAge($age)
    {
        $this->age = $age;
    }














    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * Many series have Many comm.
     * @ORM\ManyToMany(targetEntity="BlogBundle\Entity\Commentaire", inversedBy="likes")
     * * @ORM\JoinTable(name="likes",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="comm_id", referencedColumnName="id")}
     *      )
     */
    private $commLikes;







    /**
     * @param Commentaire $commentaire
     * @return $this
     */
    public function addComm(\BlogBundle\Entity\Commentaire $comm)
    {
        if (!$this->commLikes->contains($comm)) {
            $this->commLikes[] = $comm;
        }
        return $this;
    }



    
     /**
     * @param Commentaire $commentaire
     * @return $this
     */
    public function removeComm(\BlogBundle\Entity\Commentaire $comm)
    {
        if ($this->commLikes->contains($comm)) {
            $this->commLikes->removeElement($comm);
        }
        return $this;
    }












    /**
     * @return mixed
     */
    public function getCommLikes()
    {
        return $this->commLikes;
    }

    /**
     * @param mixed $commLikes
     */
    public function setCommLikes($commLikes)
    {
        $this->commLikes = $commLikes;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }




















    

}