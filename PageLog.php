<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/Estilizar.css" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <title>Login</title>
</head>
<body>
    <Form action="LoginUser.php" method="post">
        <div id="login2">
            <div class="card-header1">
                <div class="login1">
                    <h2>Login</h2>
                </div>
                <div class="login4"></div>
                <div class="login5"></div>
                    <div class="card">
                        <div class="card-header2">
                            <div class="form__group field">
                                <input type="text" class="form__field" placeholder="E-mail" name="email" required autofocus/>
                                <label for="username" class="form__label">E-mail</label>
                              </div>
                            </div>
                            <div class="form__group field">
                                <input type="password" class="form__field" placeholder="Senha" name="password" required />
                                <label for="password" class="form__label">senha</label>
                            <br/>
                            <div class="card-footer">
                                <button type="validar" name="validar">Entrar</button>
                        </div>
                        <a href="PageCad.php">
                    <div class="Roda">Não tem conta?</div>
                </a>
                <a href="Indice_Atual.php">
                    <div class="home">Página inicial</div>
                </a>
            </div>
            </div>
        </div>
    </div> 
</Form>
</body>
</html>