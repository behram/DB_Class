<?php
require_once 'config.php';

$dtbs = new app\lib\Dtbs;

echo '<pre>';
//print_r($dtbs->uyeler->select()->result());

/*
 *  /// Where çatal sokmak
 * >>>>>> sonrakiyle bağ
 * &      - a - and
 * |      - o - or
 * 
 * >>>>>> Karşılaştırma operatörleri 
 * <>     - f - false
 * =      - t - true
 * <      - s - small
 * >      - b - big
 * 
 * >>>>>> Değişken türleri
 * int    - i - integer
 * 
 * >>>>>> Null değerler
 * null - n - bu parametre boş gönderilecek ise
 *  
 * /// Where çatala sokmayacaksak default çatal parametreleri
 *
 *  sonrakiyle bağ           > n
 *  değişken türü            > n
 *  karşılaştırma operatörü  > t
 *  ========================== :n.n.t
 * 
 * 
 * dizayn = sonrakiyle bağ.değişken türü.karşılaştırma operatörü
 */
print_r($dtbs//->where('uye_id:n.i.b', 10);
//exit;
// property = özellik
// çatal = Fork
        //->where('uye_id:n.n.i.b', 10);
        //->where('uye_id:n.a.i.f', 10)
        //->where('uye_id', 10) == ->where('uye_id:n.s.t', 10)
        ->uyeler->select()
        ->where(array('uye_id:n.i.t' => 12))
        ->result());


?>
