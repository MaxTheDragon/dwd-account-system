<?php
/**
 * DWD Account System
 */
namespace App\Entity;

//TODO: Documentation

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use SpecShaper\GdprBundle\Validator\Constraints as GdprAssert;

/**
 * [User description]
 * @package   DWDAccountSystem\App
 * @author    Max Waterman <MaxTheDragon@outlook.com>
 * @copyright 2019 MaxTheDragon
 * @license   Proprietary/Closed Source
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * [$id description]
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * [$email description]
     * @var string
     * @GdprAssert\PersonalData({
     *     @Assert\NotBlank,
     *     @Assert\Email
     * })
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * [$fullName description]
     * @var string
     * @GdprAssert\PersonalData({
     *     @Assert\NotBlank
     * })
     * @ORM\Column(type="personal_data", options={
     *     "format" = "STRING",
     *     "isSensitive" = false,
     *     "isEncrypted" = true,
     *     "idMethod" = "DIRECT",
     *     "basisOfCollection" = "LEGITIMATE_INTEREST",
     *     "disposeBy"="SET_NULL"
     * })
     */
    private $fullName;

    /**
     * [$companyName description]
     * @var string
     * @GdprAssert\PersonalData({
     *     @Assert\NotBlank
     * })
     * @ORM\Column(type="personal_data", options={
     *     "format" = "STRING",
     *     "isSensitive" = false,
     *     "isEncrypted" = true,
     *     "idMethod" = "INDIRECT",
     *     "basisOfCollection" = "LEGITIMATE_INTEREST",
     *     "disposeBy"="SET_NULL"
     * })
     */
    private $companyName;

    /**
     * [$billingAddress description]
     * @var string
     * @GdprAssert\PersonalData({
     *     @Assert\NotBlank
     * })
     * @ORM\Column(type="personal_data", options={
     *     "format" = "STRING",
     *     "isSensitive" = true,
     *     "isEncrypted" = true,
     *     "idMethod" = "DIRECT",
     *     "basisOfCollection" = "LEGITIMATE_INTEREST",
     *     "identifiableBy"="Can be used to identify an individual by location",
     *     "disposeBy"="SET_NULL"
     * })
     */
    private $billingAddress;

    /**
     * [$roles description]
     * @var string[]
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * [$password description]
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * [getId description]
     * @return int|null [description]
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * [getEmail description]
     * @return string|null [description]
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * [setEmail description]
     * @param string $email [description]
     * @return User [description]
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * [getFullName description]
     * @return string|null [description]
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * [setFullName description]
     * @param string $fullName [description]
     * @return User [description]
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * [getCompanyName description]
     * @return string|null [description]
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * [setCompanyName description]
     * @param string $companyName [description]
     * @return User [description]
     */
    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * [getBillingAddress description]
     * @return string|null [description]
     */
    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    /**
     * [setBillingAddress description]
     * @param string $billingAddress [description]
     * @return User [description]
     */
    public function setBillingAddress(string $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @return string [description]
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * [getRoles description]
     * @return string[] [description]
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * [setRoles description]
     * @param string[] $roles [description]
     * @return User [description]
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * [getPassword description]
     * @return string [description]
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * [setPassword description]
     * @param string $password [description]
     * @return User [description]
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * [getSalt description]
     * @return null [description]
     * @see UserInterface
     */
    public function getSalt()
    {
    }

    /**
     * [eraseCredentials description]
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }
}
