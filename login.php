<?php

?>
<!--<p>Welcome, --><?php //echo $username ?: "guest"; echo "!";?><!--</p>-->

<?php //if (!$username) { ?>

    <form class="form-login" method="POST">
        <div class="container">
            <label>Username</label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label>Password</label>
            <input type="password" placeholder="Enter Password" name="password" required>

            <button type="submit">Login</button><br>
            <input type="checkbox" checked="checked"> Remember me
        </div>
    </form>

<?php //}

if (isset($_POST['username']) and isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM `users` WHERE username='$username' and password='$password'";

    $db = Db::getInstance();
    $result = $db->query($query);
    $_SESSION['user'] = $result['id'];

}

?>