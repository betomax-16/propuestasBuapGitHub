<?php

namespace RCMPropuestasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Propuesta
 *
 * @ORM\Table(name="Propuestas")
 * @ORM\Entity(repositoryClass="RCMPropuestasBundle\Entity\PropuestaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Propuesta
{
    /**
    * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="propuestas")
    * @ORM\joinColumn(name="idUsuario", referencedColumnName="id", onDelete="CASCADE", nullable=false)
    */
    protected $usuario;

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
     * @ORM\Column(name="titulo", type="string", length=65)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=35)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="visibilidad", type="string", columnDefinition="ENUM('PUBLIC','PRIVATE')")
     */
    private $visibilidad;

    /**
     * @var string
     *
     * @ORM\Column(name="ruta", type="string", length=255)
     */
    private $ruta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


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
     * Set titulo
     *
     * @param string $titulo
     * @return Propuesta
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Propuesta
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Propuesta
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set visibilidad
     *
     * @param string $visibilidad
     * @return Propuesta
     */
    public function setVisibilidad($visibilidad)
    {
        $this->visibilidad = $visibilidad;

        return $this;
    }

    /**
     * Get visibilidad
     *
     * @return string
     */
    public function getVisibilidad()
    {
        return $this->visibilidad;
    }

    /**
     * Set ruta
     *
     * @param string $ruta
     * @return Propuesta
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get ruta
     *
     * @return string
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Propuesta
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Propuesta
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set usuario
     *
     * @param \RCMPropuestasBundle\Entity\Usuario $usuario
     * @return Propuesta
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

    //-----------------------------------------------------------------------------------
    public function getAbsolutePath()
    {
        return null === $this->ruta
            ? null
            : $this->getUploadRootDir().'/'.$this->ruta;
    }

    public function getWebPath()
    {
        return null === $this->ruta
            ? null
            : $this->getUploadDir().'/'.$this->ruta;
    }

    protected function getUploadRootDir()
    {
        // el directorio absoluto donde se guardan los documentos
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // Eliminamos el __DIR__ para evitar errores al mostrar en view
        return 'uploads/documents';
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
      $this->file = $file;
      // comprobamos si tenemos un image path antiguo
      if (isset($this->ruta)) {
          // guardamos el nombre antiguo para borrarlo después del update
          $this->temp = $this->ruta;
          $this->ruta = null;
      } else {
          $this->ruta = 'initial';
      }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
      if (null === $this->getFile()) {
          return;
      }

      // si hay algún error cuando se mueva el archivo, se lanzará una excepción
      // automáticamente con move(). Esto prevendrá a la entidad
      // de ser persistida en la base de datos cuando haya error
      $this->getFile()->move($this->getUploadDir(), $this->ruta);

      // comprobar si tenemos una imagen vieja
      if (isset($this->temp)) {
          // borrar esa imagen
          unlink($this->getUploadDir().'/'.$this->temp);
          // limpiar el image path temporal
          $this->temp = null;
      }
      $this->file = null;
    }

    private $temp;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // hacemos cualquier cosa para generar el nombre único
            $filename = sha1(uniqid(mt_rand(), true));
            $this->ruta = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getWebPath();
        if ($file) {
            unlink($file);
        }
    }
}
