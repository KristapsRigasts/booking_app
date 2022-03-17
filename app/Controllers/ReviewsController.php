<?php

namespace App\Controllers;

use App\Connection;
use App\Exceptions\FormValidationException;
use App\Redirect;
use App\Validation\FormValidator;

class ReviewsController
{
    public function store (array $vars)
    {
        try {
            $validator =(new FormValidator($_POST, [
                'review' => ['required']
            ]));
            $validator->passes();

            Connection::connection()
                ->insert('reviews', [
                    'apartment_id' => $vars['id'],
                    'user_id' => $_SESSION['userid'],
                    'review' => $_POST['review'],
                ]);

            return new Redirect('/apartments/'. $vars['id']);

        } catch (FormValidationException $exception) {

            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['inputs'] = $_POST;

            return new Redirect('/apartments/'. $vars['id']);
        }
    }

    public function delete(array $vars)
    {
        Connection::connection()
            ->delete('reviews', ['id' => (int)$vars['uid']]);

        return new Redirect("/apartments/{$vars['id']}");
    }

}