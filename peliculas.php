<?php
    require_once 'class/conexion/conexion.php';
    require_once 'class/respuestas.class.php';
    require_once 'class/peliculas.class.php';

    // Evitar error de CORS
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: *");
    header("Access-Control-Allow-Headers: Content-Type");

    // Crear una instancia de la clase Respuestas
    $_respuestas = new Respuestas();

    // Función para responder con JSON y código de estado HTTP
    function respuestaJson($statusCode, $response) {
        http_response_code($statusCode);
        echo json_encode($response);
        exit();
    }

    // Verificar el método de la solicitud
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        $peliculas = new Peliculas();
        // Obtener películas por página
        if (isset($_GET['page'])) {
            $pagina = $_GET['page'];
            $datos = $peliculas->listarPeliculas($pagina);
        // Obtener película por su ID
        } elseif (isset($_GET['id'])) {
            $id = $_GET['id'];
            $datos = $peliculas->obtenerPelicula($id);
        // Buscar película por una parte del nombre
        } elseif (isset($_GET['buscar'])) {
            $nombre = $_GET['buscar'];
            $datos = $peliculas->buscarPelicula($nombre);
        // Obtener todas las películas sin paginar
        } else {
            $datos = $peliculas->listarPeliculasSinPaginar(1);
        }
        // Retornar los datos
        respuestaJson(200, $datos);
    } elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
        // Insertar una nueva película
        $peliculas = new Peliculas();
        $postBody = file_get_contents("php://input");
        $datosArray = $peliculas->insertarPelicula($postBody);
        // Retornar la respuesta
        respuestaJson($datosArray['statusCode'], $datosArray['response']);
    } elseif ($_SERVER['REQUEST_METHOD'] === "PUT") {
        // Actualizar una película
        $peliculas = new Peliculas();
        $postBody = file_get_contents("php://input");
        $datosArray = $peliculas->actualizarPelicula($postBody);
        // Retornar la respuesta
        respuestaJson($datosArray['statusCode'], $datosArray['response']);
    } elseif ($_SERVER['REQUEST_METHOD'] === "DELETE") {
        // Eliminar una película por su ID
        $peliculas = new Peliculas();
        $postBody = file_get_contents("php://input");
        $datosArray = $peliculas->eliminarPelicula($postBody);
        // Retornar la respuesta
        respuestaJson($datosArray['statusCode'], $datosArray['response']);
    } else {
        // Método no permitido
        $datosArray = $_respuestas->error_405();
        // Retornar la respuesta
        respuestaJson($datosArray['statusCode'], $datosArray['response']);
    }
?>
