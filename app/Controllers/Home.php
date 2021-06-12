<?php

namespace App\Controllers;

class Home extends BaseController
{

	public function index()
	{
	echo view('templates/header');
    echo view('my_welcome_message');
    echo view('templates/footer');

	}

	public function customer($page = 'customer_entry')
	{
    $data['title'] = ucfirst($page); // Capitalize the first letter

    echo view('templates/header', $data);
    echo view($page, $data);
    echo view('templates/footer', $data);
	}
	
    public function customer_profile($page = 'customer_profile')
	{
    $data['title'] = ucfirst($page); // Capitalize the first letter

    echo view('templates/header', $data);
    echo view($page, $data);
    echo view('templates/footer', $data);
	}
}


