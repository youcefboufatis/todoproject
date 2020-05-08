<?php

    class functions extends DBcontroller
    {
   
       /* --- function get url --- */
       public function getUrl($type, $admin = false)
       {
           $to = '';
           global $website;
           if ($admin == false) {
               $admin = '';
           } else {
               $admin = 'admin/';
           }
           switch ($type) {
               case 'home':
                   $to .= $website.$admin;
                   break;
               case 'css':
                   $to .= $website.$admin.'layouts/css/';
                   break;
               case 'js':
                   $to .= $website.$admin.'layouts/js/';
                   break;
               case 'img':
                   $to .= $website.$admin.'layouts/img/';
                   break;
           }

           return $to;
       }
   }
