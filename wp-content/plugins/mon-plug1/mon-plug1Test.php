<?php
/*
Plugin Name: mon-plug1
*/

// charger l'environnemt Wordpress
require_once(ABSPATH . 'wp-load.php');

register_activation_hook(__FILE__, function () {
// Je suis activé
});

register_deactivation_hook(__FILE__, function () {
// Je suis désactivé
});

// Hook pour ajouter une page dans le menu d'administration
add_action('admin_menu', 'mon_plugin1_add_menu');

// Fonction d'ajout de la page de formulaire
function mon_plugin1_add_menu() {
    add_menu_page(
        'Mon Plugin 1',
        'Mon Plugin 1',
        'manage_options',
        'mon-plugin1',
        'mon_plugin1_page'
    );
}

// Fonction de rappel pour afficher le contenu de la page de formulaire
function mon_plugin1_page() {

        global $wpdb;

        // Récupérer les catégories de formulaire
        $resultsCat = $wpdb->get_results("SELECT * FROM wp_category WHERE id_attribute = 16 ");
    
        // Récupérer les éléments de formulaire
        $elements = $wpdb->get_results("SELECT * FROM wp_formulaire_element");
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['custom_data_nonce']) && wp_verify_nonce($_POST['custom_data_nonce'], 'custom_data_add')) {
        if(isset($_POST['title'])){
        $title = sanitize_text_field($_POST['title']);}
        if(isset($_POST['content'])){
        $content = sanitize_textarea_field($_POST['content']);}
        if(isset($_POST['price'])){
        $price = sanitize_text_field($_POST['price']);}
        if(isset($_POST['image'])){
        $image = sanitize_text_field($_POST['image']);}
        if(isset($_POST['pointure40'])){
        $pointure40 = $_POST['pointure40'];}
        if(isset($_POST['pointure41'])){
        $pointure41 = $_POST['pointure41'];}
        if(isset($_POST['pointure42'])){
        $pointure42 = $_POST['pointure42'];}
        if(isset($_POST['pointure425'])){
        $pointure425 = $_POST['pointure425'];}
        if(isset($_POST['pointure43'])){
        $pointure43 = $_POST['pointure43'];}
        if(isset($_POST['pointure44'])){
        $pointure44 = $_POST['pointure44'];}
        if(isset($_POST['pointure45'])){
        $pointure45 = $_POST['pointure45'];}
        if(isset($_POST['pointure46'])){
        $pointure46 = $_POST['pointure46'];}
        if(isset($_POST['pointure47'])){
        $pointure47 = $_POST['pointure47'];}
        if(isset($_POST['pointure48'])){
        $pointure48 = $_POST['pointure48'];}
        if(isset($_POST['couleur'])){
        $couleur = sanitize_text_field($_POST['couleur']);}
        if(isset($_POST['category'])){
        $category = $_POST['category'];}
        if(isset($_POST['epaisseur'])){
        $epaisseur =$_POST['epaisseur'];}
        

        

        // Insérer les données dans wp_entity
        $wpdb->query("INSERT INTO wp_entity (status) VALUES (1)");
        $entity_id = $wpdb->insert_id; // Récupérer l'ID de l'entité nouvellement créée
        //error_log($entity_id);
        // Insérer les données dans wp_value
        if(!empty($title)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES ('1', '$entity_id', '$title')");}
        if(!empty($content)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES ('2', '$entity_id', '$content')");}
        if(!empty($price)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES ('3', '$entity_id', '$price')");}
        if(!empty($image)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('4', '$entity_id', '$image')");}
        if(!empty($pointure40)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('5', '$entity_id', '$pointure40')");}
        if(!empty($pointure41)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('6', '$entity_id', '$pointure41')");}
        if(!empty($pointure42)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('7', '$entity_id', '$pointure42')");}
        if(!empty($pointure425)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('8', '$entity_id', '$pointure425')");}
        if(!empty($pointure43)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('9', '$entity_id', '$pointure43')");}
        if(!empty($pointure44)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('10', '$entity_id', '$pointure44')");}
        if(!empty($pointure45)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('11', '$entity_id', '$pointure45')");}
        if(!empty($pointure46)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('12', '$entity_id', '$pointure46')");}
        if(!empty($pointure47)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('13', '$entity_id', '$pointure47')");}
        if(!empty($pointure48)){
           $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('14', '$entity_id', '$pointure48')");}
        if(!empty($couleur)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('15', '$entity_id', '$couleur')");}
        if(!empty($category)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('16', '$entity_id', '$category')");}
        if(!empty($epaisseur)){
            $wpdb->query("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES('18', '$entity_id', '$epaisseur')");}


            echo 'Données ajoutées avec succès !';
        }

        // Afficher le formulaire
        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">Add New Custom Data</h1>';
        echo '<hr class="wp-header-end">';

        echo '<form method="post">';
        echo '<table class="form-table">';
    

        // Parcourir les catégories
        echo '<tr>';
        echo '<th scope="row"><label for="category">Categorie</label></th>';
        echo '<td>';
        echo '<select name="category" id="category" class="regular-text" required>';
        echo '<option value="">Choisissez une catégorie</option>';
        foreach ($resultsCat as $cat) {
            echo '<option value="' . esc_html($cat->id_category) . '" class="category-option">' . esc_html($cat->name_category) . '</option>';
        }        
        echo '<input type="submit" name="submit" value="Sélectionner">';
        echo '</select>';
        echo '</td>';
        echo '</tr>';    
        echo '</table>';
        echo '</form>';
        echo '</div>';


        echo '<div class="wrap">';
        echo '<form method="post">';
        echo '<table class="form-table">';
        if (isset($_POST['category'])) {
            $selectedCategoryId = $_POST['category'];
            foreach ($resultsCat as $cat) {
                if ($selectedCategoryId == $cat->id_category) {
                    $elements_categorie = $wpdb->get_results("SELECT elem_form_name
                                                                FROM wp_formulaire_element e
                                                                JOIN wp_formulaire_liaison l
                                                                ON e.id_elem_form = l.id_elem_form
                                                                WHERE l.id_category = $cat->id_category");
        error_log(print_r($cat->id_category));
                    // Afficher les éléments de formulaire
                    foreach ($elements_categorie as $element) {
                        //error_log(print_r($element, 1));
                        echo $element->elem_form_name;
                    }
                    break;
                }
            }
        }
        echo '<tr>';
        echo '<td><input type="submit" name="submit" value="Envoyer"></td>';
        echo '</tr>';
        echo '</table>';
        echo '</form>';
        echo '</div>';
}



?>