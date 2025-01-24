<?php
class Keranjang extends CI_Controller
{

    public function add()
    {

        $redirect_page = $this->input->post('redirect_page');
        $data = array(
            'id'      => $this->input->post('id'),
            'qty'     => $this->input->post('qty'),
            'price'   => $this->input->post('price'),
            'name'    => $this->input->post('name'),
            'id_pelanggan'  => $this->input->post('id_pelanggan')


        );
   
        $this->cart->insert($data);



        redirect($redirect_page, 'refresh');
    }

    public function delete($rowid)
    {
        $this->cart->remove($rowid);
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible d-flex justify-content-center" role="alert">Produk berhasil dihapus !!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
        );
        redirect('menu');
    }
}
