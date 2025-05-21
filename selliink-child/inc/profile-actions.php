<?php
/**
 * Muestra la card “Crear Empresa” en el perfil privado de BuddyPress
 */
function slk_show_create_company_cta() {
    if ( ! is_user_logged_in() ) {
        return;
    }
    // Si ya tiene empresa, no mostramos nada
    if ( get_user_meta( bp_loggedin_user_id(), 'empresa_group_id', true ) ) {
        return;
    }
    ?>
    <div class="slk-create-company-card" style="margin:20px;padding:15px;border:1px solid #ddd;background:#fafafa;">
      <h2>¿Tienes una Empresa?</h2>
      <p>Si quieres gestionar tu perfil de compañía, haz clic abajo.</p>
      <a href="<?php echo esc_url( site_url( '/crear-empresa/' ) ); ?>"
         class="button button-primary">Crear Empresa</a>
    </div>
    <?php
}
add_action( 'bp_before_member_settings_template', 'slk_show_create_company_cta' );
