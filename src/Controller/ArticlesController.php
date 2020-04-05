<?php
// src/Controller/ArticlesController.php
namespace App\Controller;

use App\Controller\AppController;


class ArticlesController extends AppController
{


    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['display', 'view', 'index']);
        $this->Auth->allow(['tags']);
        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // Include the FlashComponent
    }




    public function index()
    {
        $this->set('authUser', $this->Auth->user());
        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find());

        $this->set(compact('articles'));
    }




    public function view($slug = null)
    {
        $this->set('authUser', $this->Auth->user());
        $article = $this->Articles->findBySlug($slug)->contain(['Tags'])->firstOrFail();
        $this->set(compact('article'));
    }


    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            $article->user_id = $this->Auth->user('id');

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        // Get a list of tags.
        $tags = $this->Articles->Tags->find('list');

        // Set tags to the view context
        $this->set('tags', $tags);
        $this->set('article', $article);
    }



    public function edit($slug)
    {
        $article = $this->Articles->findBySlug($slug)->contain(['Tags'])->firstOrFail();
        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData(), [
                'accessibleFields' => ['user_id' => false]
            ]);
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }
        $this->set('article', $article);
        // Get a list of tags.
        $tags = $this->Articles->Tags->find('list');
        // Set tags to the view context
         $this->set('tags', $tags);
    }



    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->˓→title));
            return $this->redirect(['action' => 'index']);
        }
    }




    public function tags()
    {
       $tags = $this->request->getParam('pass');

       $articles =  $this->Articles->find('tagged', ['tags'=> $tags]);

       $this->set([
           'articles' => $articles,
           'tags' => $tags
       ]);

    }


    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        // The add and tags actions are always allowed to logged in users.
        if (in_array($action, ['add', 'tags'])) {
            return true;
        }
        // All other actions require a slug.
        $slug = $this->request->getParam('pass.0');
        if (!$slug) {
            return false;
        }
        // Check that the article belongs to the current user.
        $article = $this->Articles->findBySlug($slug)->first();
        return $article->user_id === $user['id'];
    }







}
