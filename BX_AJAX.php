<?
//подключаем пролог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");

   // --- подключение ядра и расширения ajax
   CJSCore::Init(array('ajax'));
   $sidAjax = 'testAjax';

   if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
      $GLOBALS['APPLICATION']->RestartBuffer();
      echo CUtil::PhpToJSObject(array(
               'RESULT' => 'HELLO',
               'ERROR' => ''
      ));
      die();
   }

?>
<div class="group">
   <div id="block"></div >
   <div id="process">wait ... </div >
</div>

<script>

   // похоже на console.log
   window.BXDEBUG = true;

   // функция DEMOLoad
   function DEMOLoad(){

       // получение элемента block и закрытие его
      BX.hide(BX("block"));

       // получение элемента process и открытие его
      BX.show(BX("process"));

      // загрузка json и отдача на DEMOResponse
      BX.ajax.loadJSON(
         '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
         DEMOResponse
      );

   }

   // функция DEMOResponse
   function DEMOResponse (data){

      // похоже на console.log
      BX.debug('AJAX-DEMOResponse ', data);
      
      // добавляем в block data.RESULT
      BX("block").innerHTML = data.RESULT;
      
      // показываем block
      BX.show(BX("block"));

      // закрываем process
      BX.hide(BX("process"));

      // похоже на обновление страницы или загрузку
      BX.onCustomEvent(
         BX(BX("block")),
         'DEMOUpdate'
      );
   }

   // выполняем функцию после загрузки страницы
   BX.ready(function(){
     
      /*
      BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
         window.location.href = window.location.href;
      });
      */

      // получение элементов и закрытие их
      BX.hide(BX("block"));
      BX.hide(BX("process"));
      
      // кликаем по css_ajax
      BX.bindDelegate(
         document.body, 'click', {className: 'css_ajax' },
         function(e){
            if(!e)
               e = window.event;
            
            // запускаем DEMOLoad
            DEMOLoad();
            
            // что то базовое отменяем
            return BX.PreventDefault(e);
         }
      );
      
   });

</script>

<div class="css_ajax">click Me</div>

<?
//подключаем эпилог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
