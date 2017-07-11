<?php

    if (!defined('BASEPATH')) {
        exit('No direct script access allowed');
    }
    class Blacklist
    {
        /**
         * 定义需要过滤的词语
         *
         * @access  protected
         * @var     array
         */
        protected $_words = array();

        /**
         *
         * @access protected
         * @var     bool
         */
        protected $_blocked = FALSE;
        
        /*
         * 存放匹配中的黑名单词语
         */
        public $_match_words = "";
        /**
         *
         * @access public
         * @var     array
         */
        public $target_text = array();

        /**
         * Construct
         *
         * @access public
         * @param   array  $config
         * @var     array
         */
        public function __construct($config)
        {
            if (!empty($config))
            {
                foreach ($config as $key => $val)
                {
                    if (isset($this->{'_' . $key}))
                    {
                        $val = ! empty($val) ? is_array($val) ? $val : array($val) : array();
                        $this->{'_' . $key} = $val;
                    }
                }
            }
        }

        // --------------------------------------------------------------------

        /**
         *
         * @access public
         * @return bool
         */
        public function is_blocked()
        {
            return $this->_blocked;
        }

        // --------------------------------------------------------------------

        /**
         * 添加新的词语到黑名单中
         *
         * @access  public
         * @param   mixed  array|string
         * @return  object $this
         */
        public function add_word($words)
        {
            $words = ! is_array($words) ? array($words) : $words;

            $this->_words = array_merge($this->_words, $words);

            return $this;
        }

        // --------------------------------------------------------------------

        /**
         * 检查是否包含禁用的词语
         *
         * @access public
         * @param  string  检查$texts
         * @return object  $this
         */
        public function check_text($texts)
        {
            $this->_set_target_text($texts);
            foreach ($this->_words as $word)
            {
                if (stripos($this->target_text, $word) !== false)
                {
                    $this->_blocked = TRUE;
                    $this->_match_words = $word;    //存放匹配中的黑名单词语
                    log_message('debug', "发现禁用的关键词: '$word' 存在文本中！.");
                }
            }

            return $this;
        }

        // --------------------------------------------------------------------

        /**
         * 替换禁用的词语
         *
         * @access  public
         * @param   mixed  array|string 提供的一段需要过滤的字符串或数组
         * @param   string 提供新的字符串替换过滤的词语，默认为*
         * @return  mixed  array|string
         */
        public function replace($text, $fill = '*')
        {
            // 如果$text是数组
            if (is_array($text) && !empty($text))
            {
                $result = array();

                foreach ($text as $t)
                {
                    $result[] = $this->replace($t, $fill);
                }

                return $result;
            }
            // 如果$text是字符串
            else
            {
                foreach ($this->_words as $word)
                {
                    if (stripos($text, $word) !== false)
                    {
                        $replacement = implode('', array_fill(0, iconv_strlen($word, 'UTF-8'), $fill));
                        $result = str_ireplace($word, $replacement, $text);
                        $text = $result;
                    }
                }
                return $result;
            }

            return;
        }

        // --------------------------------------------------------------------

        /**
         * Set the target text block
         *
         * @access  private
         * @param   mixed  array|string
         * @return  object $this
         */
        private function _set_target_text($texts)
        {
            $texts = ! is_array($texts) ? array($texts) : $texts;

            $this->target_text = implode("\n", $texts);
        }
    }

/* End of file Blacklist.php */