<?php
/**
 * DWD Account System
 */
namespace App\Controller;

//TODO: Documentation

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * [ProfileController description]
 * @package   DWDAccountSystem\App
 * @author    Max Waterman <MaxTheDragon@outlook.com>
 * @copyright 2019 MaxTheDragon
 * @license   Proprietary/Closed Source
 */
class ProfileController extends AbstractController
{
    /**
     * [index description]
     * @return Response [description]
     * @Route("/profile", name="app_profile")
     */
    public function index()
    {
        // Redirect users if they are not logged in
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_main_index');
        }

        return $this->render('profile.html.twig');
    }
}
