<?php

namespace Cdo\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Page
 *
 * @ORM\Table(name="cdo_blog_page")
 * @ORM\Entity(repositoryClass="Cdo\BlogBundle\Entity\PageRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Page
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
     * @ORM\ManyToOne(targetEntity="Cdo\AccountBundle\Entity\Account", inversedBy="pages")
     */
    private $account;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @Gedmo\Slug(fields={"title"}, updatable=true, unique=false)
     * @ORM\Column(name="slug", type="string", length=128, unique=false)
     */
    private $slug;

    /**
     * @var boolean
     *
     * @ORM\Column(name="homepage", type="boolean")
     */
    private $homepage;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cdo\BlogBundle\Entity\Page", inversedBy="children")
     */
    private $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Cdo\BlogBundle\Entity\Page", mappedBy="parent")
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
     * @return Page
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
     * Set rank
     *
     * @param integer $rank
     * @return Page
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Page
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Page
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
     * Set homepage
     *
     * @param boolean $homepage
     * @return Page
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
    
        return $this;
    }

    /**
     * Get homepage
     *
     * @return boolean 
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * Set parent
     *
     * @param \Cdo\BlogBundle\Entity\Page $parent
     * @return Page
     */
    public function setParent(\Cdo\BlogBundle\Entity\Page $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Cdo\BlogBundle\Entity\Page 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Cdo\BlogBundle\Entity\Page $children
     * @return Page
     */
    public function addChild(\Cdo\BlogBundle\Entity\Page $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Cdo\BlogBundle\Entity\Page $children
     */
    public function removeChild(\Cdo\BlogBundle\Entity\Page $children)
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
        $this->setDisplay(true);
        $this->setHomepage(false);
        $this->setRank(999);
        $this->setLevel(0);
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
