<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA; // Naya Attribute import kiya

#[OA\Info(version: "1.0.0", description: "API Documentation for Mobile App and Frontend", title: "SP MLM Platform API")]
#[OA\Server(url: L5_SWAGGER_CONST_HOST, description: "Main Server")]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Login with Mobile and Password to get the authentication token"
)]
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}