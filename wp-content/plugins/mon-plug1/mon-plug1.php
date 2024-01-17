<?php
/*
Plugin Name: mon-plug1
*/

// Charger l'environnement Wordpress
require_once(ABSPATH . 'wp-load.php');

register_activation_hook(__FILE__, function () {
    // Je suis activé
});

register_deactivation_hook(__FILE__, function () {
    // Je suis désactivé
});

// Hook pour ajouter une page dans le menu d'administration
add_action('admin_menu', 'mon_plug1_add_menu');

// Fonction d'ajout de la page de formulaire
function mon_plug1_add_menu() {
    add_menu_page(
        'Mon Plug1',
        'Mon Plug1',
        'manage_options',
        'mon-plug1',
        'mon_plug1_page'
    );
}

// Fonction de rappel pour afficher le contenu de la page de formulaire
function mon_plug1_page() {
    global $wpdb;

    // Récupérer les catégories de formulaire
    $resultsCat = $wpdb->get_results("SELECT * FROM wp_category WHERE id_attribute = 16");
    //error_log("resultCat: " . print_r($resultsCat, 1));
    
    // Vérifier si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Créer l'entité et récupérer son ID
        if(isset($_POST['Envoyer'])){
            $wpdb->query("INSERT INTO wp_entity (status) VALUES (1)");
            $entity_id = $wpdb->insert_id;
        
            // Récupérer les attributs depuis la requête SQL
            $attributeResults = $wpdb->get_results("SELECT * FROM wp_attribute");
        
            // Tableau associatif des correspondances entre les champs de formulaire et les ID d'attribut
            $attributeMappings = array();
            
            foreach ($attributeResults as $attribute) {
                $fieldName = '';
                if (is_numeric($attribute->name_attribute)) {
                    $fieldName = 'pointure' . $attribute->name_attribute;
                } else {
                    $fieldName = $attribute->name_attribute;
                }
                $attributeMappings[$fieldName] = $attribute->id_attribute;
            }
            //error_log("Attribute Mapping :".print_r($attributeMappings, 1));
            // Parcourir les champs de formulaire
            foreach ($attributeMappings as $field => $attributeId) {
                if (isset($_POST[$field])) {
                    $value = sanitize_text_field($_POST[$field]);
        
                    // Insérer les données dans wp_value
                    $wpdb->query($wpdb->prepare("INSERT INTO wp_value (id_attribute, id_entity, name_value) VALUES (%d, %d, %s)", $attributeId, $entity_id, $value));
                }
            }
        }
    }
    
    


    // Afficher le formulaire de sélection de catégorie
    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Add New Custom Data</h1>';
    echo '<hr class="wp-header-end">';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category'])) {
        $selected_category = $_POST['category'];
        //error_log($selected_category);
        $resultsSta = $wpdb->get_results("SELECT * FROM wp_category WHERE id_attribute = 17");
        // Récupérer les éléments de formulaire correspondant à la catégorie sélectionnée
        $resultsElements = $wpdb->get_results($wpdb->prepare("SELECT e.*
                                                            FROM wp_formulaire_element e
                                                            JOIN wp_formulaire_liaison l 
                                                            ON e.id_attribute = l.id_attribute
                                                            WHERE l.id_category = %d", $selected_category));
        //error_log("ResultsElements: " . print_r($resultsElements, 1));

        // Afficher le formulaire correspondant
        echo '<form method="post">';
        echo '<table class="form-table">';
        foreach ($resultsElements as $element) {
            echo $element->elem_form_name;
        }
        echo '<tr>';
        echo '<th scope="row"><label for="statut">Statut</label></th>';
        echo '<td>';
        echo '<select name="statut" id="statut" class="regular-text" required>';
        echo '<option value="">Choisissez un statut</option>';
        foreach ($resultsSta as $sta) {
            echo '<option value="' . esc_html($sta->id_category) . '">' . esc_html($sta->name_category) . '</option>';
        }
        echo '</select>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        // Ajouter un champ nonce pour la sécurité
        wp_nonce_field('custom_data_add', 'custom_data_nonce');

        echo '<input name="Envoyer" type="submit" value="Envoyer">';
        echo '</form>';
    } else {
        echo '<form method="post">';
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th scope="row"><label for="category">Catégorie</label></th>';
        echo '<td>';
        echo '<select name="category">';
        echo '<option value="">Sélectionner une catégorie</option>';
        foreach ($resultsCat as $category) {
            echo '<option value="' . esc_html($category->id_category) . '">' . esc_html($category->name_category) . '</option>';
        }
        echo '</select>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';

        // Ajouter un champ nonce pour la sécurité
        wp_nonce_field('custom_data_add', 'custom_data_nonce');

        echo '<input type="submit" value="Sélectionner">';
        echo '</form>';
    }

    echo '</div>';
}
