<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2 style="color:#2c3e50;">Recuperar tu contraseña de Workshome</h2>

		<div>
			Hemos recibido una petición de recuperación de contraseña de tu cuenta.
		</div>
		
		<br/>
		
		<div>
			Para recuperar tu contraseña, ves a este enlace: {{ URL::to('password/reset', array($token)) }}.
		</div>
		
		<br/>
		
		<div>
			Si no funciona el enlace, copia y pega la URL en el navegador. El enlace dejará de estar activo en 60 minutos.
		</div>
		
		<br/>
		
		Atentamente, el equipo de <a style="color:#18bc9c;" href="www.socialworkshome.tk">Workshome</a>
	</body>
</html>