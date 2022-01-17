<?php
/**
 * Created by PhpStorm.
 * User: Gal Matheys
 * Date: 15/01/2022
 * Time: 14:01
 */
$rnd = time();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log-In</title>
    <link href="assets/css/app.css?v=<?=$rnd?>" rel="stylesheet"/>
</head>
<body>
<div class="wrap">
    <div class="container">
        <div class="log-in">
            <form id="login-form">
                <h1>Login</h1>
                <div class="content">
                    <div class="input-field">
                        <input type="text" id="name" value="gal matheys">
                        <label for="name">Your Name</label>
                    </div>
                    <div class="input-field">
                        <input type="email" id="email" value="galmat@gmail.com">
                        <label for="email">Your Email</label>
                    </div>
                    <div class="action">
                        <button name="submit" class="submit">Login</button>
                    </div>
                </div>

            </form>
        </div>
        <div class="dashboard">
            <h1>OnLine Users</h1>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Entrance Time</th>
                        <th>Last update Time</th>
                        <th>Ip Address</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="app/main.js?v=<?=$rnd?>" type="module"></script>
</body>
</html>
