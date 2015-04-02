<?php

namespace Cdo\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Comment
 *
 * @ORM\Table(name="cdo_blog_comment")
 * @ORM\Entity(repositoryClass="Cdo\BlogBundle\Entity\CommentRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Comment
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
     * @ORM\ManyToOne(targetEntity="Cdo\AccountBundle\Entity\Account", inversedBy="comments")
     */
    private $account;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cdo\UserBundle\Entity\User", inversedBy="comments")
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cdo\ClientBundle\Entity\Individual", inversedBy="comments", cascade={"persist"})
     */
    private $individual;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cdo\BlogBundle\Entity\Post", inversedBy="comments")
     */
    private $post;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cdo\BlogBundle\Entity\Comment", inversedBy="children")
     */
    private $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Cdo\BlogBundle\Entity\Comment", mappedBy="parent")
     */
    private $children;


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
     * @return Comment
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
     * @return Comment
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
     * Set individual
     *
     * @param \Cdo\ClientBundle\Entity\Individual $individual
     * @return Comment
     */
    public function setIndividual(\Cdo\ClientBundle\Entity\Individual $individual = null)
    {
        $this->individual = $individual;

        return $this;
    }

    /**
     * Get individual
     *
     * @return \Cdo\ClientBundle\Entity\Individual 
     */
    public function getIndividual()
    {
        return $this->individual;
    }

    /**
     * Set post
     *
     * @param \Cdo\BlogBundle\Entity\Post $post
     * @return Comment
     */
    public function setPost(\Cdo\BlogBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Cdo\BlogBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Comment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set parent
     *
     * @param \Cdo\BlogBundle\Entity\Comment $parent
     * @return Comment
     */
    public function setParent(\Cdo\BlogBundle\Entity\Comment $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Cdo\BlogBundle\Entity\Comment 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Cdo\BlogBundle\Entity\Comment $children
     * @return Comment
     */
    public function addChild(\Cdo\BlogBundle\Entity\Comment $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Cdo\BlogBundle\Entity\Comment $children
     */
    public function removeChild(\Cdo\BlogBundle\Entity\Comment $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setStatus(0);
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
