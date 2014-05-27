<?php
   if (isset($api->callback) && !empty($api->callback))
   {
       echo $api->callback . '(' .json_encode($api->json). ')';
   }
   else 
   {
       echo json_encode($api->json);
   }
?>