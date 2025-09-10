<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="css/userpage.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">

</head>
<body>
    <header>
        <nav id="navbar">
            <div class="navbar">
                <img src="#" alt="Logo Brow Cursos">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cursos.php">Cursos</a></li>
                    <li><a href="certificados.php">Certificados</a></li>
                </ul>

                <!-- Menu que abre e fecha -->
                <div class="user-menu">
                    <div class="avatar" id="avatar" >
                        <img src="img/{A456EC26-8008-422A-A305-7DCB8EA96953}.png" alt="Foto de Perfil">
                    </div>
                    <div class="dropdown" id="menu">
                        <a href="perfil.php">Perfil</a>
                        <a href="certificados.php">Certificados</a>
                        <a href="ajuda.php">Ajuda</a>
                        <hr>
                        <a href="logout.php">Sair</a>
                    </div>
            </div>
        </nav>
    </header>



    <main>
        <section class="profile-section">
            <div class="primeirosLinks">
            <h1>Perfil</h1>
            <ul>
                <li><a href="certificados.php">Certificados</a></li>
                <li><a href="ajuda.php">Ajuda</a></li>
            </ul>
            </div>
            <div class="profile-container">
                <h1>Meu Perfil</h1>
                
                <div class="profile-info">
                    <img src="img/{A456EC26-8008-422A-A305-7DCB8EA96953}.png" alt="Foto de Perfil" class="profile-picture">
                    <div class="user-details">
                        <p>Nome</p>
                        <p>João Silva</p>
                        <p>E-mail</p> 
                        <p>joaosilva@gmail.com</p>
                        <p>Telefone</p>
                        <p>(11) 91234-5678</p>
                        <p>CPF</p>
                        <p>123.456.789-00</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sectrion de alterar a senha -->
        <section class="change-password-section">
            <div class="change-password-container">
                <h2>Alterar Senha</h2>
                <form action="#" method="post" class="change-password-form">
                    <label for="current-password">Senha Atual:</label>
                    <input type="password" id="current-password" name="current-password" required>

                    <label for="new-password">Nova Senha:</label>
                    <input type="password" id="new-password" name="new-password" required>

                    <label for="confirm-password">Confirmar Nova Senha:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>

                    <button type="submit">Alterar Senha</button>
                </form>
            </div>
    </main>


    <footer>
        <p>&copy; 2025 Grow Cursos</p>
    </footer>
            

    <script src="js/user_menu.js"></script> <!-- JS do menu -->
</body>
</html>