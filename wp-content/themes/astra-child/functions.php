<?php

session_start();

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'astra-theme-css' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

function custom_data_menu() {
    $page_title = 'Custom Data';
    $menu_title = 'Custom Data';
    $capability = 'manage_options';
    $menu_slug = 'custom-data';
    $function = 'custom_data_page';
    $icon_url = 'dashicons-admin-generic';
    $position = 25;

    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);

    // Submenu pages
    add_submenu_page($menu_slug, 'Add New', 'Add New', $capability, 'custom-data-add', 'custom_data_add_page');
    add_submenu_page($menu_slug, 'Edit', 'Edit', $capability, 'custom-data-edit', 'custom_data_edit_page');
}
add_action('admin_menu', 'custom_data_menu');

function custom_data_page() {

    global $wpdb;

    $results = $wpdb->get_results("SELECT id_entity FROM wp_entity");
    //error_log(print_r($results, 1));
    $results2 = $wpdb->get_results("SELECT DISTINCT name_attribute 
                                    FROM wp_attribute
                                    ORDER BY id_attribute");
    //error_log(print_r($results2, 1));
    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Custom Data</h1>';
    echo '<a href="' . admin_url('admin.php?page=custom-data-add') . '" class="page-title-action">Add New</a>';
    echo '<hr class="wp-header-end">';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th></th>';

    foreach ($results2 as $row2) {
            echo '<th>' . esc_html($row2->name_attribute) . '</th>';
    }

    echo '</tr></thead>';
    echo '<tbody>';
    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . esc_html($row->id_entity) . '</td>';        
    
        $results3 = $wpdb->get_results("SELECT * ,GROUP_CONCAT( name_value SEPARATOR '-') AS name_value
                                        FROM wp_value
                                        WHERE id_entity=$row->id_entity
                                        GROUP BY id_attribute");
        //error_log(print_r($results3, 1));                                
        foreach ($results3 as $row3){
            echo '<td>' . esc_html($row3->name_value) . '</td>';
        }
        echo '<td><a href="' . admin_url('admin.php?page=custom-data-edit&id=' . $row->id_entity) . '">Edit</a> | <a href="#" class="delete-link" data-id="' . $row->id_entity . '">Delete</a></td>';}
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

function custom_data_add_page() {
    include_once(ABSPATH . 'wp-content/plugins/mon-plug1/mon-plug1.php');
    echo mon_plug1_page();
}

function custom_data_edit_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_data';
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));

    if (!$row) {
        echo '<div class="notice notice-error is-dismissible"><p>Invalid custom data ID!</p></div>';
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['custom_data_nonce']) && wp_verify_nonce($_POST['custom_data_nonce'], 'custom_data_edit')) {
        $title = sanitize_text_field($_POST['title']);
        $content = sanitize_textarea_field($_POST['content']);
        $updated_at = current_time('mysql');

        $wpdb->update($table_name, compact('title', 'content', 'updated_at'), array('id' => $id));

        echo '<div class="notice notice-success is-dismissible"><p>Custom data updated successfully!</p></div>';
        echo '<script>window.location.href="' . admin_url('admin.php?page=custom-data') . '";</script>';
    }

    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Edit Custom Data</h1>';
    echo '<hr class="wp-header-end">';

    echo '<form method="post">';
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th scope="row"><label for="title">Title</label></th>';
    echo '<td><input name="title" type="text" id="title" value="' . esc_attr($row->title) . '" class="regular-text" required></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th scope="row"><label for="content">Content</label></th>';
    echo '<td><textarea name="content" id="content" class="large-text" rows="10" required>' . esc_textarea($row->content) . '</textarea></td>';
    echo '</tr>';
    echo '</table>';

    echo '<input type="hidden" name="custom_data_nonce" value="' . wp_create_nonce('custom_data_edit') . '">';
    echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update"></p>';
    echo '</form>';
    echo '</div>';
}

