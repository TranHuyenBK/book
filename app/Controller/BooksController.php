<?php
App::uses('AppController', 'Controller');
/**
 * Books Controller
 *
 * @property Book $Book
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class BooksController extends AppController {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Js');

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session', 'Flash');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Book->recursive = 0;
		$this->set('books', $this->Paginator->paginate());
	}

	public function fetch() {
		$this->autoRender = false;
		echo '<meta charset="UTF-8">';
		pr($this->Book->find());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Book->exists($id)) {
			throw new NotFoundException(__('Invalid book'));
		}
		$options = array('conditions' => array('Book.' . $this->Book->primaryKey => $id));
		$this->set('book', $this->Book->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Book->create();
			if ($this->Book->save($this->request->data)) {
				$this->Flash->success(__('The book has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The book could not be saved. Please, try again.'));
			}
		}
		$categories = $this->Book->Category->find('list');
		$writers = $this->Book->Writer->find('list');
		$this->set(compact('categories', 'writers'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Book->exists($id)) {
			throw new NotFoundException(__('Invalid book'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Book->save($this->request->data)) {
				$this->Flash->success(__('The book has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The book could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Book.' . $this->Book->primaryKey => $id));
			$this->request->data = $this->Book->find('first', $options);
		}
		$categories = $this->Book->Category->find('list');
		$writers = $this->Book->Writer->find('list');
		$this->set(compact('categories', 'writers'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Book->exists($id)) {
			throw new NotFoundException(__('Invalid book'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Book->delete($id)) {
			$this->Flash->success(__('The book has been deleted.'));
		} else {
			$this->Flash->error(__('The book could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
