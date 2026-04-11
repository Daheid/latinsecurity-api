<!DOCTYPE html>
<html lang="es">

<head>
  <style>
    body {
      font-family: 'Helvetica', sans-serif;
      text-align: center;
      padding: 50px;
      border: 10px solid #ccc;
    }

    .title {
      font-size: 34px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .subtitle {
      font-size: 18px;
      color: #555;
      font-style: italic;
    }

    .name {
      font-size: 45px;
      margin: 40px 0;
      border-bottom: 2px solid #000;
      display: inline-block;
      width: 80%;
    }

    .content {
      font-size: 16px;
      margin-top: 20px;
    }

    .lang-en {
      color: #777;
      font-style: italic;
      font-size: 14px;
      margin-top: 5px;
    }
  </style>
</head>

<body>
  <div class="title">CERTIFICADO DE ASISTENCIA</div>
  <div class="lang-en">CERTIFICATE OF ATTENDANCE</div>

  <p class="content">Se otorga el presente reconocimiento a / This recognition is awarded to:</p>

  <div class="name">{{ $attendee->name }}</div>

  <p class="content">
    Por su participación en el evento / For participating in the event:<br>
    <strong>{{ $event->title }}</strong>
  </p>

  <p class="content">
    Realizado en fecha / Held on date: {{ $event->date }} <br>
    Lugar / Location: {{ $event->location }}
  </p>

  <div style="margin-top: 50px;">
    <p>__________________________</p>
    <p>Firma Autorizada / Authorized Signature</p>
  </div>
</body>

</html>