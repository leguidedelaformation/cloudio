<?php

namespace Cdo\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Individual
 *
 * @ORM\Table(name="cdo_client_individual")
 * @ORM\Entity(repositoryClass="Cdo\ClientBundle\Entity\IndividualRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Individual
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
     * @ORM\ManyToOne(targetEntity="Cdo\AccountBundle\Entity\Account", inversedBy="individuals")
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="origin", type="integer")
     */
    private $origin;
    
    /**
     * @ORM\OneToMany(targetEntity="Cdo\BlogBundle\Entity\Comment", mappedBy="individual")
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
     * @return Individual
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
     * Set username
     *
     * @param string $username
     * @return Individual
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Individual
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
     * Set origin
     *
     * @param integer $origin
     * @return Individual
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return integer 
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Add comment
     *
     * @param \Cdo\BlogBundle\Entity\Comment $comment
     * @return Individual
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
        $this->setOrigin(0);
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
