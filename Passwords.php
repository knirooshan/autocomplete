<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to index page
//if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
//    header("location: index.php");
//    exit;
//}
// Include config file
require_once "layouts/config.php";

// Define variables and initialize with empty values
$username = $ownername = $contact = $password = $acctype = "";
$username_err = $ownername_err = $acctype_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if Ownername is empty
    if (empty(trim($_POST["term"]))) {
        $ownername_err = "Please enter Owner Name.";
    } else {
        $ownername = trim($_POST["term"]);
    }

    // Check if Account Type is Not Selected
    if (!isset($_POST["acctype"])) {
            $acctype_err = "Please Select Account Type.";
    }
    
     // Check if password is empty
     if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }


    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password, name, role FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $name, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = $role;
                            $_SESSION["name"] = $name;

                            // Redirect user to welcome page
                            header("location: index.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>
    <title>Global Print</title>

    <?php include 'layouts/head.php'; ?>

    <link href="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

    <?php include 'layouts/head-style.php'; ?>

<script type="text/javascript">
  $(function() {
     $( "#term" ).autocomplete({
       source: 'ajax-db-search.php',
     });
  });
</script>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
 
<!-- jQuery UI -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
 
<!-- Bootstrap Css -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
 
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <!-- Bootstrap Css -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="frontend-script.js"></script>

</head>

<?php include 'layouts/body.php'; ?>

<!-- Begin page -->
<div id="layout-wrapper">

    <?php include 'layouts/menu.php'; ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Passwords</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Global Print</a></li>
                                    <li class="breadcrumb-item active">Passwords</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-6 col-md-12">
                        <!-- card -->
                        <div class="card card-h-100 ">
                            <div class="auth-content my-auto">
                                <div class="text-center">
                                    <p class="text-muted mt-2">Insert Password Details</p>
                                </div>
                                <form class="custom-form mt-4 pt-2" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="margin:10px;">

                        <div class="mb-3 <?php echo (!empty($ownername_err)) ? 'has-error' : ''; ?>">
                            <label class="form-label" for="ownername">Owner Name</label>
                            

                            <div class="autocomplete-container">
                            <input type="text" name="term" id="term" placeholder="Enter Owner Name" class="form-control">  
                            </div>  
                                <span class="text-danger"><?php echo $ownername_err; ?></span>
                        </div>

                                    <div class="mb-3 <?php echo (!empty($acctype_err)) ? 'has-error' : ''; ?>">
                                        <label class="form-label" for="url">Account Type</label>
                                        <select class="form-control" id="acctype" name="acctype">
                                            <option id="none" name="none" disabled selected>Select Account Type</option>
                                            <option id="woedpress" name="wordpress">WordPress</option>
                                            <option id="cpanel" name="cpanel">CPanel</option>
                                            <option id="email" name="email">Email</option>
                                        </select>
                                        <span class="text-danger"><?php echo $acctype_err; ?></span>
                                        
                                    </div>

                                    <div class="mb-3 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" class="form-control" id="username" placeholder="Username of Wordpress/CPanel/Email" name="username" value="">
                                        <span class="text-danger"><?php echo $username_err; ?></span>
                                    </div>

                                    <div class="mb-3 <?php echo (!empty($contact_err)) ? 'has-error' : ''; ?>">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="text" class="form-control" id="password" placeholder="Password of Wordpress/CPanel/Email" name="password" value="">
                                        <span class="text-danger"><?php echo $password_err; ?></span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="remarks">Remarks ( Optional )</label>
                                        <input type="textarea" class="form-control" id="remarks" placeholder="" name="remarks" value="">
                                        
                                    </div>

                                    


                                    <div class="mb-3">
                                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                    


            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <?php include 'layouts/footer.php'; ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- Right Sidebar -->
<?php include 'layouts/right-sidebar.php'; ?>
<!-- /Right-bar -->

<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>

<!-- apexcharts -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Plugins js-->
<script src="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>


<!-- App js -->
<script src="assets/js/app.js"></script>


<script>
    // Table
    window.onload = function() {

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'backend/dasboard-data.php', true);
        xhr.send();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let data_returned = JSON.parse(xhr.response)
                let seven_days = data_returned[0].sevendays;
                let thirty_days = data_returned[0].thirtydays;
                let patients = data_returned[0].registered_patients
                $("#7-days").attr("data-target", seven_days);
                $("#30-days").attr("data-target", thirty_days);
                $("#patients").attr("data-target", patients);


            }
        };

        var xhr2 = new XMLHttpRequest();
        xhr2.open('POST', 'backend/charts-data.php', true);
        xhr2.send();
        xhr2.onreadystatechange = function() {
            if (xhr2.readyState === 4 && xhr2.status === 200) {
                var options = {
                    series: [{
                        name: "Amount",
                        data: []
                    }],
                    chart: {
                        type: 'line',
                        height: 350,
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                selection: false,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: true,
                                reset: false | '<img src="/static/icons/reset.png" width="20">',
                                customIcons: []
                            },
                        }
                    },
                    xaxis: {
                        categories: [],
                        title: {
                            text: "Date"
                        }
                    },
                    yaxis: {
                        title: {
                            text: "Amount"
                        }
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth',
                    },

                };
                var chart = new ApexCharts(document.querySelector("#chart"), options);
                var data = JSON.parse(xhr2.response);
                data.forEach(function(d) {
                    options.xaxis.categories.push(d.date);
                    options.series[0].data.push(d.amount);
                });
                chart.render();




            }
        };




        var xhr3 = new XMLHttpRequest();
        xhr3.open('POST', 'backend/charts-data2.php', true);
        xhr3.send();
        xhr3.onreadystatechange = function() {
            if (xhr3.readyState === 4 && xhr3.status === 200) {
                var options2 = {
                    series: [{
                        name: "Number",
                        data: []
                    }],
                    chart: {
                        type: 'line',
                        height: 350,
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                selection: false,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: true,
                                reset: false | '<img src="/static/icons/reset.png" width="20">',
                                customIcons: []
                            },
                        }
                    },
                    xaxis: {
                        categories: [],
                        title: {
                            text: "Date"
                        }
                    },
                    yaxis: {
                        title: {
                            text: "Number"
                        }
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth',
                    },

                };
                var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
                var data2 = JSON.parse(xhr3.response);
                data2.forEach(function(d) {
                    options2.xaxis.categories.push(d.date);
                    options2.series[0].data.push(d.patno);
                });
                chart2.render();




            }
        };


    };
</script>





</body>

</html>