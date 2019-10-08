<?php

namespace Louvre\BookingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Louvre\BookingBundle\Validator\ValidateDate;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="Louvre\BookingBundle\Repository\CommandeRepository")
 */
class Commande
{
    // Constantes de status
    const STATUS_START = 10;
    const STATUS_INPROGRESS = 20;
    const STATUS_VALIDATE = 30;
    const STATUS_VERIFIED = 40;
    const STATUS_PAID = 50;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     * @Assert\NotBlank(message=\IntlDateFormatter::SHORT)
     * @ValidateDate()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="codeCommande", type="string", length=12, unique=true, options={"collation"="utf8_bin"})
     * @Assert\Length(max=12)
     */
    private $codeCommande = null;

    /**
     * @var bool
     *
     * @ORM\Column(name="halfDay", type="boolean")
     * @Assert\Type(type="bool")
     */
    private $halfDay = false;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Email(message="message.email")
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="nbTickets", type="integer")
     * @Assert\Range(min = 1, max = 10)
     */
    private $nbTickets;
    
    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status = self::STATUS_START;

    /**
     * @ORM\OneToMany(targetEntity="Louvre\BookingBundle\Entity\Ticket", mappedBy="commande", cascade={"persist"})
     * @Assert\Valid()
     */
    private $tickets;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     * @Assert\Type(type="int")
     */
    private $price;
    
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set codeCommande
     *
     * @param string $codeCommande
     *
     * @return Commande
     */
    public function setCodeCommande($codeCommande)
    {
        $this->codeCommande = $codeCommande;

        return $this;
    }

    /**
     * Get codeCommande
     *
     * @return string
     */
    public function getCodeCommande()
    {
        return $this->codeCommande;
    }

    /**
     * Set halfDay
     *
     * @param boolean $halfDay
     *
     * @return Commande
     */
    public function setHalfDay($halfDay)
    {
        $this->halfDay = $halfDay;

        return $this;
    }

    /**
     * Get halfDay
     *
     * @return bool
     */
    public function getHalfDay()
    {
        return $this->halfDay;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Commande
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
     * Set nbTickets
     *
     * @param integer $nbTickets
     *
     * @return Commande
     */
    public function setNbTickets($nbTickets)
    {
        $this->nbTickets = $nbTickets;

        return $this;
    }

    /**
     * Get nbTickets
     *
     * @return int
     */
    public function getNbTickets()
    {
        return $this->nbTickets;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Commande
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param Ticket $ticket
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        $ticket->setCommande($this);
    }

    /**
     * @param Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Commande
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Commande
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }
}
