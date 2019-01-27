<?php
// получим массив объектов заказов
$orders = $modx->getCollection('msOrder');
$list=[];
// запишем в массив list идентификаторы всех заказов
foreach ($orders as $order){
    $list[]=$order->get('id');
}

$products=[];
$miniShop2 = $modx->getService('miniShop2');
$miniShop2->initialize($modx->context->key);
/** @var pdoFetch $pdoFetch */
if (!$modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
    return false;
}

//для кадлого заказа получаем товары этого заказа и заносим в массив products (ключем является идентификатор товара а значением будет количество раз когда его купили)
foreach ($list as $id) {
$pdoFetch = new pdoFetch($modx, $scriptProperties);
if (!$order = $modx->getObject('msOrder', $id)) {
    //return $modx->lexicon('ms2_err_order_nf');
}
$where = array(
    'msOrderProduct.order_id' => $id,
);

// Include products properties
$leftJoin = array(
    'msProduct' => array(
        'class' => 'msProduct',
        'on' => 'msProduct.id = msOrderProduct.product_id',
    )
);

// Select columns
$select = array(
    'msProduct' => !empty($includeContent)
        ? $modx->getSelectColumns('msProduct', 'msProduct')
        : $modx->getSelectColumns('msProduct', 'msProduct', '', array('content'), true),
   
);

// Tables for joining
$default = array(
    'class' => 'msOrderProduct',
    'where' => $where,
    'leftJoin' => $leftJoin,
    'select' => $select,
    'joinTVsTo' => 'msProduct',
    'sortby' => 'msOrderProduct.id',
    'sortdir' => 'asc',
    'groupby' => 'msOrderProduct.id',
    'fastMode' => false,
    'limit' => 0,
    'return' => 'data',
    'decodeJSON' => true,
    'nestedChunkPrefix' => 'minishop2_',
);
// Merge all properties and run!
$pdoFetch->setConfig(array_merge($default, $scriptProperties), true);
$rows = $pdoFetch->run();



foreach ($rows as $product) {
    if ($product['id']>0){
   $products[(int)$product['id']]++;
    }
}

} //end foreach
// сортируем массив по значению, чтобы первыми были те записи у кого значение больше
arsort($products);
// формируем ссылку на товар, находим его имя и выводим на экран
$result=[];
foreach ($products as $id=>$oneProduct){
    
    $url = $modx->makeUrl($id);
    $test=$modx->getObject('modResource',$id);
    $name = $test->get('pagetitle');
    $result[]=['id'=>$id,'name'=>$name,'url'=>$url,'counter'=>$oneProduct];

}
$output = $pdoFetch->getChunk($tpl, array(
    'products' => $result
    
));
return $output;
