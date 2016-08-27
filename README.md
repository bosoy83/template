# Favez Template

A lightweight and mostly native php template engine supporting nested blocks.

## Installation
```bash
$ composer require favez/template
```

### Basic usage
index.php
```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

\Favez\Template\Environment::addTemplateDir(__DIR__ . '/views');

$template = \Favez\Template\Environment::create('index.phtml');
$template->assign('username', 'tyurderi');
```

index.phtml
```phtml
<!DOCTYPE html>
<html>
<head>
    <title>Favez Template</title>
</head>
<body>
    Hello, <?php echo $username?>!
</body>
</html>
```

### Including other templates
```php
<?php $this->import('path/to/template.phtml')?>
```

### Basic usage for (nested) blocks
index.phtml
```phtml
<!DOCTYPE html>
<html>
<head>
    <title><?php $this->block('title')>Home<?php $this->endblock()?> | Favez Template</title>
</head>
<body>
<?php $this->block('body')?>
Hello World
<?php $this->endblock()?>
</body>
</html>
```

index2.phtml
```phtml
<?php $this->extend('index.phtml')?>

<?php $this->block('body')?>
    Hello, <?php echo $username?>!
<?php $this->endblock()?>
```