function delete_custom_data() {
    check_ajax_referer('delete_custom_data_nonce', 'nonce');
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_data';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    $result = $wpdb->delete($table_name, array('id' => $id));
    wp_send_json_success($result);
}
add_action('wp_ajax_delete_custom_data', 'delete_custom_data');

function custom_data_admin_scripts() {
    wp_enqueue_script('custom-data', get_template_directory_uri() . '/js/custom-data.js', array('jquery'), false, true);
    wp_localize_script('custom-data', 'customData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'delete_nonce' => wp_create_nonce('delete_custom_data_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'custom_data_admin_scripts');


function display_product($id_product) {
    global $wpdb;

    // Récupérer les données de l'entité
    $entity = $wpdb->get_row("SELECT * FROM wp_entity WHERE id_entity = {$id_product}", ARRAY_A);
    if (!$entity) {
        echo "Produit non trouvé";
        return;
    }

    // Récupérer les valeurs des attributs
    $attributes = $wpdb->get_results("SELECT * FROM wp_value WHERE id_entity = {$id_product}", ARRAY_A);

    // Organiser les attributs par leur identifiant
    $attributesById = [];
    foreach ($attributes as $attribute) {
        $attributesById[$attribute['id_attribute']] = $attribute['name_value'];
    }

    // Récupérer les noms des attributs
    $attributeNames = $wpdb->get_results("SELECT * FROM wp_attribute", ARRAY_A);

    //Organiser les noms des attributs par leur identifiant
    $attributeNamesById = [];
    foreach ($attributeNames as $attributeName) {
        $attributeNamesById[$attributeName['id_attribute']] = $attributeName['name_attribute'];
    }
    //error_log("{$attributesById[15]}");

    // Récupérer le nom de la catégorie
    $category = $wpdb->get_row("SELECT name_category FROM wp_category WHERE id_category = {$attributesById[16]}", ARRAY_A);

    // Récupérer le statut
    $status = $wpdb->get_row("SELECT name_category FROM wp_category WHERE id_category = {$attributesById[17]}", ARRAY_A);

    // Début de la mise en page
    echo "<div style='display: flex; align-items: center;'>";

    // Afficher l'image
    echo "<div style='flex: 1;'>";
    echo "<img src='{$attributesById[4]}' alt='{$attributesById[1]}' style='width: 100%; max-width: 400px;' />";
    echo "</div>";

    // Afficher les informations
    echo "<div style='flex: 1; margin-left: 20px;'>";
    echo "<form method='post' action=''>";

    echo "<h1>{$attributesById[1]}</h1>";
    echo "<p>{$attributesById[2]}</p>";
    echo "<h4>{$attributesById[3]}</h4>";

    // Afficher les boutons de pointure
    echo "<h4>Pointures disponibles:</h4>";
    echo "<div>";

    $selectedSize = isset($_POST['size']) ? $_POST['size'] : '';

    for ($i = 5; $i <= 14; $i++) {
        $size = $attributeNamesById[$i];
        $isAvailable = $attributesById[$i] === '1';
        $buttonClass = $isAvailable ? 'available' : 'unavailable';
        $buttonDisabled = $isAvailable ? '' : 'disabled';

        // Ajouter la classe 'selected' au bouton de pointure sélectionné
        $buttonSelected = $size === $selectedSize ? 'selected' : '';

        echo "<button type='button' name='size' class='size-button {$buttonClass}' value='{$size}' {$buttonDisabled}>{$size}</button>";
    }

    echo "</div>";

    // Afficher les boutons de couleur
    echo "<h4>Couleurs:</h4>";
    echo "<div>";
    $colors = $wpdb->get_results("SELECT name_value FROM wp_value WHERE id_attribute = 15 AND id_entity = {$id_product}");
    //error_log(print_r($colors, 1));

    $selectedColor = isset($_POST['color']) ? $_POST['color'] : '';

    foreach($colors as $color){
        $colorName = $color->name_value;

        // Ajouter la classe 'color-selected' au bouton de couleur sélectionné
        $buttonSelectedColor = $colorName === $selectedColor ? 'color-selected' : '';

        echo "<button type='button' name='color' class='color-button {$buttonSelectedColor}' value='{$colorName}'>{$colorName}</button>";
    }

    echo "<h4>Catégorie: {$category['name_category']}</h4>";
    echo "<h4>Statut: {$status['name_category']}</h4>";    

    // Bouton "Ajouter au panier"
    echo "<input type='hidden' name='size' value='{$selectedSize}' />";
    echo "<input type='hidden' name='color' value='{$selectedColor}' />";
    echo "<input type='hidden' name='id_product' value='{$id_product}' />";
    echo "<button type='submit' name='add_to_cart'>Ajouter au panier</button>";
    echo "</form>";

    echo "</div>";    

    // Styles CSS pour les boutons
    echo "<style>
        .size-button {
            padding: 5px 10px;
            margin-right: 5px;
            border: 1px solid #ccc;
            background-color: #fff;
            color: #000;
            cursor: pointer;
        }

        .size-button.available {
            background-color: green;
            color: #fff;
        }

        .size-button.selected {
            background-color: blue;
            color: #fff;
        }

        .size-button.unavailable {
            pointer-events: none;
            opacity: 0.5;
        }

        .color-button {
            padding: 5px 10px;
            margin-right: 5px;
            border: 1px solid #ccc;
            background-color: #fff;
            color: #000;
            cursor: pointer;
        }

        .color-button.color-selected {
            background-color: orange;
            color: #fff;
        }
    </style>";

    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        var sizeButtons = document.querySelectorAll('.size-button');
        var colorButtons = document.querySelectorAll('.color-button');
    
        function handleSizeButtonClick() {
            var selectedSize = this.value;
    
            sizeButtons.forEach(function(button) {
                button.classList.remove('selected');
            });
    
            this.classList.add('selected');
    
            document.querySelector('input[name=\"size\"]').value = selectedSize;
        }
    
        function handleColorButtonClick() {
            var selectedColor = this.value;
    
            colorButtons.forEach(function(button) {
                button.classList.remove('color-selected');
            });
    
            this.classList.add('color-selected');
    
            document.querySelector('input[name=\"color\"]').value = selectedColor;
        }
    
        sizeButtons.forEach(function(button) {
            button.addEventListener('click', handleSizeButtonClick);
        });
    
        colorButtons.forEach(function(button) {
            button.addEventListener('click', handleColorButtonClick);
        });
    });
    </script>";

}


