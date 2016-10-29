<?php

namespace RCMPropuestasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comentario
 *
 * @ORM\Table(name="Comentarios")
 * @ORM\Entity(repositoryClass="RCMPropuestasBundle\Entity\ComentarioRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comentario
{
    /**
    * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="comentarios")
    * @ORM\joinColumn(name="idUsuario", referencedColumnName="id", onDelete="CASCADE", nullable=false)
    */
    protected $usuario;

    /**
    * @ORM\ManyToOne(targetEntity="Propuesta", inversedBy="comentarios")
    * @ORM\joinColumn(name="idComentario", referencedColumnName="id", onDelete="CASCADE", nullable=false)
    */
    protected $propuesta;

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
     * @ORM\Column(name="comentario", type="string", length=255)
     */
    private $comentario;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isRead", type="boolean")
     */
    private $isRead;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


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
     * Set comentario
     *
     * @param string $comentario
     * @return Comentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     * @return Comentario
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Comentario
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function setReadValue()
    {
        $this->isRead = false;
    }

    /**
     * Set usuario
     *
     * @param \RCMPropuestasBundle\Entity\Usuario $usuario
     * @return Comentario
     */
    public function setUsuario(\RCMPropuestasBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \RCMPropuestasBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set propuesta
     *
     * @param \RCMPropuestasBundle\Entity\Propuesta $propuesta
     * @return Comentario
     */
    public function setPropuesta(\RCMPropuestasBundle\Entity\Propuesta $propuesta)
    {
        $this->propuesta = $propuesta;

        return $this;
    }

    /**
     * Get propuesta
     *
     * @return \RCMPropuestasBundle\Entity\Propuesta
     */
    public function getPropuesta()
    {
        return $this->propuesta;
    }
}
