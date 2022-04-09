<?php

/**
 * Options page "Site" para configurações gerais do mesmo
 * @link https://www.advancedcustomfields.com/resources/options-page/
 */
if ( function_exists('acf_add_options_page') ) :
  
  acf_add_options_page(array(
    'page_title' 		  => 'Opções do Site',
    'menu_title'			=> 'Site',
    'menu_slug' 			=> 'site',
    'capability'			=> 'activate_plugins',
    'redirect'				=> false,
    'position'				=> '30',
    'icon_url'				=> 'dashicons-hammer',
    'update_button'		=> __('Salvar', 'acf'),
    'updated_message'	=> __("Informações do site atualizadas com sucesso", 'acf'),
    'post_id'				  => 'options_site'
  ));
endif;

/**
 * Adicionar campo geral no options_site
 * @link @https://www.advancedcustomfields.com/resources/register-fields-via-php/
 */
if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array (
    'key' => 'ade_group_site',
    'title' => 'Geral',
    'fields' => array (
      array (
        'key' => 'ade_field_url',
        'label' => 'Url',
        'name' => 'site_url',
        'type' => 'url',
        'required' => 1,
        'instructions' => 'Informe a url (com https) do front do site para redirecionamento dos botão ver mais aqui do painel.',
      ),
      array (
        'key' => 'ade_blog_field',
        'label' => 'O site possui blog/notícias?',
        'name' => 'site_blog',
        'type' => 'true_false',
        'required' => 0,
        'message' => 'Liberar edição de posts do blog',
        'instructions' => 'Se o site possuir blog, marque o checkbox abaixo para habilitar a edição de posts no painel do cliente.',
      ),
      array (
        'key' => 'ade_script_field',
        'label' => 'Scripts do site',
        'name' => 'site_scripts',
        'type' => 'textarea',
        'required' => 0,
        'message' => 'Liberar edição de posts do blog',
        'instructions' => 'Insira os scripts (Facebook pixel, Google Analytics, etc) para serem inseridos na do site.',
      )
    ),
    'location' => array (
      array (
        array (
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'site',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
  ));
endif;