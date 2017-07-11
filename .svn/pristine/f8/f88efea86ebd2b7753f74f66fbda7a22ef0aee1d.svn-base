<?php

/**
 * # 存在性校验
 * required						# 字段值为必填
 * required_without:foo,bar,...	# 字段值 仅在 任一指定字段没有值情况下为必填
 *
 * # 功能性校验
 * accepted 					# 字段值为 yes, on, 或是 1 时，验证才会通过。这在确认"服务条款"是否同意时很有用
 * active_url					# 字段值通过 PHP 函数 checkdnsrr 来验证是否为一个有效的网址
 * alpha						# 字段仅全数为字母字串时通过验证
 * alpha_dash					# 字段值仅允许字母、数字、破折号（-）以及底线（_）
 * alpha_num					# 字段值仅允许字母、数字
 * array						# 字段值仅允许为数组
 * between:min,max				# 字段值需介于指定的 min 和 max 值之间。字串、数值或是文件则是判断 size 大小来进行验证。。注：规则中只有包括了 Numeric 或 Integer 才会视为数字，否则视为字串
 * boolean						# 需要验证的字段必须可以转换为 boolean 类型的值。可接受的输入是true、false、1、0、"1" 和 "0"
 * date							# 字段值是否为一个合法的日期
 * digits_between:min,max		# 字段值需为数字，且长度需介于 min 与 max 之间
 * in:foo,bar,...				# 字段值需符合事先给予的清单的其中一个值
 * integer						# 字段值需为一个整型值
 * max:value					# 字段值需小于等于 value。字串、数组和文件则是判断 size 大小。注：规则中只有包括了 Numeric 或 Integer 才会视为数字，否则视为字串
 * min:value					# 字段值需大于等于 value。字串、数组和文件则是判断 size 大小。注：规则中只有包括了 Numeric 或 Integer 才会视为数字，否则视为字串
 * not_in:foo,bar,...			# 字段值不得为给定清单中其一
 * numeric						# 字段值需为数字
 */

class Validator
{
	private $err_msg;
	private $err_lang_msg;// 多语言消息

	private function set_err_msg($rule, $field, $params)
	{
		$this->set_lang_err_msg($rule, $field, $params);
		switch ($rule)
		{
			case "accepted":
				$this->err_msg = "The {$field} must be accepted.";
				break;

			case "active_url":
				$this->err_msg = "The {$field} is not a valid URL.";
				break;

			case "alpha":
				$this->err_msg = "The {$field} may only contain letters.";
				break;

			case "alpha_dash":
				$this->err_msg = "The {$field} may only contain letters, numbers, and dashes.";
				break;

			case "alpha_num":
				$this->err_msg = "The {$field} may only contain letters and numbers.";
				break;

			case "array":
				$this->err_msg = "The {$field} must be an array.";
				break;

			case "between":
				$min = isset($params[0]) ? $params[0] : "min";
				$max = isset($params[1]) ? $params[1] : "max";
				$this->err_msg = "The {$field} must be between {$min} and {$max}.";
				break;

			case "boolean":
				$this->err_msg = "The {$field} must be a boolean.";
				break;

			case "date":
				$this->err_msg = "The {$field} is not a valid date.";
				break;

			case "digits_between":
				$min = isset($params[0]) ? $params[0] : "min";
				$max = isset($params[1]) ? $params[1] : "max";
				$this->err_msg = "The {$field} must be between {$min} and {$max} digits.";
				break;

			case "in":
				$this->err_msg = "The selected {$field} is invalid.";
				break;

			case "integer":
				$this->err_msg = "The {$field} must be an integer.";
				break;

			case "max":
				$max = isset($params[0]) ? $params[0] : "min";
				$this->err_msg = "The {$field} may not be greater than {$max}.";
				break;

			case "min":
				$min = isset($params[0]) ? $params[0] : "min";
				$this->err_msg = "The {$field} must be at least {$min}.";
				break;

			case "not_in":
				$this->err_msg = "The selected {$field} is invalid.";
				break;

			case "numeric":
				$this->err_msg = "The {$field} must be a number.";
				break;

			case "required":
				$this->err_msg = "The {$field} field is required.";
				break;

			case "required_without":
				$attr = isset($params) ? implode(", ", $params) : "attr";
				$this->err_msg = "The {$field} field is required when {$attr} is not present.";
				break;
			case "email":
				$this->err_msg = "The {$field} field must be a E-mail.";
				break;
			# 未知规则
			default:
				$this->err_msg = "unknown rule: {$rule}";
				return FALSE;
		}
		return TRUE;
	}

