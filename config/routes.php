<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\ProveedorController;

return function (App $app)
{
    $app->group("/api/auth",function($app)
    {
        $app->post("/login",[AuthController::class,"Login"]);
    });

    $app->group("/api/users",function($app)
    {
         $app->get("/view-user",[UserController::class, 'viewUser']);
         $app->get("/view-user/{id}",[UserController::class,'viewUserId']);

         $app->get("/view-user-roll",[UserController::class,'viewUserRoll']);
         $app->get("/view-user-roll/{id}",[UserController::class,'viewUserRollid']);

         $app->get("/view-user-auditoria",[UserController::class,'viewUserAuditoria']);
         $app->get("/view-user-auditoria/{id}",[UserController::class,'viewUserAuditoriaid']);

         $app->get("/view-user-permiso",[UserController::class,'viewUserPermiso']);
         $app->get("/view-user-permiso/{id}",[UserController::class,'viewUserPermisoid']);

         $app->get("/view-user-menu",[UserController::class,'viewUserMenu']);
         $app->get("/view-user-menu/{id}",[UserController::class,'viewUserMenuid']);

         $app->get("/view-user-menu-usuario",[UserController::class,'viewUserMenuUsuario']);
         $app->get("/view-user-menu-usuario/{id}",[UserController::class,'viewUserMenuUsuarioid']);

         $app->get("/view-user-acceso",[UserController::class,'viewUserAcceso']);
         $app->get("/view-user-acceso/{id}",[UserController::class,'viewUserAccesoid']);
         
         $app->get("/view-user-gt",[UserController::class,'viewUserGt']);
         $app->get("/view-user-gt/{id}",[UserController::class,'viewUserGtid']);

         $app->post('/create-user',[UserController::class,'createUsers']);
         $app->post('/create-user-auditoria',[UserController::class,'createuserAuditoria']);
         $app->post('/create-user-permiso',[UserController::class,'createuserPermiso']);
         $app->post('/create-user-menu',[UserController::class,'createuserMenu']);
         $app->post('/create-user-acceso',[UserController::class,'createuserAcceso']);
         $app->post('/create-user-gt',[UserController::class,'createuserGt']);
         $app->post('/create-user-menu-usuario',[UserController::class,'createuserMenuUsuario']);

         $app->put("/edit-user/{id}",[UserController::class,'editUsers']);
         $app->put('/edit-user-auditoria/{id}',[UserController::class,'editUserAuditoria']);
         $app->put('/edit-user-permiso/{id}',[UserController::class,'editUserPermiso']);
         $app->put('/edit-user-menu/{id}',[UserController::class,'editUserMenu']);
         $app->put('/edit-user-gt/{id}',[UserController::class,'editUserGt']);
         $app->put('/edit-user-menu-usuario/{id}',[UserController::class,'editUserMenuUsuario']);
        // $app->delete('/delete-user/{id}',[UserController::class,'deleteUsers']);
    });

};