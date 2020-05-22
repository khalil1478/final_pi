<?php

namespace EspritApiBundle\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="EspritApiBundle\Repository\CommentaireRepository")
 */
class Commentaire
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
     */
    private $content;









    /**
     * Many series have Many panier.
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="commLikes")
     */
    private $likes;
    
    




    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param mixed $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }





    /**
     * @ORM\ManyToOne(targetEntity="EspritApiBundle\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;




    /**
     * @var \DateTime
     *
     * @ORM\Column(name="posted_at", type="date")
     */
    private $posted_at;










    public function addLikes(\AppBundle\Entity\User $user)
    {
        if (!$this->likes->contains($user)) {
            $this->likes[] = $user;
        }
        return $this;
    }






    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }










    /**
     * @return \DateTime
     */
    public function getPostedAt()
    {
        return $this->posted_at;
    }
    /**
     * @param \DateTime $posted_at
     */
    public function setPostedAt()
    {
        $this->posted_at = new \DateTime('now');
    }








    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }



    













}

