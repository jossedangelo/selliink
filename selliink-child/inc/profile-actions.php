<?php
// Evitamos accesos directos
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Muestra la card “Crear Empresa” en el perfil privado
 */
function slk_show_create_company_cta() {
    // Solo usuarios logueados
    if ( ! is_user_logged_in() ) {
        return;
    }

    // Solo en tu propio perfil BuddyPress
    if ( bp_displayed_user_id() !== get_current_user_id() ) {
        return;
    }

    // Si ya creó empresa, no mostramos nada
    if ( get_user_meta( get_current_user_id(), 'empresa_group_id', true ) ) {
        return;
    }

    // Salida HTML de la card
    ?>
    <div class="slk-create-company-card" style="border:1px solid #ddd; padding:15px; margin:20px 0; background:#f9f9f9;">
      <h2 style="margin-top:0;">¿Tienes una Empresa?</h2>
      <p>Gestiona tu perfil de compañía o crea uno nuevo cuando quieras.</p>
      <p><a href="<?php echo esc_url( site_url( '/crear-empresa/' ) ); ?>" class="button button-primary">
        Crear Empresa
      </a></p>
    </div>
    <?php
}
add_action( 'bp_before_member_body', 'slk_show_create_company_cta' );
