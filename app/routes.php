<?php

use Slim\App;
use App\Http\Controllers\InitialController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app) {
    // $app->get('/home/{name}', function (Request $request, Response $response, $parameters) {
    //     $name = 'Clean Code Studio';

    //     return view($response, 'auth.home', compact('name'));
    // });

    // $app->get('/', [WelcomeController::class, 'index']);
    // $app->get('/{name}/{id}', [WelcomeController::class, 'show']);
     $app->post('/api/v1/init', [InitialController::class, 'index']);
     $app->post('/api/v1/wallet', [InitialController::class, 'enableWallet']);
     $app->post('/api/v1/wallet/deposits', [InitialController::class, 'deposits']);
     $app->post('/api/v1/wallet/withdrawals', [InitialController::class, 'withdrawals']);
     $app->get('/api/v1/wallet', [InitialController::class, 'checkBallance']);
     
};
