<?php namespace Siakad\Services;
	/* http://stackoverflow.com/a/28425173 */
	class MyValidator extends \Illuminate\Validation\Validator{
		
		protected function validateGreaterThan($attribute, $value, $parameters, $validator) {
			$min_field = $parameters[0];
			$data = $validator->getData();
			$min_value = $data[$min_field];
			return $value > $min_value;
		}
		
		/**
			* Validate that an attribute contains only alphabetic characters, spaces, dot, apostrophe.
			*
			* @param  string  $attribute
			* @param  mixed   $value
			* @return bool
			*
			* http://blog.elenakolevska.com/laravel-alpha-validator-that-allows-spaces/ 
		*/
		protected function validateValidNameNumber($attribute, $value)
		{
			return preg_match('/^[\pL\s\.\'\pN]+$/u', $value);
		}
		protected function validateValidName($attribute, $value)
		{
			return preg_match('/^[\pL\s\.\'\-]+$/u', $value);
		}
		
	}		