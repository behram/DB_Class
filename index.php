<?php
header('Content-type: text/html; charset=utf8');
define('ATTAINABLE', true);
require_once 'dtbs.php';

$dtbs = new Dtbs;
echo '<pre>';
/*
 *  /// Where çatal sokmak
 * >>>>>> öncekiyle sonrakiyle bağ
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
 *  öncekiyle bağ            > n
 *  sonrakiyle bağ           > n
 *  değişken türü            > n
 *  karşılaştırma operatörü  > t
 *  ========================== :n.n.n.t
 * 
 * 
 * dizayn = öncekiyle bağ.sonrakiyle bağ.değişken türü.karşılaştırma operatörü
 */
//$dtbs->where('uye_id:n.n.i.b', 10);
//exit;
$result = $dtbs->uyeler->select()
        ->where('uye_id:n.n.i.b', 10);
        //->where('uye_id:n.a.i.f', 10)
        //->where('uye_id', 10) == ->where('uye_id:n.n.s.t', 10)
        //->where(array('uye_id:n.a.i.f' => 10, 'uye_aktif:a.n.i.t'))
        //->result();
print_r($result);

?>
