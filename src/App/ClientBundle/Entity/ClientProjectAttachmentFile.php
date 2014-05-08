<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientProjectAttachmentFile
 *
 * @ORM\Table(name="client_project_attachment_file")
 * @ORM\Entity
 */
class ClientProjectAttachmentFile
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $idProjectAttachment
     *
     * @ORM\Column(name="id_project_attachment", type="integer", nullable=true)
     */
    private $idProjectAttachment;

    /**
     * @var string $file
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;


    /**
     * @var number $fileSize
     *
     * @ORM\Column(name="size", type="decimal", precision=5, scale=3)
     */
    private $fileSize;

    /**
     * @param number $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return number
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }


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
     * Set idProjectAttachment
     *
     * @param integer $idProjectAttachment
     * @return ClientProjectAttachmentFile
     */
    public function setIdProjectAttachment($idProjectAttachment)
    {
        $this->idProjectAttachment = $idProjectAttachment;

        return $this;
    }

    /**
     * Get idProjectAttachment
     *
     * @return integer
     */
    public function getIdProjectAttachment()
    {
        return $this->idProjectAttachment;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return ClientProjectAttachmentFile
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
}