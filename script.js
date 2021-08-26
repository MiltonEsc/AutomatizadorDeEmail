/**
 * Ejemplo 1 de html2canvas para convertir el HTML de una web
 * a un elemento canvas y adjuntarlo al contenido actual
 * 
 * @author parzibyte
 */
//Definimos el botón para escuchar su click, y también el contenedor del canvas
// const $boton = document.querySelector("#btnCapturar"), // El botón que desencadena
   // A qué le tomamos la foto
   $contenedorCanvas = document.querySelector("#contenedor"); // En dónde ponemos el elemento canvas
   const contenedorCanvas = document.querySelector("#can"); // En dónde ponemos el elemento canvas
  
  // Agregar el listener al botón
  // $boton.addEventListener("click", () => {
  function tomarImagenPorSeccion(div,nombre) {
  
      html2canvas(document.querySelector("#" + div),{ letterRendering: 1, useCORS:true, allowTaint : true, onrendered : function (canvas) { } }) // Llamar a html2canvas y pasarle el elemento  
      .then(canvas => {
          // $contenedorCanvas.appendChild(canvas); // Lo agregamos como hijo del div
          var dataURL = canvas.toDataURL();
          console.log(dataURL);
          contenedorCanvas.src = dataURL; 
        // Cuando se resuelva la promesa traerá el canvas
           base = "img=" + dataURL + "&nombre=" + nombre;
          
           $.ajax({
            type:"POST",
            url:"crearImagenes.php",
            data:base,
            success:function(respuesta) {	
              respuesta = parseInt(respuesta);
              if (respuesta > 0) {
                alert("Imagen creada con exito!");
              } else {
                alert("No se pudo crear la imagen :(");
              }
            }
          });
      });
  }
  
  // });