<?php

namespace RCMPropuestasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Usuario
 *
 * @ORM\Table(name="Usuarios")
 * @ORM\Entity(repositoryClass="RCMPropuestasBundle\Entity\UsuarioRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("email")
 */
class Usuario implements AdvancedUserInterface, \Serializable
{
    /**
    * @ORM\OneToMany(targetEntity="Propuesta", mappedBy="usuario")
    */
    protected $propuestas;

    /**
    * @ORM\OneToMany(targetEntity="Comentario", mappedBy="usuario")
    */
    protected $comentarios;

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
     * @ORM\Column(name="nombre", type="string", length=65)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidoPaterno", type="string", length=40)
     */
    private $apellidoPaterno;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidoMaterno", type="string", length=40)
     */
    private $apellidoMaterno;

    /**
     * @var string
     *
     * @ORM\Column(name="departamento", type="string", length=50)
     */
    private $departamento;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=255)
     */
    private $foto;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=72)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", columnDefinition="ENUM('ROLE_SUPER_ADMIN','ROLE_ADMIN', 'ROLE_USER')")
     */
    private $role;

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

    public function __construct()
    {
      $this->propuestas = new ArrayCollection();
      $this->comentarios = new ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellidoPaterno
     *
     * @param string $apellidoPaterno
     * @return Usuario
     */
    public function setApellidoPaterno($apellidoPaterno)
    {
        $this->apellidoPaterno = $apellidoPaterno;

        return $this;
    }

    /**
     * Get apellidoPaterno
     *
     * @return string
     */
    public function getApellidoPaterno()
    {
        return $this->apellidoPaterno;
    }

    /**
     * Set apellidoMaterno
     *
     * @param string $apellidoMaterno
     * @return Usuario
     */
    public function setApellidoMaterno($apellidoMaterno)
    {
        $this->apellidoMaterno = $apellidoMaterno;

        return $this;
    }

    /**
     * Get apellidoMaterno
     *
     * @return string
     */
    public function getApellidoMaterno()
    {
        return $this->apellidoMaterno;
    }

    /**
     * Set departamento
     *
     * @param string $departamento
     * @return Usuario
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return string
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
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
     * Set foto
     *
     * @param string $foto
     * @return Usuario
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Usuario
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Usuario
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
     * @return Usuario
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

    public function getNombreCompleto()
    {
      return strtoupper($this->getNombre().' '.$this->getApellidoPaterno().' '.$this->getApellidoMaterno());
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

    public function getUsername()
    {
      return $this->getEmail();
    }

    public function getRoles()
    {
      return array($this->role);
    }

    public function getSalt()
    {
      return null;
    }

    public function eraseCredentials()
    {
      # code...
    }

    public function serialize()
    {
      return serialize(array($this->id, $this->email, $this->password));
    }

    public function unserialize($serialized)
    {
      list($this->id, $this->email, $this->password) = unserialize($serialized);
    }

    public function isAccountNonExpired()
    {
      return true;
    }

    public function isAccountNonLocked()
    {
      return true;
    }

    public function isCredentialsNonExpired()
    {
      return true;
    }

    public function isEnabled()
    {
      return true;
    }
    //--------------------------------------------------------------------------------
    public function getAbsolutePath()
    {
        return null === $this->foto
            ? null
            : $this->getUploadRootDir().'/'.$this->foto;
    }

    public function getWebPath()
    {
        return null === $this->foto
            ? null
            : $this->getUploadDir().'/'.$this->foto;
    }

    protected function getUploadRootDir()
    {
        // el directorio absoluto donde se guardan los documentos
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // Eliminamos el __DIR__ para evitar errores al mostrar en view
        return 'uploads/imageUser';
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
      if (isset($this->foto)) {
          // guardamos el nombre antiguo para borrarlo después del update
          $this->temp = $this->foto;
          $this->foto = null;
      } else {
          $this->foto = 'initial';
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
      $this->getFile()->move($this->getUploadDir(), $this->foto);

      // comprobar si tenemos una imagen vieja
      if (isset($this->temp) && $this->temp != '') {
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
            $this->foto = $filename.'.'.$this->getFile()->guessExtension();
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

    /**
     * Add propuestas
     *
     * @param \RCMPropuestasBundle\Entity\Propuesta $propuestas
     * @return Usuario
     */
    public function addPropuesta(\RCMPropuestasBundle\Entity\Propuesta $propuestas)
    {
        $this->propuestas[] = $propuestas;

        return $this;
    }

    /**
     * Remove propuestas
     *
     * @param \RCMPropuestasBundle\Entity\Propuesta $propuestas
     */
    public function removePropuesta(\RCMPropuestasBundle\Entity\Propuesta $propuestas)
    {
        $this->propuestas->removeElement($propuestas);
    }

    /**
     * Get propuestas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPropuestas()
    {
        return $this->propuestas;
    }

    /**
     * Add comentarios
     *
     * @param \RCMPropuestasBundle\Entity\Comentario $comentarios
     * @return Usuario
     */
    public function addComentario(\RCMPropuestasBundle\Entity\Comentario $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \RCMPropuestasBundle\Entity\Comentario $comentarios
     */
    public function removeComentario(\RCMPropuestasBundle\Entity\Comentario $comentarios)
    {
        $this->comentarios->removeElement($comentarios);
    }

    /**
     * Get comentarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }
}
