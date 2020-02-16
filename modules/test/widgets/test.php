<?php

namespace ElementorCustomPlugin\Modules\Test\Widgets;

class Test extends \Elementor\Widget_Base {

	/**
	 * @var string
	 */
	private $url = '';

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		if ( isset( $data['settings'] ) ) {
			$this->load_settings( $data['settings'] );
		}
	}

	public function get_name() {
		return 'test widget';
	}

	public function get_title() {
		return 'Test Widget';
	}

	public function get_icon() {
		return 'fab fa-elementor';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	private function load_settings( $settings ) {
		if ( isset( $settings['url'] ) ) {
			$this->url = $settings['url'];
		}
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => 'Content',
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'url',
			[
				'label' => 'Remote HTML URL',
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'url',
				'placeholder' => 'https://link.com/index.html',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! $this->url ) {
			echo '';
			return;
		}

		echo '<div class="test-widget">' . wp_remote_retrieve_body( wp_remote_get( $this->url ) ) . '</div>';
	}

	protected function _content_template() {
		return $this->render();
	}
}
