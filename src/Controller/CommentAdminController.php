<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentAdminController extends Controller
{
    /**
     * @Route("/admin/comment", name="comment_admin")
     */
    public function index(CommentRepository $commentRepository, Request $request)
    {
        $q = $request->query->get('q'); //ovo je GEt
        $comments = $commentRepository->findAllWithSearch($q);//ovako cemo da ih trazimo

       // $comments = $commentRepository->findBy([],['createdAt' => 'DESC']);

        return $this->render('comment_admin/index.html.twig', [
            //'controller_name' => 'CommentAdminController',
            'comments' => $comments,
        ]);
    }
}