	/** 多语言消息  by john 2016-1-1 */
	private function set_lang_err_msg($rule, $field, $params)
	{
		switch ($rule)
		{
			case "accepted":
			case "active_url":
			case "alpha":
			case "alpha_dash":
			case "alpha_num":
			case "array":
			case "boolean":
			case "date":
			case "in":
			case "integer":
			case "not_in":
			case "numeric":
			case "required":
			case "email":
				$this->err_lang_msg = sprintf(lang('validate_'.$rule),lang('pc_'.$field));
				break;
			case "between":
			case "digits_between":
				$min = isset($params[0]) ? $params[0] : "min";
				$max = isset($params[1]) ? $params[1] : "max";
				$this->err_lang_msg = sprintf(lang('validate_'.$rule),lang('pc_'.$field),$min,$max);
				break;
			case "max":
			case "min":
				$min = isset($params[0]) ? $params[0] : "min";
				$this->err_lang_msg = sprintf(lang('validate_'.$rule),lang('pc_'.$field),$min);
				break;
			case "required_without":
				$attr = isset($params) ? implode(", ", $params) : "attr";
				$this->err_lang_msg = sprintf(lang('validate_'.$rule),lang('pc_'.$field),$attr);
				break;
			default:
				# 未知规则
				$this->err_msg = "unknown rule: {$rule}";
				return FALSE;
		}
		return TRUE;
	}

	public function get_err_msg()
	{
		return $this->err_msg;
	}
	public function get_err_lang_msg()
	{
		return $this->err_lang_msg;
	}

	/**
	 * 根据 $rules 规则校验数据 $attr
	 * @param array $attr
	 * @param array $rules
	 * $rules = array(
	 * 	   array('field' => "index1:rule1|index2:rule2"),
	 * );
     * @return bool
	 */
	public function validate($attr, $rules)
	{
		# 输入参数必须为数组
		if (!is_array($attr) || !is_array($rules))
		{
			$this->err_msg = "校验输入参数必须为数组";
			return FALSE;
		}

		# 将空输入转化为 null
		array_walk($attr, function(&$val)
		{
			if ($val == "")
			{
				$val = null;
			}
		});

		$required_arr = array(
			'required',
			'required_without',
		);

		$functionality_arr = array(
			'accepted',
			'active_url',
			'alpha',
			'alpha_dash',
			'alpha_num',
			'array',
			'between',
			'boolean',
			'date',
			'digits_between',
			'in',
			'integer',
			'max',
			'min',
			'not_in',
			'numeric',
			'email'
		);

		foreach ($rules as $field => $rule_str)
		{
			$rule_required = array();
			$rule_functionality = array();

			// 将校验规则划分为存在性和功能性两种
			$rule_arr = str_getcsv($rule_str, "|");
			foreach ($rule_arr as $v)
			{
				$arr = explode(":", $v);
				$arr[1] = isset($arr[1]) ? explode(",", $arr[1]) : array();
				list($rule, $params) = $arr;

				if (in_array($rule, $required_arr))
				{
					$rule_required[$rule] = $params;
				}
				else if (in_array($rule, $functionality_arr))
				{
					$rule_functionality[$rule] = $params;
				}
				else
				{
					// 未定义的规则直接报错返回
					$this->err_msg = "unknow rule: {$rule}";
					return FALSE;
				}
			}

			// 存在性校验
			if (FALSE === $this->check_required($attr, $field, $rule_required))
			{
				return FALSE;
			}

			// 如果通过了存在性校验而字段值为空，则证明字段允许为空，无需做功能性校验
			if (!isset($attr[$field]))
			{
				unset($rule_required, $rule_functionality);
				continue;
			}

			// 功能性校验
			if (FALSE === $this->check_functionality($attr, $field, $rule_functionality))
			{
				return FALSE;
			}

			unset($rule_required, $rule_functionality);
		}

		return TRUE;
	}

	private function check_required($attr, $field, $rules)
	{
		foreach ($rules as $rule => $params)
		{
			$func = "validate_{$rule}";
			$value = isset($attr[$field]) ? $attr[$field] : null;
			if (FALSE === $this->$func($value, $params, $attr))
			{
				$this->set_err_msg($rule, $field, $params);
				return FALSE;
			}
		}
		return TRUE;
	}

