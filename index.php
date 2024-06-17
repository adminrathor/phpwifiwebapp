<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Yinka Enoch Adedokun">
    <title>Login Page</title>
    <link rel="icon" type="image/x-icon" href="./favicon.ico">
    <link rel="stylesheet" href="styles.css">
    <script>
        function validateForm() {
            var firstname = document.getElementById("FirstName").value.trim();
            var lastname = document.getElementById("LastName").value.trim();
            var email = document.getElementById("Email").value.trim();
            var phone = document.getElementById("Phone").value.trim();
           
            if (firstname === "" || lastname === "" || email === "" ) {
                alert("Please fill out all fields.");
                return false; 
            }
            return true; 
        };
    </script>
</head>

<body>
    <!-- Main Content -->
    <div class="container">
        <div class="row main-content bg-success text-center">
            <div class="col-md-4 text-center company__info">
                <span class="company__logo"><h2><span class="fa fa-android"></span></h2></span>
                <img src="./CDG_LOCKUP (1).png" class="logo">
            </div>
            <div class="col-md-8 col-xs-12 col-sm-12 login_form ">
                <div class="container">
                    <div class="row">
                        <h2 class="login-heading">Wifi Log In</h2>
                    </div>
                    <div class="row">
                        <form action="form.php" method="post" class="form-group" onsubmit="return validate()">
                            <div class="row">
                                <input type="text" name="FirstName" id="FirstName" class="form__input" placeholder="First Name">
                            </div>
                            <div class="row">
                                <input type="text" name="LastName" id="LastName" class="form__input" placeholder="Last Name">
                            </div>
                            <div class="row">
                                <input type="text" name="Email" id="Email" class="form__input" placeholder="Email">
                            </div>
                            <div class="row">
                                <input type="text" name="Phone" id="Phone" class="form__input" placeholder="Phone">
                            </div>
                            <div class="row">
                                <input type="submit" value="Submit" class="btn">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container text-center footer">
    </div>
</body>

</html>
