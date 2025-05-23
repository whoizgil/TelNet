<?php
session_start();
include('conexaosql.php');

if (isset($_POST['login']) && isset($_POST['senha'])) {
    if (empty($_POST['login'])) {
        echo "Preencha seu login";
    } else if (empty($_POST['senha'])) {
        echo "Preencha sua senha";
    } else {
        $login = $mysqli->real_escape_string($_POST['login']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        if (isset($_POST['tipousuario'])) {
            $tipousuario = $mysqli->real_escape_string($_POST['tipousuario']);
        } else {
            echo "Selecione o tipo de usuário (Master ou Comum)";
            exit();
        }


        $sql_code = "SELECT CPF, Nome, Email, Nome_Materno, Celular, Tel_Fixo, Endereco, Login, Data_Nascimento, Sexo, Senha, Tipo, Statuses, CEP FROM usuario WHERE login = '$login' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL:" . $mysqli->error);
        $usuario = $sql_query->fetch_assoc();
        $quantidade = $sql_query->num_rows;


        if ($quantidade == 1) {

            if ($usuario['Statuses'] == 2) {
                header("Location: ../../assets/error/erro_status_off.php");
            } else {
                $sql_tipo = "SELECT tipo FROM usuario WHERE login = '$login'";
                $result_tipo = $mysqli->query($sql_tipo);
                $tipo = $result_tipo->fetch_assoc()['tipo'];


                if ($tipousuario == $tipo) {
                    $_SESSION['nome'] = $usuario['Nome'];
                    $_SESSION['senha'] = $senha;
                    $_SESSION['login'] = $login;
                    $_SESSION['tipo'] = $usuario['Tipo'];
                    $_SESSION['CPF'] = $usuario['CPF'];
                    header("Location: ../../public/2fa.php");
                } else {
                    header("Location: ../../assets/error/erro_login.php");
                }
            }
            exit();
        } else {
            header("Location: ../../assets/error/erro_login.php");
            exit();
        }
    }
}
