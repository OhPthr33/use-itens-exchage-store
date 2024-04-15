<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/Estilizar1.css">
    <title>Cadastro</title>
</head>
<body>
    <div class="img">
    <img src="./images/Clover1.png">
</div>
    <form action="Cadastro.php" method="post">
        <div id="login">
            <div class="card-header1">
                <div class="login1">
                    <h2>Cadastro</h2>
                </div>

                <div class="login2"></div>
                <div class="login3"></div>

                <div class="card">

                    <div class="card-header2">
                        <div class="form__group field">
                            <input type="text" name="nome" class="form__field" placeholder="nome" required autofocus>
                            <label for="nome" class="form__label">Nome</label>
                        </div>
                        <div class="form__group field">
                            <input type="text" name="e-mail" class="form__field" placeholder="E-mail" required autofocus>
                            <label for="e-mail" class="form__label">E-mail</label>
                        </div>
                        <div class="form__group field">
                            <input type="text" name="telefone" class="form__field" placeholder="telefone" required autofocus>
                            <label for="telefone" id="telefone" class="form__label">Telefone</label>
                        </div>
                        <div class="form__group field">
                            <input type="password" name="password" placeholder="senha" class="form__field"required autofocus> 
                            <label for="password" id="senha" class="form__label">Senha</label>
                        </div>
                        <div class="form__group field">
                            <input type="password" name="confirmaSenha" placeholder="confirmaSenha" class="form__field"required autofocus> 
                            <label for="confirmaSenha" id="confirmaSenhaLabel" class="form__label">Confirma Senha</label>
                            <br/>
                            <button>Cadastrar</button>
                            <a href="PageLog.php">
                                <div class="Roda">Já tem conta ?</div>
                            </a>
                        </div>
                        <a href="Indice_Atual.php">
                            <div class="home2">Página inicial</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
