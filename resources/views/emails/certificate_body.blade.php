<!DOCTYPE html>
<html>

<head>
  <title>Certificado de Asistencia</title>
</head>

<body>
  <h2>¡Hola, {{ $attendance->name }}!</h2>
  <p>Gracias por haber participado en <strong>{{ $event->title }}</strong>.</p>
  <p>Adjunto a este correo encontrarás tu certificado de asistencia en formato PDF.</p>
  <br>
  <p>Saludos cordiales,</p>
  <p>El equipo organizador.</p>
</body>

</html>