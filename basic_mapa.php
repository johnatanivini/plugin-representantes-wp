<?php
/** 
* 
* conexao com o banco de dados e criacao de tabela
*
*  */
try {

    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Error Handling
    $table = 'mapa_representantes';
    $colunas = " ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY, estado varchar(30), descricao text, titulo varchar(50) ";
    $criaTabela = $pdo->exec("CREATE TABLE IF NOT EXISTS " . $table . "(" . $colunas . ")");
} catch (PDOException $e) {
    //echo $e->getMessage();
}


/** shotcodes **/




/** scripts **/
wp_enqueue_script('bootstrap-js', plugins_url('js/bootstrap/js/bootstrap.min.js', __FILE__), false, 3.1, true);
wp_enqueue_script('main', plugins_url('js/main.js', __FILE__), false, 3.1, true);
wp_enqueue_style('bootstrap-css', plugins_url('js/bootstrap/css/bootstrap.min.css', __FILE__), false, 3.1, false);
wp_enqueue_style('estilo', plugins_url('css/style.css', __FILE__), false, 3.1, false);




$estados = array(
    "AC" => "Acre",
    "AL" => "Alagoas",
    "AM" => "Amazonas",
    "AP" => "Amapá",
    "BA" => "Bahia",
    "CE" => "Ceará",
    "DF" => "Distrito Federal",
    "ES" => "Espirito Santo",
    "GO" => "Goiais",
    "MA" => "Maranhão",
    "MT" => "Mato Grosso",
    "MG" => "Minas Gerais",
    "MS" => "Mato Grosso do Sul",
    "PA" => "Pará",
    "PB" => "Paraíba",
    "PR" => "Paraná",
    "PE" => "Pernambuco",
    "PI" => "Piauí",
    "RJ" => "Rio de Janeiro",
    "RN" => "Rio Grande do Norte",
    "RO" => "Rondônia",
    "RS" => "Rio Grande do Sul",
    "RR" => "Roraima",
    "SC" => "Santa Catarina",
    "SE" => "Sergipe",
    "SP" => "São Paulo",
    "TO" => "Tocantins"
);
?>
<div class="container well well-large">
    <div class="col-md-12">

        <h1>Olá,<?php echo wp_get_current_user()->user_login; ?></h1>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#representantes" role="tab" data-toggle="tab">Cadastrar Representantes</a></li>
            <li><a href="#lista" role="tab" data-toggle="tab">Ver Representantes</a></li>
            <?php if (isset($_GET['editar'])) { ?>
                <li><a href="#editar" role="tab" data-toggle="tab">Edição</a></li>
            <?php } ?>
        </ul>



        <div class="tab-content">
            <div class="tab-pane active" id="representantes">
                <form class="form-wrap" name="mapa_admin_form" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                    <label>Selecione o Estado:</label>
                    <select name="estado_form" id="estado_form" class="form-control">
                        <option>Escolha um estado</option>
                        <?php foreach ($estados as $e => $k) { ?>
                            <option value="<?= $e; ?>" <?= $_POST['estado_form'] == $e ? 'selected' : ''; ?>><?= $k; ?></option> 
                        <?php } ?>
                    </select>

                    <div class="representantes">
                        <p>Titulo:</p>
                        <p><input type="text" class="form-control" name="titulo_form" value="<?= $_POST['titulo_form']; ?>"/></p>
                        <p>Descrição:</p>
                        <p><?php
                            $settings = array('media_buttons' => false);
                            wp_editor($_POST['descricao_form'], 'descricao_form', $settings);
                            ?> </p>
                    </div>

                    <p class="submit">
                        <input type="hidden" name="mapa_hidden"  value="Y"/>
                        <input type="submit" name="submit" value="Atualizar" class="btn btn-primary" />
                    </p>

                </form>

            </div> <!-- end panel representantes -->
            <div class="tab-pane" id="lista">
                <h2>Lista</h2>

                <div id="descricao">
                    <table class="widefat fixed table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Titulo</th>
                                <th>Descricao</th>
                                <th>Estado</th>
                                <th>Excluir</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM  mapa_representantes ";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();

                            foreach ($stmt->fetchAll() as $es) {
                                ?>
                                <tr>
                                    <td><?= $es['ID'] ?></td>
                                    <td><?= $es['titulo']; ?></td>
                                    <td><?= substr($es['descricao'],0,100); ?></td>
                                    <td><?= $es['estado']; ?></td>
                                    <td><a href="<?=  site_url();?>/wp-admin/admin.php?page=Mapas&excluir=<?= $es['ID'] ?>" id="<?= $es['ID'] ?>">[x] excluir</a></td>
                                    <td><a href="<?=  site_url();?>/wp-admin/admin.php?page=Mapas&editar=<?= $es['ID'] ?>#editar" id="<?= $es['ID'] ?>">[ ] editar</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div> <!-- end panel lista -->
        </div> <!-- end tab content -->


        <?php
        if ($_POST['mapa_hidden'] == 'Y') {

            $titulo = $_POST['titulo_form'];
            $descricao = $_POST['descricao_form'];
            $estado = $_POST['estado_form'];

            $sql = "INSERT INTO mapa_representantes SET "
                    . " titulo=:titulo,"
                    . " descricao=:descricao,"
                    . "estado=:estado ";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':estado', $estado);

            if ($estado == NULL || $estado == '') {
                echo "<div class='updated'><p><strong> Escolha um Estado;</strong></p></div>";
            }
            if ($titulo == NULL || $titulo == '') {
                echo "<div class='updated'><p><strong> Coloque um titulo;</strong></p></div>";
            }
            if ($descricao == NULL || $descricao == '') {
                echo "<div class='updated'><p><strong> Coloque sua descrição;</strong></p></div>";
            }

            if ($stmt->execute()) {
                
                $_SESSION['mensagem'] =  "<div class='updated'><p><strong>Dados Inseridos</strong></p></div>";
                echo "<script>location.href='".site_url()."/wp-admin/admin.php?page=Mapas#lista'</script>";
            } else {

                $_SESSION['mensagem'] =  "<div class='updated'><p><strong>Dados não foram inseridos, " . $pdo->errorInfo() . " </strong></p></div>";
                echo "<script>location.href='".site_url()."/wp-admin/admin.php?page=Mapas#lista'</script>";
            }
        }



        if (isset($_GET['excluir'])) {
            $stmt = $pdo->prepare("DELETE FROM mapa_representantes WHERE id=:id");
            $stmt->bindValue(':id', intval($_GET['excluir']));
            if ($stmt->execute()) {
               $_SESSION['deletado'] = "<div class='updated'><p><strong>Dado Deletado</strong></p></div>";
                echo "<script>location.href='".site_url()."/wp-admin/admin.php?page=Mapas#lista'</script>";
            } else {
                $_SESSION['deletado'] = "<div class='updated'><p><strong>O regitro não foi deletado," . $pdo->errorInfo() . " </strong></p></div>";
                echo "<script>location.href='".site_url()."/wp-admin/admin.php?page=Mapas#lista'</script>";
            }
        }

        
        if (isset($_GET['update'])) {
            
        }
        ?>
        <?php if($_SESSION['deletado']){
            echo $_SESSION['deletado'];
        }?>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>

