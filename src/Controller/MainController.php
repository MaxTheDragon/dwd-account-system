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
 * [MainController description]
 * @package   DWDAccountSystem\App
 * @author    Max Waterman <MaxTheDragon@outlook.com>
 * @copyright 2019 MaxTheDragon
 * @license   Proprietary/Closed Source
 */
class MainController extends AbstractController
{
    /**
     * [index description]
     * @return Response [description]
     * @Route("/")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }
}
