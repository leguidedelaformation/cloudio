<?php

namespace Cdo\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Category
 *
 * @ORM\Table(name="cdo_blog_category")
 * @ORM\Entity(repositoryClass="Cdo\BlogBundle\Entity\CategoryRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Category
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
     * @ORM\ManyToOne(targetEntity="Cdo\AccountBundle\Entity\Account", inversedBy="categorys")
     */
    private $account;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"}, updatable=true, unique=false)
     * @ORM\Column(name="slug", type="string", length=128, unique=false)
     */
    private $slug;
    
    /**
     * @ORM\ManyToMany(targetEntity="Cdo\BlogBundle\Entity\Post", mappedBy="categorys")
     */
    private $posts;


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
     * @return Category
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
     * Set slug
     *
     * @param string $slug
     * @return Category
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
     * Add post
     *
     * @param \Cdo\BlogBundle\Entity\Post $post
     * @return Category
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
     * Constructor
     */
    public function __construct()
    {
        $this->setDisplay(false);
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
