<?php
    session_start();
    if (!isset($_SESSION['role'])) {
        header('location:index.php');
    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<?php
        include "Components/head.php"
?>
    <body>

        <!--wrapper start-->
        <div class="wrapper">
            <!--header menu start-->
            <?php
                include "Components/navbar.php"
            ?>
            <!--header menu end-->
            <!--sidebar start-->
            <div class="sidebar">
                <div class="sidebar-menu">
                    <center class="profile">
                        <img src="https://ssl.gstatic.com/images/branding/product/2x/avatar_circle_grey_512dp.png" alt="">
                        <p><?php echo $_SESSION['name']; ?></p>
                        <span><?php echo $_SESSION['role']; ?></span>
                    </center>
                    <li class="item" id="dashboard">
                        <a href="dashboard.php" class="menu-btn">
                            <i class="fas fa-desktop"></i><span>Dashboard</span>
                        </a>
                    </li>
                    <?php
                        if($_SESSION['role'] === "admin" or $_SESSION['role'] === "subject_staff"){
?>
                    <li class="item" id="student">
                        <a href="Student.php" class="menu-btn" >
                            <i class="fas fa-user-graduate"></i><span> Student</span>
                        </a>
                    </li>
                    <?php
                        }
                        if($_SESSION['role'] === "admin"){
?>
                    <li class="item" id="faculty">
                        <a href="faculty.php" class="menu-btn">
                            <i class="fas fa-user"></i><span> Faculty</span>
                        </a>
                    </li>
<?php
                        }
                        if($_SESSION['role'] === "admin" or $_SESSION['role'] === "subject_staff"){
?>
                    <li class="item active" id="classes">
                        <a href="Classes.php" class="menu-btn">
                            <i class="fas fa-university"></i><span>Classes</span>
                        </a>
                    </li>
<?php
                        }
?>
                    <li class="item active" id="exam">
                        <a href="Exam.php" class="menu-btn">
                            <i class="fas fa-paste"></i><span> Exams</span>
                        </a>
                    </li>
                    <li class="item" id="result">
                        <a href="Result.php" class="menu-btn">
                            <i class="fas fa-graduation-cap"></i>Result</span>
                        </a>
                    </li>
                </div>
            </div>
            <!--sidebar end-->
            <!--main container start-->
            <div class="main-container">
                <div class="card">
                    <div id="wrapper">
                        <div class="top">
                            <h1>Exam Detail</h1>
<?php                        
                        if($_SESSION['role'] === "admin"){
?>
                            <button id="myBtn"><i class="fas fa-plus"></i></button>
<?php
                        }
?>                        </div>
                        <table id="keywords" >
                            <thead>
                                <tr>
                                <th><span>Exam Id</span></th>
                                <th><span>Class Id</span></th>
                                <th><span>Exam Name</span></th>
                                <th><span>Exam Date</span></th>
                                <th><span>Exam Result Date</span></th>
<?php
                        if($_SESSION['role'] === "admin"){
?>
                                <th><span>Result Live</span></th>
                                <th><span>Status</span></th>
                                <th><span>Action</span></th>
<?php
                        }
?>
                                </tr>
                            </thead>
                           <tbody>

<?php
                                include 'Components/connection.php';
                            
                                $user_Login_query = "select * from exam_details order by exam_id DESC";  
                                  
                                if($_SESSION['role'] !== "admin"){
                                    $user_Login_query = "select * from  exam_details where status='Active' order by exam_id DESC";
                                }           

                                $user_Login_submit = mysqli_query($con, $user_Login_query); 
                                $check = true;
                                $checkuser = "no";

                                if(mysqli_num_rows($user_Login_submit)){
                                    while($row = mysqli_fetch_assoc($user_Login_submit)){
                                        if($check){
                                            $checkuser = $row['exam_id'];
                                            $check = false;
                                        }
                                          
?>
                             <tr>
                                <td><?php echo $row['exam_id']; ?></td>
                                <td><?php echo $row['class_id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['exam_date']; ?></td>
                                <td><?php echo $row['exam_result_date']; ?></td>
<?php
                        if($_SESSION['role'] === "admin"){
?>
                                <td>
<?php
                                        if($row['result_published'] === "Yes"){
?>
                                            <button class="enable-btn" disabled >Yes</button>
<?php }else{ ?>
                                        <form method="POST" action="Components/publish.php">
                                            <input type="text" name="id" value="<?php echo $row['exam_id']; ?>" style="display: none">
                                            <input type="text" name="role" value="exam_details" style="display: none">
                                            <input type="text" name="status" value="<?php echo $row['status']; ?>" style="display: none">
                                            <button class="disable-btn" type="submit" <?php if($row['status'] === "Inactive"){echo "Inactive";} ?>>No</button>
                                        </form>
<?php
                                        }
?> 
                                </td>
                                <td>
<?php
                                        if($row['status'] === "Active"){
?>
                                        <form method="POST" action="Components/change.php">
                                            <input type="text" name="id" value="<?php echo $row['exam_id']; ?>" style="display: none">
                                            <input type="text" name="role" value="exam_details" style="display: none">
                                            <input type="text" name="status" value="Active" style="display: none">
                                            <button class="enable-btn" type="submit" <?php if($row['result_published'] === "Yes"){echo "Inactive";} ?> >Active</button>
                                        </form>
<?php }else{ ?>
                                        <form method="POST" action="Components/change.php">
                                            <input type="text" name="id" value="<?php echo $row['exam_id']; ?>" style="display: none">
                                            <input type="text" name="role" value="exam_details" style="display: none">
                                            <input type="text" name="status" value="Inactive" style="display: none">
                                            <button class="disable-btn" type="submit" <?php if($row['result_published'] === "Yes"){echo "Inactive";} ?>>Inactive</button>
                                        </form>
<?php
                                        }
?>  
                                </td>
                                <td>
                                    <!--            change            -->
                                    <div class="action-icon">
                                        <form method="POST" action="Components/delete.php">
                                            <input type="text" name="id" value="<?php echo $row['exam_id']; ?>" style="display: none">
                                            <input type="text" name="role" value="exam_details" style="display: none">
                                            <button class="btn2" type="submit" <?php if($row['result_published'] === "Yes"){echo "Inactive";} ?>><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </td>
<?php
                        }
?>
                             </tr>
<?php
                                    }
                                }
?>                           
                           </tbody>
                         </table>
                        </div> 
                </div>
            </div>
            <!--main container end-->
        </div>

<!-- The Modal -->
        <div id="myModal" class="modal">

        <!-- Modal content -->
            <div class="modal-content">

                <span class="close">&times;</span>
                <form action="Components/add.php" method="POST">
                    <input type="text" name="role" value="exam_details" style="display: none">
                    <input type="text" name="exid" placeholder="exam_ID">
                    <input type="text" name="clsid" placeholder="class_ID">
                    <input type="text" name="name" placeholder="Exam Name">
                    <input type="text" name="exdate" placeholder="exam date-YYYY/MM/DD HH:MM:SS">
                    <input type="text" name="exrdate" placeholder="exam result date-YYYY/MM/DD HH:MM:SS">
                    <button type="submit">Add</button>
                </form>
            </div>

        </div>
        <!--wrapper end-->
        <!-- pm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> --> -->
        <script type="text/javascript">
            $(document).ready(function(){
                $(".sidebar-btn").click(function(){
                    $(".wrapper").toggleClass("collapse");
                });
            });
            var modal = document.getElementById("myModal");

            // Get the button that opens the modal
            var btn = document.getElementById("myBtn");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on the button, open the modal
            btn.onclick = function() {
            modal.style.display = "block";
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
            modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
            }
        </script>

    </body>
</html>
