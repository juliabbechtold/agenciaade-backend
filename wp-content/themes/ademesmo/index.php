<?php 

global $wp;

// Pega slug atual
$slug = add_query_arg( array(), $wp->request );

// Remover a / do final
$options_site = str_replace(".app/",".app", get_field('site_url', 'options_site'));
$options_site = str_replace(".com.br/",".com.br", $options_site);
$options_site = str_replace(".com/",".com", $options_site);

// Monta a url final
$url = $options_site . '/' . $slug;

// Faz o redicionamento
header('Location: ' . $url);
exit();