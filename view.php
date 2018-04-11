<?php
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    require_once 'dbconfig.php';
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
else{
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View User</title>
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
                        <h1>View User</h1>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <p class="form-control-static"><?php echo $name; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <p class="form-control-static"><?php echo $email; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <p class="form-control-static"><?php echo $address; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <p class="form-control-static">
                            <img src="data:<?php echo $imagetype; ?>;base64, <?php echo base64_encode($image);?>" class="img-thumbnail"/>
                        </p>
                    </div>
                    <?php if(!empty($tree)):?>
                    <div class="form-group">
                        <label>Family Tree</label>
                        <ul>
                            <?php foreach ( $tree as $tree ): ?>
                                <li><?php echo $tree['name'];?></li>    
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif;?>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
