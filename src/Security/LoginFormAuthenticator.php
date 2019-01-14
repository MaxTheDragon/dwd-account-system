<?php
/**
 * DWD Account System
 */
namespace App\Security;

//TODO: Documentation

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * [LoginFormAuthenticator description]
 * @package   DWDAccountSystem\App
 * @author    Max Waterman <MaxTheDragon@outlook.com>
 * @copyright 2019 MaxTheDragon
 * @license   Proprietary/Closed Source
 */
class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /**
     * [$entityManager description]
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * [$router description]
     * @var RouterInterface
     */
    private $router;

    /**
     * [$csrfTokenManager description]
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * [$passwordEncoder description]
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * [__construct description]
     * @param EntityManagerInterface       $entityManager [description]
     * @param RouterInterface              $router [description]
     * @param CsrfTokenManagerInterface    $csrfTokenManager [description]
     * @param UserPasswordEncoderInterface $passwordEncoder [description]
     */
    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * [supports description]
     * @param Request $request [description]
     * @return bool [description]
     */
    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * [getCredentials description]
     * @param Request $request [description]
     * @return array|mixed [description]
     */
    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    /**
     * [getUser description]
     * @param mixed                 $credentials [description]
     * @param UserProviderInterface $userProvider [description]
     * @return User|object|UserInterface|null [description]
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        return $user;
    }

    /**
     * [checkCredentials description]
     * @param mixed         $credentials [description]
     * @param UserInterface $user [description]
     * @return bool [description]
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * [onAuthenticationSuccess description]
     * @param Request        $request [description]
     * @param TokenInterface $token [description]
     * @param string         $providerKey [description]
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response|null [description]
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->router->generate('app_main_index'));
    }

    /**
     * [getLoginUrl description]
     * @return string [description]
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('app_login');
    }
}
