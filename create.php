<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$empid = $name = $age = $depid = $depname = "";
$empid_err = $name_err = $age_err = $depid_err = $depname_err = "";


// Validate empid
$input_empid = trim($_POST["empid"]);
if(empty($input_empid)){
    $empid_err = "Please enter a empid.";
} elseif(!ctype_digit($input_empid)){
    $empid_err = "Please enter a positive integer value.";
} else{
    $empid = $input_empid;
}


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
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
        $age_err = "Please enter a age.";
    } elseif(!ctype_digit($input_age)){
        $age_err = "Please enter a positive integer value.";
    } else{
        $age = $input_age;
    }

    // Validate depid
    $input_depid = trim($_POST["depid"]);
    if(empty($input_depid)){
        $depid_err = "Please enter the depid amount.";
    } elseif(!ctype_digit($input_depid)){
        $depid_err = "Please enter a positive integer value.";
    } else{
        $depid = $input_depid;
    }


    // Validate depname
    $input_depname = trim($_POST["depname"]);
    if(empty($input_depname)){
        $depname_err = "Please enter depname.";
    } elseif(!filter_var($input_depname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $depname_err = "Please enter a valid depname.";
    } else{
        $depname = $input_depname;
    }


    // Check input errors before inserting in database
    if(empty($empid_err) && empty($name_err) && empty($age_err) && empty($depid_err) && empty($depname_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (empid, name, age, depid, depname) VALUES (?, ?, ?, ?, ?)";


        if($stmt = mysqli_prepare($link,$sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_empid, $param_name, $param_age, $param_depid, $param_depname);

            // Set parameters
            $param_empid = $empid;
            $param_name = $name;
            $param_age = $age;
            $param_depid = $depid;
            $param_depname = $depname;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
          mysqli_stmt_close ($stmt);
      }

      // Close connection
      mysqli_close($link);
  }
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                            <textarea name="depid" class="form-control <?php echo (!empty($depid_err)) ? 'is-invalid' : ''; ?>"><?php echo $depid; ?></textarea>
                            <span class="invalid-feedback"><?php echo $depid_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Depname</label>
                            <input type="text" name="depname" class="form-control <?php echo (!empty($depname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $depname; ?>">
                            <span class="invalid-feedback"><?php echo $depname_err;?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
