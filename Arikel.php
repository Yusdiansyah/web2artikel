<?php

namespace App\Controllers;

use App\Models\artikelmodel;
use CodeIgniter\Exceptions\PageNotFoundException;

class artikel extends BaseController
{
  public function index()
  {
    $title = 'Daftar Artikel';
    $models = new artikelmodel;
    $artikel = $models->findAll();
    return view('artikel/index', compact('artikel', 'title'));
  }
  public function view($slug)
  {

    $models = new artikelmodel();
    $artikel = $models->where([
      'slug' => $slug
    ])->first();

    if (!$artikel) {
      throw PageNotFoundException::forPageNotFound();
    }
    $title = $artikel['judul'];
    return view('artikel/detail', compact('artikel', 'title'));
  }

  public function admin_index()
  {
    $title = 'Daftar Artikel';
    $models = new artikelmodel();
    $artikel = $models->findAll();
    return view('artikel/admin_index', compact('artikel', 'title'));
  }

  public function add()
  {
    $validation = \Config\Services::validation();
    $validation->setRules(['judul' => 'required']);
    $isDataValid = $validation->withRequest($this->request)->run();

    if ($isDataValid) {
      $artikel = new artikelmodel();
      $artikel->insert([
        'judul' => $this->request->getPost('judul'),
        'isi' => $this->request->getPost('isi'),
        'slug' => url_title($this->request->getPost('judul')),
      ]);
      return redirect('admin/artikel');
    }
    $title = "Tambah Artikel";
    return view('artikel/form/add', compact('title'));
  }

  public function edit($id)
  {
    $artikel = new artikelmodel();

    $validation = \Config\Services::validation();
    $validation->setRules(['judul' => 'required']);
    $isDataValid = $validation->withRequest($this->request)->run();
    if ($isDataValid) {
      $artikel->update($id, [
        'judul' => $this->request->getPost('judul'),
        'isi' => $this->request->getPost('isi'),
      ]);
      return redirect('admin/artikel');
    }

    $data = $artikel->where('id', $id)->first();
    $title = "Edit Artikel";
    return view('artikel/form_edit', compact('title', 'data'));
  }

  public function delete($id)
  {
    $artikel = new artikelmodel;
    $artikel->delete($id);
    return redirect('admin/artikel');
  }
}
