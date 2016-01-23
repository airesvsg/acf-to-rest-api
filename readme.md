ACF to REST API
====
Edit, Get and Puts [ACF](https://wordpress.org/plugins/advanced-custom-fields/) data into [WordPress REST API ( WP-API )](https://wordpress.org/plugins/rest-api/)

- [Installation](#installation)
- [Endpoints](#endpoints)
- [Filters](#filters)
- [Editing the fields](#editing-the-fields)
- [Example](#example)

Installation
====
1. Copy the `acf-to-rest-api` folder into your `wp-content/plugins` folder
2. Activate the `ACF to REST API` plugin via the plugin admin page

Endpoints
====

| Endpoint | READABLE | EDITABLE |
|----------|:--------:|:--------:|
| /wp-json/acf/v2/post/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v2/page/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v2/user/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v2/term/**{taxonomy}**/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v2/comment/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v2/media/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v2/**{post-type}**/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v2/options | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v2/options/**{name}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |

Filters
====
| Filter    | Argument(s) |
|-----------|-----------|
| acf/rest_api/types | array **$types** |
| acf/rest_api/type | string **$type** |
| acf/rest_api/id | mixed ( string, integer, boolean ) **$id** |
| acf/rest_api/key | string **$key**<br>WP_REST_Request **$request**<br>string **$type** |
| acf/rest_api/item_permissions/get | boolean **$permission**<br>WP_REST_Request **$request**<br>string **$type** |
| acf/rest_api/item_permissions/update | boolean **$permission**<br>WP_REST_Request **$request**<br>string **$type** |
| acf/rest_api/**{type}**/prepare_item | mixed ( array, boolean ) **$item**<br>WP_REST_Request **$request** |
| acf/rest_api/**{type}**/get_fields | mixed ( array, WP_REST_Request ) **$data**<br>mixed ( WP_REST_Request, NULL ) **$request**<br>mixed ( WP_REST_Response, NULL ) **$response**<br>mixed ( WP_Post, WP_Term, WP_User, NULL ) **$object** |

If you do not want edit/show the fields of posts. So, you must use the filter `acf/rest_api/types`

```PHP
add_filter( 'acf/rest_api/types', function( $types ) {
	if ( array_key_exists( 'post', $types ) ) {
		unset( $types['post'] );
	}

	return $types;
} );
```

Editing the fields
====
The fields should be sent into the key `fields`.

![Field Name](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/field-name.jpg)

**Action:** http://localhost/wp-json/acf/v2/post/1

```HTML
<form action="http://localhost/wp-json/acf/v2/post/1" method="POST">
	<?php 
		// http://v2.wp-api.org/guide/authentication
		wp_nonce_field( 'wp_rest' ); 
	?>
	<label>Site: <input type="text" name="fields[site]"></label>
	<button type="submit">Save</button>
</form>
```

**Action:** http://localhost/wp-json/wp/v2/posts/1

```HTML
<form action="http://localhost/wp-json/wp/v2/posts/1" method="POST">
	<?php 
		// http://v2.wp-api.org/guide/authentication
		wp_nonce_field( 'wp_rest' ); 
	?>
	<label>Title: <input type="text" name="title"></label>
	<h3>ACF</h3>
	<label>Site: <input type="text" name="fields[site]"></label>
	<button type="submit">Save</button>
</form>
```

Use the filter `acf/rest_api/key` to change the key `fields`.

```PHP
add_filter( 'acf/rest_api/key', function( $key, $request, $type ) {
	return 'acf_fields';
}, 10, 3 );
```

Now, the fields should be sent into the key `acf_fields`

```HTML
<form action="http://localhost/wp-json/acf/v2/post/1" method="POST">
	<?php 
		// http://v2.wp-api.org/guide/authentication
		wp_nonce_field( 'wp_rest' ); 
	?>
	<label>Site: <input type="text" name="acf_fields[site]"></label>
	<button type="submit">Save</button>
</form>
```

Example
====
Sample theme to edit the ACF Fields.

https://github.com/airesvsg/acf-to-rest-api-example