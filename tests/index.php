<?php

require __DIR__ . '/../vendor/autoload.php';

use Chaos\Http\Http;

$http = new Http();

$response = $http->get('https://jsonplaceholder.typicode.com/todos/1');
if (!$response->ok()) {
    echo "Request failed: " . $response->error();
    exit;
}

$test = $_GET['test'] ?? "full";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chaos Http Testing</title>
    <style>
        html,
        body {
            width: 100dvw;
            overflow-x: hidden;
        }
    </style>
</head>

<body>

    <pre>
        <?php

        switch ($test) {
            case "json":
                echo "STRING RESPONSE:<br>";
                var_dump($response->json());
                break;
            case "object":
                echo "OBJECT RESPONSE:<br>";
                var_dump($response->object());
                break;
            case "array":
                echo "ARRAY RESPONSE:<br>";
                var_dump($response->array());
                break;
            // ? ADD ADDITIONAL TESTS HERE ? //
            default:
                echo "FULL RESPONSE:<br>";
                var_dump($response);
        }

        ?>
    </pre>

</body>

</html>