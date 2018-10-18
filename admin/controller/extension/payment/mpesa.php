<?php

class ControllerExtensionPaymentMpesa extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/payment/mpesa');
        $this->document->setTitle('Lipa na MPESA Configuration');
        $this->load->model('setting/setting');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('payment_mpesa', $this->request->post);
            $this->session->data['success'] = 'Success: You have modified Lipa na Mpesa details!';
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        }
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['entry_mpesa'] = $this->language->get('entry_mpesa');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_dev'] = $this->language->get('entry_dev');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['app_id'])) {
            $data['error_app_id'] = $this->error['app_id'];
        } else {
            $data['error_app_id'] = '';
        }

        if (isset($this->error['merchant_private_key'])) {
            $data['error_merchant_private_key'] = $this->error['merchant_private_key'];
        } else {
            $data['error_merchant_private_key'] = '';
        }

        if (isset($this->error['mpesa_public_key'])) {
            $data['error_mpesa_public_key'] = $this->error['mpesa_public_key'];
        } else {
            $data['error_mpesa_public_key'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/mpesa', 'user_token=' . $this->session->data['user_token'], true)
        );


        $data['action'] = $this->url->link('extension/payment/mpesa', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);


        if (isset($this->request->post['payment_mpesa_app_id'])) {
            $data['payment_mpesa_app_id'] = $this->request->post['payment_mpesa_app_id'];
        } else {
            $data['payment_mpesa_app_id'] = $this->config->get('payment_mpesa_app_id');
        }

        if (isset($this->request->post['payment_mpesa_merchant_private_key'])) {
            $data['payment_mpesa_merchant_private_key'] = $this->request->post['payment_mpesa_merchant_private_key'];
        } else {
            $data['payment_mpesa_merchant_private_key'] = $this->config->get('payment_mpesa_merchant_private_key');
        }

        if (isset($this->request->post['payment_mpesa_portal_public_key'])) {
            $data['payment_mpesa_portal_public_key'] = $this->request->post['payment_mpesa_portal_public_key'];
        } else {
            $data['payment_mpesa_portal_public_key'] = $this->config->get('payment_mpesa_portal_public_key');
        }

        if (isset($this->request->post['payment_mpesa_total'])) {
            $data['payment_mpesa_total'] = $this->request->post['payment_mpesa_total'];
        } else {
            $data['payment_mpesa_total'] = $this->config->get('payment_mpesa_total');
        }

        if (isset($this->request->post['payment_mpesa_order_status_id'])) {
            $data['payment_mpesa_order_status_id'] = $this->request->post['payment_mpesa_order_status_id'];
        } else {
            $data['payment_mpesa_order_status_id'] = $this->config->get('payment_mpesa_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['payment_mpesa_geo_zone_id'])) {
            $data['payment_mpesa_geo_zone_id'] = $this->request->post['payment_mpesa_geo_zone_id'];
        } else {
            $data['payment_mpesa_geo_zone_id'] = $this->config->get('payment_mpesa_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['payment_mpesa_test'])) {
            $data['payment_mpesa_test'] = $this->request->post['payment_mpesa_test'];
        } else {
            $data['payment_mpesa_test'] = $this->config->get('payment_mpesa_test');
        }

        if (isset($this->request->post['payment_mpesa_status'])) {
            $data['payment_mpesa_status'] = $this->request->post['payment_mpesa_status'];
        } else {
            $data['payment_mpesa_status'] = $this->config->get('payment_mpesa_status');
        }

        if (isset($this->request->post['payment_mpesa_sort_order'])) {
            $data['payment_mpesa_sort_order'] = $this->request->post['payment_mpesa_sort_order'];
        } else {
            $data['payment_mpesa_sort_order'] = $this->config->get('payment_mpesa_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/mpesa', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/mpesa')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['payment_mpesa_app_id']) {
            $this->error['app_id'] = $this->language->get('error_app_id');
        }

        if (!$this->request->post['payment_mpesa_merchant_private_key']) {
            $this->error['merchant_private_key'] = $this->language->get('error_merchant_private_key');
        }

        if (!$this->request->post['payment_mpesa_portal_public_key']) {
            $this->error['mpesa_public_key'] = $this->language->get('error_mpesa_public_key');
        }

        return !$this->error;
    }
}