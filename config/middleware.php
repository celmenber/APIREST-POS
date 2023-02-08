<?php
use Slim\App;


return function (App $app)
{
  $app->getContainer()->get('settings');
  $app->add(
      new \Tuupola\Middleware\JwtAuthentication([
          "secure" => false,
          "attribute" => "jwt",
          "ignore"=>["/api/auth/login"],
          "secret"=>\App\Interfaces\SecretKeyInterface::JWT_SECRET_KEY,
          "error"=>function($response,$arguments)
          {
              $data["success"] = false;
              $data["response"]=$arguments["message"];
              $data["status_code"]= "401";

              return $response->withHeader("Content-type","application/json")
                  ->getBody()->write(json_encode($data,JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ));
          }
      ])
  );
  
 $app->addRoutingMiddleware();
 

  $app->add(function ($request, $handler) {
            $response = $handler->handle($request);
            return $response
                    ->withHeader('Access-Control-Allow-Origin', '*')
                    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        });

  $app->addErrorMiddleware(true,true,true);
};