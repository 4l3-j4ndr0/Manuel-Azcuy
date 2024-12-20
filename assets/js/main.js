;
(function ($) {
    "use strict";
    jQuery(document).ready(function () {

        // == AOS Init== //
        AOS.init({
            disable: 'mobile'
        });

        // == Search Bar== //
        if ($('.search-icon').length) {
            $('.search-icon').on('click', function () {
                $('.search-form').toggleClass('show');
            });
        }

        // == Hero Slider== //
        if ($('.hero-slider').length) {
            var swiper = new Swiper('.hero-slider', {
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: true,
                },
                speed: 900,
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                navigation: {
                    nextEl: '.arr-right',
                    prevEl: '.arr-left',
                },
                on: {
                    slideChangeTransitionStart: function () {
                        $('.slide-content h1, .slide-content p, .slide-content a').removeClass('aos-init').removeClass('aos-animate');
                    },
                    slideChangeTransitionEnd: function () {
                        AOS.init();
                    },
                },
            });

            $(".hero-slider").hover(function () {
                (this).swiper.autoplay.stop();
            }, function () {
                (this).swiper.autoplay.start();
            });
        }

        // == Testimonial Slider== //
        if ($('.test-slider').length) {
            var swiper = new Swiper('.test-slider', {
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: true,
                },
                speed: 1200,
                loop: true,
                pagination: {
                    el: '.test-pagination',
                    clickable: true
                },
                on: {
                    slideChangeTransitionStart: function () {
                        $('.testimonials .test-img, .testimonials h5, .testimonials span, .testimonials p').removeClass('aos-init').removeClass('aos-animate');
                    },
                    slideChangeTransitionEnd: function () {
                        AOS.init();
                    },
                },
            });

            $(".test-slider").hover(function () {
                (this).swiper.autoplay.stop();
            }, function () {
                (this).swiper.autoplay.start();
            });
        }

        // == Clients Slider== //
        if ($('.clients-slider').length) {
            var swiper = new Swiper('.clients-slider', {
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: true,
                },
                speed: 900,
                loop: true,
                slidesPerView: 5,
                breakpoints: {
                    1200: {
                        slidesPerView: 4
                    },
                    992: {
                        slidesPerView: 3
                    },
                    576: {
                        slidesPerView: 2
                    },
                    400: {
                        slidesPerView: 1
                    }
                }
            });

            $(".clients-slider").hover(function () {
                (this).swiper.autoplay.stop();
            }, function () {
                (this).swiper.autoplay.start();
            });
        }

        // == Light Gallery== //
        if ($('#lightgallery').length) {
            $("#lightgallery").lightGallery();
        }
    });

    jQuery(window).on('load', function () {
        // == Animate loader off screen == //
        $(".css-loader").fadeOut("slow");
        AOS.init({
            disable: 'mobile'
        });

    });
})(jQuery)

// ########## MANEJO DEL MODAL  #################3
function openVideoModal() {
    const videoModal = document.getElementById("videoModal");
    videoModal.style.display = "flex"; // Muestra el modal nuevamente
    videoModal.style.opacity = "1"; // Restablece la opacidad al abrir
    videoModal.style.visibility = "visible"; // Asegura que sea visible

    const video = videoModal.querySelector("video");
    video.play(); // Opcional: reproduce el video automáticamente al abrir el modal
}

function closeVideoModal() {
    const videoModal = document.getElementById("videoModal");
    videoModal.style.display = "none"; // Oculta el modal
    videoModal.style.opacity = "0"; // Opcional: restablece la opacidad a 0
    videoModal.style.visibility = "hidden"; // Opcional: oculta la visibilidad

    const video = videoModal.querySelector("video");
    video.pause(); // Pausa el video
    video.currentTime = 0; // Reinicia el video al inicio para la próxima vez que se reproduzca
}

// ########## END MANEJO DEL MODAL  #################

$(document).ready(function () {
    document.getElementById('subscribe-btn').addEventListener('click', function () {
        console.log('Botón clickeado en test');
    });
    
    let recaptchaValidated = false; // Estado del reCAPTCHA

    // Callback de reCAPTCHA
    window.recaptchaCallback = function () {
        console.log('reCAPTCHA completado');
      recaptchaValidated = true; // Cambiar estado del reCAPTCHA a válido
      toggleSubscribeButton(); // Revalidar el estado del botón
    };

    // Función para validar el estado del botón de suscripción
    function toggleSubscribeButton() {
      const emailInput = $('#popup-email').val().trim();
      const termsChecked = $('#accept-terms').is(':checked');
      const subscribeButton = $('#subscribe-button');

      // Verificar todas las condiciones necesarias
      if (emailInput !== "" && termsChecked && recaptchaValidated) {
        subscribeButton.prop('disabled', false);
        subscribeButton.addClass('enabled'); // Estilo habilitado
      } else {
        subscribeButton.prop('disabled', true);
        subscribeButton.removeClass('enabled'); // Estilo deshabilitado
      }
    }

    // Abrir el popup
    $('#subscribe-btn').on('click', function () {
        console.log('Botón clickeado');
      const email = $('#mc4wp-form input[name="email"]').val();
      $('#popup-email').val(email); // Cargar email en el popup
      $('#accept-terms').prop('checked', false); // Reiniciar checkbox
      $('#subscribe-button').prop('disabled', true); // Deshabilitar botón
      $('#subscribe-button').removeClass('enabled'); // Quitar clase habilitada
      $('#subscribe-popup').show(); // Mostrar el popup
      $('body').addClass('no-scroll'); // Deshabilitar desplazamiento
    });

    // Cerrar el popup
    $('#close-popup').on('click', function () {
      $('#subscribe-popup').hide(); // Ocultar el popup
      $('body').removeClass('no-scroll'); // Habilitar desplazamiento
    });

    // Validar al interactuar con el email o el checkbox
    $('#popup-email').on('input', toggleSubscribeButton);
    $('#accept-terms').on('change', toggleSubscribeButton);

    // Manejar el envío del formulario
    $('#popup-form').on('submit', function (e) {
      e.preventDefault(); // Prevenir envío real

      const email = $('#popup-email').val();
      const termsAccepted = $('#accept-terms').is(':checked');
      const alertBox = $('#alert_newletter'); // Selecciona el alert dentro del formulario

      // Validar que los campos estén completos
      if (!termsAccepted) {
        alertBox.removeClass('alert-success').addClass('alert-danger');
        alertBox.text('You must accept the terms and conditions..');
        alertBox.show();
        return;
      }

      // Datos adicionales a enviar
      const data = {
        email: email,
        version: "1.0", // Versión de la política de privacidad
        purpose: "Recepción de newsletters y promociones relacionadas con ND&D ART"
      };

      // Enviar los datos al servidor usando fetch
      fetch('assets/config/procesado_datos_newsletter.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            alertBox.removeClass('alert-danger').addClass('alert-success');
            alertBox.text(result.message); // Mostrar mensaje del servidor
            alertBox.show();

            // Ocultar el popup después de 3 segundos
            setTimeout(function () {
              $('#subscribe-popup').hide();
              $('body').removeClass('no-scroll'); // Habilitar desplazamiento
              alertBox.hide(); // Ocultar el alert
            }, 3000);
          } else {
            alertBox.removeClass('alert-success').addClass('alert-danger');
            alertBox.text(result.message);
            alertBox.show();
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alertBox.removeClass('alert-success').addClass('alert-danger');
          alertBox.text('There was an issue with the server. Please try again.');
          alertBox.show();
        });
    });
  });
/* ====== conf del pop up de news letters  =========*/
