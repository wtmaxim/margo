<?php

namespace Margo\Controller;

use Margo\Entity\Category;
use Margo\Entity\Subject;
use Margo\Form\Type\CategoryType;
use Margo\Form\Type\SubjectType;

use Silex\Application;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Margo\Test\TestEntity;

class AdminCategoryController {

    public function indexAction(Request $request, Application $app)
    {
        // Perform pagination logic.
        $limit = 100;
        $total = $app['repository.category']->getCount();
        $numPages = ceil($total / $limit);
        $currentPage = $request->query->get('page', 1);
        $offset = ($currentPage - 1) * $limit;
        $classes = $app['repository.category']->findAll($limit, $offset);
        $data = array(
            'classes' => $classes,
            'currentPage' => $currentPage,
            'numPages' => $numPages,
            'here' => $app['url_generator']->generate('admin_categories'),
        );
        return $app['twig']->render('adminCategory.html.twig', $data);
    }
    

    public function addAction(Request $request, Application $app) {
        $category = new Category();
        $form = $app['form.factory']->create(new CategoryType(), $category);
        if ($request->isMethod('POST')) {
            $form->bind($request);
            $nameFormation = $category->getFormation();
            $nameCategory = $category->getCategName();
            $category2 = $app['repository.category']->selectOneByNameCateg($nameCategory);
            $formation = $app['repository.formation']->selectOneByNameFormation($nameFormation);
            if ($form->isValid() && !empty($formation) ) {
                if(empty($category2)){
                    $app['repository.category']->save($category);
                    $message = 'La classe ' . $category->getCategName() . ' à été ajouté.';
                    $app['session']->getFlashBag()->add('success', $message);
                    // Redirect to the edit page.
                    $redirect = $app['url_generator']->generate('admin_category_add', array('category' => $category->getCategId()));
                    return $app->redirect($redirect);
                }else{
                $message = 'La classe inscrite existe déjà.';
                $app['session']->getFlashBag()->add('error', $message);
                }
            } else {
                $message = 'La formation inscrite n\'existe pas.';
                $app['session']->getFlashBag()->add('error', $message);
            }
        }
        $data = array(
            'form' => $form->createView(),
            'title' => 'Ajout d\'une classe',
        );
        return $app['twig']->render('form.html.twig', $data);
    }

    public function editAction(Request $request, Application $app) {
        $classe = $request->attributes->get('classe');
        if (!$classe) {
            $app->abort(404, 'La  classe n\'a pas été trouvé.');
        }
        $form = $app['form.factory']->create(new CategoryType(), $classe);
        if ($request->isMethod('POST')) {
            $form->bind($request);
            $name = $classe->getFormation();
            $formation = $app['repository.formation']->selectOneByNameFormation($name);
            $nameCategory = $classe->getCategName();
            $category2 = $app['repository.category']->selectOneByNameCateg($nameCategory);
            if ($form->isValid()&& !empty($formation)) {
                if(empty($category2)){
                $app['repository.category']->save($classe);
                $message = 'La classe à été modifié !.';
                $app['session']->getFlashBag()->add('success', $message);
                }else{
                $message = 'La classe inscrite existe déjà.';
                $app['session']->getFlashBag()->add('error', $message);
                }
            }else {
                $message = 'La formation inscrite n\'existe pas.';
                $app['session']->getFlashBag()->add('error', $message);
            }
        }
        $data = array(
            'form' => $form->createView(),
            'title' => 'Edition d\'une classe',
        );
        return $app['twig']->render('form.html.twig', $data);
    }

    public function deleteAction(Request $request, Application $app) {
        $classe = $request->attributes->get('classe');
        if (!$classe) {
            $app->abort(404, 'La classe n\'a pas été trouvé.');
        }

        $student = $app['repository.etudiant']->selectOneByNameCateg($classe->getCategName());
        $subject = $app['repository.subject']->selectOneByNameCateg($classe->getCategName());
        if (!empty($student)) {
            $message = 'Impossible de supprimer : un ou plusieurs étudiants sont inscrits à cette classe';
            $app['session']->getFlashBag()->add('error', $message);
        } else if (!empty($subject)) {
            $message = 'Impossible de supprimer : la matière est suivi par un ou plusieurs classes';
            $app['session']->getFlashBag()->add('error', $message);
        } else {
            $app['repository.category']->delete($classe);
            $message = 'Suppression du cours';
            $app['session']->getFlashBag()->add('success', $message);
        }

        return $app->redirect($app['url_generator']->generate('admin_categories'));
    }

}
