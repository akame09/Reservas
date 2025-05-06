
<?php
class reservaController extends Controller{
    public function __construct(){
        parent::__construct();
        $this->loadModel('reserva');
    }

    public function index(){
        $this->view->render('reserva/index');
    }

    public function create(){
        $this->view->render('reserva/create');
    }

    public function store(){
        // Logic to store reservation
        $this->model->store($_POST);
        header('Location: ' . URL . 'reserva/index');
    }

    public function edit($id){
        $this->view->reservation = $this->model->getById($id);
        $this->view->render('reserva/edit');
    }

    public function update($id){
        // Logic to update reservation
        $this->model->update($id, $_POST);
        header('Location: ' . URL . 'reserva/index');
    }
}

?>