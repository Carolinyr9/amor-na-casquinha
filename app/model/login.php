<?php 
    require_once '../config/database.php';
    session_start();

    class Login {
        private $conn;

        public function __construct(){
            $database = new DataBase();
            $this->conn = $database->getConnection();
        }

        public function login($email){

            if(isset($_SESSION["userEmail"])) {
                header('location: ../sobre.php');

            } else if(isset($_POST["email"]) && isset($_POST["senha"])){
                $email = $_POST["email"];
                $senha = $_POST["senha"];
            
                $stmt = $this->conn->prepare("CALL Login(?)");
                $stmt->bindParam(1, $email);
                $stmt->execute();
                $result = $stmt->fetchAll();
            
                foreach($result as $row)
                {
                    $perfil = $row["perfil"];
                    $_SESSION[$perfil] = $perfil;
                    $_SESSION['perfil'] = $perfil;
                    $desativado = $row["desativado"];
            
                    if($desativado == 1){
                        echo '
                        <script>
                            alert("Este usuário não possui mais acesso, entre em contato com um administrador!");
                            window.location.replace = "../view/login.php"; 
                        </script> 
                        ';
                    } else {
                        switch($perfil){
                            case 'FUNC':
                                // if(password_verify($senha, $row["senha"])) {
                                if($senha == $row["senha"]) {
                                    $_SESSION["userEmail"] = $row["email"];
                                    $_SESSION["userName"] = $row["nome"];
                                    $_SESSION["userTel"] = $row["telefone"];
                                    $_SESSION["userPerfil"] = ($row["adm"] == 1 ? "FADM" : "FUNC" );
                                    header("location: ../view/pedidos.php");
                                } else {
                                    echo '
                                        <script>
                                            alert("Senha errada!");
                                            location.replace("../view/login.php"); 
                                        </script> 
                                    ';
                                }
                                break;
                            case 'CLIE':
                                // if(password_verify($senha, $row["senha"])){
                                if($senha == $row["senha"]) {
                                    $_SESSION["userEmail"] = $row["email"];
                                    $_SESSION["userName"] = $row["nome"];
                                    $_SESSION["userTel"] = $row["telefone"];
                                    $_SESSION["userCep"] = $row["cep"];
                                    $_SESSION["userRua"] = $row["rua"];
                                    $_SESSION["userNum"] = $row["numero"];
                                    $_SESSION["userCompl"] = $row["complemento"];
                                    $_SESSION["userBairro"] = $row["bairro"];
                                    $_SESSION["userCidade"] = $row["cidade"];
                                    $_SESSION["userEstado"] = $row["estado"];
                                    $_SESSION["userEnderecoId"] = $row["idEndereco"];
                                    $_SESSION["userPerfil"] = $row["perfil"];
                                    header("location: ../view/sobre.php");
                                }
                                else
                                {
                                    echo '
                                        <script>
                                            alert("Senha errada!");
                                            location.replace("../view/login.php"); 
                                        </script> 
                                    ';
                                }
                                break;
                            case 'ENTR':
                                if($senha == $row["senha"]) {
                                    $_SESSION["userEmail"] = $row["email"];
                                    $_SESSION["userName"] = $row["nome"];
                                    $_SESSION["userTel"] = $row["telefone"];
                                    $_SESSION["userPerfil"] = $row["perfil"];
                                    header("location: ../view/pedidos.php");
                                } else {
                                    echo '
                                        <script>
                                            alert("Senha errada!");
                                            location.replace("../view/login.php"); 
                                        </script> 
                                    ';
                                }
                            default:
                                echo '
                                    <script>
                                        alert("Ocorreu um erro! Perfil não encontrado.");
                                        location.replace("../view/login.php");  
                                    </script> 
                                ';
                                break;
                        }
                    }
                }
            
            }
            else
            {
                header('location: ../view/index.php');
            }
            
        }
 
        //Depois substituir o arquivo de logoff por essa função
        public function logout() {
            session_start();
            session_destroy();
            header("location: ../view/index.php");
        }

    }
?>