	private function check_functionality($attr, $field, $rules)
	{
		foreach ($rules as $rule => $params)
		{
			$func = "validate_{$rule}";
			if (FALSE === $this->$func($attr[$field], $params, $rules))
			{
				$this->set_err_msg($rule, $field, $params);
				return FALSE;
			}
		}
		return TRUE;
	}

	private function get_size($value, $rules)
	{
		// 如果规则中包括 Numeric 或 Integer 则字段视为数字
		if (array_key_exists('numeric', $rules) || array_key_exists('integer', $rules))
		{
			$size = $value;
		}
		// 数组
		else if (is_array($value))
		{
			$size = count($value);
		}
		// 文件
		else if ($value instanceof FILE)
		{
			$size = $value->getSize() / 1024;
		}
		// 字符串
		else
		{
			$size = mb_strlen($value);//strlen
		}
		return $size;
	}

	private function validate_accepted($value, $params, $rules)
	{
		return in_array($value, array('yes', 'on', '1', 1, true, 'true'), true);
	}

	private function validate_active_url($value, $params, $rules)
	{
		return checkdnsrr(str_replace(array('http://', 'https://', 'ftp://'), '', strtolower($value)));
	}

	private function validate_alpha($value, $params, $rules)
	{
		return preg_match('/^[\pL\pM]+$/u', $value);
	}

	private function validate_alpha_dash($value, $params, $rules)
	{
		return preg_match('/^[\pL\pM\pN_-]+$/u', $value);
	}

	private function validate_alpha_num($value, $params, $rules)
	{
		return preg_match('/^[\pL\pM\pN]+$/u', $value);
	}

	private function validate_array($value, $params, $rules)
	{
		return is_array($value);
	}

	private function validate_between($value, $params, $rules)
	{
		if (count($params) != 2)
		{
			return FALSE;
		}
		list($min, $max) = $params;
		if ($min >= $max)
		{
			return FALSE;
		}

		$size = $this->get_size($value, $rules);
		return $size >= $min && $size <= $max;
	}

	private function validate_boolean($value, $params, $rules)
	{
		return in_array($value, array(true, false, 0, 1, '0', '1'), true);
	}

	private function validate_date($value, $params, $rules)
	{
		if ($value instanceof DateTime)
		{
			return TRUE;
		}

		if (strtotime($value) === FALSE)
		{
			return FALSE;
		}

		$date = date_parse($value);

		return checkdate($date['month'], $date['day'], $date['year']);
	}

	private function validate_digits_between($value, $params, $rules)
	{
		if (count($params) != 2)
		{
			return FALSE;
		}
		list($min, $max) = $params;
		if ($min >= $max)
		{
			return FALSE;
		}

		$length = strlen((string)$value);

		return $length >= $min && $length <= $max;
	}

	private function validate_in($value, $params, $rules)
	{
		return in_array((string)$value, $params);
	}

	private function validate_integer($value, $params, $rules)
	{
		return filter_var($value, FILTER_VALIDATE_INT) !== FALSE;
	}

	private function validate_max($value, $params, $rules)
	{
		if (count($params) != 1)
		{
			return FALSE;
		}

		return $this->get_size($value, $rules) <= $params[0];
	}

	private function validate_min($value, $params, $rules)
	{
		if (count($params) != 1)
		{
			return FALSE;
		}

		return $this->get_size($value, $rules) >= $params[0];
	}

	private function validate_not_in($value, $params, $rules)
	{
		return !in_array((string)$value, $params);
	}

	private function validate_numeric($value, $params, $rules)
	{
		return is_numeric($value);
	}

	/** 邮箱验证 by john 2016-1-1 */
	private function validate_email($value, $params, $rules)
	{
		$result = preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/", $value);
		return $result ? TRUE : FALSE;
	}

	private function validate_required($value, $params, $attr)
	{
		if (is_null($value))
		{
			return FALSE;
		}
		else if (is_string($value) && trim($value) === '')
		{
			return FALSE;
		}
		else if (is_array($value) && count($value) < 1)
		{
			return FALSE;
		}
		else if ($value instanceof File)
		{
			return (string)$value->getPath() != '';
		}

		return TRUE;
	}

	private function validate_required_without($value, $params, $attr)
	{
		if (!$this->validate_required($value, array(), array()))
		{
			foreach ($params as $key)
			{
				if (!$this->validate_required($attr[$key], array(), array()))
				{
					return FALSE;
				}
			}
		}
		return TRUE;
	}
}
