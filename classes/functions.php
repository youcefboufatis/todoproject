<?php

    class functions extends DBcontroller
    {
       /* --- Function Count Row --- */
       public function count($array)
       {
           $result = count($array);

           return $result;
       }

       /* --- Function redirect to url --- */
       public function red($filename) {
        if (!headers_sent())
            header('Location: '.$filename);
        else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.$filename.'";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url='.$filename.'" />';
            echo '</noscript>';
        }
    }

       /* --- Function get msg --- */
       public function getMsg($text, $case)
       {
           $msg = '';
           switch ($case) {
               case 'success':
                   $msg .= "<div class='alert alert-success' role='alert'>$text<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                   break;
               case 'alert':
                   $msg .= "<div class='alert alert-danger' role='alert'>$text<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                   break;
               default:
               $msg .= "<div class='alert alert-primary' role='alert'>$text<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
           break;
           }

           echo $msg;
       }
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
       /* --- function get Title --*/
       public function match($array, $matchKey, $matchValue)
       {
           $item = null;
           foreach($array as $struct) {
               if ($matchValue == $struct[$matchKey]) {
                   $item = $struct;
                   return $item;
                   break;
               }
           }
       }
      public function dmatch($array, $matchKey, $matchValue)
       {
           $item1 = null;
           $item2 = null;

           foreach($array as $struct) {
               if ($matchValue == $struct[$matchKey]) {
                   $item1 = $matchValue;
                   $item2 = null;

                    break;
               } elseif ($matchValue != $struct[$matchKey]) {
                    $item1 = null;
                    $item2 = $matchValue;
                    break;
               }
           }

           return $e = array('a'=> $matchKey,'m' => $item1, 'dm' => $item2);

       }
        public function emptySection($text)
        {
            $card = '<div class="card_section_empty" >'.$text.'</div>';

            return $card;
        }

        public function errorHandle($text)
        {
            $error = '<div data-type="alert" class="alert alert-red">
                        <i class="icon fas fa-exclamation-triangle"></i>
                        <div class="alert-body">
                            '.$text.'
                        </div>
                    </div>';

            return $error;
        }

        public function getWebsiteSetting()
    {
        $posts = $this->querySelect('web_about', null, null);
        $data_posts = $this->getAll($posts);
        while ($row =  $this->fetch($data_posts['query'], $data_posts['status'])) {
            $settings = array($row['option_name'] => $row['option_value'], );

        }
        return $settings;
    }

    public function getTitle($pageTitle)
        {
            if (isset($pageTitle)) {
                echo $pageTitle;
            } else {
                echo 'Default';
            }
        }
        public function getYoutube($url){
            $youtube = "http://www.youtube.com/oembed?url=". $url ."&format=json";
            $curl = curl_init($youtube);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $return = curl_exec($curl);
            curl_close($curl);
            return json_decode($return, true);
        }
        public function getButton($url, $type)
        {
            switch ($type) {
                case 'add':
                    $html = "<span class='fixed-action-btn'>
                                <a class='btn btn-success' href='./{$url}?do=add'><i class='large material-icons right'>add</i></a>
                             </span>";
                    break;
                case 'edit':
                    $html = "<a class='btn btn-success' target='_blank'  href='./{$url}?do=edit&id={$id}'><i class='material-icons'>edit</i>Edit</a>";
                    break;
                case 'delete':
                    $html = "<a class='btn btn-danger' target='_blank'  href='./{$url}?do=delete&id={$id}'><i class='material-icons'>delete</i>Delete</a>";
                    break;
            }
            return $html;
        }
   }
