<?php
/**
 * DWD Account System
 */
namespace App\Security;

//TODO: Documentation

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * [ManualPasswordValidator description]
 * @package   DWDAccountSystem\App
 * @author    Max Waterman <MaxTheDragon@outlook.com>
 * @copyright 2019 MaxTheDragon
 * @license   Proprietary/Closed Source
 */
class ManualPasswordValidator
{
    /**
     * [$encoderFactory description]
     * @var EncoderFactory
     */
    protected $encoderFactory;

    /**
     * [$tokenStorage description]
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * [__construct description]
     * @param EncoderFactoryInterface $encoderFactory [description]
     * @param TokenStorageInterface $tokenStorage [description]
     */
    public function __construct(TokenStorageInterface $tokenStorage, EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * [passwordIsValidForCurrentUser description]
     * @param string $password [description]
     * @return bool [description]
     */
    public function passwordIsValidForCurrentUser(string $password): bool
    {
        $token = $this->tokenStorage->getToken();

        if ($token) {
            $user = $token->getUser();

            if ($user) {
                $encoder = $this->encoderFactory->getEncoder($user);

                if ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
                    return true;
                }
            }
        }

        return false;
    }
}