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
    <title>Subir Obra</title>
    <!-- CSS links -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- site favicon -->
	<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <!-- Modernizr JS -->
    <script src="js/modernizr-2.6.2.min.js"></script>
    <!-- Incluye jQuery  -->
    <script src="js/jquery.min.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            /* Elimina el margen del body */
            padding: 0;
            /* Elimina el padding del body */
        }

        .centered-container {
            flex: 0 1 auto;
            /* Cambiado de flex: 1 para que no tome espacio extra */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem 0;
            /* Añade un padding arriba y abajo */
        }

        #fh5co-footer {
            text-align: center;
            /* Centra el texto del footer */
            padding: 1em 0;
            /* Reduce el padding del footer */
            width: 100%;
            margin-top: auto;
            /* Esto empujará el footer hacia abajo solo lo necesario */
        }

        /* Ajusta el contenedor del footer */
        #fh5co-footer .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Centra el texto del footer */
        #fh5co-footer .row {
            display: flex;
            justify-content: center;
            text-align: center;
        }

        /* Ajusta el contenedor del formulario */
        .form-container {
            max-width: 950px;
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.15);
            margin: 0.5rem;
            /* Añade un margen alrededor */
        }

        /* Estilos para el texto de bienvenida */
        .welcome-text {
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Estilos de los campos de entrada */
        .form-control {
            height: 3rem;
            font-size: 1.5rem;
            border: none;
            border-bottom: 1px solid #ccc;
            border-radius: 0;
            box-shadow: none;
            margin-bottom: 20px;
        }

        .form-control::placeholder {
            font-size: 1.5rem;
        }

        .form-control:focus {
            border-color: #00c6ff;
            box-shadow: 0 1px 0 0 #00c6ff;
        }

        /* Estilos para los botones */
        .btn-custom {
            padding: 0.5rem 2.5rem;
            font-size: 1.5rem;
            border-radius: 8px;
            background-color: #33CCCC;
            color: white;
            text-align: center;
            border: none;
        }

        .btn-custom:hover {
            background-color: #009ec3;
            color: white;
        }

        /* Centrado completo de los botones en el contenedor del formulario */
        .button-group {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        /* Centrado vertical y horizontal completo */
        .centered-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .row {
            margin: 0;
            /* Elimina los márgenes de las filas */
        }

        .col-md-6 {
            padding: 0 10px;
            /* Reduce el padding de las columnas */
        }

        .mb-4 {
            margin-bottom: 1rem !important;
            /* Reduce el margen inferior */
        }

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

        /* Estilos responsive */
        @media (max-width: 768px) {

            .form-container {
                max-width: 95%;
                padding: 1.5rem;
                margin: 1rem;
            }

            .form-container {
                max-width: 95%;
                padding: 1.5rem;
                margin: 1rem;
            }

            .form-control {
                height: 2.5rem;
                font-size: 1.2rem;
            }

            .form-control::placeholder {
                font-size: 1.2rem;
            }

            .btn-custom {
                padding: 0.4rem 2rem;
                font-size: 1.2rem;
            }

            .welcome-text {
                font-size: 1.2rem;
            }

            .centered-container {
                padding: 1rem 0;
                /* Reduce el padding en móviles */
            }

            .form-container {
                margin: 0.5rem;
                /* Reduce el margen en móviles */
                padding: 1.5rem;
            }

            #fh5co-footer {
                padding: 1.5em 0;
                /* Reduce aún más el padding en móviles */
            }

            #categoria {
                font-size: 1.2rem !important;
            }

            #subcategoria select {
                font-size: 1.2rem !important;
                ;
            }


        }

        /* Estilos para el footer */
        .footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            position: relative;
            width: 100%;
            bottom: 0;
            margin-top: 2rem;
        }

        .footer-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-links {
            margin: 10px 0;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        .footer-links a:hover {
            color: #33CCCC;
        }

        .footer-text {
            text-align: center;
            margin: 10px 0;
        }
    </style>
    <script>
        // Mostrar subcategoría solo si la categoría es "Pintura"
        function toggleSubcategoria() {
            var categoria = document.getElementById("categoria").value;
            var subcategoria = document.getElementById("subcategoria").querySelector("select");

            if (categoria === "pintura" ) {
                subcategoria.parentElement.style.display = "block";
                subcategoria.setAttribute("required", "required");
            } else {
                subcategoria.parentElement.style.display = "none";
                subcategoria.removeAttribute("required");
            }
        }

        // Función de Búsqueda en la tabla 
        $(document).ready(function () {
            // Función de búsqueda en la tabla
            $("#searchInput").on("keyup", function () {
                // Verificar si la tabla está visible
                if ($("#tablaEdicion").is(":visible")) {
                    var value = $(this).val().toLowerCase(); // Obtener el valor del campo de búsqueda
                    $("#tablaEdicion tbody tr").filter(function () {
                        // Mostrar/ocultar filas según el texto de búsqueda
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                }
            });
        });

        // Inicializar evento para exportar datos de la tabla newsletter
$(document).ready(function() {
    $('#exportNewsletter').click(function(e) {
        e.preventDefault();

        // Crear un formulario para enviar la solicitud de descarga
        const form = $('<form>', {
            action: 'config/newsletter_get_email.php',
            method: 'POST'
        }).append($('<input>', {
            type: 'hidden',
            name: 'action',
            value: 'exportNewsletter'
        }));

        $('body').append(form);
        form.submit(); // Enviar la solicitud
        form.remove(); // Eliminar el formulario después de enviarlo
    });
});




    </script>
</head>

<body style="background-color: #f7f7f7;">
<div class="text-center mb-4">
                <h1 class="card-title ">Bienvenido :
                    <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                </h1>
                <h4  style="color: #848484;">Estas en : Edición de Obras</h4>
            </div>
    <div class="centered-container">
        <div class="form-container">
            <!-- Menú de navegación con un solo elemento -->
            <nav class="navbar navbar-expand-lg d-flex justify-content-end">
                <div class="navbar-brand">
                <a  href="#" style="margin-right: 20px;" id="exportNewsletter">
                <img src="images/email.gif" alt="GIF animado" style="width: 40px;
    height: 40px; margin-top: 0;">Get Email</a>|
    <a  href="blog_upload.php" >
                <img src="images/walking.gif" alt="GIF animado" style="width: 40px;
    height: 40px; margin-top: 0; ">Go Blog</a>
                </div>
            </nav>
            <form action="config/upload.php" method="POST" id="uploadForm" enctype="multipart/form-data"
                style="width: 100%;">
                <div class="row ">
                    <div class="form-group">
                        <div class="alert alert-success" role="alert" style=" text-align: center; display:none;">Success
                        </div>
                        <small id="current-file" style="display: none; margin-top: 5px; color: #555;">Archivo actual:
                            Ninguno</small>
                    </div>
                    <div class="col-md-6 mb-4">
                        <input type="file" class="form-control" id="file" name="imagen" accept=".jpg, .jpeg, .png"
                            placeholder="Seleccionar archivo" required="">
                        <input type="hidden" id="original_ruta_imagen" name="original_ruta_imagen">

                    </div>
                    <div class="col-md-6 mb-4">
                        <input type="text" class="form-control" id="title" name="titulo" placeholder="Título">
                        <input type="hidden" id="original_titulo" name="original_titulo">
                    </div>
                    <div class="col-md-6 mb-4">
                        <input type="text" class="form-control" id="technique" name="tecnica" placeholder="Técnica"
                            required="">
                        <input type="hidden" id="original_tecnica" name="original_tecnica">
                    </div>
                    <div class="col-md-6 mb-4">
                        <input type="text" class="form-control" id="dimensions" name="medidas" placeholder="Medidas"
                            required="">
                        <input type="hidden" id="original_medidas" name="original_medidas">
                    </div>
                    <div class="col-md-6 mb-4">
                        <input type="number" class="form-control" id="year" name="ano" placeholder="Año">
                        <input type="hidden" id="original_ano" name="original_ano">
                    </div>
                    <div class="col-md-6 mb-4">
                        <input type="text" class="form-control" id="exhibition" name="expuesto"
                            placeholder="Lugar de exposición">
                        <input type="hidden" id="original_expuesto" name="original_expuesto">
                    </div>
                    <div class="col-md-6 mb-4">
                        <input type="text" class="form-control" id="series" name="serie" placeholder="Serie (opcional)">
                        <input type="hidden" id="original_serie" name="original_serie">
                    </div>
                    <div class="col-md-6 mb-4">
                        <select class="form-control" id="categoria" name="categoria" required=""
                            onchange="toggleSubcategoria()" style="font-size: 1.5rem;" required="">
                            <option value="">Seleccione Categoría</option>
                            <option value="escultura">Escultura</option>
                            <option value="pintura">Pintura</option>
                            <option value="dibujo">Dibujo</option>
                        </select>
                        <input type="hidden" id="original_categoria" name="original_categoria">
                    </div>
                    <div class="col-md-6 mb-4" id="subcategoria" style="display: none;">
                        <select class="form-control" id="selectSubcategoria" name="subcategoria"
                            style="font-size: 1.5rem;">
                            <option value="">Seleccione Subcategoría</option>
                            <option value="abstracta">Abstracta</option>
                            <option value="figurativa">Figurativa</option>
                        </select>
                        <input type="hidden" id="original_subcategoria" name="original_subcategoria">
                    </div>
                </div>
                <!-- Checkbox Section -->
                <div class="row mt-4" style="display: flex; align-items: center; justify-content: center; gap: 20px;">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="relevante" name="relevante">
                        <label class="form-check-label" for="relevante">Relevante</label>
                        <input type="hidden" id="original_relevante" name="original_relevante">
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="vendido" name="vendido">
                        <label class="form-check-label" for="vendido">Vendido</label>
                        <input type="hidden" id="original_vendido" name="original_vendido">
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="portfolio_principal"
                            name="portfolio_principal">
                        <label class="form-check-label" for="portfolio_principal">Portfolio principal</label>
                        <input type="hidden" id="original_portfolio_principal" name="original_portfolio_principal">
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" id="subir_button" class="btn btn-custom">Subir</button>
                    <a href="index.php" class="btn btn-custom">Cerrar sesión</a>
                </div>
                <div class="button-group">
                    <button type="button" id="editarExistente" class="btn btn-custom">Editar existente</button>
                    <button type="button" id="clear" class="btn btn-custom" onclick="clearFields()">Limpiar
                        campos</button>
                </div>

                <!-- Tabla oculta dentro del form -->
                <div id="tablaEdicion" style=" margin-top: 20px; display: none;">
                    <input type="text" id="searchInput" placeholder="Buscar en la tabla...">
                    <button type="button" id="ocultarTabla" class="btn-custom">Ocultar tabla</button>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Título</th>
                                <th>Técnica</th>
                                <th>Medidas</th>
                                <th>Año</th>
                                <th>Expuesta</th>
                                <th>Serie</th>
                                <th>Categoría</th>
                                <th>Sub-Categoría</th>
                                <th>Vendida</th>
                                <th>Relevante</th>
                                <th>Portfolio Principal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Las filas se llenarán dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </form>

            <style>
                #tablaEdicion {
                    margin-top: 20px;
                    max-height: 300px;
                    /* Altura máxima de la tabla */
                    overflow-x: auto;
                    overflow-y: auto;
                    /* Scroll vertical */
                }

                .table {
                    width: 100%;
                    margin-bottom: 1rem;
                    background-color: #fff;
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
            </style>

            </form>
        </div>
    </div>



    <div id="fh5co-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>&copy; Copyright © Noel Dobarganes all rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript files -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    <!-- Placeholder -->
    <script src="js/jquery.placeholder.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>

    <script>
        // AJAX para que no recargue la pagina 
        $(document).ready(function () {
            $("#uploadForm").submit(function (e) {
                e.preventDefault(); // Previene el envío normal del formulario

                // Validar la extensión del archivo
                var fileInput = document.getElementById('file');
                var filePath = fileInput.value;
                var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

                if (!allowedExtensions.exec(filePath)) {
                    const alertBox = $(".alert");
                    alertBox.removeClass("alert-success").addClass("alert-danger");
                    alertBox.text("Error: Solo se permiten archivos JPG y PNG.");
                    alertBox.css("display", "block");
                    fileInput.value = '';
                    return false;
                }

                var formData = new FormData(this); // Crea un objeto FormData con los datos del formulario

                $.ajax({
                    url: $(this).attr('action'), // URL a la que se enviarán los datos
                    type: 'POST',
                    data: formData,
                    contentType: false, // No se establece el tipo de contenido
                    processData: false, // No se procesan los datos
                    success: function (response) {
                        const alertBox = $(".alert");
                        if (response.trim() === "success") {
                            alertBox.removeClass("alert-danger").addClass("alert-success");
                            alertBox.text("¡Subida exitosa!");
                            alertBox.css("display", "block");
                            setTimeout(function () {
                                window.location.href = "upload.php"; // Redirige después de 3 segundos
                            }, 1000);
                        } else {
                            alertBox.removeClass("alert-success").addClass("alert-danger");
                            alertBox.text(response); // Muestra el mensaje de error del servidor
                            alertBox.css("display", "block");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        const alertBox = $(".alert");
                        alertBox.removeClass("alert-success").addClass("alert-danger");
                        alertBox.text("Error en la subida. Inténtalo de nuevo."); // Mensaje genérico de error
                        alertBox.css("display", "block");
                    }
                });
            });
        });
    </script>
    <script>


        let editMode = false;
        // tratamiento de tabla 
        $(document).ready(function () {
            let selectedRowId = null;

            $("#editarExistente").click(async function () {
                if (!editMode) {
                    $('#subir_button').prop('display', 'none');
                    // Mostrar tabla y cargar datos
                    $("#tablaEdicion").show();
                    loadTableData();
                    $(this).text("Guardar cambios");
                    editMode = true;
                } else {
                    try {
                        // Guardar cambios
                        await saveChanges();
                        $('#subir_button').prop('display', 'none');
                        $(this).text("Editar existente");
                        $("#tablaEdicion").hide();
                        editMode = false;
                    } catch (error) {
                        // Mostrar mensaje de error de forma permanente
                        alertBox.removeClass("alert-success").addClass("alert-danger");
                        alertBox.text(response);
                        alertBox.css("display", "block");
                    }
                }
            });

            function loadTableData() {
                $.ajax({
                    url: 'config/obtener_obras.php',
                    type: 'GET',
                    dataType: 'json', // Especifica que esperamos JSON
                    success: function (obras) {
                        let tableContent = '';

                        obras.forEach(obra => {
                            tableContent += `
                            
                    <tr data-id="${obra.id}">
                        <td><img src="${obra.ruta_imagen}" class="imagen-miniatura" alt="Miniatura"></td>
                        <td>${obra.titulo}</td>
                        <td>${obra.tecnica}</td>
                        <td>${obra.medidas}</td>
                        <td>${obra.ano > 0 ? obra.ano : ''}</td> <!-- Mostrar vacío si ano es 0 -->
                        <td>${obra.expuesto}</td>
                        <td>${obra.serie}</td>
                        <td>${obra.categoria}</td>
                        <td>${obra.subcategoria}</td>
                        <td>${obra.vendido ? 'Sí' : 'No'}</td>
                        <td>${obra.relevante ? 'Sí' : 'No'}</td>
                        <td>${obra.portfolio_principal ? 'Sí' : 'No'}</td>
                    </tr>
                `;
                        });

                        $("#tablaEdicion tbody").html(tableContent);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al obtener los datos:", error);
                        console.log("Respuesta del servidor:", xhr.responseText);
                        alert("Error al cargar los datos. Por favor, revisa la consola para más detalles.");
                    }
                });
            }

            // Doble clic para editar
            $(document).on('dblclick', '#tablaEdicion tr', function () {
                const id = $(this).data('id');
                selectedRowId = id;
                fillFormWithRowData($(this));
                $("#current-file").show(); // Mostrar el nombre del archivo actual al editar
            });

            // Clic derecho para mostrar menú contextual
            $(document).on('contextmenu', '#tablaEdicion tr', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                showContextMenu(e.pageX, e.pageY, id);
            });

            function fillFormWithRowData($row) {
                // Rellenar el formulario con los datos seleccionados
                $('#title').val($row.find('td:eq(1)').text()); // Campo título
                $('#technique').val($row.find('td:eq(2)').text()); // Campo técnica
                $('#dimensions').val($row.find('td:eq(3)').text()); // Campo medidas
                $('#year').val($row.find('td:eq(4)').text()); // Campo año
                $('#exhibition').val($row.find('td:eq(5)').text()); // Campo expuesto
                $('#series').val($row.find('td:eq(6)').text()); // Campo serie
                $('#categoria').val($row.find('td:eq(7)').text()); // Campo categoría

                // Almacenar valores originales en campos ocultos
                $('#original_ruta_imagen').val($row.find('td:eq(0) img').attr('src').split('/').pop());
                $('#original_titulo').val($row.find('td:eq(1)').text());
                $('#original_tecnica').val($row.find('td:eq(2)').text());
                $('#original_medidas').val($row.find('td:eq(3)').text());
                $('#original_ano').val($row.find('td:eq(4)').text());
                $('#original_expuesto').val($row.find('td:eq(5)').text());
                $('#original_serie').val($row.find('td:eq(6)').text());
                $('#original_categoria').val($row.find('td:eq(7)').text());
                $('#original_subcategoria').val($row.find('td:eq(8)').text());
                $('#original_vendido').val($row.find('td:eq(9)').text() === 'Sí' ? 1 : 0);
                $('#original_relevante').val($row.find('td:eq(10)').text() === 'Sí' ? 1 : 0);
                $('#original_portfolio_principal').val($row.find('td:eq(11)').text() === 'Sí' ? 1 : 0);

                // Actualizar el texto de "Archivo actual"
                const archivoActual = $row.find('td:eq(0) img').attr('src').split('/').pop();
                $('#current-file').text("Archivo actual: " + archivoActual);

                // Mostrar subcategoría si la categoría es "Pintura" o "Dibujo"
                const categoria = $row.find('td:eq(7)').text().toLowerCase();
                if (categoria === "pintura" || categoria === "dibujo") {
                    $('#subcategoria').show();
                    $('#subcategoria select').val($row.find('td:eq(8)').text()); // Campo subcategoría
                } else {
                    $('#subcategoria').hide();
                }

                $('#vendido').prop('checked', $row.find('td:eq(9)').text() === 'Sí'); // Campo vendido
                $('#relevante').prop('checked', $row.find('td:eq(10)').text() === 'Sí'); // Campo relevante
                $('#portfolio_principal').prop('checked', $row.find('td:eq(11)').text() === 'Sí'); // Campo portfolio principal

                // Llamar a toggleSubcategoria para actualizar la visibilidad
                toggleSubcategoria();
            }


            function showContextMenu(x, y, id) {
                // Eliminar cualquier menú contextual existente
                $('#contextMenu').remove();

                // Crear y mostrar el nuevo menú contextual
                $('body').append(`
            <div id="contextMenu" style="position:absolute;top:${y}px;left:${x}px;background:white;border:1px solid black;padding:5px;">
                <button id="deleteRow">Eliminar</button>
            </div>
        `);

                $('#deleteRow').click(function () {
                    deleteRow(id);
                    $('#contextMenu').remove();
                });

                $(document).click(function () {
                    $('#contextMenu').remove();
                });
            }

            function deleteRow(id) {
                if (confirm("¿Estás seguro de que deseas eliminar esta imagen?")) {
                    const formData = new FormData();
                    formData.append('id', id);
                    const alertBox = $(".alert"); // Selecciona el alert dentro del formulario
                    fetch('config/eliminar_obra.php', {
                        method: 'POST',
                        body: formData,
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                alertBox.removeClass("alert-danger").addClass("alert-success");
                                alertBox.text('¡ Obra eliminada con exito !');
                                alertBox.css("display", "block");
                                setTimeout(function () {
                                    window.location.href = "upload.php"; // Redirige después de 1 segundos
                                }, 1000);
                                const rowElement = document.getElementById(`row-${id}`);
                                if (rowElement) { // Verificar si el elemento existe antes de eliminarlo
                                    rowElement.remove();
                                } else {
                                    console.warn(`El elemento con ID row-${id} no existe en el DOM.`);
                                }
                            } else {
                                alertBox.removeClass("alert-success").addClass("alert-danger");
                                alertBox.text(response);
                                alertBox.css("display", "block");; // Usar alertBox para error
                            }
                        })
                        .catch(error => {
                            alertBox.removeClass("alert-success").addClass("alert-danger");
                            alertBox.text(error);
                            alertBox.css("display", "block");;
                        });
                }
            }


            function saveChanges() {
                return new Promise((resolve, reject) => {
                    if (!selectedRowId) {
                        alert('No se ha seleccionado ninguna obra para editar');
                        return;
                    }

                    const formData = new FormData($('#uploadForm')[0]);
                    formData.append('id', selectedRowId);

                    $.ajax({
                        url: 'config/actualizar_obra.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            const alertBox = $(".alert"); // Selecciona el alert dentro del formulario
                            if (response === 'success') {

                                const successMessage = "¡Cambios guardados con éxito";
                                alertBox.removeClass("alert-danger").addClass("alert-success");
                                alertBox.text(successMessage);
                                alertBox.css("display", "block");
                                setTimeout(function () {
                                    window.location.href = "upload.php"; // Redirige después de 3 segundos
                                }, 1000);
                                loadTableData(); // Recargar la tabla
                                $("#current-file").hide(); // Ocultar el nombre del archivo al guardar cambios
                                resolve(); // Indica éxito
                                selectedRowId = null;
                            } else {

                                // Mostrar mensaje de error de forma permanente
                                alertBox.removeClass("alert-success").addClass("alert-danger");
                                alertBox.text(response);
                                alertBox.css("display", "block");

                                // alert('Error al guardar los cambios');
                            }
                        },
                        error: function () {

                            const alertBox = $(".alert"); // Selecciona el alert dentro del formulario
                            alertBox.removeClass("alert-success").addClass("alert-danger");
                            alertBox.text("Hubo un error al Intentar loguearse. Inténtalo de nuevo.");
                            alertBox.css("display", "block");

                            alert('Error al conectar con el servidor');
                        }
                    });


                });
            }
        });

        // limpiar todos los campos
        function clearFields() {
            document.getElementById('categoria').value = "";
            document.getElementById('title').value = "";
            document.getElementById('technique').value = "";
            document.getElementById('dimensions').value = "";
            document.getElementById('year').value = "";
            document.getElementById('exhibition').value = "";
            document.getElementById('series').value = "";
            document.getElementById('categoria').selectedIndex = 0;
            document.getElementById('selectSubcategoria').selectedIndex = 0;
            document.getElementById('vendido').checked = false;
            document.getElementById('relevante').checked = false;
            document.getElementById('portfolio_principal').checked = false;
            document.getElementById('file').value = "";

            // Ocultar el select subcategoria y el small de seleccionar archivo
            document.getElementById('selectSubcategoria').style.display = "none";
            document.getElementById('current-file').style.display = "none";
        }

        // Botón Ocultar tabla
        document.getElementById('ocultarTabla').addEventListener('click', function () {
            document.getElementById('tablaEdicion').style.display = 'none';
            document.getElementById('subir_button').style.display = 'block';
            document.getElementById('editarExistente').textContent = 'Editar existente';
            editMode = false;
        });
    </script>

</body>

</html>