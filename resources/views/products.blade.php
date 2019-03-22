<?php /* /var/www/lv-shopify.loc/resources/views/products.blade.php */ ?>
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/picnic/6.1.1/picnic.min.css">
    <script src="http://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
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
        <?php
        //dd($all_products);
        $options_list = '';
        foreach ($all_products['products'] as $value){
            $options_list .= "<option value=".$value['handle'].">".$value['title']."</option>";
        } ?>
        <div>
            <?php $__currentLoopData = $all_products['products']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="card">
                <section>
                    <h3><?php echo e($product['title']); ?></h3>
                </section>
                <section>
                    <h4>handle: {{$product['handle']}}</h4>
                </section>
                <?php if($product['images']): ?>
                <section>
                    <?php $__currentLoopData = $product['images']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img style="width: 100px; height: 100px;" id="<?php echo e($image['id']); ?>" src="<?php echo e($image['src']); ?>" alt="<?php echo e($product['title']); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </section>
                <?php endif; ?>
                <?php if($product['metafields']): ?>
                <section>
                    <form class="metafields_update">
                        @csrf
                        <input type="hidden" name="owner_id" value="<?php echo e($product['id']); ?>">
                        <?php $__currentLoopData = $product['metafields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $metafield): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="margin-bottom: 20px">
                            @if(false)<span>key: <span style="display: block;"><?php echo e($metafield['key']); ?></span></span>@endif
                                <select name="value[<?php echo e($key); ?>]" multiple="multiple" style="height: 100px;"><?php echo $options_list ?></select>
                            <!--<span>value: </span><input type="text" name="value[<?php echo e($key); ?>]" value="<?php echo e($metafield['value']); ?>">-->
                                @if(false)<a onclick="delete_metafield({{$key}}, {{$product['id']}}, '{{ csrf_token() }}', this)"><span>DELETE</span></a>@endif
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <button>Отправить все кастом поля для продукта</button>
                    </form>
                </section>
                @else
                    <section>
                        <form class="metafields_create">
                            @csrf
                            <input type="hidden" name="owner_id" value="{{$product['id']}}">
                            <select name="value" multiple="multiple" style="height: 100px;"><?php echo $options_list ?></select>
                            <button>создать кастом поле</button>
                        </form>
                    </section>
                <?php endif; ?>
            </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <script>
                function delete_metafield(metafield_id, product_id, token, e){
                    //get the action-url of the form
                    var actionurl = window.location.search;
                    //do your own request an handle the results
                    $.ajax({
                        url: 'delete'+actionurl,
                        type: 'post',
                        dataType: 'text',
                        //contentType: "text",
                        data: 'owner_id='+product_id+'&metafield_id='+metafield_id+'&_token='+token,
                        success: function(data) {
                            $(e).parent().parent().remove();
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            console.log("Status: " + textStatus); console.log("Error: " + errorThrown);
                        }
                    });
                    return false;
                }
                $(function() {
                    $("form.metafields_update").submit(function(e) {
                        //prevent Default functionality
                        e.preventDefault();
                        //get the action-url of the form
                        var actionurl = window.location.search;
                        var data_selected = "";
                        $(this).find('select option:selected').each(function(index, item) {
                            data_selected += $( this ).val() + ",";
                        });
                        var data_selected_name = $(this).find('select').attr( "name" );
                        var serialize_data = $(this).find('input').serialize();
                        //do your own request an handle the results
                        $.ajax({
                            url: 'update'+actionurl,
                            type: 'post',
                            dataType: 'json',
                            //contentType: "text",
                            data: serialize_data+'&'+data_selected_name+'='+data_selected,
                            success: function(data) {
                                $(e.target.getElementsByTagName('button')[0]).html('Прошло успешно '+data.metafield.value);
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                console.log("Status: " + textStatus); console.log("Error: " + errorThrown);
                            }
                        });
                        return false;
                    });

                    $("form.metafields_create").submit(function(e) {
                        //prevent Default functionality
                        e.preventDefault();
                        //get the action-url of the form
                        var actionurl = window.location.search;
                        var data_selected = "";
                        $(this).find('select option:selected').each(function(index, item) {
                            data_selected += $( this ).val() + ",";
                        });
                        var data_selected_name = $(this).find('select').attr( "name" );
                        var serialize_data = $(this).find('input').serialize();
                        //do your own request an handle the results
                        $.ajax({
                            url: 'create'+actionurl,
                            type: 'post',
                            dataType: 'json',
                            //contentType: "text",
                            data: serialize_data+'&'+data_selected_name+'='+data_selected,
                            success: function(data) {
                                //console.log(e.target.getElementsByTagName('button')[0]);
                                $(e.target.getElementsByTagName('button')[0]).html('Прошло успешно '+data.metafield.value);
                            },
                            error: function(data, XMLHttpRequest, textStatus, errorThrown) {
                                console.log("Status: " + textStatus); console.log("Error: " + errorThrown);
                            }
                        });
                        return false;
                    });

                });
            </script>
        </div>
    </div>
</main>

</body>
</html>