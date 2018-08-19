<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
//load the database configuration file
include 'dbConfig.php';

if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'succ':
            $statusMsgClass = 'alert-success';
            $statusMsg = 'Data has been inserted successfully.';
            break;
        case 'err':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        default:
            $statusMsgClass = '';
            $statusMsg = '';
    }
}
?>
<div class="container">
    <?php if(!empty($statusMsg)){
        echo '<div class="alert '.$statusMsgClass.'">'.$statusMsg.'</div>';
    } ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Devices
            <a href="javascript:void(0);" onclick="$('#importFrm').slideToggle();">Import Devices</a>
        </div>
        <div class="panel-body">
            <form action="importData.php" method="post" enctype="multipart/form-data" id="importFrm">
                <input type="file" name="file" />
                <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
                <form method="post" action="delete_table.php">
		<input type="submit" class="btn btn-danger" id='delete' class='delete' name="delete" value='DELETE ALL'></input>
    		</form>
            <table class="table table-bordered">
                <thead>
                    <tr>
                      <th>Serial</th>
                      <th>Asset Tag</th>
                      <th>Username</th>
		              <th>Full Name</th>
		              <th>Email</th>
		              <th>Position</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    //get records from database
                    $query = $db->query("SELECT * FROM assets ORDER BY username DESC");
                    if($query->num_rows > 0){ 
                        while($row = $query->fetch_assoc()){ ?>
                    <tr>
                      <td><?php echo $row['serialnumber']; ?></td>
                      <td><?php echo $row['assettag']; ?></td>
                      <td><?php echo $row['username']; ?></td>
		      		  <td><?php echo $row['fullname']; ?></td>
		      		  <td><?php echo $row['email']; ?></td>
		      		  <td><?php echo $row['position']; ?></td>
			          <td><a href="deleterow.php?serialnumber=<?php echo $row['serialnumber']; ?>">Delete</a></td>
                    </tr>
                    <?php } }else{ ?>
                    <tr><td colspan="5">No member(s) found.....</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>
