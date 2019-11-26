<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
$app->add(new \Tuupola\Middleware\JwtAuthentication([
    "path" => "/apis", /* or ["/api", "/admin"] */
    "attribute" => "decoded_token_data",
    "secret" => "IajxETyHgKbWADSfG9pBmJuFkwlsZrtC",
    "algorithm" => ["HS256"],
    "error" => function ($response, $arguments) {
        $data["status"] = "error";
        $data['type'] = "AUTHORIZATION_ERROR";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

]));