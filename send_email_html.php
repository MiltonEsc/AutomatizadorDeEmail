<?php
    // permite hacer cambios y testear sin necesidad de enviar correos
	define("DEMO", true); // cuanto esta en TRUE ningun correo podra ser enviado.

	// conexion a la base de datos
    $mysqli = new mysqli("127.0.0.1", "root", "" , "cumpleanios");
	if ($mysqli->connect_errno) {
        echo "Lo sentimos, este sitio web está experimentando problemas.";
        // Podría ser conveniente mostrar algo interesante, aunque nosotros simplemente saldremos
        exit;
    }
	// comsultas simples mysqli
	$cons_fecha = "SELECT * FROM personas WHERE DATE_FORMAT(fecha_nacimiento, '%m-%d') = DATE_FORMAT(now(),'%m-%d')";           
	$cons_correo = "SELECT * FROM personas";		
	$resultado_cons_correo = $mysqli->query($cons_correo);	
	$resultado_cons_fecha = $mysqli->query($cons_fecha); 

	// obtengo la ubicacion de la plantilla del correo html
	$template_file = "template_email_activation.html";
	
	$body = "template.html";
	$files = glob("Archivos/*");
	$files = array_combine($files, array_map("filemtime", $files));
	arsort($files);
	// $latest_file = key($files);
	$latest_file = "Archivos/plantilla.png";


	// cabeceras del correo
	$email_from = "SuperBrix <miltonescorcia2020@gmail.com>";
	$email_headers = "From: ".$email_from."\r\nReply-To: ".$email_from."\r\n";
	$email_headers .= "MIME-Version: 1.0\r\n";
	$email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	while ($resp = $resultado_cons_fecha->fetch_Assoc()) {
		
		if (file_exists($template_file) && file_exists($body)){
			$email_message = file_get_contents($template_file);
			$mensaje = file_get_contents($body);
		} else{
			die ("Unable to locate your template file");
		}

		$id = $resp['id'];
		$nombres=$resp['nombres'];
		$nombress[]=strtoupper($resp['nombres']);
		$apellidoss=strtoupper($resp['apellidos']);
		$cargo = $resp['cargo'];
		$foto = $resp['foto'];
		$exten = $resp['exten'];
		$fechaActual = date('d-m-Y');

		$email_message = str_replace('[TO_NAME]' , $nombres ,   $email_message);
		$email_message = str_replace('[TO_NAMES]' , implode(',', $nombress) ,   $email_message);
		$email_message = str_replace('[CARGO]', $cargo, $email_message);
		$email_message = str_replace('[EXTEN]', $exten, $email_message);
		$email_message = str_replace('[nombres]' , $apellidoss ,   $email_message);
		$email_message = str_replace('[ID]' , $id ,   $email_message);
		$email_message = str_replace('[FECHA]', $fechaActual, $email_message);
		$email_message = str_replace('[LOGO]' , 'https://superbrix.com/images'.$foto ,$email_message);
		$mensaje = str_replace('[TEMPLATE]' , 'https://superbrix.com/'.$latest_file ,   $mensaje);
		echo $email_message;
		$email_subject = 'Feliz Cumpleanos! ' . strtoupper($resp['nombres']);
	}

		
	
	while ($persona = $resultado_cons_correo->fetch_assoc()) {
		$to = $persona['correo'].',';

		if (mysqli_num_rows($resultado_cons_fecha) != 0) {
			echo "consulta no vacía";
			// check if the email script is in demo mode, if it is then dont actually send an email
			if (DEMO)
			die("<hr /><center>Esto es un demo de la plantilla HTML. El correo no fue enviado. </center>");
			
			if ( mail($to, $email_subject, $mensaje, $email_headers) )
			
			echo '<hr /><center>Success! Your email has been sent to '. $to .'</center>';
			else
			echo '<hr /><center> UNSUCCESSFUL... Your email' . $to .  'was <b>NOT</b> sent or found. </center>';

			}
		 else {
		   echo "consulta vacía";
		   }
	
	}
	
?>