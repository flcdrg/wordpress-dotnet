<?php
/**
 * Plugin Name: Open ID Configuration
 * Description: This is my plugin description.
 */
add_filter( 'oidc_registered_clients', 'my_oidc_clients' );
function my_oidc_clients() {
	return array(
		'client_id_random_string' => array(
			'name' => 'F6DD8234-5549-4DD2-9FE0-FDA5D7197C41',
            'client_id' => 'kbyuFDidLLm280LIwVFiazOqjO3ty8KH',
			'secret' => '60Op4HFM0I8ajz0WdiStAbziZ-VFQttXuxixHHs2R7r7-CW8GR79l-mmLqMhc-Sa',
			'redirect_uri' => 'https://example.com/redirect.uri',
			'grant_types' => array( 'authorization_code' ),
			'scope' => 'openid profile',
		),
	);
}