<?php

class ControllerExtensionPaymentMpesa extends Controller
{
    private $error = array();

    public function index()
    {
        $this->language->load('payment/mpesa');
        $this->document->setTitle('Lipa na MPESA Configuration');
        $this->load->model('setting/setting');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('mpesa', $this->request->post);
            $this->session->data['success'] = 'Success: You have modified Lipa na Mpesa details!';
            $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
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
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        foreach ($languages as $language) {
            if (isset($this->error['mpesa_' . $language['language_id']])) {
                $data['error_mpesa_' . $language['language_id']] = $this->error['mpesa_' . $language['language_id']];
            } else {
                $data['error_mpesa_' . $language['language_id']] = '';
            }
        }
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['user_token'], 'SSL'),
            'separator' => false
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['user_token'], 'SSL'),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/mpesa', 'token=' . $this->session->data['user_token'], 'SSL'),
            'separator' => ' :: '
        );
        $data['action'] = $this->url->link('payment/mpesa', 'token=' . $this->session->data['user_token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['user_token'], 'SSL');
        $this->load->model('localisation/language');
        foreach ($languages as $language) {
            if (isset($this->request->post['mpesa_' . $language['language_id']])) {
                $data['mpesa_' . $language['language_id']] = $this->request->post['mpesa_' . $language['language_id']];
            } else {
                $data['mpesa_' . $language['language_id']] = $this->config->get('mpesa_' . $language['language_id']);
            }
        }
        $data['languages'] = $languages;
        if (isset($this->request->post['mpesa_total'])) {
            $data['mpesa_total'] = $this->request->post['mpesa_total'];
        } else {
            $data['mpesa_total'] = $this->config->get('mpesa_total');
        }
        if (isset($this->request->post['mpesa_order_status_id'])) {
            $data['mpesa_order_status_id'] = $this->request->post['mpesa_order_status_id'];
        } else {
            $data['mpesa_order_status_id'] = $this->config->get('mpesa_order_status_id');
        }
        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        if (isset($this->request->post['mpesa_geo_zone_id'])) {
            $data['mpesa_geo_zone_id'] = $this->request->post['mpesa_geo_zone_id'];
        } else {
            $data['mpesa_geo_zone_id'] = $this->config->get('mpesa_geo_zone_id');
        }
        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['mpesa_status'])) {
            $data['mpesa_status'] = $this->request->post['mpesa_status'];
        } else {
            $data['mpesa_status'] = $this->config->get('mpesa_status');
        }
        if (isset($this->request->post['mpesa_sort_order'])) {
            $data['mpesa_sort_order'] = $this->request->post['mpesa_sort_order'];
        } else {
            $data['mpesa_sort_order'] = $this->config->get('mpesa_sort_order');
        }
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/mpesa', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/mpesa')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        foreach ($languages as $language) {
            if (!$this->request->post['mpesa_' . $language['language_id']]) {
                $this->error['mpesa_' . $language['language_id']] = $this->language->get('error_mpesa');
            }
        }
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}