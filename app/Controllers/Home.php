<?php
//Handles the logic of Your app

class Home 
{

    use Controllers;
    public function index($a = '', $b = '', $c = '')
    {



        $data['username'] = empty($_SESSION['USER']) ? 'Friend' : $_SESSION['USER']->full_name;
        $data['title'] = 'Maravel - Home';
        
        $this->views('home', $data);
        // $user = new User();
        // $model->retrieve();
        // $arr['id'] = 1;
        // $arr['names'] = 'Samuel';
        // $arr['age'] = 24;
        // $result = $model->where($arr);

        // $arr['names'] = 'Samuel';
        // $arr['age'] = 24;
        // $result = $model->insert($arr);

        // $result = $model->delete(4, 'names');
        // $result = $model->delete(5);

        // $arr['names'] = 'Sammy';
        // $arr['age'] = 27;

        // $result = $user->update(4, $arr);

        // $result = $user->findAll();
        // show($result);
    }

   
}

