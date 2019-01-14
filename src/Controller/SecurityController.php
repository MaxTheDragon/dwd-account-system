<?php
/**
 * DWD Account System
 */
namespace App\Controller;

//TODO: Documentation

use App\Security\ManualPasswordValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * [SecurityController description]
 * @package   DWDAccountSystem\App
 * @author    Max Waterman <MaxTheDragon@outlook.com>
 * @copyright 2019 MaxTheDragon
 * @license   Proprietary/Closed Source
 */
class SecurityController extends AbstractController
{
    /**
     * [login description]
     * @param AuthenticationUtils $authenticationUtils [description]
     * @return Response [description]
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Redirect users if they are already logged in
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_main_index');
        }

        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * [confirmDelete description]
     * @param Request $request [description]
     * @param ManualPasswordValidator $manualPasswordValidator [description]
     * @param EntityManagerInterface $entityManager [description]
     * @return Response [description]
     * @Route("/confirmdelete", name="app_confirm_delete", methods={"POST"})
     */
    public function confirmDelete(Request $request, ManualPasswordValidator $manualPasswordValidator, EntityManagerInterface $entityManager): Response
    {
        // Check if user has been logged in
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_main_index');
        }

        // Manually check CSRF token
        if (!$this->isCsrfTokenValid('confirmdelete', $request->get('_csrf_token'))) {
            $this->render('profile.html.twig', ['error' => 'Invalid CSRF token.']);
        }

        // Check if password confirmation passes
        if (!$manualPasswordValidator->passwordIsValidForCurrentUser($request->get('password'))) {
            return $this->render('profile.html.twig', ['error' => 'Password incorrect.']);
        }

        // Manual logout
        $user = $this->getUser();
        $this->get('security.token_storage')->setToken(null);

        // Delete the user
        $entityManager->remove($user);
        $entityManager->flush();

        // Redirect to main page
        return $this->redirectToRoute('app_main_index');
    }

    /**
     * [logout description]
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
    }
}
