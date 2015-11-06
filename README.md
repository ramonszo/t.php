# t.php
## A tiny php templating framework based on t.js

`t.php` is a simple solution to interpolating values in an html string.

### Features
 * Simple interpolation: `{{=value}}`
 * Scrubbed interpolation: `{{%unsafe_value}}`
 * Name-spaced variables: `{{=User.address.city}}`
 * If/else blocks: `{{value}} <<markup>> {{:value}} <<alternate markup>> {{/value}}`
 * If not blocks: `{{!value}} <<markup>> {{/!value}}`
 * Object/Array iteration: `{{@object_value}} {{=_key}}:{{=_val}} {{/@object_value}}`
 * Multi-line templates (no removal of newlines required to render)
 * Render the same template multiple times with different data
 * Works in all modern browsers

### How to use

	$template = new T("<div>Hello {{=name}}</div>");
	echo $template->parse(array('name' => 'World!'));

For more advanced usage check the [`t_test.php`](https://github.com/ramon82/t.php/blob/master/t_test.php).

This software is released under the MIT license.

___

[Javascript version](https://github.com/jasonmoo/t.js) maintained by [@jasonmoo](https://github.com/jasonmoo)
