<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$empid = $name = $age = $depid = $depName = "";
$empid_err = $name_err = $age_err = $depid_err = $depName_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    // Validate empid
    $input_empid = trim($_POST["empid"]);
    if(empty($input_empid)){
        $empid_err = "Please enter the empid.";
    } elseif(!ctype_digit($input_empid)){
        $empid_err = "Please enter a positive integer value.";
    } else{
        $empid = $input_empid;
    }

    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }

    // Validate age
    $input_age = trim($_POST["age"]);
    if(empty($input_age)){
        $age_err = "Please enter the age.";
    } elseif(!ctype_digit($input_age)){
        $age_err = "Please enter a positive integer value.";
    } else{
        $age = $input_age;
    }

    // Validate  depid
    $input_depid = trim($_POST["depid"]);
    if(empty($input_depid)){
        $depid_err = "Please enter an depid.";
      } elseif(!ctype_digit($input_depid)){
          $age_err = "Please enter a positive integer value.";
     } else{
        $depid = $input_depid;
    }

    // Validate depname
    $input_depname = trim($_POST["depname"]);
    if(empty($input_depname)){
        $depname_err = "Please enter a depname.";
    } elseif(!filter_var($input_depname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $depname_err = "Please enter a valid depname.";
    } else{
        $depname = $input_depname;
    }

    // Check input errors before inserting in database
    if(empty($empid_err) && empty($name_err) && empty($age_err) && empty($depid_err) && empty($depname_err)){
        // Prepare an update statement
        $sql = "UPDATE employees SET empid=?, name=?, age=?, depid=?, depname=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_empid, $param_name, $param_age, $param_depid, $param_depName, $param_id);

            // Set parameters
            $param_empid = $empid;
            $param_name = $name;
            $param_age = $age;
            $param_depid = $depid;
            $param_depname = $depname;
            $param_id = $id;


            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM employees WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $empid = $row["empid"];
                    $name = $row["name"];
                    $age = $row["age"];
                    $depid = $row["depid"];
                    $depname = $row["depname"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                      <div class="form-group">
                          <label>Empid</label>
                          <input type="text" name="empid" class="form-control <?php echo (!empty($empid_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $empid; ?>">
                          <span class="invalid-feedback"><?php echo $empid_err;?></span>
                      </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>">
                            <span class="invalid-feedback"><?php echo $age_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Depid</label>
                            <input type="text" name="depid" class="form-control <?php echo (!empty($depid_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $depid; ?>">
                            <span class="invalid-feedback"><?php echo $depid_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Depname</label>
                            <input type="text" name="depname" class="form-control <?php echo (!empty($depname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $depname; ?>">
                            <span class="invalid-feedback"><?php echo $depname_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
