<?php
// database connection
include 'includes/db_connection.php';

// Query for Student
$query = "SELECT CONCAT(student_fname, ' ', student_Mname, ' ', student_lname) AS full_name, 
                 student_number, 
                 student_program, 
                 Adviser, 
                 student_address, 
                 student_email, 
                 Contact_Number FROM student";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/index.css">
  <title>Student Information</title>
</head>
<style>
  body {
    background-image: url("assets/backg.png");
    background-attachment: fixed;
    background-position: center;
    background-size: cover;
  }

  .container {
    background: whitesmoke;
    width: 65vw;
    height: 72vh;
    margin: 0 auto;
    position: relative;
    margin-top: 7%;
    box-shadow: 2px 5px 20px rgba(119, 119, 119, 0.5);
    border-radius: .6rem;
  }


  .btn_record {
    display: inline-block;
    margin-top: 1rem;
    margin-left: 20rem;
    background-color: #3c6048;
  }

  nav img {
    margin-left: 2.2rem;
    margin-top: 3rem;
    height: 260px;
    width: 255px;
    position: relative;
    border: 5px solid #72d292;
    border-radius: 50%;
    box-shadow: 0px 0px 20px 5px rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
  }

  .btn_record {
  margin-top: 1rem;
  margin-left: 17rem;
  background-color: #3c6048;
  font-size: 18px;
  border:  2px solid  rgb(2, 49, 9);
}

  .btn_record:hover {
  background-color: rgb(29, 134, 73);
  border-color: #04AA6D;

}


  @media (max-width: 1224px) {
    nav img {
      height: 200px;
      width: 200px;
      margin-left: 2rem;
      margin-top: 2.5rem;
    }
  }

  /* For mobile phones */
  @media (max-width: 900px) {
    nav img {
      height: 150px;
      width: 150px;
      margin-left: 1.5rem;
      margin-top: 2rem;
    }
  }

  /* For smaller mobile phones */
  @media (max-width: 450px) {
    nav img {
      height: 100px;
      width: 100px;
      margin-left: 1rem;
      margin-top: 1.5rem;
    }
  }
</style>


<body>
  <div class="container">
    <div id="logo">
      <h1 class="logo"><img src="assets/logo.png" alt=""></h1>
    </div>
    <div class="leftbox">
      <nav>
        <img src="assets/profile.JPG" alt="">
        <div class="titles">
          <h1 class="cs">COMPUTER SCIENCE STUDENT</h1>
          <h1 class="campus">Cavite State University</h1>
          <h1 class="campus">Bacoor City Campus</h1>
          <h1>Regular</h1>
        </div>

      </nav>
    </div>
    <div class="rightbox">
      <div class="profile">
        <h1>Student's Information</h1>
        <h2>Full Name</h2>
        <p><?php echo $row['full_name']; ?></p>
        <h2>Student Number</h2>
        <p><strong style="color: darkgreen;"><?php echo $row['student_number']; ?></strong></p>
        <h2>Program</h2>
        <p><?php echo $row['student_program']; ?></p>
        <h2>CvSU Email</h2>
        <p><?php echo $row['student_email']; ?></p>
        <h2>Contact Number</h2>
        <p><?php echo $row['Contact_Number']; ?></p>
      </div>
      <button type="button" class="btn_record btn btn-success btn-rounded btn-block btn-lg">View Student Checklist</button>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
      $(document).ready(function() {
        $('.btn_record').on('click', function() {
          window.location.href = 'http://localhost/studentchecklist/checklist_record.php';
        });
      });
    </script>

</body>

</html>