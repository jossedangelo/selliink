<?php
/**
 * Enqueue parent & child styles
 */
function selliink_child_enqueue_styles() {
    wp_enqueue_style( 'selliink-parent', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'selliink_child_enqueue_styles' );

/**
 * Shortcode: Frontend form to create slk_performance
 */
function slk_frontend_performance_form_shortcode() {
    if ( ! is_user_logged_in() ) {
        return '<p>Por favor, <a href="' . wp_login_url( get_permalink() ) . '">inicia sesión</a> para enviar tu desempeño.</p>';
    }

    // Process submission
    if ( 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['slk_perf_title'] ) ) {
        // Sanitize input
        $title   = sanitize_text_field( $_POST['slk_perf_title'] );
        $content = sanitize_textarea_field( $_POST['slk_perf_content'] );

        // Prepare the new post
        $new_post = array(
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'pending',         // o 'publish' si quieres auto-publicar
            'post_type'    => 'slk_performance',
            'post_author'  => get_current_user_id(),
        );
        $post_id = wp_insert_post( $new_post );

        if ( is_wp_error( $post_id ) ) {
            return '<p>Error al enviar: ' . $post_id->get_error_message() . '</p>';
        }

        // Si usas ACF, guarda aquí campos extra, ej:
        // if ( isset($_POST['acf']['field_XXXXX']) ) {
        //     update_field('field_XXXXX', sanitize_text_field($_POST['acf']['field_XXXXX']), $post_id);
        // }

        return '<p>¡Gracias! Tu desempeño ha quedado registrado y está pendiente de revisión.</p>';
    }

    // Show the form
    ob_start();
    ?>
    <form method="post" class="slk-performance-form" style="max-width:500px;margin:1em 0;">
      <p>
        <label for="slk_perf_title">Título del Desempeño:<br>
          <input type="text" id="slk_perf_title" name="slk_perf_title" required style="width:100%;padding:8px;">
        </label>
      </p>
      <p>
        <label for="slk_perf_content">Descripción:<br>
          <textarea id="slk_perf_content" name="slk_perf_content" rows="5" required style="width:100%;padding:8px;"></textarea>
        </label>
      </p>
      <p>
        <button type="submit" class="button button-primary">Enviar Desempeño</button>
      </p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode( 'slk_performance_form', 'slk_frontend_performance_form_shortcode' );
