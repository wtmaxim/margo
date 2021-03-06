<?php

namespace Margo\Controller;

use Margo\Entity\Student;
use Margo\Form\Type\StudentType;
use Silex\Application;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;


class StudentController
{

    public function indexAction(Request $request, Application $app)
    {
        // Perform pagination logic.
        $limit = 100;
        $total = $app['repository.etudiant']->getCount();
        $numPages = ceil($total / $limit);
        $currentPage = $request->query->get('page', 1);
        $offset = ($currentPage - 1) * $limit;
        $etudiants = $app['repository.etudiant']->findAll($limit, $offset);
        $data = array(
            'etudiants' => $etudiants,
            'currentPage' => $currentPage,
            'numPages' => $numPages,
            'here' => $app['url_generator']->generate('etudiants'),
        );
        return $app['twig']->render('student.html.twig', $data);
    }

}