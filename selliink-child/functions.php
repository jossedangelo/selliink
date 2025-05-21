<?php
/**
 * Enqueue parent and child theme styles
 */
function selliink_child_enqueue_styles() {
    wp_enqueue_style( 'selliink-parent', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'selliink-child',  get_stylesheet_directory_uri() . '/style.css', array( 'selliink-parent' ) );
}
add_action( 'wp_enqueue_scripts', 'selliink_child_enqueue_styles' );

/**
 * Include the “Crear Empresa” card in the private profile
 */
require_once get_stylesheet_directory() . '/inc/profile-actions.php';

/**
 * Shortcode: Formulario de “Crear Empresa”
 */
function slk_create_company_form_shortcode() {
    if ( ! is_user_logged_in() ) {
        return '<p>Debes iniciar sesión para crear una empresa.</p>';
    }
    $user_id = get_current_user_id();
    $type    = strtolower( xprofile_get_field_data( 'Tipo de cuenta', $user_id ) );
    if ( ! in_array( $type, array( 'profesional', 'empresa' ) ) ) {
        return '<p>Sólo cuentas Profesionales o Empresa pueden crear una empresa.</p>';
    }

    if ( 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['slk_company_name'] ) ) {
        $name        = sanitize_text_field( $_POST['slk_company_name'] );
        $description = sanitize_textarea_field( $_POST['slk_company_desc'] );

        $group_id = groups_create_group( array(
            'creator_id'   => $user_id,
            'name'         => $name,
            'description'  => $description,
            'enable_forum' => 0,
        ) );

        if ( $group_id ) {
            update_user_meta( $user_id, 'empresa_group_id', $group_id );
            $group = groups_get_group( array( 'group_id' => $group_id ) );
            $link  = bp_get_group_permalink( $group );
            return sprintf(
                '<p>Empresa creada con éxito. <a href="%s">Ver perfil de empresa</a></p>',
                esc_url( $link )
            );
        } else {
            return '<p>Error al crear la empresa. Inténtalo de nuevo.</p>';
        }
    }

    ob_start(); ?>
    <form method="post" class="slk-create-company-form" style="max-width:400px;margin:1em auto;">
      <p>
        <label>Nombre de la empresa:<br>
          <input type="text" name="slk_company_name" required style="width:100%;padding:8px">
        </label>
      </p>
      <p>
        <label>Descripción:<br>
          <textarea name="slk_company_desc" rows="4" required style="width:100%;padding:8px"></textarea>
        </label>
      </p>
      <p><button type="submit" class="button button-primary">Crear Empresa</button></p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode( 'slk_create_company_form', 'slk_create_company_form_shortcode' );
