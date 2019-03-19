<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/picnic/6.1.1/picnic.min.css">
</head>
<body>

<nav>
    <a href="#" class="brand">
        <span>Shopify App</span>
    </a>
</nav>

<main>
    <h1>Products</h1>
    <div class="flex two">
        @php
            //dd($all_products);
        @endphp
        <div>
        @foreach ($all_products['products'] as $product)
            <article class="card">
                <section>
                    <h3>{{ $product['title'] }}</h3>
                </section>
                @if($product['images'])
                <section>
                    @foreach ($product['images'] as $image)
                        <img style="width: 100px; height: 100px;" id="{{$image['id']}}" src="{{$image['src']}}" alt="{{ $product['title'] }}">
                    @endforeach
                </section>
                @endif
                @if($product['metafields'])
                <section>
                    <form method="POST">
                        <input type="hidden" name="owner_id" value="{{$product['id']}}">
                        @foreach ($product['metafields'] as $key => $metafield)
                            <div style="margin-bottom: 20px">
                                <span>key: </span><input type="text" name="key[{{$key}}]" value="{{$metafield['key']}}">
                                <span>value: </span><input type="text" name="value[{{$key}}]" value="{{$metafield['value']}}">
                            </div>
                        @endforeach
                        <button>Отправить все кастом поля для продукта</button>
                    </form>
                </section>
                @endif
            </article>
        @endforeach
        </div>
    </div>
</main>

</body>
</html>