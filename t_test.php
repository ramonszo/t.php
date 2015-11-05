<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>t.php</title>
<style>
	body { background:black; color:white; padding:40px; font-family: 'PT Sans', Helvetica, Arial, sans-serif;}
	a { color:#9bf; }
	p > span { display:inline-block; margin:0 10px; font-weight:bold; }
</style>
</head>
<body>
<?php
$template = new T('
    <h1>{{=greeting}}</h1>
    <h2>{{=user.display_name}}</h2>

    {{user.address}}
        <address>{{%user.address}}</address>
    {{/user.address}}

    <h4>Friends</h4>

    {{@user.friends}}
        <a href="{{%_val.url}}">{{=_val.name}}</a><br>
    {{/@user.friends}}

    {{=not_a_value}}

    {{=not.a.value}}

    {{!not_a_value}}
        <p>Missing required information!</p>
    {{/!not_a_value}}

    {{user.display_name}}
        <p>Bacon ipsum {{=user.display_name}}?</p>
    {{:user.display_name}}
        This should not be here.
    {{/user.display_name}}

    {{not_a_value}}
        This should not be here.
    {{:not_a_value}}
        <p>Yes bacon ipsum {{=user.display_name}}!</p>
    {{/not_a_value}}

    {{@user.prefs}}

        <p><span>{{=_key}}</span>{{=_val}}</p>

    {{/@user.prefs}}

    <h4>Test Values</h4>
    {{@test_values}}

        <p>{{=_key}}<span id="{{=_key}}" data-val="{{%_val}}">{{=_val}}</span></p>

    {{/@test_values}}
');

echo $template->parse(
    array(
            'greeting'=> "Welcome!",
            'user' => array(
                'display_name' => "Jason",
                'address' => "111 State St, Ithaca,<script>alert(1);<\/script> NY 14853",
                'friends' => array(
                    array('name' => "Kunal", 'url' => "http://whatspop.com"),
                    array('name' => "Francisco", 'url' => "http://smalldemons.com"),
                    array('name' => "Nick", 'url' => "http://milewise.com")
                ),
                'prefs' => array(
                    'Notifications' => "Yes!",
                    '"Stay logged in"' => "No"
                )
            ),
            'test_values' => array(
                "true" => true,
                "false" => false,
                "zero" => 0,
                "string_zero" => "0",
                "null" => null
            )
    )
);
?>
</body>
</html>