add_action('init', 'add_product_to_cart');
function add_product_to_cart() {
    if (isset($_POST['add_to_cart'])) {
        // Récupérer les données du formulaire
        $id_product = $_POST['id_product'];
        $selectedSize = isset($_POST['size']) ? sanitize_text_field($_POST['size']) : '';
        $selectedColor = isset($_POST['color']) ? sanitize_text_field($_POST['color']) : '';
        //error_log(print_r($selectedSize, 1));
        //error_log(print_r($selectedColor, 1));
        // Vérifier si le panier existe dans la session, sinon le créer
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Récupérer le prix du produit depuis la base de données
        global $wpdb;
        $price = $wpdb->get_var("SELECT name_value FROM wp_value WHERE id_entity = {$id_product} AND id_attribute = 3");
        $productName = $wpdb->get_var("SELECT name_value FROM wp_value WHERE id_entity = {$id_product} AND id_attribute = 1");
        $productImage = $wpdb->get_var("SELECT name_value FROM wp_value WHERE id_entity = {$id_product} AND id_attribute = 4");

        // Ajouter le produit au panier dans la session
        $_SESSION['cart'][] = array(
            'id_product' => $id_product,
            'name' => $productName,
            'price' => $price,
            'image' => $productImage,
            'size' => $selectedSize,
            'color' => $selectedColor
        );

        
        //error_log("Add product :".print_r($_SESSION['cart'], 1));

        // Rediriger vers la page panier après l'ajout au panier
        wp_redirect('http://localhost/Sacapuce/panier');
        exit();
    }
}




