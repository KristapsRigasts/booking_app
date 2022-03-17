<?php

namespace App\Controllers;

use App\Connection;
use App\DBConnection;
use App\Exceptions\FormValidationException;
use App\Redirect;
use App\Session;
use App\Validation\Errors;
use App\Validation\FormValidator;
use App\View;

class UsersController

{
    public function home(): View
    {
            return new View('Home/home', ['userName'=> $_SESSION['name'], 'userId' =>$_SESSION['userid']]);
    }

    public function index(): View
    {
        return  new View('Users/index');
    }

        public function show(array $vars): View
    {
        return  new View('Users/show', [
            'id' => $vars['id']
        ]);
    }

    public function register(): View
    {
        return new View('Users/register', [
                'inputs' => $_SESSION['inputs'] ?? [],
                'errors' => Errors::getAll()]);

    }

    public function store():Redirect
    {

        try {
            $validator =(new FormValidator($_POST, [
                'name' => ['required'],
                'surname' => ['required'],
                'email' => ['required'],
                'password' => ['required'],
            ]));
            $validator->passes();

            $userQueryCheck = Connection::connection()
                ->createQueryBuilder()
                ->select('*')
                ->from('users')
                ->where('email = ?')
                ->setParameter(0, $_POST['email'])
                ->executeQuery()
                ->fetchAssociative();

            if ($userQueryCheck != false) {
                return new Redirect('/users/register?error=emailalreadytaken');
            }

            $passwordHashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
            Connection::connection()
                ->insert('users', [
                    'name' => $_POST['name'],
                    'surname' => $_POST['surname'],
                    'email' => $_POST['email'],
                    'password' => $passwordHashed
                ]);

            return new Redirect('/');

        } catch (FormValidationException $exception) {

            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['inputs'] = $_POST;

            return new Redirect('/users/register');
        }
    }

    public function logIn(): View
    {
        return new View('Users/login',[
            'errors' => Errors::getAll(),
            'inputs' => $_SESSION['inputs'] ?? []
        ]);
    }

    public function validateLogIn(): Redirect
    {
        try {
            $validator =(new FormValidator($_POST, [
                'email' => ['required'],
                'password' => ['required']
            ]));
            $validator->passes();

            $userQuery = Connection::connection()
                ->createQueryBuilder()
                ->select('*')
                ->from('users')
                ->where('email = ?')
                ->setParameter(0, $_POST['email'])
                ->executeQuery()
                ->fetchAssociative();

            if ($userQuery === false) {
                return new Redirect('/users/login?error=usernotfound');
            }
            else{
                $checkPwd = password_verify($_POST['password'], $userQuery['password']);

                if($checkPwd == false)
                {
                    return new Redirect('/users/login?error=wrongpassword');
                }
                else
                {
                    $_SESSION["userid"] = $userQuery['id'];
                    $_SESSION["name"] = $userQuery['name'];
                    $_SESSION["surname"] = $userQuery['surname'];

                    return new Redirect('/');
                }
            }} catch (FormValidationException $exception) {

            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['inputs'] = $_POST;

            return new Redirect('/users/login');
        }
    }

    public function logOut(): Redirect
    {
        unset ($_SESSION["userid"]);
        unset ($_SESSION["name"]);
        unset ($_SESSION["surname"]);
        return new Redirect('/');
    }

}