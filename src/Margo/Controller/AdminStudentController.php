<?php

namespace Margo\Controller;

use Margo\Entity\Category;
use Margo\Entity\Student;
use Margo\Form\Type\StudentType;
use Silex\Application;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;


class AdminStudentController
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
            'here' => $app['url_generator']->generate('admin_etudiants'),
        );
        return $app['twig']->render('adminStudent.html.twig', $data);


    }

    public function addAction(Request $request, Application $app)
    {
        $etudiant = new Student();
        $form = $app['form.factory']->create(new StudentType(), $etudiant);
        if ($request->isMethod('POST')) {
            $form->bind($request);
            $name = $etudiant->getCategory();
            $category = $app['repository.category']->selectOneByNameCateg($name);


            if ($form->isValid()&& !empty($category) ) {
                if (preg_match('/^[a-zA-Z\s]+$/', $etudiant->getName())&& preg_match('/^[a-zA-Z\s]+$/', $etudiant->getFirstName())){                
                $app['repository.etudiant']->save($etudiant);
                $message = 'L\' étudiant ' . $etudiant->getName() . ' à été ajouté.';
                $app['session']->getFlashBag()->add('success', $message);
                // Redirect to the edit page.
                $redirect = $app['url_generator']->generate('admin_etudiant_add', array('etudiant' => $etudiant->getStudentId()));
//                return $app->redirect($redirect);
                }else{
                    $message = 'Les champs de noms et prénoms ne doivent pas comportés de caractère spéciaux ni de chiffres';
                    $app['session']->getFlashBag()->add('error', $message);
                    $redirect = $app['url_generator']->generate('admin_etudiant_add', array('etudiant' => $etudiant->getStudentId()));
                    return $app->redirect($redirect);
                }
            }else {
               $message = 'La classe inscrite n\'existe pas.';
               $app['session']->getFlashBag()->add('error', $message);

            }
        }
        $data = array(
            'form' => $form->createView(),
            'title' => 'Ajout d\'un étudiant',
        );
        return $app['twig']->render('form.html.twig', $data);
    }

    public function editAction(Request $request, Application $app)
    {
        $etudiant = $request->attributes->get('etudiant');
        if (!$etudiant) {
            $app->abort(404, 'La requête étudiant n\a pas été trouvé.');
        }
        $form = $app['form.factory']->create(new StudentType(), $etudiant);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
            $name = $etudiant->getCategory();
            $category = $app['repository.category']->selectOneByNameCateg($name);
            if ($form->isValid()&& !empty($category)) {
                if (preg_match('/^[a-zA-Z\s]+$/', $etudiant->getName())&& preg_match('/^[a-zA-Z\s]+$/', $etudiant->getFirstName())){                
                $app['repository.etudiant']->save($etudiant);
                $message = 'L\'étudiant à été modifié !.';
                $app['session']->getFlashBag()->add('success', $message);
                }else{
                    $message = 'Les champs de noms et prénoms ne doivent pas comportés de caractère spéciaux ni de chiffres';
                    $app['session']->getFlashBag()->add('error', $message);
                    $redirect = $app['url_generator']->generate('admin_etudiant_add', array('etudiant' => $etudiant->getStudentId()));
                    return $app->redirect($redirect);
                }
            }else {
               $message = 'La classe inscrite n\'existe pas.';
               $app['session']->getFlashBag()->add('error', $message);
            }
        }
        $data = array(
            'form' => $form->createView(),
            'title' => 'Edition d\'un étudiant',
        );
        return $app['twig']->render('form.html.twig', $data);
    }

    public function deleteAction(Request $request, Application $app)
    {
        $etudiant = $request->attributes->get('etudiant');
        if (!$etudiant) {
            $app->abort(404, 'The requested student was not found.');
        }
        $app['repository.etudiant']->delete($etudiant);
        return $app->redirect($app['url_generator']->generate('admin_etudiants'));
    }


    public function orderBy(Request $request, Application $app)
    {
        $limit = 10;
        $total = $app['repository.etudiant']->getCount();
        $numPages = ceil($total / $limit);
        $currentPage = $request->query->get('page', 1);
        $offset = ($currentPage - 1) * $limit;
        $etudiants = $app['repository.etudiant']->orderByCateg($limit, $offset);
        $data = array(
            'etudiants' => $etudiants,
            'currentPage' => $currentPage,
            'numPages' => $numPages,
            'here' => $app['url_generator']->generate('admin_etudiants'),
        );


        return $app['twig']->render('adminStudent.html.twig', $data);
    }


}