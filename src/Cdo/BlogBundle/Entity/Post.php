<?php

namespace Cdo\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Post
 *
 * @ORM\Table(name="cdo_blog_post")
 * @ORM\Entity(repositoryClass="Cdo\BlogBundle\Entity\PostRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Post
{
	use Traits\ContentTrait,
	    ORMBehaviors\Timestampable\Timestampable,
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
     * @ORM\ManyToOne(targetEntity="Cdo\AccountBundle\Entity\Account", inversedBy="posts")
     */
    private $account;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cdo\UserBundle\Entity\User", inversedBy="posts")
     */
    private $user;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"}, updatable=true, unique=false)
     * @ORM\Column(name="slug", type="string", length=128, unique=false)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="releasedate", type="datetime")
     */
    private $releasedate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="author", type="boolean")
     */
    private $author;
    
    /**
     * @ORM\ManyToMany(targetEntity="Cdo\BlogBundle\Entity\Category", inversedBy="posts")
     * @ORM\JoinTable(name="cdo_blog_post2category")
     * @ORM\OrderBy({"title" = "ASC"})
     */
    private $categorys;
    
    /**
     * @ORM\OneToMany(targetEntity="Cdo\BlogBundle\Entity\Comment", mappedBy="post")
     */
    private $comments;

    /**
     * @var integer
     *
     * @ORM\Column(name="numberComments", type="integer")
     */
    private $numberComments;


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
     * @return Post
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
     * Set user
     *
     * @param \Cdo\UserBundle\Entity\User $user
     * @return Post
     */
    public function setUser(\Cdo\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Cdo\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set releasedate
     *
     * @param \DateTime $releasedate
     * @return Post
     */
    public function setReleasedate($releasedate)
    {
        $this->releasedate = $releasedate;

        return $this;
    }

    /**
     * Get releasedate
     *
     * @return \DateTime 
     */
    public function getReleasedate()
    {
        return $this->releasedate;
    }

    /**
     * Set author
     *
     * @param boolean $author
     * @return Post
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return boolean 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add category
     *
     * @param \Cdo\BlogBundle\Entity\Category $category
     * @return Post
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
     * Add comment
     *
     * @param \Cdo\BlogBundle\Entity\Comment $comment
     * @return Post
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
     * Set numberComments
     *
     * @param integer $numberComments
     * @return Post
     */
    public function setNumberComments($numberComments)
    {
        $this->numberComments = $numberComments;

        return $this;
    }

    /**
     * Get numberComments
     *
     * @return integer 
     */
    public function getNumberComments()
    {
        return $this->numberComments;
    }
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDisplay(true);
        $this->setReleasedate(new \Datetime('now'));
        $this->setAuthor(true);
        $this->categorys = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setNumberComments(0);
    }
}
