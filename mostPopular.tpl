<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<div class="container">
<h1>Наиболее покупаемые товары</h1>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">ID товара</th>
      <th scope="col">Название товара / ссылка</th>
      <th scope="col">Сколько раз был куплен</th>
      
    </tr>
  </thead>
  <tbody>
   
   {foreach $products as $product}
    <tr>
      <th scope="row">{$product.id}</th>
      <td><a href="{$product.url}">{$product.name}</a></td>
      <td><strong>{$product.counter}</strong> раз(а)</td>
      
    </tr>

{/foreach}
  </tbody>
</table>
</div>