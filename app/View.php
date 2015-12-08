<?php

/**
 * @category view
 */
class View {

	/**
	 * Sufix for the template name, the extention of the tamplates
	 */
	const TEMPLATE_EXT = '.php';

	private $vars,
			$path,
			$layout,
			$template;

	public function __construct($layout = false) {
		if ($layout) {
			$this->layout = $layout;
		}
		$this->path = APPPATH . 'Template' . DS;
	}

	public function __get($name) {
		if (isset($this->vars[$name])) {
			return $this->vars[$name];
		}
		return null;
	}

	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}

	/**
	 * Displays a template with layout
	 * @param string $template The name of the templated to be displayed
	 */
	public function display($template) {
		$this->template = $template;
		if ($this->layout) {
			include $this->path . $this->layout . self::TEMPLATE_EXT;
		} else {
			$this->displayTemplate();
		}
	}

	/**
	 * DIsplays a teplate
	 */
	private function displayTemplate() {
		include $this->path . $this->template . self::TEMPLATE_EXT;
	}

	/**
	 * Displays JSON
	 * @param array $data
	 */
	public function dislayJson(Array $data) {
		header('Content-Type: application/json');
		echo (json_encode($data));
	}

}
