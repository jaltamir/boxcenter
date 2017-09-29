<?php

namespace Jaltamir\BoxCoreBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping AS ORM;
use Jaltamir\BoxCoreBundle\Entity\Base\BaseEntity;

/**
 *
 * @ApiResource()
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Jaltamir\BoxCoreBundle\Repository\PassRepository")
 *
 */
class Pass extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(name="pass_name", type="string", nullable=false)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="pass_desc", type="string", nullable=false)
     *
     * @var string
     */
    private $desc;

    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var integer
     */
    private $numSessions;

    /**
     * @ORM\Column(type="float", nullable=false)
     *
     * @var float
     */
    private $price;

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $desc
     * @return $this
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * @param string $name
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
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param $numSessions
     * @return $this
     */
    public function setNumSessions($numSessions)
    {
        $this->numSessions = $numSessions;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNumSessions()
    {
        return $this->numSessions;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameForFront()
    {
        return sprintf(
            "%s - %s - %s €/mes",
            $this->name,
            $this->numSessions > 100 ? 'Sesiones Ilimitadas' : $this->numSessions.' Sesiones',
            $this->price
        );
    }

    /**
     * @return string
     */
    public function getNameForFrontShort()
    {
        return sprintf("%s (%s€)", $this->name, $this->price);
    }

    /**
     * @return string
     */
    public function getNameForProfile()
    {
        return sprintf(
            "%s - %s",
            $this->name,
            $this->numSessions > 100 ? 'Sesiones Ilimitadas' : $this->numSessions.' Sesiones'
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getName();
    }

}