<footer>
    <div class="rodape conteiner-fluid d-flex justify-content-center align-items-center flex-column">
        <div class="d-flex flex-row justify-content-around align-items-center">
            <img src="../images/iceCream.png" alt="Logo Amor de Casquinha" class="pe-4 logo">
            <h3>Amor de Casquinha</h3>
        </div>
        <img src="../images/Rectangle 1048.png" class="img pt-3 linha">
        <div class="text-center">
            <p>&copy; 2023 Amor de Casquinha Todos os direitos reservados</p>
        </div>
        <?php
            if(isset($_SESSION["userEmail"])){
                switch($_SESSION["userPerfil"]){                        
                    case "FUNC":
                        echo "<a href='faqFunc.php' class='text-black text-decoration-none mb-3 fs-6'>FAQ</a>";
                        break;
                        
                    case "FADM":
                        echo "<a href='faqFunc.php' class='text-black text-decoration-none mb-3 fs-6'>FAQ</a>";
                        break;
                        
                    case "ENTR":
                        echo "<a href='faqFunc.php' class='text-black text-decoration-none mb-3 fs-6'>FAQ</a>";
                        break;

                    default:
                        echo "<a href='faqCliente.php' class='text-black text-decoration-none mb-3 fs-6'>FAQ</a>";
                        break;
                }
            } else {
                echo "<a href='faqCliente.php' class='text-black text-decoration-none mb-3 fs-6'>FAQ</a>";
            }
            ?>
    </div>
</footer>

<script src="script/header.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>