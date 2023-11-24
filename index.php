<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Corretor</title>

    <style>
    .divCorretor {
        width: 500px;
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
    }

    .divErro {
        width: 500px;
        height: 10px;
        border-radius: 5px;
        background-color: #ff0000;
        padding: 20px;
        text-align: center;
    }

    .divSucesso {
        width: 500px;
        height: 10px;
        border-radius: 5px;
        background-color: #4CAF50;
        padding: 20px;
        text-align: center;
    }
    
    .botaoCorretor {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 7px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .botaoCorretor:hover {
        background-color: #45a049;
    }
</style>

</head>
<body>



    <div class="divCorretor">
        <h2 align="center">Cadastro de Corretor</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" id="cpf" name="cpf" placeholder="Digite seu CPF" style="width:240px">
            <input type="text" id="creci" name="creci" placeholder="Digite seu Creci" style="width:240px">
            <br><br>
            <input type="text" id="nome" name="nome" placeholder="Digite seu Nome" style="width:490px">
            <br><br>
            <input type="submit" value="Salvar" class="botaoCorretor">
        </form>
    </div>

    <?php


    // Configurações do banco de dados
 
    $servername = "localhost:3306";
    $username = "root";
    $password = "";
    $dbname = "juliaguerra";
 
    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Verifica a conexão
    if ($conn->connect_error) {
        echo("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }


    
 
    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["editar"])) {
            // Captura o ID do corretor a ser editado
            $corretor_id = $_POST["editar"];
    
            // Obtém os dados do corretor a ser editado
            $sql = "SELECT id, cpf, creci, nome FROM corretor WHERE id = $corretor_id";
            $result = $conn->query($sql);
    
            if ($result->num_rows > 0) {
                $corretor = $result->fetch_assoc();
                $cpf = preg_replace("/\D/", '', $corretor['cpf']);


                // Exibe o formulário de edição com os dados do corretor
                echo "<div class='divCorretor'>";
                echo "<h2 align='center'>Editar Corretor</h2>";
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";

                echo "<input type='hidden' name='corretor_id' value='{$corretor['id']}'>";
                echo "<input type='text' name='cpf' value='$cpf' placeholder='Digite seu CPF' style='width:240px'>"; 
                echo "<input type='text' name='creci' value='{$corretor['creci']}' placeholder='Digite seu Creci' style='width:240px'><br><br>";

                echo "<input type='text' name='nome' value='{$corretor['nome']}' placeholder='Digite seu Nome' style='width:490px'><br><br>";

                echo "<input type='submit' class='botaoCorretor' value='Alterar'>";
                echo "</form><br/><br/>";
                echo "</div>";
            }
        } elseif (isset($_POST["corretor_id"])) {

            // Se o formulário foi enviado após a edição, atualiza os dados no banco de dados
            $corretor_id = $_POST["corretor_id"];
            $cpf = $_POST["cpf"];
            $creci = $_POST["creci"];
            $nome = $_POST["nome"];
    

            $formularioValido = true;

            $CPF_LENGTH = 11;
            $CRECI_NOME_LENGTH = 2;
            
            $cpf = preg_replace("/\D/", '', $cpf);
            if (strlen($cpf) === $CPF_LENGTH) {
                $cpf = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf);
            } else {
                $formularioValido = false;
                echo "<div class='divErro'>CPF Inválido</div>";
            }


            $creci = $_POST["creci"];
            if (strlen($creci) < $CRECI_NOME_LENGTH) {
                $formularioValido = false;
                echo "<div class='divErro'>Creci Inválido</div>";
            }

            $nome = $_POST["nome"];
            if (strlen($nome) < $CRECI_NOME_LENGTH) {
                $formularioValido = false;
                echo "<div class='divErro'>Nome Inválido</div>";
            }


            if($formularioValido) {
                $sql = "UPDATE corretor SET cpf='$cpf', creci='$creci', nome='$nome' WHERE id=$corretor_id";
        
                if ($conn->query($sql) === TRUE) {
                    echo "<div class='divSucesso'>Dados Alterados com sucesso!</div>";
                } else {
                    echo "Erro ao editar o corretor: " . $conn->error;
                }
            }
            // Verifica se a ação é de exclusão
        } elseif(isset($_POST["excluir"])) {
            // Captura o ID do corretor a ser excluído
            $corretor_id = $_POST["excluir"];

            // Exclui o corretor do banco de dados
            $sql = "DELETE FROM corretor WHERE id = $corretor_id";

            if ($conn->query($sql) === TRUE) {
                echo "<div class='divSucesso'>Dados Excluídos com sucesso!</div>";
            }
        } else {
            $formularioValido = true;

            $CPF_LENGTH = 11;
            $CRECI_NOME_LENGTH = 2;
            
            $cpf = preg_replace("/\D/", '', $_POST["cpf"]);
            if (strlen($cpf) === $CPF_LENGTH) {
                $cpf = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf);
            } else {
                $formularioValido = false;
                echo "<div class='divErro'>CPF Inválido</div>";
            }


            $creci = $_POST["creci"];
            if (strlen($creci) < $CRECI_NOME_LENGTH) {
                $formularioValido = false;
                echo "<div class='divErro'>Creci Inválido</div>";
            }

            $nome = $_POST["nome"];
            if (strlen($nome) < $CRECI_NOME_LENGTH) {
                $formularioValido = false;
                echo "<div class='divErro'>Nome Inválido</div>";
            }


            if($formularioValido) {
                $sql = "INSERT INTO corretor (cpf, creci, nome) VALUES ('$cpf', '$creci', '$nome')";

                if ($conn->query($sql) === TRUE) {
                    echo "<div class='divSucesso'>Dados Cadastrados com sucesso!</div>";
                }
            }

        }

    }
    


        echo "<div class='divCorretor'>";
        echo "<h2 align='center'>Lista de Corretores</h2>";

        // Conecta novamente para obter a lista atualizada
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        // Verifica a conexão novamente
        if ($conn->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }
    
        // Obtém a lista de corretores
        $sql = "SELECT id, cpf, creci, nome FROM corretor";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tbody>";
            echo "<tr>";
            echo "<td style='width:30%'>CPF</td>";
            echo "<td style='width:30%'>CRECI</td>";
            echo "<td style='width:30%'>NOME</td>";
            echo "<td style='width:30%'></td>";
            echo "</tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['nome']}</td>";
                echo "<td>{$row['cpf']}</td>";
                echo "<td>{$row['creci']}</td>";               

                echo "<td><form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='editar' value='{$row['id']}'>";
                echo "<input type='submit' value='Editar'></form></td>";
                
                echo "<td><form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='excluir' value='{$row['id']}'>";
                echo "<input type='submit' value='Excluir'></form></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }
        echo "</div>";
    

    ?>

</body>
</html>