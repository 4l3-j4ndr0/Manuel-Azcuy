// Función para mostrar alertas
function showAlert(message, isSuccess = true) {
    const alertDiv = document.querySelector('.alert');
    alertDiv.textContent = message;
    alertDiv.classList.remove('alert-success', 'alert-danger');
    alertDiv.classList.add(isSuccess ? 'alert-success' : 'alert-danger');
    alertDiv.style.display = 'block';

    // Si es éxito, ocultar después de 1 segundo
    if (isSuccess) {
        setTimeout(() => {
            alertDiv.style.display = 'none';
        }, 1000);
    }
}

// Función para añadir un nuevo registro
function agregarRegistro() {
    const form = document.getElementById('blogUploadForm');

    // Validar el formulario antes de enviar
    if (!form.checkValidity()) {
        form.reportValidity(); // Muestra mensajes de validación nativos
        return; // Salir si la validación falla
    }

    // Obtener referencia al campo de fecha
    const fechaInput = form.querySelector('input[name="fecha"]');

    // Si el campo de fecha está vacío, asignar la fecha actual
    if (!fechaInput.value) {
        const today = new Date();
    const formattedDate = today.toISOString().split('T')[0]; // Obtiene solo la fecha en formato YYYY-MM-DD
    fechaInput.value = formattedDate; // Asignar la fecha formateada
    }

    const formData = new FormData(form);

    fetch('config/blog_upload.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showAlert('Datos guardados correctamente');
                form.reset(); // Limpiar formulario
            } else {
                showAlert('Error: ' + data.message, false);
            }
        })
        .catch(error => {
            showAlert('Error al procesar la solicitud', false);
        });
}


function formatoFecha(fechaISO) {
    // Transformar la fecha al formatodel navegador dinamicamente
    // Descomponer la fecha ISO en partes
    const [anio, mes, dia] = fechaISO.split("-");

    // Crear la fecha en el formato dinámico del navegador sin zonas horarias
    const fecha = new Date(anio, mes - 1, dia); // El mes en Date comienza en 0 (enero)

    // Convertir a formato dinámico según el navegador
    return fecha.toLocaleDateString();

}

// Función para mostrar la tabla con datos de la BD (solo cuando se presiona "Editar existente")
function cargarTabla() {
    fetch('config/blog_read.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#tablaBlog tbody');
            tbody.innerHTML = ''; // Limpiar contenido anterior
            data.forEach(row => {
                // Modificar la ruta de la imagen
                const rutaTratada = row.ruta_imagen.replace('../', '');
                // Transformar el campo relevante
                const relevanteTexto = row.relevante == 1 ? 'Sí' : 'No';
                const tr = document.createElement('tr');
                tr.dataset.id = row.id;
                tr.dataset.url = row.url;
                tr.dataset.titulo = row.titulo;
                tr.dataset.extracto = row.extracto;
                tr.dataset.fecha = formatoFecha(row.fecha);
                tr.dataset.relevante = relevanteTexto;
                tr.dataset.imagen = rutaTratada; 

                tr.innerHTML = `
                   <td><img src="${rutaTratada}" alt="Imagen" style="max-width: 50px;"></td>
                    <td>${row.titulo}</td>
                    <td>${row.extracto}</td>
                    <td>${row.url}</td>
                    <td>${formatoFecha(row.fecha)}</td>
                    <td>${relevanteTexto}</td>
                `;
                tbody.appendChild(tr);
            });


            // Clic derecho para mostrar menú contextual
            $(document).on('contextmenu', '#tablaBlog tr', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                showContextMenu(e.pageX, e.pageY, id);
            });

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

            // Asignar evento de doble clic para editar
            document.querySelectorAll('#tablaBlog tr').forEach(row => {
                row.addEventListener('dblclick', rellenarFormulario);
            });

            function deleteRow(id) {
                if (confirm("¿Estás seguro de que deseas eliminar esta imagen?")) {
                    const formData = new FormData();
                    formData.append('id', id);
                    fetch('config/blog_delete_Registro.php', {
                        method: 'POST',
                        body: formData,
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                showAlert('Registro eliminado con éxito');
                                setTimeout(function () {
                                    window.location.href = "blog_upload.php"; // Redirige después de 1 segundos
                                }, 1000);
                                const rowElement = document.getElementById(`row-${id}`);

                                if (rowElement) { // Verificar si el elemento existe antes de eliminarlo
                                    rowElement.remove();
                                } 
                            } else {
                                showAlert(responce);
                            }
                        })
                        .catch(error => {
                            showAlert(error);
                        });
                }
            }

        })
        .catch(error => showAlert('Error al cargar los datos', false));
}


// convertir al formato ISO que espera por defecto en input de fecha 
function convertirAFormatoISO(fechaEntrada) {
    // Crear un objeto de fecha desde la entrada
    const fecha = new Date(fechaEntrada);

    // Validar si la fecha es válida
    if (isNaN(fecha.getTime())) {
        console.error("Fecha no válida. Verifica el formato o los valores.");
        return null;
    }

    // Obtener las partes de la fecha
    const año = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, "0"); // Los meses en JS son base 0
    const dia = String(fecha.getDate()).padStart(2, "0");

    // Devolver en formato ISO (año/mes/día)
    return `${año}-${mes}-${dia}`;
}


