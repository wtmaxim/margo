<?php

// Register route converters.
// Each converter needs to check if the $id it received is actually a value,
// as a workaround for https://github.com/silexphp/Silex/pull/768.
$app['controllers']->convert('etudiant', function ($id) use ($app) {
    if ($id) {
        return $app['repository.etudiant']->find($id);
    }
});
$app['controllers']->convert('prof', function ($id) use ($app) {
    if ($id) {
        return $app['repository.prof']->find($id);
    }
});

$app['controllers']->convert('user', function ($id) use ($app) {
    if ($id) {
        return $app['repository.user']->find($id);
    }
});
$app['controllers']->convert('classe', function ($id) use ($app) {
   if ($id) {
       return $app['repository.category']->find($id);
   }
});
$app['controllers']->convert('matiere', function ($id) use ($app) {
   if ($id) {
       return $app['repository.subject']->find($id);
   }
});
$app['controllers']->convert('formation', function ($id) use ($app) {
    if ($id) {
        return $app['repository.formation']->find($id);
    }
});



// Register routes.
$app->get('/', 'Margo\Controller\IndexController::indexAction')
    ->bind('homepage');
$app->get('/etudiants', 'Margo\Controller\StudentController::indexAction')
    ->bind('etudiants');
$app->get('/classes', 'Margo\Controller\CategoryController::indexAction')
    ->bind('categories');
$app->get('/profs', 'Margo\Controller\TeacherController::indexAction')
    ->bind('teachers');
$app->get('/cours', 'Margo\Controller\SubjectController::indexAction')
    ->bind('subject');
$app->get('/formations', 'Margo\Controller\FormationController::indexAction')
    ->bind('formations');
// Login route
$app->match('/login', 'Margo\Controller\UserController::loginAction')
    ->bind('login');
$app->get('/logout', 'Margo\Controller\UserController::logoutAction')
    ->bind('logout');

// Admin root
$app->get('/admin', 'Margo\Controller\AdminController::indexAction')
    ->bind('admin');

// Admin route eleve
$app->get('/admin/etudiants', 'Margo\Controller\AdminStudentController::indexAction')
    ->bind('admin_etudiants');
$app->match('/admin/etudiant/add', 'Margo\Controller\AdminStudentController::addAction')
    ->bind('admin_etudiant_add');
$app->match('/admin/etudiant', 'Margo\Controller\AdminStudentController::orderBy')
    ->bind('admin_etudiant_orderBy');
$app->match('/admin/etudiants/{etudiant}/edit', 'Margo\Controller\AdminStudentController::editAction')
    ->bind('admin_etudiant_edit');
$app->match('/admin/etudiants/{etudiant}/delete', 'Margo\Controller\AdminStudentController::deleteAction')
    ->bind('admin_etudiant_delete');
//admin route prof
$app->get('/admin/teachers', 'Margo\Controller\AdminTeacherController::indexAction')
    ->bind('admin_teachers');
$app->match('/admin/teachers/add', 'Margo\Controller\AdminTeacherController::addAction')
    ->bind('admin_teacher_add');
$app->match('/admin/teachers/{prof}/edit', 'Margo\Controller\AdminTeacherController::editAction')
    ->bind('admin_teacher_edit');
$app->match('/admin/teachers/{prof}/delete', 'Margo\Controller\AdminTeacherController::deleteAction')
    ->bind('admin_teacher_delete');

//admin route utilisateurs
$app->get('/admin/users', 'Margo\Controller\UserController::indexAction')
    ->bind('admin_users');
$app->match('/admin/users/add', 'Margo\Controller\UserController::addAction')
    ->bind('admin_user_add');
$app->match('/admin/users/{user}/edit', 'Margo\Controller\UserController::editAction')
    ->bind('admin_user_edit');
$app->match('/admin/users/{user}/delete', 'Margo\Controller\UserController::deleteAction')
    ->bind('admin_user_delete');

//admin route classe
$app->get('/admin/categories', 'Margo\Controller\AdminCategoryController::indexAction')
    ->bind('admin_categories');
$app->match('/admin/categories/add', 'Margo\Controller\AdminCategoryController::addAction')
    ->bind('admin_category_add');
$app->match('/admin/categories/{classe}/edit', 'Margo\Controller\AdminCategoryController::editAction')
    ->bind('admin_category_edit');
$app->match('/admin/categories/{classe}/delete', 'Margo\Controller\AdminCategoryController::deleteAction')
    ->bind('admin_category_delete');

//admin route matière
$app->get('/admin/cours', 'Margo\Controller\AdminSubjectController::indexAction')
    ->bind('admin_subjects');
$app->match('/admin/cours/add', 'Margo\Controller\AdminSubjectController::addAction')
    ->bind('admin_subject_add');
$app->match('/admin/subject/{matiere}/edit', 'Margo\Controller\AdminSubjectController::editAction')
    ->bind('admin_subject_edit');
$app->match('/admin/cours/{matiere}/delete', 'Margo\Controller\AdminSubjectController::deleteAction')
    ->bind('admin_subject_delete');
// admin route formation
$app->get('/admin/formations', 'Margo\Controller\AdminFormationController::indexAction')
    ->bind('admin_formations');
$app->match('/admin/formation/add', 'Margo\Controller\AdminFormationController::addAction')
    ->bind('admin_formation_add');
$app->match('/admin/formation/{formation}/edit', 'Margo\Controller\AdminFormationController::editAction')
    ->bind('admin_formation_edit');
$app->match('/admin/formation/{formation}/delete', 'Margo\Controller\AdminFormationController::deleteAction')
    ->bind('admin_formation_delete');

//page404
$app->error(function (\Exception $e, $code) use ($app) {
    switch ($code) {
        case 404:
            return $app['twig']->render('/Exception/error404.html.twig');
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }
});
