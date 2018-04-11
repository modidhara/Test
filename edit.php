<?php error_reporting(0);
require_once 'dbconfig.php';

$name = $email = $address = $image = $imagetype = '';
$name_err = $email_err = $address_err = $image_err = '';

if( isset($_POST['id']) && !empty(trim($_POST['id'])) ){  
    $id = trim($_POST['id']);
    
    $input_name = trim($_POST['name']);
    if( empty($input_name) ){
        $name_err = 'Please enter a name';
    }
    else{
        $name = $input_name;
    }
    
    $input_email = trim($_POST['email']);
    if( empty($input_email) ){
        $email_err = 'Please enter a email address.';
    }
    elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
        $email_err = 'Please enter a valid email address.';
    }
    else{
        $email = $input_email;
    }
    
    $input_address = trim($_POST['address']);
    if( empty($input_address) ){
        $address_err = 'Please enter an address.';
    }
    else{
        $address = $input_address;
    }
    
    $input_image = $_FILES['image']['tmp_name'];
    if( getimagesize($_FILES['image']['tmp_name']) == false ){ 
        $image_err = 'Please upload an image.';
    }
    else{
        $image = addslashes(file_get_contents($input_image));
        $imagetype = getimagesize($_FILES['image']['tmp_name'])['mime'];
    }
    
    if( empty($name_err) && empty($email_err) && empty($address_err) ){
        $db = new DBConfig();
        
        if(empty($image_err)){
           $query = "UPDATE users SET name = '".$name."', email = '".$email."', address = '".$address."', image = '".$image."', imagetype = '".$imagetype."' WHERE id =".$id; 
        }
        else{
           $query = "UPDATE users SET name = '".$name."', email = '".$email."', address = '".$address."' WHERE id =".$id;
        }
        
        $result1 = $db->execute($query);
        
        if( $result1 == true ){
            $dquery = "DELETE FROM family_tree WHERE user_id = ".$id;
            
            $db->execute($dquery);
            
            $count = count($_POST['fname']);
            
            if( $count > 1 ){
                for( $i = 0; $i < $count; $i++ ){
                    if( trim($_POST['fname'][$i]) != '' ){
                        $fquery = "INSERT INTO family_tree (user_id, name) VALUES ('".$id."', '".$_POST['fname'][$i]."')";
                        
                        $result2 = $db->execute($fquery);
                    }
                }
            }
        }
        
        if( $result1 == true ){
            header("location: index.php");
            exit();
        }
    }
}
else{
    if(isset($_GET['id']) && !empty(trim($_GET['id']))){
        $id = trim($_GET['id']);
        
        $db = new DBConfig();
        $query = "SELECT * FROM users WHERE id = ".$id;
        $result = $db->getData($query);
        
        foreach ( $result as $row ):
            $name = $row['name'];
            $email = $row['email'];
            $address = $row['address'];
            $image = $row['image'];
            $imagetype = $row['imagetype'];
        endforeach;
        
        $fquery = "SELECT * FROM family_tree WHERE user_id = ".$id;
        $tree = $db->getData($fquery);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit User</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <style type="text/css">
            .wrapper{
                width: 500px;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Update User</h2>
                        </div>
                            <form name="edit" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                                    <label>Name</label>
                                    <input type="text" name="name" value="<?php echo $name;?>" class="form-control"/>
                                    <span class="help-block"><?php echo $name_err;?></span>
                                </div>
                                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                    <label>Email</label>
                                    <input type="text" name="email" value="<?php echo $email;?>" class="form-control"/>
                                    <span class="help-block"><?php echo $email_err;?></span>
                                </div>
                                <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                                    <label>Address</label>
                                    <textarea name="address" class="form-control"><?php echo $address;?></textarea>
                                    <span class="help-block"><?php echo $address_err;?></span>
                                </div>
                                <div class="form-group <?php echo (!empty($image_err)) ? 'has-error' : ''; ?>">
                                    <label>Image</label>
                                    <input type="file" name="image" class="form-control"/>
                                    <img src="data:<?php echo $imagetype; ?>;base64, <?php echo base64_encode($row['image']);?>" class="img-thumbnail"/>
                                    <span class="help-block"><?php echo $image_err;?></span>
                                </div>
                                <div class="form-group">
                                    <table id="dynamic_field" class="table table-bordered">
                                        <tr>
                                            <td><input type="text" name="fname[]" id="fname" class="form-control"/></td>
                                            <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
                                        </tr>
                                        <?php foreach( $tree as $k=>$v ): ?>
                                        <tr id="<?php echo 'row'.$k; ?>">
                                            <td><input type="text" name="fname[]" id="fname" class="form-control" value="<?php echo $v['name'];?>"/></td>
                                            <td><button type="button" name="remove" id="<?php echo $k;?>" class="btn btn-danger btn_remove">X</button></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id;?>"/>
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <a href="index.php" class="btn btn-default">Cancel</a>
                            </form>
                        </div>
                </div>        
            </div>
        </div>
    </body>
</html>

<script>
    jQuery(document).ready(function(){ 
        var i = jQuery("table#dynamic_field tr:last-child button.btn_remove").attr("id");

        jQuery("#add").click(function(){ 
            i++;
            jQuery("#dynamic_field").append('<tr id="row'+i+'"><td><input type="text" name="fname[]" id="fname" class="form-control"/></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
        });
        jQuery(document).on('click', '.btn_remove', function(){ 
            var button_id = jQuery(this).attr("id"); 
            jQuery("#row"+button_id+"").remove();
        });
    });
</script>