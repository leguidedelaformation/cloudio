<?php

namespace Cdo\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Image
 *
 * @ORM\Table(name="cdo_media_image")
 * @ORM\Entity(repositoryClass="Cdo\MediaBundle\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Image
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
    public $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cdo\AccountBundle\Entity\Account", inversedBy="images")
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    public $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255)
     */
    public $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true)
     */
    public $alt;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    
    // propriété utilisé temporairement pour la suppression
    private $filenameForRemove;

    /**
     * @var boolean
     *
     * @ORM\Column(name="navbarlogo", type="boolean")
     */
    private $navbarlogo;


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
     * @return Image
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
     * Set filename
     *
     * @param string $filename
     * @return Image
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }


    public function getAbsolutePath()
    {
        return (null === $this->filename OR null === $this->extension) ? null : $this->getUploadRootDir().'/'.$this->id.'_'.$this->filename.'.'.$this->extension;
    }

    public function getWebPath()
    {
        return (null === $this->filename OR null === $this->extension) ? null : $this->getUploadDir().'/'.$this->id.'_'.$this->filename.'.'.$this->extension;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'media/image';
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $this->filename = substr(sha1(uniqid(mt_rand(), true)), 0, 8);
            $this->extension = $this->file->guessExtension();
            $this->createdAt = new \Datetime('now');
            $this->updatedAt = new \Datetime('now');
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // vous devez lancer une exception ici si le fichier ne peut pas
        // être déplacé afin que l'entité ne soit pas persistée dans la
        // base de données comme le fait la méthode move() de UploadedFile
        $this->file->move($this->getUploadRootDir(), $this->id.'_'.$this->filename.'.'.$this->extension);

        unset($this->file);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->filenameForRemove) {
            unlink($this->filenameForRemove);
        }
    }

    /**
     * Set navbarlogo
     *
     * @param boolean $navbarlogo
     * @return Image
     */
    public function setNavbarlogo($navbarlogo)
    {
        $this->navbarlogo = $navbarlogo;

        return $this;
    }

    /**
     * Get navbarlogo
     *
     * @return boolean 
     */
    public function getNavbarlogo()
    {
        return $this->navbarlogo;
    }
}
