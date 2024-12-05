<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP para reinicio de contraseña</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      background-color: #f0f0f0;
      color: #444;
      margin: 0;
      padding: 0;
      line-height: 1.8;
    }

    .container {
      max-width: 600px;
      margin: 30px auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #004085;
      font-size: 24px;
      margin-bottom: 20px;
      text-align: center;
    }

    p {
      margin: 15px 0;
      font-size: 16px;
    }

    .otp {
      background-color: #e2f0ff;
      padding: 10px;
      border-radius: 6px;
      font-size: 18px;
      font-weight: bold;
      text-align: center;
      margin: 20px 0;
      color: #004085;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    /* Button styles */
    .cta-button {
      display: inline-block;
      padding: 12px 25px;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
      font-weight: bold;
      text-align: center;
      margin: 25px 0;
      transition: background-color 0.3s ease;
    }

    .cta-button:hover {
      background-color: #0056b3;
    }

    .footer {
      font-size: 12px;
      color: #888;
      text-align: center;
      margin-top: 40px;
      border-top: 1px solid #ddd;
      padding-top: 15px;
    }

    .footer strong {
      color: #0056b3;
    }

    /* Responsive Design */
    @media only screen and (max-width: 600px) {
      .container {
        padding: 20px;
        margin: 20px;
      }

      h1 {
        font-size: 20px;
      }

      p {
        font-size: 14px;
      }

      .cta-button {
        font-size: 14px;
        padding: 10px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Código para reinicio de contraseña</h1>
    <p>Hola {{ $user_name }}, hemos recibido una solicitud para reiniciar tu contraseña. Usa el siguiente código para completar el proceso de reinicio:</p>

    <div class="otp">{{ $token }}</div>

    <p>Este código es válido solo por 10 minutos. Si no solicitaste el cambio de contraseña, puedes ignorar este mensaje.</p>

    <div class="footer">
      <p>Este correo fue enviado automáticamente. Por favor, no respondas a este mensaje.</p>
      <p>Gracias por confiar en nuestros servicios.</p>
    </div>
  </div>
</body>

</html>