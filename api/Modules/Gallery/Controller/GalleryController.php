<?php

namespace Tulic\aPiE\Gallery;

use Tulic\aPiE\Base\Controller;

class GalleryController extends Controller
{

     /**
      * @Inject
      * @namespace Tulic\aPiE\Base
      * @var DbAdapter
      */
     private $db;

     /**
      * @Inject
      * @namespace Tulic\aPiE\Photo
      * @var PhotoController
      */
     private $photoController;

     /**
      * @Inject
      * @namespace Tulic\aPiE\User
      * @var User
      */
     private $user;

     public function processRequest($params = [])
     {
          $id = $this->request->getNextParam(true);
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
               $this->photoController->processRequest();
               $this->setMessage($this->photoController->getMessage());
               return $this->photoController->getData();
          } else {
               return $this->db->select('gallery');
          }
     }

     protected function post($input)
     {
          if ($this->user->getId()) {
               if ($this->db->insert('gallery', ['name' => $input['name']])) {
                    return true;
               } else {
                    return false;
               }
          } else {
               $this->setMessage('missing rights');
          }
     }

     protected function put($id, $input)
     {
          if ($this->user->getId()) {
               if ($this->db->update('gallery', ['name' => $input['name']], 'id = ' . $id)) { 
                    return true;
               } else {
                    return false;
               }
          } else {
               $this->setMessage('missing rights');
          }
     }

     protected function delete($id)
     {
          if ($this->user->getId()) {
               if ($this->db->delete('gallery', 'id = ' . $id)) {
                    return true;
               } else {
                    return false;
               }
          } else {
               $this->setMessage('missing rights');
          }
     }
}
