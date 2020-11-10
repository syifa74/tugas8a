<?php

namespace App\Controllers;

use App\Models\BukuModel;

class Listbuku extends BaseController
{
    protected $bukuModel;
    public function __construct()
    {
        $this->bukuModel = new BukuModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List buku',
            'buku' => $this->bukuModel->getBuku()
        ];

        return view('/pages/beranda', $data);
    }

    public function detail($slug)
    {
        $data = [
            'title' => 'Detail buku',
            'buku' => $this->bukuModel->getBuku($slug)
        ];

        if (empty($data['buku'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Berita' . $slug . 'tidak ditemukan');
        }

        return view('/pages/detail', $data);
    }

    public function create()
    {
        session();
        $data = [
            'title' => 'Tambah Buku',
            'validation' => \Config\Services::validation()
        ];

        return view('pages/tambah', $data);
    }

    public function save()
    {
        if (!$this->validate([
            'judul' => 'required|is_unique[list-buku.judul]',
            'penulis' => 'required',
            'harga' => 'required',
            'review' => 'required'
        ])) {
            $validation = \Config\Services::validation();

            return redirect()->to('/listbuku/create')->withInput()->with('validation', $validation);
        }
        session()->setFlashdata('pesan', 'Data telah berhasil ditambahkan');
        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->bukuModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'harga' => $this->request->getVar('harga'),
            'review' => $this->request->getVar('review')
        ]);

        return redirect()->to('/listbuku/#list');
    }

    public function delete($id)
    {
        $this->bukuModel->delete($id);

        return redirect()->to('/listbuku/#list');
    }

    //--------------------------------------------------------------------

}
