<?php
class ControllerExtensionModulelatestLib extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/latest_lib');

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		$this->load->model('tool/image');

		$data['products'] = array();

		$results = $this->model_catalog_product->getLatestProducts($setting['limit']);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if (!is_null($result['special']) && (float)$result['special'] >= 0) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$tax_price = (float)$result['special'];
				} else {
					$special = false;
					$tax_price = (float)$result['price'];
				}
	
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format($tax_price, $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				
				
				$total_sales = $this->model_catalog_product->getTotalProductSales($result['product_id']);
				
				 if($result['price'] > 0){
				    $discount = round((($result['price'] -  $result['special'])/$result['price']) * 100 ,0). '%';
				    } else {
				     $discount = '';
				   }
				   
				

				if(mb_strlen($result['name'], 'UTF-8') > 50){
						$name = utf8_substr($result['name'], 0, 50) . '...';
					} else {
						$name = $result['name'];
				}

				if($total_sales > $setting['topselling'] &&  $setting['topselling'] != '0'){
					$top_selling = $this->language->get('text_topselling');
					} else {
					$top_selling = "";
				}
				

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $name,
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'discount' 	  => $discount,
					'top_selling' => $top_selling,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			return $this->load->view('extension/module/latest_lib', $data);
		}
	}
}
