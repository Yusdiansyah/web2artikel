<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Artikel extends BaseController
{
  public function index()
  {
    $title = 'Daftar Artikel';
    $models = new ArtikelModel();
    $artikel = $models->findAll();
    return view('artikel/index', compact('artikel', 'title'));
  }
  public function view($slug)
  {

    $models = new ArtikelModel();
    $artikel = $models->where([
      'slug' => $slug
    ])->first();
    if ($this->request->isAJAX()) {
      return view('artikel/detail', ['artikel' => $artikel]);
    }

    if (!$artikel) {
      throw PageNotFoundException::forPageNotFound();
    }
    $title = $artikel['judul'];
    return view('artikel/detail', compact('artikel', 'title'));
  }
  public function adminview($slug)
  {

    $models = new ArtikelModel();
    $artikel = $models->where([
      'slug' => $slug
    ])->first();
    if ($this->request->isAJAX()) {
      return view('admin/artikel/detail', ['artikel' => $artikel]);
    }

    if (!$artikel) {
      throw PageNotFoundException::forPageNotFound();
    }
    $title = $artikel['judul'];
    return view('artikel/detail', compact('artikel', 'title'));
  }

  public function admin_index()
  {
    $title = 'Daftar Artikel';
    $q = $this->request->getVar('q') ?? '';
    $models = new ArtikelModel();
    $data = [
      'title' => $title,
      'q' => $q,
      'artikel' => $models->like('judul', $q)->paginate(2),
      'pager' => $models->pager,
    ];
    return view('artikel/admin_index', $data);
  }

  public function add()
  {
    $validation = \Config\Services::validation();
    $validation->setRules(['judul' => 'required']);
    $isDataValid = $validation->withRequest($this->request)->run();

    if ($isDataValid) {
      $file = $this->request->getFile('gambar');
      $file->move(ROOTPATH . 'public/gambar');

      $artikel = new ArtikelModel();
      $artikel->insert([
        'judul' => $this->request->getPost('judul'),
        'isi' => $this->request->getPost('isi'),
        'slug' => url_title($this->request->getPost('judul')),
        'gambar' => $file->getName(),
      ]);
      return redirect('admin/artikel');
    }
    $title = "Tambah Artikel";
    return view('artikel/form_add', compact('title'));
  }

  public function edit($id)
  {
    $artikel = new ArtikelModel();

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

  public function getData()
  {
    $models = new ArtikelModel;
    $data = $models->findAll();
    return $this->response->setJSON($data);
  }

  public function delete($id)
  {
    $models = new ArtikelModel;
    $models->delete($id);
    return redirect('admin/artikel');
  }
}
