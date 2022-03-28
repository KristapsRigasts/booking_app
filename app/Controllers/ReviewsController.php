<?php

namespace App\Controllers;

use App\Exceptions\FormValidationException;
use App\Redirect;
use App\Services\Review\Delete\DeleteReviewRequest;
use App\Services\Review\Delete\DeleteReviewService;
use App\Services\Review\Store\StoreReviewRequest;
use App\Services\Review\Store\StoreReviewService;
use App\Validation\FormValidator;

class ReviewsController
{
    public function store(array $vars): Redirect
    {
        try {
            $validator = (new FormValidator($_POST, [
                'review' => ['required']
            ]));
            $validator->passes();

            $service = new StoreReviewService();
            $service->execute(new StoreReviewRequest($_SESSION['userid'], $vars['id'], $_POST['review']));

            return new Redirect('/apartments/' . $vars['id']);

        } catch (FormValidationException $exception) {

            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['inputs'] = $_POST;

            return new Redirect('/apartments/' . $vars['id']);
        }
    }

    public function delete(array $vars): Redirect
    {
        $service = new DeleteReviewService();
        $service->execute(new DeleteReviewRequest((int)$vars['uid']));

        return new Redirect("/apartments/{$vars['id']}");
    }
}