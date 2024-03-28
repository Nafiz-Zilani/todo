<?php
include_once("config.php");

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if(!$connection){
    throw new Exception("Can't connect.\n");
}

//$query = "SElECT * FROM tasks ORDER BY date DESC";
$query = "SElECT * FROM tasks WHERE complete = 0 ORDER BY date DESC";
$result  = mysqli_query($connection, $query);

$completeTaskQuery = "SElECT * FROM tasks WHERE complete = 1 ORDER BY date DESC";
$resultCompleteTask  = mysqli_query($connection, $completeTaskQuery );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <style>
        body {
            margin-top: 30px;
        }

        #main{
            padding: 0px 150px 0px 150px;
        }

        #action{
            width: 150px;
        }
    </style>
    <title>Task Manager</title>
</head>
<body>
    <div class="container" id="main">
        <h1>Task Manager</h1>
        <p>This is a simple project from managing our daily tasks. We're gojng to use HTML, CSS, PHP, MySQL for the project.</p>
        <?php
        if(mysqli_num_rows($resultCompleteTask)>0){
            ?>
            <h4>Complete Task</h4>
            <table>
                <thead>
                <tr>
                    <th></th>
                    <th>Id</th>
                    <th>Task</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
            <?php
            while ($cData=mysqli_fetch_assoc($resultCompleteTask)){
                $timestamp = strtotime($cData['date']);
                $cDate = date("jS M,Y",$timestamp);
                ?>
                <tr>
                    <td><input class="label-inline" type="checkbox" value="<?php echo $cData['id'];?>"></td>
                    <td><?php echo $cData['id'];?></td>
                    <td><?php echo $cData['task'];?></td>
                    <td> <?php echo $cDate;?></td>
                    <td><a class="delete" data-taskid="<?php echo $cData['id'];?>" href="#">Delete</a> | <a class="incomplete" data-taskid="<?php echo $cData['id'];?>" href="#">Incomplete</a></td>
                </tr>
                <?php
            }
            ?>
                </tbody>
            </table>
            <p>...</p>
            <?php
        }
            ?>
        <?php
        if(mysqli_num_rows($result) == 0){
            ?>
            <P>No task found</P>
            <?php
        } else{
            ?>
        <h4>Upcoming Task</h4>
            <form action="tasks.php" method="post">
                <table>
                    <thead>
                    <tr>
                        <th></th>
                        <th>Id</th>
                        <th>Task</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($data = mysqli_fetch_assoc($result)){
                        $timestamp = strtotime($data['date']);
                        $date = date("jS M,Y",$timestamp);
                        ?>
                        <tr>
                            <td><input name="taskids[]" class="label-inline" type="checkbox" value="<?php echo $data['id'];?>"></td>
                            <td><?php echo $data['id'];?></td>
                            <td><?php echo $data['task'];?></td>
                            <td> <?php echo $date;?></td>
                            <td><a class="delete" data-taskid="<?php echo $data['id'];?>" href="#">Delete</a> | <a href="#">Edit</a> | <a class="complete" data-taskid="<?php echo $data['id'];?>" href="#">Complete</a></td>
                        </tr>
                        <?php
                    }
                    mysqli_close($connection);
                    ?>
                    </tbody>
                </table>
                <select id="bulkaction" name="action">
                    <option value="0">With Selected</option>
                    <option value="bulkdelete">Delete</option>
                    <option value="bulkcomplete">Mask as complete</option>
                </select>
                <input class="button-primary" id="bulksubmit" type="submit" value="Submit">
            </form>
        <?php
        }
        ?>
        <p>...</p>
        <h4>Add Task</h4>
        <form method="post" action="tasks.php">
            <fieldset>
                <?php
                    $added = $_GET['added'] ?? '';
                    if ($added){
                        echo '<p>Task successfully added.</p>';
                    }
                ?>
                <label for="task">Task</label>
                <input type="text" placeholder="Task Details" id="task" name="task">
                <label for="date">Date</label>
                <input type="text" placeholder="Task Date(Year-Month-Day)" id="date" name="date">

                <input class="button-primary" type="submit" value="Add Task">
                <input type="hidden" name="action" value="add">
            </fieldset>
        </form>
        <p><strong><i>Build By</i></strong> Md. Nafiz Imam Zilani</p>
    </div>
<form action="tasks.php" method="post" id="completeform">
    <input type="hidden" name="action" value="complete">
    <input type="hidden" id="taskid" name="taskid">
</form>
<form action="tasks.php" method="post" id="deleteform">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" id="deltaskid" name="taskid">
</form>
<form action="tasks.php" method="post" id="incompleteform">
    <input type="hidden" name="action" value="incomplete">
    <input type="hidden" id="incompleteid" name="taskid">
</form>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
<script>
    ;(function ($){
        $(document).ready(function (){
            $(".complete").on('click', function (){
                var id = $(this).data("taskid");
                $("#taskid").val(id);
                $("#completeform").submit();
            })

            $(".delete").on('click', function (){
                if(confirm("Do you want to delete the task?")) {
                    var id = $(this).data("taskid");
                    $("#deltaskid").val(id);
                    $("#deleteform").submit();
                }
            })

            $(".incomplete").on('click', function (){//incomplete
                var id = $(this).data("taskid");
                $("#incompleteid").val(id);
                $("#incompleteform").submit();
            })

            $("#bulksubmit").on('click', function (){
                if ($("#bulkaction").val()=="bulkdelete"){
                    if (!confirm("Do you want to delete.")){
                        return false;
                    }
                }
            })
            $("#bulkdelete").on('click', function (){
                if ($("#bulkaction").val()=="bulkdelete"){
                    if (!confirm("Do you want to delete.")){
                        return false;
                    }
                }
            })
        });
    })(jQuery);
</script>
</html>
