<?php
    class T {
        private $blockregex = '/\\{\\{(([@!]?)(.+?))\\}\\}(([\\s\\S]+?)(\\{\\{:\\1\\}\\}([\\s\\S]+?))?)\\{\\{\\/\\1\\}\\}/';
        private $valregex = '/\\{\\{([=%])(.+?)\\}\\}/';

        private $vars = false;
        private $key = false;
        private $t = '';
        private $result = '';

        public function __construct($template){
            $this->t = $template;
        }

        public function scrub($val){
            //useful to parse messages, emoji etc
            return htmlspecialchars($val.'', ENT_QUOTES);
        }

        public function get_value($index) {            
            $index = explode('.', $index);

            return $this->search_value($index, $this->vars);
        }

        private function search_value($index, $value) {
            if(is_array($index) and
               count($index)) {
                $current_index = array_shift($index);
            }
            if(is_array($index) and
               count($index) and
               is_array($value[$current_index]) and
               count($value[$current_index])) {
                return $this->search_value($index, $value[$current_index]);
            } else {
                $val = isset($value[$current_index])?$value[$current_index]:'';
                return str_replace('{{', "{\f{", $val);
            }
        }

        public function firstCallback($matches) {
            $_ = $matches[0];
            $__ = $matches[1];
            $meta = $matches[2];
            $key = $matches[3];
            $inner = $matches[4];
            $if_true = $matches[5];
            $has_else = $matches[6];
            $if_false = $matches[7];

            $val = $this->get_value($key);

            $temp = "";
            $i;

            if (!$val) {
                // handle if not
                if ($meta == '!') {
                    return $this->render($inner);
                }
                // check for else
                if ($has_else) {
                    return $this->render($if_false);
                }

                return "";
            }

            // regular if
            if (!$meta) {
                return $this->render($if_true);
            }

            // process array/obj iteration
            if ($meta == '@') {
                // store any previous vars
                // reuse existing vars
                $_ = $this->vars['_key'];
                $__ = $this->vars['_val'];
                
                foreach ($val as $i => $v) {
                    $this->vars['_key'] = $i;
                    $this->vars['_val'] = $v;

                    $temp .= $this->render($inner);
                }

                $this->vars['_key'] = $_;
                $this->vars['_val'] = $__;
                
                return $temp;
            }
        }

        public function secondCallback($matches) {
            $_ = $matches[0];
            $meta = $matches[1];
            $key = $matches[2];

            $val = $this->get_value($key);

            if ($val || $val === 0) {
                return $meta == '%' ? $this->scrub($val) : $val;
            }

            return "";
        }

        private function render($fragment) {
            $firstCallback = preg_replace_callback($this->blockregex, array($this, "firstCallback"), $fragment);
            $secondCallback = preg_replace_callback($this->valregex, array($this, "secondCallback"), $firstCallback);

            return $secondCallback;
        }

        public function parse($obj){
            $this->vars = $obj;

            return $this->render($this->t);
        }
    }
?>
