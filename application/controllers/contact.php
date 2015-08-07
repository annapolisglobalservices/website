<?php
/**
 * Created by PhpStorm.
 * User: bstalnaker
 * Date: 8/7/2015
 * Time: 1:08 AM
 */
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Contact extends CI_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following
     *        http://example.com/index.php/contact
     *    - or -
     *        http://example.com/index.php/contact/index
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/contact/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    private function _display($output)
    {
        $this->output->set_content_type('application/json');
        echo $output;
        return;
    }

    public function json_submit()
    {
        $this->load->library('email');
        $this->load->helper('url');
        $name = $this->input->post('name');
        $customer_email = $this->input->post('email');
        $subject = $this->input->post('subject');
        $message_post = $this->input->post('message');
        $message = "$name at $customer_email made a submission on the contact form.\n" . $message_post;
        $result = $this->email
            ->from('contact@annapolisgs.com', 'Contact Submission')
//            ->reply_to('domoogiefo@annapolisgs.com')// Optional, an account where a human being reads.
            ->to('contact@annapolisgs.com', 'Annapolis GS')
            ->subject($subject)
            ->message($message)
            ->send();
        $display = 0;
        if ($result)
        {
            //if it sent to us let's send one back to them
            $subject = 'Thanks for contacting us!';
            $data = array();
            $data['name'] = $name;
            $data['message'] = 'Thank you for getting in touch with us! We will receive your email shortly, and try our hardest to respond promptly.';
            $html = $this->load->view('contact/contact_thanks', $data, true);
            $result = $this->email
                ->from('contact@annapolisgs.com', 'Annapolis GS')
                ->reply_to('domoogiefo@annapolisgs.com')// Optional, an account where a human being reads.
                ->to($customer_email, $name)
                ->subject($subject)
                ->message($html)
                ->send();
            $display = 1;
        }
        return $this->_display($display);
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */