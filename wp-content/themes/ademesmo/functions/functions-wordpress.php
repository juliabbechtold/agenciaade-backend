<?php

/**
 * Desabilita o Editor Gutenberg
 * @author Armando
 */
add_filter('use_block_editor_for_post', '__return_false');

/**
 * Habilita a imagem destaque
 * @author Armando
 */
add_theme_support('post-thumbnails');

/**
 * Remove a toolbar no site quando está logado no admin
 * @author Armando
 */
add_filter('show_admin_bar', '__return_false');

/**
 * Remove alerta de atualizar wordpres
 * @author Armando
 */
function wordpress_update_alert() {
  if (!current_user_can('publish_pages')) {
    remove_action('admin_notices', 'update_nag', 3);
  }
}
add_action('admin_menu', 'wordpress_update_alert');

/**
 * Remove a metabox de atributos da página
 * @author Armando
 */
function page_attribute_support() {
  remove_post_type_support('page', 'page-attributes');
}
add_action('init', 'page_attribute_support');

/**
 * Remove links da adminbar
 * @author Armando
 */
function admin_bar_links() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('updates');
  $wp_admin_bar->remove_menu('comments');
  $wp_admin_bar->remove_menu('new-content');
}
add_action('wp_before_admin_bar_render', 'admin_bar_links');

/**
 * Remove links do menu principal (!= administrador)
 * @author Armando
 */
function remove_links_menu() {
  if (!current_user_can('publish_pages')) {
    remove_menu_page('profile.php');
    remove_menu_page('upload.php');
    remove_menu_page('link-manager.php');
    remove_menu_page('tools.php');
    remove_menu_page('edit-comments.php');
  }
}
add_action('admin_menu', 'remove_links_menu');

/**
 * Remove post padrão do menu do cliente senão tiver liberado no menu Site
 * @author Frodines
 */
function remove_post_default_do_menu() {

  // Se não for administrador
  if ( !current_user_can('publish_pages') ) {
    
    // Verifica se a opção "Sim" está checada
    if ( get_field('site_blog', 'options_site') == 0 ) {
      remove_menu_page('edit.php');
    }
  }
}
add_action('admin_menu', 'remove_post_default_do_menu');

/**
 * Remove widgets da dashboard
 * @author Armando
 */
function default_dashboard_widgets() {
  remove_action('welcome_panel', 'wp_welcome_panel');
  remove_meta_box('dashboard_right_now', 'dashboard', 'core');
  remove_meta_box('dashboard_activity', 'dashboard', 'core');
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
  remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
  remove_meta_box('dashboard_plugins', 'dashboard', 'core');
  remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
  remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
  remove_meta_box('dashboard_primary', 'dashboard', 'core');
  remove_meta_box('dashboard_secondary', 'dashboard', 'core');
  remove_meta_box('dashboard_site_health', 'dashboard', 'core');
}
add_action('admin_menu', 'default_dashboard_widgets');

/**
 * Permite o upload de SVG
 * @author Armando
 */
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

/**
 * Corrige problema na acentuação das imagens
 * @author Armando
 */
function hash_filename($filename) {
  $info = pathinfo($filename);
  $ext = empty($info['extension']) ? '' : '.' . $info['extension'];
  $name = basename($filename, $ext);
  return md5($name) . $ext;
}
add_filter('sanitize_file_name', 'hash_filename');

/**
 * Define tamanho das imagens de upload
 * @author Armando
 */
update_option('thumbnail_crop', 1);
update_option('thumbnail_size_w', 300);
update_option('thumbnail_size_h', 300);
update_option('medium_size_w', 800);
update_option('medium_size_h', 800);
update_option('large_size_w', 1800);
update_option('large_size_h', 1800);
add_filter('big_image_size_threshold', '__return_false');

/**
 * Remove tamanhos adicionais de imagem
 * @author Armando
 */
function filter_image_sizes() {
  remove_image_size('1536x1536');
  remove_image_size('2048x2048');
}
add_action('init', 'filter_image_sizes');

add_filter('intermediate_image_sizes', function ($sizes) {
  return array_diff($sizes, ['medium_large']);
});

/**
 * Sustitui a imagem original pela sizes['large'] e exclui a original
 * @author Frodines
 */
function trocar_imagem_do_upload($image_data) {
  if (!isset($image_data['sizes']['large'])) {
    return $image_data;
  }

  $upload_dir = wp_upload_dir();
  $uploaded_image_location = $upload_dir['basedir'] . '/' . $image_data['file'];
  $current_subdir = substr($image_data['file'], 0, strrpos($image_data['file'], "/"));
  $large_image_location = $upload_dir['basedir'] . '/' . $current_subdir . '/' . $image_data['sizes']['large']['file'];

  unlink($uploaded_image_location);
  rename($large_image_location, $uploaded_image_location);

  $image_data['width'] = $image_data['sizes']['large']['width'];
  $image_data['height'] = $image_data['sizes']['large']['height'];
  unset($image_data['sizes']['large']);

  return $image_data;
}
add_filter('wp_generate_attachment_metadata', 'trocar_imagem_do_upload');

/**
 * Exclui midias ao excluir post (da lixeira)
 * @author Frodines
 */
function deletar_imagem_apos_exclusao_do_post($post_id) {
  $attachments = get_attached_media('', $post_id);
  foreach ($attachments as $attachment) {
    wp_delete_attachment($attachment->ID, true);
  }
}
add_action('before_delete_post', 'deletar_imagem_apos_exclusao_do_post');

/**
 * Remover metabox de ações do post
 * @author Frodines
 */
function mg_publishing_actions() {
  if (!current_user_can('publish_pages')) {
    echo '<style type="text/css">#misc-publishing-actions,#minor-publishing-actions{display:none}</style>';
  }
}
add_action('admin_head-post.php', 'mg_publishing_actions');
add_action('admin_head-post-new.php', 'mg_publishing_actions');

/**
 * Remover links da listagem (edição rápida, ver)
 * @author Frodines
 */
function desabilitar_edicao_rapida($actions = array(), $post = null) {
  if (!current_user_can('publish_pages')) {
    if (isset($actions['inline hide-if-no-js'])) {
      unset($actions['inline hide-if-no-js']);
    }
    if (isset($actions['view'])) {
      unset($actions['view']);
    }
  }
  return $actions;
}
add_filter('post_row_actions', 'desabilitar_edicao_rapida');
add_filter('page_row_actions', 'desabilitar_edicao_rapida');

/**
 * Bloquear as atualizações do plugin do analytics
 * @link http://www.thecreativedev.com/disable-updates-for-specific-plugin-in-wordpress/
 * @author Frodo
 */
function desabilitar_atualizacoes_gadwp( $value ) {
  if ( isset($value) && is_object($value) ) {
    if ( isset( $value->response['google-analytics-dashboard-for-wp/gadwp.php'] ) ) {
      unset( $value->response['google-analytics-dashboard-for-wp/gadwp.php'] );
    }
  }
  return $value;
}
add_filter( 'site_transient_update_plugins', 'desabilitar_atualizacoes_gadwp' );

/**
 * Adiciona funcao de orderby=rand na API
 * @author Armando
 */
function add_rand_orderby_rest_post_collection_params( $query_params ) {
	$query_params['orderby']['enum'][] = 'rand';
	return $query_params;
}
add_filter( 'rest_post_collection_params', 'add_rand_orderby_rest_post_collection_params' );