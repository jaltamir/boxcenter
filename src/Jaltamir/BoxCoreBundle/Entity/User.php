<?php

namespace Jaltamir\BoxCoreBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Jaltamir\BoxCoreBundle\Entity\Base\BaseEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface as UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;


/**
 * TODO Revisa los campos
 * TODO Añadir validations messages keys
 * @ApiResource()
 *
 * @ORM\Entity
 * @ORM\Table(indexes={
 *    @ORM\Index(columns={"email"})
 *    ,@ORM\Index(name="combined_idx", columns={"name", "surname", "email", "nif"})
 * })
 * @UniqueEntity("nif")
 * @UniqueEntity("email")
 * @Uploadable
 *
 */
class User extends BaseEntity implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=3,max=20)
     * @Assert\Regex("/^[a-zA-Z0-9_ áéíóú]+$/")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=2,max=40)
     * @Assert\Regex("/^[a-zA-Z0-9_ áéíóúñ]+$/")
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=9, nullable=true)
     * @Assert\Regex("/^[0-9]{8}[a-zA-Z]{1}$/", message="El NIF no es válido")
     *
     */
    private $nif;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email(message = "El email indicado para el usuario no es válido")
     * @ORM\Column(type="string", unique=true, length=255, nullable=false, name="email")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * TODO añadir la validacion de phone
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @Assert\Image(
     *     maxSize="2M",
     *     maxSizeMessage="Size allowed: up to 2M",
     *     maxWidth="1920",
     *     maxWidthMessage="The max width is 1920px",
     *     maxHeight="1080",
     *     maxHeightMessage="The max height is 1080px",
     *     mimeTypes = {"image/jpeg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid image. The supported formats are JPEG, PNG and GIF"
     * )
     * @UploadableField(mapping="userImage", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $flagAdmin;

    public function getRoles()
    {
        $roles = ['ROLE_USER'];
        if ($this->flagAdmin) {
            $roles[] = 'ROLE_ADMIN';
        }

        return $roles;

    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
        return false;
    }

    public function isEqualTo(UserInterface $user)
    {
        return $this->getUsername() === $user->getUsername();
    }

    public function __construct()
    {
        $this->flagAdmin = false;
        parent::__construct();
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
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $surname
     * @return $this
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param $nif
     * @return $this
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * @return string
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     *
     * @return Imagen
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     *
     * @param $value
     * @return $this
     */
    public function setImagen($value)
    {
        $this->imagen = $value;

        return $this;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return base64_encode($this->email);
    }

    /**
     * Set email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return $this
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedDatetime = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageDile;
    }

    /**
     * @param string $imageName
     *
     * @return $this
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    public function __toString()
    {
        if ($this->name !== null && $this->surname !== null) {
            return (string) (ucfirst($this->name).' '.ucfirst($this->surname)).' ('. $this->email .')';
        }

        return (string)$this->email;
    }

    /**
     * @param bool $flagAdmin
     * @return $this
     */
    public function setFlagAdmin(bool $flagAdmin)
    {
        $this->flagAdmin = $flagAdmin;

        return $this;
    }

    /**
     * @return bool
     */
    public function getFlagAdmin(): bool
    {
        return $this->flagAdmin;
    }
}
