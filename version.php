<?php
//Blog info API

add_action('rest_api_init', function() {
    register_rest_route( 'app/v1', '/info', array(
        'methods' => 'GET',
        'callback' => 'apiGetInfo',
    ) );
});


function apiGetInfo()
{

    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $pluginsInstalled = get_plugins();
    $plugins = [];

    foreach($pluginsInstalled as $path => $plugin) {
        $plugins[str_replace(' ', '-', strtolower($plugin['Name']))] = [
            'name'    => $plugin['Name'],
            'version' => !empty($plugin['Version']) ? $plugin['Version'] : false,
            'uri'     => !empty($plugin['PluginURI']) ? $plugin['PluginURI'] : false,
            'active'  => is_plugin_active($path)
        ];
    }


    return [
        'version' => get_bloginfo('version', 'display'),
        'plugins' => $plugins
    ];
}
