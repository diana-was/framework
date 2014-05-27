<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
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
            <h2><?php echo $Example->form['title']; ?></h2>
            <form action="<?php echo $Example->form['action']; ?>" method="<?php echo $Example->form['method']; ?>" enctype="multipart/form-data" name="<?php echo $Example->form['name']; ?>">
            <ul>
                <li>
                    <label for="<?php echo $Example->form['input']['name']; ?>"><?php echo $Example->form['input']['label']; ?></label>
                    <?php switch ($Example->form['input']['type']) { 
                        case 'text' : ?>
                        <input type="$Example->form['input']['type'];" name="<?php echo $Example->form['input']['name']; ?>" id="<?php echo $Example->form['input']['name']; ?>" value="<?php echo $Example->form['input']['value']; ?>">
                        <?php break;
                        case 'textarea': ?>
                        <textarea rows="10" cols="50" name="<?php echo $Example->form['input']['name']; ?>" id="<?php echo $Example->form['input']['name']; ?>">
                        <?php echo $Example->form['input']['value']; ?>
                        </textarea>
                        <?php break;
                    } ?>
                </li>
                <?php if (is_array($Example->form['output'])) : ?>
                    <li>Result : </li>
                    <?php foreach ($Example->form['output'] as $key => $data) : ?>
                    <li>
                        <label><?php echo $key; ?></label>
                         <?php if (is_array($data)) : ?>
                            <ul>
                            <?php foreach ($data as $k => $v) : ?>
                            <li>
                                <label><?php echo $k; ?></label>
                                 <?php if (is_array($v)) : ?>
                                <p><?php print_r ($v); ?></p>
                                <?php else: ?>
                                <p><?php echo $v; ?></p>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p><?php echo $data; ?></p>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Result : <?php echo $Example->form['output']; ?></li>
                <?php endif; ?>
                <li><input type="submit" value="Send"></li>
            </ul>
        </form>
    </section>
</body>
</html>
