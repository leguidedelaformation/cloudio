<?php

namespace Cdo\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Model\User as BaseUser;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * User
 *
 * @ORM\Table(name="cdo_user_user")
 * @ORM\Entity(repositoryClass="Cdo\UserBundle\Entity\UserRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class User extends BaseUser
{
	use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\SoftDeletable\SoftDeletable
    ;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cdo\AccountBundle\Entity\Account", inversedBy="users")
     */
    private $account;
    
    /**
     * @var string $firstname
     *
     * @Assert\NotBlank(message="Votre prénom :", groups={"Profile"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $firstname;
    
    /**
     * @var string $lastname
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message="Votre nom :", groups={"Profile"})
     * @Assert\Length(
     *     min=2,
     *     max="255",
     *     minMessage="Ce nom est trop court.",
     *     maxMessage="Ce nom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $lastname;
    
    /**
     * @var string $phone
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $phone;
    
    /**
     * @ORM\OneToMany(targetEntity="Cdo\BlogBundle\Entity\Post", mappedBy="user")
     */
    private $posts;
    
    /**
     * @ORM\OneToMany(targetEntity="Cdo\BlogBundle\Entity\Comment", mappedBy="user")
     */
    private $comments;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set account
     *
     * @param \Cdo\AccountBundle\Entity\Account $account
     * @return User
     */
    public function setAccount(\Cdo\AccountBundle\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Cdo\AccountBundle\Entity\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Add post
     *
     * @param \Cdo\BlogBundle\Entity\Post $post
     * @return User
     */
    public function addPost(\Cdo\BlogBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Cdo\BlogBundle\Entity\Post $post
     */
    public function removePost(\Cdo\BlogBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add comment
     *
     * @param \Cdo\BlogBundle\Entity\Comment $comment
     * @return User
     */
    public function addComment(\Cdo\BlogBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Cdo\BlogBundle\Entity\Comment $comment
     */
    public function removeComment(\Cdo\BlogBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->roles = array('ROLE_USER');
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
