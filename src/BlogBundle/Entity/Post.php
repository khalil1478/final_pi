<?php

namespace BlogBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="veuillez remplir champs")
     * @Assert\Length(min=5, max=255)
     *  minMessage = "Your first name must be at least {{ limit }} characters long"
     *  maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     */
    private $title;


    /**
     * @ORM\Column(type="string", length=8000)
     *  @Assert\NotBlank
     * @Assert\Length(min = 5)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrpost", type="integer")
     */
    private $nbrpost = 0;




    /**
     * @ORM\Column(name="photo", type="string", length=5000)
     * @Assert\File(maxSize="500k", mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/GIF"})
     * @Assert\NotBlank
     */
    private $photo;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="postdate", type="datetime")
     */
    private $postdate;


    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\Commentaire", mappedBy="post",cascade={"remove"}, orphanRemoval=true)
     */
    private $comments;






    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     *@ORM\JoinColumn(name="creator", referencedColumnName="id")
     */
    private $creator;






    /**
     * @return \DateTime
     */
    public function getPostdate()
    {
        return $this->postdate;
    }

    /**
     * @param \DateTime $postdate
     */
    public function setPostdate($postdate)
    {
        $this->postdate = $postdate;
    }






    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

 /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="`rating`", type="integer", unique=false)
     */
    private $rating;




    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }





    /**
     * @return mixed
     */
    public function getDescription()
    {


        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }






    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }








    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }



    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return int
     */
    public function getNbrpost()
    {
        return $this->nbrpost;
    }

    /**
     * @param int $nbrpost
     */
    public function setNbrpost($nbrpost)
    {
        $this->nbrpost = $nbrpost;
    }











}

