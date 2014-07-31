<?php

/* /
  Plugin Name: Basic Mapa
  Plugin URI: www.ivinidesigner.com.br
  Description: Demonstra como um plugin trabalha
  Version: 1.0
  Author: Johnatan Ívini
  Author URI: www.ivinidesiger.com.br/sobre
  / */

function mapa_admin() {
    include 'basic_mapa.php';
}

function mapa_admin_actions() {
    add_menu_page('Mapas', 'Mapas', 1, 'Mapas', 'mapa_admin');
}

add_action('admin_menu', 'mapa_admin_actions');

function representantes_basic_mapa() {
    /** estados * */
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
    /** adicionando mapa de representantes * */
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Error Handling



    /** adicionado scripts do plugin * */
    wp_enqueue_script('raphael', plugins_url('js/mapa/raphael.js', __FILE__), false, 3.1, true);
    wp_enqueue_script('main', plugins_url('js/mapa/main.js', __FILE__), false, 3.1, true);
    wp_enqueue_style('estilo-r', plugins_url('js/mapa/css/estilo.css', __FILE__), false, 3.1, false);

    echo "<div id=\"canvas\">";
    echo "<div id=\"paper\"></div>";
    echo "</div>";
    echo "<div class=\"box-representantes\">";
    echo "<div class=\"titulo-mapa\"><h2>Mapa de Representantes</div>";
    echo "<p>Passe o mouse sobre os estados</p>";
    foreach ($estados as $e=>$k) {
        echo "<div id=".strtolower($e)." style=\"display:none\" class=\"est\">";
        $sql = "SELECT * FROM mapa_representantes WHERE estado =:e ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':e', $e);
        $stmt->execute();
        foreach ($stmt->fetchAll() as $es) {
         echo "<div class=\"estados\">
        <h2>" . $es['titulo'] ."-".$es['estado']."</h2>
        <p>" . $es['descricao'] . "</p>
        </div>";
        }
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";
    
}

add_shortcode('representantes', 'representantes_basic_mapa');



