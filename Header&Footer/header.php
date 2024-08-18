<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Executives</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i"
      rel="stylesheet"
    />

    <!-- Vendor CSS Files -->
    <link
      href="../assets/vendor/bootstrap/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link href="../assets/vendor/icofont/icofont.min.css" rel="stylesheet" />
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />
    <link
      href="../assets/vendor/owl.carousel/assets/owl.carousel.min.css"
      rel="stylesheet"
    />
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet" />
    <link href="../assets/vendor/venobox/venobox.css" rel="stylesheet" />
    <link href="../assets/vendor/aos/aos.css" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="style.css" rel="stylesheet" />

    <!-- Font Awesome CDN --> 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <!-- Profile DropDown --> 
    <script>
      $(document).ready(function () {
        $(".profile .profile_icon").click(function () {
          $(this).parent().toggleClass("active");
        });
      });
    </script>

    <!-- Validation of the GIG Form --> 
    <script>
      function validate() {
        var valid = false;
        var valid_delivery = false;
        var x = document.myform.pricecategory;
        var y = document.myform.deliverycategory;

        for (var i = 0; i < x.length; i++) {
          if (x[i].checked) {
            valid = true;
            break;
          }
        }

        for (var i = 0; i < y.length; i++) {
          if (y[i].checked) {
            valid_delivery = true;
            break;
          }
        }

        if (valid && valid_delivery) {
          
        } else {
          alert("Please Select the Price and the Delivery time");
          return false;
        }
      }
    </script>

  </head>

<body>