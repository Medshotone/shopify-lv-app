<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Install</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/picnic/6.1.1/picnic.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/picnic/6.1.1/plugins.min.css">
    <link rel="stylesheet" href="css/picnic.css">
</head>
<body>

<nav>
    <a href="#" class="brand">
        <span>Shopify App</span>
    </a>
</nav>

<main>
    <form method="POST">
        {{ csrf_field() }}
        <fieldset class="flex one">
            <label>
                Store Name
                <input type="text" name="store" placeholder="e.g. test-shop.myshopify.com">
            </label>
        </fieldset>
        <button>Install</button>
    </form>
</main>

</body>
</html>