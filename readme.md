ACF to REST API
====
Exposes [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) Endpoints in the [WordPress REST API](https://developer.wordpress.org/rest-api/)

https://wordpress.org/plugins/acf-to-rest-api/

- [Installation](#installation)
- [Endpoints](#endpoints)
- [Filters](#filters)
- [Deprecated Filters ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png)](#deprecated-filters)
- [Request API Version ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png)](#request-api-version)
- [Field Settings ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png)](#field-settings)
- [Editing the Fields](#editing-the-fields)
- [Examples](#examples)
- [Get ACF Fields Recursively ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png)](#get-acf-fields-recursively)
- [Cache](#cache)

Installation
====
1. Copy the `acf-to-rest-api` folder into your `wp-content/plugins` folder
2. Activate the `ACF to REST API` plugin via the plugin admin page

Endpoints
====

| Endpoint | READABLE | EDITABLE |
|----------|:--------:|:--------:|
| /wp-json/acf/v3/posts ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![no](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/no.png) |
| /wp-json/acf/v3/posts/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/posts/**{id}**/**{field-name}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/pages ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![no](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/no.png) |
| /wp-json/acf/v3/pages/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/pages/**{id}**/**{field-name}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/users ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![no](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/no.png) |
| /wp-json/acf/v3/users/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/users/**{id}**/**{field-name}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/**{taxonomy}** ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![no](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/no.png) |
| /wp-json/acf/v3/**{taxonomy}**/**{id}** ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/**{taxonomy}**/**{id}**/**{field-name}** ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/comments ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![no](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/no.png) |
| /wp-json/acf/v3/comments/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/comments/**{id}**/**{field-name}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/media ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![no](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/no.png) |
| /wp-json/acf/v3/media/**{id}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/media/**{id}**/**{field-name}** | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/**{post-type}** ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![no](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/no.png) |
| /wp-json/acf/v3/**{post-type}**/**{id}** ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/**{post-type}**/**{id}**/**{field-name}** ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/options/**{id}** ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |
| /wp-json/acf/v3/options/**{id}**/**{field-name}** ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) | ![yes](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/yes.png) |

Filters
====
| Filter    | Argument(s) |
|-----------|-----------|
| acf/rest_api/id | mixed ( string, integer, boolean ) **$id**<br>string **$type** ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png)<br>string **$controller** ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) |
| acf/rest_api/key | string **$key**<br>WP_REST_Request **$request**<br>string **$type** |
| acf/rest_api/item_permissions/get | boolean **$permission**<br>WP_REST_Request **$request**<br>string **$type** |
| acf/rest_api/item_permissions/update | boolean **$permission**<br>WP_REST_Request **$request**<br>string **$type** |
| acf/rest_api/**{type}**/prepare_item | mixed ( array, boolean ) **$item**<br>WP_REST_Request **$request** |
| acf/rest_api/**{type}**/get_fields | mixed ( array, WP_REST_Request ) **$data**<br>mixed ( WP_REST_Request, NULL ) **$request** |
| acf/rest_api/field_settings/show_in_rest ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | boolean **$show** |
| acf/rest_api/field_settings/edit_in_rest ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png) | boolean **$edit** |

Basic example of how to use the filters, in this case I will set a new permission to get the fields
```PHP
add_filter( 'acf/rest_api/item_permissions/get', function( $permission ) {
  return current_user_can( 'edit_posts' );
} );
```

Deprecated filters
====
| Filter    | Argument(s) |
|-----------|-----------|
| acf/rest_api/type | string **$type** |
| acf/rest_api/types | array **$types** |
| acf/rest_api/default_rest_base | boolean **$default**<br>string **$type** |

Request API version
====
See below how to select the Request API Version.

1. Open the plugins page;
2. Click the settings link under the pluing name ( `ACF to REST API` );
3. Select your version in the `ACF to REST API` session;
4. Click in the button Save changes.

![Choose request API version](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/request-api-version-v3.jpg)

The other alternative is to define the constant `ACF_TO_REST_API_REQUEST_VERSION` in your `wp-config.php`

```PHP
define( 'ACF_TO_REST_API_REQUEST_VERSION', 2 );
```

Field Settings
====
In this version is possible to configure the field options via admin.

The options are enabled using the filters below, by default theses options are disabled.

```PHP
// Enable the option show in rest
add_filter( 'acf/rest_api/field_settings/show_in_rest', '__return_true' );

// Enable the option edit in rest
add_filter( 'acf/rest_api/field_settings/edit_in_rest', '__return_true' );
```

After you activate the filters, all your fields should show these options:
![Choose request API version](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/field-settings-v3.jpg)


Editing the fields
====
The fields should be sent into the key `fields`.

![Field Name](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/field-name-v3.jpg)

**Action:** http://localhost/wp-json/acf/v3/posts/1

```HTML
<form action="http://localhost/wp-json/acf/v3/posts/1" method="POST">
  <?php 
    // https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
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
    // https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
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
<form action="http://localhost/wp-json/acf/v3/posts/1" method="POST">
  <?php 
    // https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
    wp_nonce_field( 'wp_rest' ); 
  ?>
  <label>Site: <input type="text" name="acf_fields[site]"></label>
  <button type="submit">Save</button>
</form>
```

Examples
====
Sample theme to edit the ACF Fields.

https://github.com/airesvsg/acf-to-rest-api-example

To-do list ![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png)

https://github.com/airesvsg/to-do-list-acf-to-rest-api


Get ACF Fields Recursively![new](http://airesgoncalves.com.br/screenshot/acf-to-rest-api/readme/new-v3.1.png)
====
https://github.com/airesvsg/acf-to-rest-api-recursive

More details:

- Issues
  - https://github.com/airesvsg/acf-to-rest-api/issues/109
  - https://github.com/airesvsg/acf-to-rest-api/issues/223
  - https://github.com/airesvsg/acf-to-rest-api/issues/9

- Pull Request
  - https://github.com/airesvsg/acf-to-rest-api/pull/95

Cache
====
Enable caching for WordPress REST API and increase speed of your application.

https://github.com/airesvsg/wp-rest-api-cache
