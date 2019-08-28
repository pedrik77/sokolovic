<?php

namespace Tulic\aPiE\Photo;

use Tulic\aPiE\Base\Controller;

class PhotoController extends Controller
{

     /**
      * @Inject
      * @namespace Tulic\aPiE\Base
      * @var DbAdapter
      */
     private $db;

     /**
      * @Inject
      * @namespace Tulic\aPiE\User
      * @var User
      */
     private $user;

     public function processRequest($params = [])
     {
          $id = $this->request->getNextParam();
          $input = $this->request->getInput();
          if ($this->request->getMethod() == 'GET') {
               $result = $this->get($id);
          } else if ($this->request->getMethod() == 'POST') {
               $result = $this->post($input);
          } else if ($this->request->getMethod() == 'PUT') {
               $result = $this->put($id, $input);
          } else if ($this->request->getMethod() == 'DELETE') {
               $result = $this->delete($id);
          }
          $this->mapData($result);
     }

     protected function get($id)
     {
          if ($id) {
               return $this->db->select('photo', 'gallery_id = ' . $id, '', 'id as id, title as caption, path as src, path as thumbnail, gallery_id');
          } else {
               return $this->db->select('photo','', '', 'id as id, title as caption, path as src, path as thumbnail, gallery_id');
          }
     }

     protected function post($input)
     {
          if ($this->user->getId()) {
               if ($input) {
                    $files = $this->request->getFiles();
                    $success = false;
                    foreach ($files as $file) {
                         $explodedName = explode('.', $file['name']);
                         array_pop($explodedName);
                         $title = implode('.', $explodedName);
                         $path = IMAGE_FOLDER . $file['name'];
                         if (@move_uploaded_file($file['tmp_name'], $path)) {
                              $this->db->insert('photo', ['title' => $title, 'path' => $path, 'gallery_id' => $input['gallery_id']]);                                 
                              $success = true;
                         } else {
                              $this->setMessage('file upload failed');
                         }
                    }
                    return [$success];
               } else {
                    $this->setMessage('Missing input!');
               }
          } else {
               $this->setMessage('missing rights');
          }
     }

     protected function put($id, $input)
     {
          return false;
     }

     protected function delete($id)
     {
          if ($this->user->getId()) {
               if ($res = $this->db->select('photo', 'id = ' . $id)) {
                    $path = $res[0]['path'];
                    if ($this->db->delete('photo', 'id = ' . $id)) {
                         if (unlink($path)) {
                              return true;
                         } else {
                              return false;
                         }
                    } else {
                         return false;
                    }
               }
          } else {
               $this->setMessage('missing rights');
          }
     }
}
