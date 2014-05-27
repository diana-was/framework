<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Example Website</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>css/basic.css">
    <script src="<?php echo PUBLIC_URL; ?>js/vendor/jquery.min.js"></script>
</head>
<body>
    <section>
        <div class="center">
            <h2>Examples</h2>
            <ul>
                <li><a href="<?php echo PUBLIC_URL; ?>example/showDatabase">Show Database Structure</a></li>
                <li><a href="<?php echo PUBLIC_URL; ?>example/splitReg">Split a CamelCase string</a></li>
                <li><a href="<?php echo PUBLIC_URL; ?>example/displayDate">Convert Unix time to Date and Time</a></li>
                <li><a href="<?php echo PUBLIC_URL; ?>example/displayUnix">Convert Date and Time to Unix time</a></li>
                <li><a href="<?php echo PUBLIC_URL; ?>example/convertJson">Convert Json string to Array</a></li>
                <li><a href="<?php echo PUBLIC_URL; ?>example/unserializeText">Unserialize Text</a></li>
                <li><a href="<?php echo PUBLIC_URL; ?>example/showRequest">Test the controller object</a></li>
                <li><a href="<?php echo PUBLIC_URL; ?>example/api/bitly?url=http://google.com">Test call to API</a></li>
            </ul>
        </div>
    </section>
</body>
</html>
