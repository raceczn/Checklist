<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Splash Screen</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    body,
    html {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;

      background-image: url("assets/backg.png");
      background-attachment: fixed;
      background-position: center;
      background-size: cover;
    }

    .splash-screen {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      /* Ensure items stack vertically */
      position: relative;
      /* Positioning context for absolute positioning */
      height: 100%;
      /* Occupy full height */
    }

    .logo {
      width: 180px;
      height: 180px;
      margin-bottom: 5rem;
      background-image: url('assets/logo.png');
      background-size: contain;
      background-repeat: no-repeat;
      animation: scaleUp 1.5s ease-in-out;
    }

    h2 {
      position: absolute;
      top: 20px;
      left: 4rem;
      color: 	#FAF9F6;
      font-size: 20px;
      /* Font size */
      font-family: "Poppins";
      z-index: 1;
    }

    @keyframes scaleUp {
      0% {
        transform: scale(0);
      }

      100% {
        transform: scale(1);
      }
    }

    @keyframes fadeOut {
      0% {
        opacity: 1;
      }

      100% {
        opacity: 0;
      }
    }

    @keyframes fadeIn {
      0% {
        opacity: 0;
      }

      100% {
        opacity: 1;
      }
    }
  </style>
  <script>
    setTimeout(function() {
      var splash = document.querySelector('.splash-screen');
      splash.style.animation = 'fadeOut 0.5s ease-out forwards';
      setTimeout(function() {
        window.location.href = 'http://localhost/studentchecklist/profile.php';
      }, 500);
    }, 4000); 
  </script>
</head>

<body>
  <div class="splash-screen">
    <h2>C v S U</h2>
    <div class="logo"></div>
  </div>
</body>

</html>