<?php

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); // Redirige al login si no hay sesión
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión del Blog</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- site favicon -->
	<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <script src="js/modernizr-2.6.2.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/ajax-blog_crud.js"></script>
    <style>
        /* Ajustes generales */
        .navbar {
            display: flex;
            /* Usamos flexbox para alinear los elementos */
            justify-content: flex-end !important;
            /* Empuja todo hacia la derecha */
            align-items: center;
            /* Centra verticalmente */
            padding: 0 0px;
            padding-bottom: 25px;
            /* Espaciado interno */
            margin-top: 0;
        }

        .navbar-brand {
            color: #848484 !important; /* Color del texto */
            font-size: 1.5rem; /* Tamaño del texto */
            font-weight: bold; /* Negrita */
            margin-right: 0; /* Evitar márgenes innecesarios */
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.400);  
            border-radius: 5%;
            padding-top: 6px;
        }

        .navbar-brand:hover {
            color: black !important;
            /* Cambiar color al pasar el ratón */
            transition: color 0.3s ease-in-out;
        }


        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .centered-container {
            flex: 0 1 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem 0;
        }

        #fh5co-footer {
            text-align: center;
            padding: 1em 0;
            width: 100%;
            margin-top: auto;
        }

        .form-container {
            max-width: 950px;
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.15);
            margin: 0.5rem;
        }

        .form-control {
            height: 3rem;
            font-size: 1.5rem;
            border: none;
            border-bottom: 1px solid #ccc;
            border-radius: 0;
            box-shadow: none;
            margin-bottom: 20px;
        }

        .textArea {
            width: 100%;
            max-width: 100%;
            min-height: 60px;
            max-height: 200px;
            resize: vertical;
            font-size: 1.2rem;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-custom {
            padding: 0.5rem 2.5rem;
            font-size: 1.5rem;
            border-radius: 8px;
            background-color: #33CCCC;
            color: white;
            border: none;
        }

        .btn-custom:hover {
            background-color: #009ec3;
        }

        .button-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        #tablaBlog {
            margin-top: 20px;
            max-height: 300px;
            overflow-x: auto;
            overflow-y: auto;
            width: 100%;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: #fff;
            table-layout: fixed;
            /* Evita que las celdas crezcan sin control */
            word-wrap: break-word;
            /* Ajusta texto largo en las celdas */
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
            font-size: 14px;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .btn-editar,
        .btn-eliminar {
            padding: 5px 10px;
            margin: 2px;
            font-size: 12px;
        }

        .imagen-miniatura {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
        }



        /* Responsividad */
        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
            }

            .form-control {
                font-size: 1rem;
                height: auto;
            }

            .btn-custom {
                font-size: 1rem;
                padding: 0.5rem 1.5rem;
            }

            .button-group {
                gap: 1rem;
            }

            .table th,
            .table td {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>


    <header class="centered-container">
        <h1>Bienvenido : <?php echo htmlspecialchars($_SESSION['usuario']); ?></h1>
        
    </header>
    <h4  style="color: #848484; display: flex; justify-content:center;">Estas en : Edición de Blogs</h4>

    <div class="centered-container">
        <div class="form-container">
            <!-- Menú de navegación con un solo elemento -->
            <nav class="navbar navbar-expand-lg d-flex justify-content-end">
                <div class="navbar-brand">
                <a  href="#" style="margin-right: 20px;" id="exportNewsletter">
                <img src="images/email.gif" alt="GIF animado" style="width: 40px;
    height: 40px; margin-top: 0;">Get Email</a>|
    <a  href="upload.php" >
                <img src="images/walking.gif" alt="GIF animado" style="width: 40px;
    height: 40px; margin-top: 0; ">Go Obras</a>
                </div>
            </nav>
            <form id="blogUploadForm" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="alert alert-success" role="alert" style="text-align: center; display:none;">Success
                    </div>
                </div>
                <input type="hidden" id="id" name="id" value="0">
                <div class="form-group">
                    <label for="url">URL del artículo:</label>
                    <input type="url" id="url" name="url" class="form-control" required>
                    <input type="hidden" id="original_url" name="original_url">
                </div>
                <div class="form-group" >
                    <label for="titulo">Título:</label>
                    <textarea class="textArea" id="titulo" name="titulo" maxlength="100"></textarea>
                    <input type="hidden"  id="original_textArea" name="original_textArea">
                </div>
                <div class="form-group" >
                    <label for="extracto">Extracto:</label>
                    <textarea class="textArea" id="extracto" name="extracto" maxlength="216"></textarea>
                    <input type="hidden" id="original_extracto" name="original_extracto">
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" class="form-control">
                    <input type="date" style="display:none;" id="original_fecha" name="original_fecha">
                </div>
                <div class="form-group">
                    <label for="imagen">Subir imagen:</label>
                    <input type="file" id="imagen" name="imagen" class="form-control" accept="image/png, image/jpeg"
                        required>
                    <small id="current-file" style="display: none; margin-top: 5px; color: #555;">Archivo actual:
                        Ninguno</small>
                        <input type="hidden" id="current-file-hidden" name="current-file" value="Ninguno">
                </div>
                <div class="form-group">
                    <input type="checkbox" id="relevante" name="relevante">
                    <label for="relevante">Relevante</label>
                    <input type="hidden" id="original_relevante" name="original_relevante">
                </div>
                <div class="button-group">
                    <button type="button" id="subir" class="btn-custom">Subir</button>
                    <button type="button" id="editar" class="btn-custom">Editar existente</button>
                    <button type="button" id="limpiar" class="btn-custom">Limpiar campos</button>
                    <button type="button" id="cerrarSesion" class="btn-custom"
                        onclick="window.location.href='index.php'">Cerrar sesión</button>

                </div>
                <div id="tablaBlog" style="margin-top: 20px; display: none;">
                    <input type="text" id="searchInput" placeholder="Buscar en la tabla...">
                    <button type="button" id="ocultarTabla" class="btn-custom">Ocultar tabla</button>
                    <div style="overflow-x: auto; width: 100%;">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Título</th>
                                    <th>Extracto</th>
                                    <th>URL</th>
                                    <th>Fecha</th>
                                    <th>Relevante</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Las filas se llenarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <footer id="fh5co-footer">
        <p>© Copyright © Noel Dobarganes all rights reserved.</p>
    </footer>
</body>

</html>