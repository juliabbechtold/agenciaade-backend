<?php

/**
 * Modifica o css da tela de login (/wp-login)
 * @author Armando
 */
add_action('login_head', 'my_css_login');
function my_css_login() {
  echo '<style type="text/css">body{background:#fff}#login h1 a{background:url(' . get_template_directory_uri() . '/img/ade/ade.svg) no-repeat scroll center 0 transparent;width:100%;background-size:contain;height:90px}</style>';
}

/**
 * Modifica o css do painel do wordpress (/wp-admin)
 * @author Armando
 */
add_action('admin_head', 'my_css_admin');
function my_css_admin() {
  echo '<style type="text/css">#wp-admin-bar-wp-logo{background:url(' . get_template_directory_uri() . '/img/ade/ade.svg) no-repeat scroll center center transparent!important;width:35px;background-size:contain;margin:0 5px 0 10px!important;filter:brightness(3);height:32px}#wp-admin-bar-wp-logo a,#wp-admin-bar-wp-logo div, .acf-to-rest-api-donation-notice,.ac-notice{display:none!important}.acf-float-left{float:left!important;min-height:0!important}</style>';
}

/**
 * Personaliza o rodapé do wp-admin
 * @author Armando
 */
add_filter('admin_footer_text', 'custom_admin_footer');
function custom_admin_footer() {
  echo 'Desenvolvido com <img width="15" src="' . get_template_directory_uri() . '/img/ade/coracao.svg" /> por <a href="http://agenciaade.com.br" target="_blank" title="Clique para visitar o site" ><img width="45" style="position: relative;top: 2px;" src="' . get_template_directory_uri() . '/img/ade/ade.svg" ></a>';
}

/**
 * Remove a permisão de criação de páginas
 * @author Armando
 */
function permissions_admin_redirect() {
  $result = stripos($_SERVER['REQUEST_URI'], 'post-new.php?post_type=page');
  if ($result !== false && !current_user_can('publish_pages')) {
    wp_redirect(get_option('siteurl') . '/wp-admin/edit.php?post_type=page&permissions_error=true');
  }
}
add_action('admin_menu', 'permissions_admin_redirect');

function permissions_admin_notice() {
  echo "<div id='permissions-warning' class='error fade'><p><strong>" . __('Você não tem permissão para criar novas páginas.') . "</strong></p></div>";
}

function permissions_show_notice() {
  if (@$_GET['permissions_error']) {
    add_action('admin_notices', 'permissions_admin_notice');
  }
}
add_action('admin_init', 'permissions_show_notice');

/**
 * Ajustes nos tipos de usuários
 * @author Armando
 */
add_role('cliente', 'Cliente');
remove_role('editor');
remove_role('file_uploader');
remove_role('author');
remove_role('contributor');
remove_role('subscriber');

global $wp_roles;
$role = get_role('cliente');
$role->add_cap('read');
$role->add_cap('edit_files'); //upload de arquivos
$role->add_cap('upload_files'); //upload de arquivos
$role->add_cap('remove_upload_files'); //upload de arquivos
$role->add_cap('delete_posts'); //upload de arquivos
$role->add_cap('edit_pages'); //edicao de paginas
$role->add_cap('edit_others_pages'); //edicao de paginas
$role->add_cap('edit_published_pages'); //edicao de paginas
$role->add_cap('edit_others_posts'); //edicao de paginas
$role->add_cap('edit_posts'); //edicao de posts
$role->add_cap('edit_published_posts'); //edicao de posts
$role->add_cap('publish_posts'); //edicao de posts
$role->add_cap('delete_others_posts'); //upload de posts
$role->add_cap('delete_published_posts'); //upload de posts
$role->add_cap('manage_categories'); //categorias (posts e custom post types)

/**
 * Adiciona widgets personalizados no dash
 * @author Armando
 */
function add_dashboard_widgets() {
  wp_add_dashboard_widget(
    'dashboard_suporte_widget',
    'Suporte',
    function () {
      echo "<p>Em caso de dúvidas ou problemas com o site, entrar em contato conosco!</p>
 			 <p><ul><li>- Telefone: (42) 3028-7790</li><li>- Whatsapp: (42) 98807-7188</li><li>- E-mail: web@agenciaade.com.br</li></ul>";
    }
  );
  wp_add_dashboard_widget(
    'dashboard_obvio_widget',
    'agenciaade.com.br',
    function () {
      echo "<a target='_blank' href='http://agenciaade.com.br'><img width='100%' src='" . get_template_directory_uri() . "/img/ade/marca.png' /></a>";
    }
  );
}
add_action(
  'wp_dashboard_setup',
  'add_dashboard_widgets'
);
