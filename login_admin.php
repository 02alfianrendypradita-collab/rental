<?php
session_start();

$conn = mysqli_connect("localhost","root","","rental_mobil");

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn,"
    SELECT * FROM admin
    WHERE username='$username'
    AND password='$password'
    ");

    if(mysqli_num_rows($query) > 0){

        $_SESSION['admin'] = $username;

        echo "
        <script>
            alert('Login berhasil!');
            window.location='dashboard_admin.php';
        </script>
        ";

    }else{

        echo "
        <script>
            alert('Username atau Password salah!');
        </script>
        ";

    }

}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#2563eb,#1e3a8a);
}

/* CARD LOGIN */
.login-box{
    width:100%;
    max-width:400px;
    background:white;
    padding:40px;
    border-radius:25px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
}

.login-box h1{
    text-align:center;
    margin-bottom:30px;
    color:#0f172a;
}

/* INPUT */
.input-group{
    margin-bottom:20px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
    color:#334155;
}

.input-group input{
    width:100%;
    padding:14px;
    border:1px solid #cbd5e1;
    border-radius:12px;
    outline:none;
    transition:0.3s;
}

.input-group input:focus{
    border-color:#2563eb;
    box-shadow:0 0 10px rgba(37,99,235,0.2);
}

/* BUTTON */
.btn{
    width:100%;
    padding:15px;
    border:none;
    border-radius:12px;
    background:#2563eb;
    color:white;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

.btn:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

/* TEXT */
.text{
    text-align:center;
    margin-top:20px;
    color:#64748b;
}

</style>
</head>
<body>

<div class="login-box">

    <h1>🔐 Login Admin</h1>

    <form method="POST">

        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" name="login" class="btn">
            Login
        </button>

    </form>

    <div class="text">
        Rental Mobil Admin Panel
    </div>

</div>

</body>
</html>