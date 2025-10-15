<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class CustomSwaggerController extends Controller
{
    public function api(): Response
    {
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MiniBlog API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui.css" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin:0;
            background: #fafafa;
        }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                dom_id: "#swagger-ui",
                url: "' . url('/docs/api-docs.json') . '",
                operationsSorter: null,
                configUrl: null,
                validatorUrl: null,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                docExpansion: "none",
                deepLinking: true,
                filter: true,
                persistAuthorization: true,
                supportedSubmitMethods: ["get", "post", "put", "delete", "patch"],
                onComplete: function() {
                    console.log("Swagger UI loaded");
                },
                requestInterceptor: function(request) {
                    return request;
                }
            });
            window.ui = ui;
        }
    </script>
</body>
</html>';

        return response($html)->header('Content-Type', 'text/html');
    }
}
