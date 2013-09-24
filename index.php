<?php

require_once 'config.php';
header('Content-type: text/html; charset=utf8');

$dtbs = new app\lib\Dtbs;
/*
 *       // WHERE KULLANIMI
 * ->where('uye_id:{sonrakiBag}.{veriType}.{karsilastirmaOperator}.{parantezAcKapa}', 10);
 * ->where('uye_id:{&&}-{||}.{int}-{string}-{float}.{=}-{<>}-{<}-{>}.{(}-{)}-{()}', 10);
 * ->where('uye_id:{a}-{o}.{i}-{s}-{f}.{t}-{f}-{s}-{b}.{o}-{c}-{oc}')
 * ->where('uye_id:a.i.t.[oc]', 10);
 * ->where('uye_id:a.i.f', 10);
 */

/*
 *      // SELECT KULLANIMI
 * ->table->select()->where('uye_id:n.i.b', 3);
 */

/*
 *      // INSERT KULLANIMI
 * ->table->insert(array('uye_kadi/uye_kadi:s' => 'bilalsay', 'uye_rutbe:i' => 1, 'uye_kilo:f' => 75.50));
 */

/*
 *      // UPDATE KULLANIMI
 * ->table->update(array('uye_kadi/uye_kadi:s' => 'bilalsay', 'uye_rutbe:i' => 1, 'uye_kilo:f' => 75.50));
 */

/*
 *      // DELETE KULLANIMI
 * ->table->delete()->where('uye_id:n.i.t', 12);
 */

/*
 *  /// Where Fork sokmak
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
 * int    - i
 * string - s
 * float  - f
 * 
 * >>>>>> Parantez aç kapa
 * (      - o
 * )      - c
 * ()     - oc
 * 
 * >>>>>> Null değerler
 * null - n - bu parametre boş gönderilecek ise
 *  
 * /// Where çatala sokmayacaksak default çatal parametreleri 
 *  sonrakiyle bağ           > n
 *  değişken türü            > n
 *  karşılaştırma operatörü  > t
 *  ========================== :n.n.t 
 * dizayn = sonrakiyle bağ.değişken türü.karşılaştırma operatörü.parantez aç kapa
 */
       /* SELECT*/
        $execute = $dtbs->uyeler->select()
        ->where('uye_id:a.i.b', 1)
        ->where('uye_kadi')       
        ->like('b')
        ->order('uye_id')
        ->limit(5)       
        ->execute();
              
       echo '<hr/><pre>';
       print_r($execute);
        

       /* INSERT
       $data = array(
           'uye_kadi' => 'eklendi',
           'uye_rutbe:i' => 1,
           'uye_eposta' => 'eklendi@gmail.com'
       );
       $sonuc = $dtbs->uyeler->select('uye_kadi')->where('uye_kadi', $data['uye_kadi'])->execute();
       if ($sonuc) {
           echo "Var";
       } else {
           $insert = $dtbs->uyeler->insert($data)->execute();
           if ($insert) {
               echo $dtbs->lastInsertId();
           } else {
               echo "Başarısız";
           }
       }*/

       /* UPDATE
       $data = array(
           'uye_kadi' => 'yeniBilalsay',
           'uye_rutbe:i' => 1,
           'uye_eposta' => 'yenideveloper@gmail.com'
       );
       $sonuc = $dtbs->uyeler->select('uye_kadi')->where('uye_kadi', 'bilalsay')->execute();
       if ($sonuc) {
           $update = $dtbs->uyeler->update($data)->where('uye_kadi', 'bilalsay')->execute();
           if ($update) {
               echo "Eklendi";
           } else {
               echo "Başarısız";
           }
       } else {
           echo "yok";
       }
       */
       
       /* DELETE
        $sonuc = $dtbs->uyeler->select('uye_id')->where('uye_id:n.i.t', 12)->execute();
       if ($sonuc) {
           $delete = $dtbs->uyeler->delete()->where('uye_id:n.i.t', 12)->execute();
           if ($delete) {
               echo "Silindi";
           } else {
               echo "Başarısız";
           }
       } else {
           echo "yok";
       }
       */
?>
