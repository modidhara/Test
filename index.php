<?php
    require_once 'dbconfig.php';
    $query = "SELECT * FROM users";
    
    $db = new DBConfig();
    $rows = $db->getData($query);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Users</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
        <style type="text/css">
            .wrapper{
                width: 950px;
                margin: 0 auto;
            }
            .page-header h2{
                margin-top: 0;
            }
            table tr td:last-child a{
                margin-right: 15px;
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();   
            });
        </script>
    </head>
    <body>
        <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Users Details</h2>
                        <a href="create.php" class="btn btn-success pull-right">Add New User</a>
                    </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $rows as $row ): ?>
                    <tr>
                        <td><?php echo $row['name'];?></td>
                        <td><?php echo $row['email'];?></td>
                        <td><?php echo $row['address'];?></td>
                        <td><?php echo '<img src="data:'.$row['imagetype'].';base64,'.base64_encode( $row['image'] ).'"/>'; ?></td>
                        <td>
                            <a href="<?php echo 'view.php?id='.$row['id'];?>" title='View Record' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a> 
                            <a href="<?php echo 'edit.php?id='.$row['id'];?>" title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a> 
                            <a href="<?php echo 'delete.php?id='.$row['id'];?>" title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
            </div>
        </div>
    </div>
    </body>
</html>