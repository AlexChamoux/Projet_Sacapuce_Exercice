<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link https://fr.wordpress.org/support/article/editing-wp-config-php/ Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'Sacapuce' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'root' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'L :|5-lAE.dJ`_@]nPg8X2efVle[NbV)$_)h]D*Q:y/c]QHI+OiMz&s-GaWHv^77' );
define( 'SECURE_AUTH_KEY',  '(phw8ezA-EWBr4RFg0)Vv;H^SZ `ms@v&o5U)^5-mF4?QC&LVcoL3>s=0d+F}L:b' );
define( 'LOGGED_IN_KEY',    '/v$q,s~i4#H1]afW%@cxz_tUUDgKh1-y9$7K}wyTRa(kjM};8UI:qxJK9u{ViccY' );
define( 'NONCE_KEY',        '5D x{1,!jT;E pl:~Mt7Of%RcAf&He{UO-HyS3D#KA2)(!< pZdG42,0P*<G.yF+' );
define( 'AUTH_SALT',        '88pP.`)c&oT!!`<Jw! $BEki>0`.syQn8B/5uV: cx9=JV=X[Hzv0X$/&hn~<fY5' );
define( 'SECURE_AUTH_SALT', 'y~WLlCY)/HcebX,*CJa>9i<GWm>|dm1KMB$jrKPiQYsy,an#Tu1PVn<g-HH}8oMV' );
define( 'LOGGED_IN_SALT',   'WQ{qv}-K/k$0|(Cmn,&_&Q/w&IJd&0&=4;B/1$r<-aW? pd-#4%fK;tpI<D395Iq' );
define( 'NONCE_SALT',       ',>?L}4.1]m7<d%eO4#~m~,x}&OZZFenSsjT=ii|kU/we@N0%o1K{=(Q~2{rwx,dj' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs et développeuses : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur la documentation.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
