<!DOCTYPE html>
<html>
<head>
	<title>Mangan Uenak</title>
	<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<style>
  body {
  background-image: url('img/bg.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  }
</style>

<body>
 
	<?php 
	if(isset($_GET['pesan'])){
		if($_GET['pesan']=="gagal"){
			echo "<script type='text/javascript'>alert('Akun invalid');</script>";
		}
	}
	?>
 
  <div class="login-container">
    <div class="login-box">
      <h2>Log in</h2>
      <form id="login-form">
        <div class="input-group">
          <label for="username"></label>
          <input type="text" id="input-username" name="username" placeholder="Username" class="form-control">
        </div>
        <div class="input-group">
          <label for="password"></label>
          <input type="password" id="input-password" name="password" placeholder="Password" class="form-control">
        </div>
        <button type="submit" id="btn-login" class="btn btn-primary">Login</button>
        <p id="login-error" style="color:red; display:none; margin-top:10px;"></p>
      </form>
      <p class="signup-text">atau <a href="createuser.php">Buat Akun</a></p>
    </div>
  </div>
		
<script src="assets/js/api_helper.js"></script>
<script>
document.getElementById('login-form').addEventListener('submit', async function(e) {
    e.preventDefault(); // Cegah form melakukan refresh halaman (GET)

    const username = document.getElementById('input-username').value.trim();
    const password = document.getElementById('input-password').value.trim();
    const btnLogin = document.getElementById('btn-login');
    const errMsg   = document.getElementById('login-error');

    // Validasi kosong
    if (!username || !password) {
        errMsg.textContent = 'Username dan password wajib diisi.';
        errMsg.style.display = 'block';
        return;
    }

    // Tombol loading
    btnLogin.disabled    = true;
    btnLogin.textContent = 'Memproses...';
    errMsg.style.display = 'none';

    // Panggil API
    const result = await apiRequest('/auth/login', 'POST', { username, password });

    if (!result) {
        btnLogin.disabled    = false;
        btnLogin.textContent = 'Login';
        return;
    }

    if (result.ok) {
        try {
            // Login berhasil — simpan JWT ke PHP session
            const saveRes = await fetch('/uts-testing-main/kelompok_gamasuk/Resto1/save_token.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({
                    token:    result.data.data.token,
                    level:    result.data.data.level,
                    username: result.data.data.username
                })
            });

            const saveText = await saveRes.text();
            
            try {
                const saveJson = JSON.parse(saveText);
                if (saveJson.status === 'ok') {
                    showToast('Login berhasil! Mengalihkan...', 'success');
                    setTimeout(() => {
                        window.location.href = result.data.data.level === 'admin' 
                            ? 'halaman_admin.php' 
                            : 'halaman_user.php'; 
                    }, 800);
                } else {
                    alert('Gagal menyimpan sesi PHP: ' + saveText);
                    btnLogin.disabled = false;
                    btnLogin.textContent = 'Login';
                }
            } catch (parseErr) {
                alert('Terdapat error di save_token.php:\n' + saveText);
                btnLogin.disabled = false;
                btnLogin.textContent = 'Login';
            }

        } catch (fetchErr) {
            alert('File save_token.php tidak ditemukan di folder Resto1!');
            btnLogin.disabled = false;
            btnLogin.textContent = 'Login';
        }

    } else {
        // Login gagal (password salah, dll)
        errMsg.textContent   = result.data.message || 'Login gagal.';
        errMsg.style.display = 'block';
        btnLogin.disabled    = false;
        btnLogin.textContent = 'Login';
    }
});
</script>
 
</body>
</html>