// Función para rellenar el formulario al hacer doble clic en una fila
function rellenarFormulario(event) {
    const row = event.currentTarget;
    document.getElementById('id').value = row.dataset.id;

    document.getElementById('url').value = row.dataset.url;
    document.getElementById('original_url').value = row.dataset.url;

    document.getElementById('titulo').value = row.dataset.titulo;
    document.getElementById('original_textArea').value = row.dataset.titulo;

    document.getElementById('extracto').value = row.dataset.extracto;
    document.getElementById('original_extracto').value = row.dataset.extracto;

    document.getElementById('fecha').value = convertirAFormatoISO(row.dataset.fecha);
    document.getElementById('original_fecha').value = convertirAFormatoISO(row.dataset.fecha);

    
    

    // Manejar el checkbox "relevante"
    const checkboxRelevante = document.getElementById('relevante');
    checkboxRelevante.checked = row.dataset.relevante != "No"; // Marcar si es "1", desmarcar si es "0"

    // Actualizar el valor original para "relevante"
    document.getElementById('original_relevante').value = row.dataset.relevante;

    const currentFile = document.getElementById('current-file');
    const imagePath = row.dataset.imagen || 'Ninguno'; // Asegurarte de manejar el caso de "Ninguno"
    const imageName = imagePath.split('/').pop(); // Obtener solo el nombre del archivo
    currentFile.style.display = 'block';
    currentFile.textContent = `Archivo actual: ${imageName}`;
    document.getElementById('current-file-hidden').value = imageName;
}


// Función para editar un registro existente
function guardarCambios() {
    const formData = new FormData(document.getElementById('blogUploadForm'));
    const form = document.getElementById('blogUploadForm');

    fetch('config/blog_update.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('subir').style.display = 'block';
                document.getElementById('editar').textContent = 'Editar existente';
                document.getElementById('current-file').style.display = 'none';
                form.reset();
                showAlert('Registro actualizado con éxito');
                cargarTabla(); // Actualizar la tabla
            } else {
                document.getElementById('subir').style.display = 'block';
                document.getElementById('tablaBlog').style.display = 'block';
                document.getElementById('editar').textContent = 'Guardar cambios';
                showAlert('Error: ' + data.message, false);
            }
        })
        .catch(error => {
            showAlert('Error al procesar la solicitud: ' + error.message, false); // Mostrar el mensaje de error al usuario
        });
}



// Asignar eventos a los botones al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    // Botón Subir
    document.getElementById('subir').addEventListener('click', agregarRegistro);

    // Botón Editar existente (muestra la tabla o guarda cambios)
    document.getElementById('editar').addEventListener('click', function () {
        const tabla = document.getElementById('tablaBlog');
        const botonSubir = document.getElementById('subir'); // Selección con jQuery

        if (tabla.style.display === 'none') {
            tabla.style.display = 'table'; // Mostrar tabla
            this.textContent = 'Guardar cambios';
            botonSubir.style.display = 'none'; // Deshabilitar el botón
            // Cargar los datos en la tabla desde la base de datos
            cargarTabla();
        } else {
            guardarCambios();
            this.textContent = 'Editar existente'; // Cambiar texto
            tabla.style.display = 'none'; // Ocultar tabla
        }
    });

    // Botón Limpiar
    document.getElementById('limpiar').addEventListener('click', function () {
        document.getElementById('blogUploadForm').reset();
        document.getElementById('current-file').style.display = 'none';
        const alertDiv = document.querySelector('.alert');
        alertDiv.style.display = 'none';
    });

    // Botón Ocultar tabla
    document.getElementById('ocultarTabla').addEventListener('click', function () {
        document.getElementById('tablaBlog').style.display = 'none';
        document.getElementById('subir').style.display = 'block';
        document.getElementById('editar').textContent = 'Editar existente';
    });

    document.getElementById('url').addEventListener('input', function () {
        const url = this.value;

        // Solo procesar si la URL no está vacía
        if (url.trim() === '') {
            document.getElementById('titulo').value = '';
            document.getElementById('extracto').value = '';
            return;
        }

        fetch('config/blog_tomar_titulo_extracto.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ url }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then((data) => {
                if (data.status === 'success') {
                    // Mostrar los datos en los textareas
                    document.getElementById('titulo').value = data.titulo || 'No se pudo obtener el título';
                    document.getElementById('extracto').value = data.extracto.trim() || 'No se pudo obtener el extracto';

                    // Hacer visibles los textareas
                    document.getElementById('titulo').parentElement.style.display = 'block';
                    document.getElementById('extracto').parentElement.style.display = 'block';
                } else {
                    // Mostrar mensaje de error
                    showAlert(data.message || 'Error al procesar la URL', false);
                }
            })
            .catch((error) => {
                showAlert('Error al procesar la solicitud. Detalles: ' + error.message, false);
            });

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