function display_cart() {
    // Vérifier si le panier existe dans la session
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Regrouper les produits par leurs valeurs
        $groupedProducts = array();
        $totalPrice = 0.0;
        $totalItems = 0;
        foreach ($_SESSION['cart'] as $product) {
            $key = $product['name'] . $product['price'] . $product['size'] . $product['color'];
            if (isset($groupedProducts[$key])) {
                // Si le produit existe déjà, augmenter le compteur
                $groupedProducts[$key]['count']++;
            } else {
                // Sinon, ajouter le produit au groupe avec un compteur initial de 1
                $groupedProducts[$key] = $product;
                $groupedProducts[$key]['count'] = 1;
            }
            $totalPrice += floatval($product['price']);
            $totalItems++;
        }

        // Afficher les informations du panier dans un tableau
        echo "<table>";
        echo "<tr><th>Image</th><th>Nom</th><th>Prix</th><th>Pointure</th><th>Couleur</th><th>Quantité</th></tr>";
        foreach ($groupedProducts as $key => $product) {
            $name = $product['name'];
            $price = $product['price'];
            $image = $product['image'];
            $size = $product['size'];
            $color = $product['color'];
            $count = $product['count'];

            // Afficher les informations du produit dans le panier avec le compteur
            echo "<tr>";
            echo "<td><img src='$image' alt='$name' width='150' height='150'></td>";
            echo "<td>$name</td>";
            echo "<td>$price</td>";
            echo "<td>$size</td>";
            echo "<td>$color</td>";
            echo "<td>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='product_key' value='$key' />";
            echo "<input type='number' name='quantity' value='$count' min='1' />";
            echo "<button type='submit' name='update_quantity' style='padding: 5px; font-size: 0.8em; min-width: 110px;'>Mettre à jour</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "<tr><td colspan='2'>Total</td><td>$totalPrice €</td><td colspan='2'></td><td>$totalItems</td></tr>";
        // Ajouter le bouton "Vider le panier" dans une nouvelle ligne
        echo "<tr>";
        echo "<td colspan='3'>";
        echo "<form method='post' action=''>";
        echo "<button type='submit' name='clear_cart' style='padding: 5px; font-size: 0.8em; min-width: 110px;'>Vider le panier</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "Le panier est vide.";
    }
}

// Ajouter la gestion de la mise à jour de la quantité
add_action('template_redirect', 'handle_quantity_update');
function handle_quantity_update() {
    if (isset($_POST['update_quantity'])) {
        $productKey = $_POST['product_key'];
        $quantity = intval($_POST['quantity']);

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $count = 0;
            $matchingProduct = null; // Produit correspondant à productKey
            foreach ($_SESSION['cart'] as $product) {
                $key = $product['name'] . $product['price'] . $product['size'] . $product['color'];
                if ($key === $productKey) {
                    $count++;
                    $matchingProduct = $product;
                }
            }

            if ($quantity > $count) {
                for ($i = $count; $i < $quantity; $i++) {
                    $_SESSION['cart'][] = $matchingProduct;
                }
            } 
            else if ($quantity < $count) {
                foreach ($_SESSION['cart'] as $index => $product) {
                    $key = $product['name'] . $product['price'] . $product['size'] . $product['color'];
                    if ($key === $productKey) {
                        unset($_SESSION['cart'][$index]);
                        if (--$count == $quantity) {
                            break;
                        }
                    }
                }
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }
        }

        wp_redirect('http://localhost/Sacapuce/panier');
        exit();
    }

    // Ajouter la gestion du bouton "Vider le panier"
    if (isset($_POST['clear_cart'])) {
        unset($_SESSION['cart']);
        wp_redirect('http://localhost/Sacapuce/panier');
        exit();
    }
}

?>
