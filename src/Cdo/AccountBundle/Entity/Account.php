<?php

namespace Cdo\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Account
 *
 * @ORM\Table(name="cdo_account_account")
 * @ORM\Entity(repositoryClass="Cdo\AccountBundle\Entity\AccountRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @UniqueEntity(fields="subdomain", message="Ce sous-domaine est déjà pris.")
 */
class Account
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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="subdomain", type="string", length=255, unique=true)
     */
    private $subdomain;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;
    
    /**
     * @ORM\OneToMany(targetEntity="Cdo\UserBundle\Entity\User", mappedBy="account")
     */
    private $users;
    
    /**
     * @ORM\OneToMany(targetEntity="Cdo\BlogBundle\Entity\Page", mappedBy="account")
     */
    private $pages;
    
    /**
     * @ORM\OneToMany(targetEntity="Cdo\BlogBundle\Entity\Post", mappedBy="account")
     */
    private $posts;
    
    /**
     * @ORM\OneToMany(targetEntity="Cdo\BlogBundle\Entity\Comment", mappedBy="account")
     */
    private $comments;
    
    /**
     * @ORM\OneToMany(targetEntity="Cdo\BlogBundle\Entity\Category", mappedBy="account")
     */
    private $categorys;


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
     * Set subdomain
     *
     * @param string $subdomain
     * @return Account
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
    
        return $this;
    }

    /**
     * Get subdomain
     *
     * @return string 
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Account
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add user
     *
     * @param \Cdo\UserBundle\Entity\User $user
     * @return Account
     */
    public function addUser(\Cdo\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Cdo\UserBundle\Entity\User $user
     */
    public function removeUser(\Cdo\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add page
     *
     * @param \Cdo\BlogBundle\Entity\Page $page
     * @return Account
     */
    public function addPage(\Cdo\BlogBundle\Entity\Page $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * Remove page
     *
     * @param \Cdo\BlogBundle\Entity\Page $page
     */
    public function removePage(\Cdo\BlogBundle\Entity\Page $page)
    {
        $this->pages->removeElement($page);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Add post
     *
     * @param \Cdo\BlogBundle\Entity\Post $post
     * @return Account
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
     * @return Account
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
     * Add category
     *
     * @param \Cdo\BlogBundle\Entity\Category $category
     * @return Account
     */
    public function addCategory(\Cdo\BlogBundle\Entity\Category $category)
    {
        $this->categorys[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Cdo\BlogBundle\Entity\Category $category
     */
    public function removeCategory(\Cdo\BlogBundle\Entity\Category $category)
    {
        $this->categorys->removeElement($category);
    }

    /**
     * Get categorys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategorys()
    {
        return $this->categorys;
    }
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categorys = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
