<?php namespace Pizza;

// TODO: ADD SUPPORT FOR SELECT ELEMENTS

include_once(dirname(__FILE__) . '/Validate.php');

/*
$form = array(
	'PizzaFormDetails' => array(
		'name' => 'formname',
		'action' => 'action',
		'enctype' => 'enctype',
		'method' => 'method',
		'novalidate',
		'target' => 'target',
		'onsubmit' => 'myFunction()',
	),
	
	'PizzaFormStructure' => array(
		'fieldset' => array (
			'PizzaElementDetails' => array(
				'name' => 'name',
			);
			'firstname',
		);
	);

	'firstname' => array(
		'id' => 'firstname',
		'label' => 'First Name',
		'type' => 'email'
		'defaultvalue' => 'default value',
		
		'validation' = array(
			'ascii',
			'alphanumeric',
			'numeric'
			'email',
			'url',
			'ukpostcode',
			'datetime' => 'date string here',
			'decimal' => 2,
			'presence',
			'minlength' => 5,
			'maxlength' => 20,
			'sameas' => 'other field index',
			'regex' => 'regex string here',
			'custom' => function($string) { return true; }
		),
	),
);
*/

class Form
{

	private $formarray;
	
	function __construct($formarray) {
		$this->formarray = $formarray;
		
		$this->formarray['PizzaSubmitCheck'] = array(
			'type' => 'hidden',
			'defaultvalue' => '1',
		);
	}
	
	private function echoStructureElement($element, $details) {
		$output = "<$element";
	
		$temp = !empty(strval($details['name'])) ? strval($details['name']) : '';
		
		$output .= " name=\"$temp\"";
		
		$temp = !empty(strval($details['id'])) ? strval($details['id']) : $temp;
		$output .= " id=\"$temp\"";
		
		foreach ($details as $key => $value) {
			if($key=='id' || $key=='name') {
				continue;
			}
			
			if(!empty(strval($value))) {
				$value = strval($value);
				$output .= " $key=\"$value\"";
			} else {
				$output .= " $key=\"$key\"";
			}
		}
		
		$output .= '>';
		
		echo $output;
	}
	
	private function echoStructure($structure) {
		if(empty($structure)) {
			return;
		}
		
		foreach ($structure as $key => $value) {
		
			if(isset($this->formarray[$key])) {
			
				if(!empty($value) && $value=='label') {
					$this->echoLabel($key);
				} else if($value=='nopost') {
					$this->echoElement($key, false);
				} else {
					$this->echoElement($key);
				}
			} else {
				$details = !empty($value['PizzaElementDetails']) ? $value['PizzaElementDetails'] : array();
				$this->echoStructureElement($key, $details);
				$this->echoStructure($value);
				echo "</$key>";
			}
		}
	}
	
	public function echoForm($leftclass = 'PizzaFormLabel', $rightclass = 'PizzaFormElement', $divortable = 'table') {
		$output = '';
	
		if(isset($this->formarray['PizzaFormStructure']) && is_array($this->formarray['PizzaFormStructure'])) {
			$this->echoStructure($this->formarray['PizzaFormStructure']);
		} else {
			$this->echoFormHead();
			
			foreach ($this->formarray as $key => $value) {
				if($divortable=='table') {
					$output .= "<tr><td class=\"$leftclass\">";
					$output .= $this->echoLabel($key);
					$output .= "</td><td class=\"$rightclass\">";
					$output .= $this->echoElement($key);
					$output .= "</td></tr>";
				} else {
					$output .= "<div class=\"$leftclass\">";
					$output .= $this->echoLabel($key);
					$output .= "</div><div class=\"$rightclass\">";
					$output .= $this->echoElement($key);
					$output .= "</div>";
				}
			}
			
			$output .= '</form>';
		}
		
		echo $output;
	}
	
	public function echoFormHead() {
		$output = '<form ';
		
		$details = $this->formarray['PizzaFormDetails'];
		
		$temp = !empty(strval($details['name'])) ? strval($details['name']) : '';
		
		$output .= " name=\"$temp\"";
		
		$temp = !empty(strval($details['id'])) ? strval($details['id']) : $temp;
		$output .= " id=\"$temp\"";
		
		foreach ($details as $key => $value) {
			if($key=='id' || $key=='name') {
				continue;
			}
			
			if(!empty(strval($value))) {
				$value = strval($value);
				$output .= " $key=\"$value\"";
			} else {
				$output .= " $key=\"$key\"";
			}
		}
		
		echo $output;
	}
	
	public function echoLabel($fieldname) {
		$label = isset($this->formarray[$fieldname]['label']) ? $this->formarray[$fieldname]['label'] : '';
		echo "<label for=\"$fieldname\">$label</label>";
	}
	
	public function echoElement($fieldname, $showenteredvalue = true) {
	
		$fieldname = strval($fieldname);
	
		if(isset($this->formarray[$fieldname])) {
			$formvalues = $this->formarray[$fieldname];
		} else {
			return false;
		}
		
		$output .= " name=\"$fieldname\"";
		
		$temp = !empty($formvalues['id']) ? strval($formvalues['id']) : $fieldname;
		$output .= " id=\"$temp\"";
	
		if($type=='select') {
			
		} else {
			$output = '<input' . $output;
			
			$temp = !empty(strval($formvalues['type'])) ? strval($formvalues['type']) : 'text';
			$output .= " type=\"$temp\"";
			
			$type = $temp;
			
			if($showenteredvalue==true && !empty(strval($formvalues['enteredvalue'])) && $type!='password') {
				$temp = strval($formvalues['enteredvalue']);
				$output .= " value=\"$temp\"";
			} else {
				$temp = !empty(strval($formvalues['defaultvalue'])) ? strval($formvalues['defaultvalue']) : '';
				$output .= " value=\"$temp\"";
			}
			
			foreach ($formvalues as $key => $value) {
				if($key=='id' || $key=='name' || $key=='type' || $key=='enteredvalue' || $key=='defaultvalue' || $key=='label') {
					continue;
				}
				
				if(!empty(strval($value))) {
					$value = strval($value);
					$output .= " $key=\"$value\"";
				} else {
					$output .= " $key=\"$key\"";
				}
			}
			
			$output .= ' />';
		}
		
		echo $output;
	}
	
	public function validate() {
		
		$validator = new Pizza\Validate();
	
		foreach ($this->formarray as $fieldname => $value) {
			
			foreach (array_keys($value['validation']) as $key) {
				// type, value, validation value
				$validator->autoValidate($key, $value['enteredvalue'], $value['validation'][$key]);
			}
		}
	}
	
	public function autoFillFormFromPost() {
		foreach ($this->formarray as $key => $value) {
			if($key!='PizzaFormDetails') {
			
				// strip out [] from end of fields
				str_replace('[]', '', $key);
				
				$value['enteredvalue'] = isset($_POST[$key]) ? $_POST[$key] : NULL;
			}
		}
	}
	
	public function checkFormSubmitted() {
		if ($this->formarray['PizzaSubmitCheck']['enteredvalue']=='1') {
			return true;
		}
		
		return false;
	}
	
	public function isValid() {
		return $this->validate();
	}
}
