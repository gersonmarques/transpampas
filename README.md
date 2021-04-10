# Transpampas
Repositório do site transpampas.com.br

# Plugin Transport

Plugin Transport used to save request to transport of vehicle

## Installation

It to install package transporte, assets and file of request to use plugin transport.

```bash
wp-content/plugins/transporte/
wp-content/themes/astra/solicitacao.php
assets/
```

## Usage

Added to command bellow 

```php
file: wp-content/themes/astra/functions.php

require_once  __DIR__."/../../../assets/templates/router.php";
```

Added this command bellow `astra_head_top()` in `header.php`

```php
file: wp-content/themes/astra/header.php

<!-- Usado no plugin de transporte -->
<link rel="stylesheet" type="text/css" href="<?= get_site_url()."/assets/vendor/bootstrap/css/bootstrap.min.css" ?>" />
<script src="<?= get_site_url()."/assets/vendor/jquery/jquery-3.5.1.min.js"?>"></script>
<script src="<?= get_site_url()."/assets/vendor/jquery/jquery.mask.js"?>"></script>
<script src="<?= get_site_url()."/assets/vendor/bootstrap/js/bootstrap.min.js"?>"></script>
<!-- Usado no plugin de transporte -->
```

## Custom Fields

Added custom field of the called `E-mails formulários` and your key `email_solicitacoes` to use in the request transport page. It this fields should added emails of recipients, but to use more than one must be separete e-mails with `;` sample `fulano@mail.com;fulaninho@mail.com`