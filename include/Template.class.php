<?php
/**
 * Copyright 2012-2014 Stephen Just <stephenjust@gmail.com>
 *
 * This file is part of stkaddons
 *
 * stkaddons is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * stkaddons is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with stkaddons.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once('libs/Smarty.class.php');
require_once(INCLUDE_DIR . 'File.class.php');
require_once(INCLUDE_DIR . 'User.class.php');

/**
 * Create a template object 
 */
class Template {
    protected $smarty = NULL;
    
    private $file = NULL;
    private $directory = NULL;

    public function __construct($template_file, $template = NULL) {
        $this->createSmartyInstance();
        $this->setTemplateDir($template);
        $this->setTemplateFile($template_file);
    }
    
    /**
     * Setup function for children to override
     */
    protected function setup() {}
    
    /**
     * Set the template directory to use
     * @param string $template_name
     * @throws TemplateException
     */
    private function setTemplateDir($template_name) {
        if ($this->file !== NULL)
            throw new TemplateException('You cannot change the template after a template file is selected.');
        $dir = Template::getTemplateDir($template_name);
        $this->directory = $dir;
    }
    
    /**
     * Set the template file to use
     * @param string $file_name
     * @throws TemplateException
     */
    private function setTemplateFile($file_name) {
        if ($this->directory === NULL)
            throw new TemplateException('You cannot select a template file until you select a template.');
        if (!file_exists($this->directory . $file_name))
            throw new TemplateException(sprintf('Could not find template file "%s".', htmlspecialchars($file_name)));
        
        $this->file = $this->directory . $file_name;
    }

    /**
     * Create an instance of Smarty to use
     * @throws TemplateException
     */
    private function createSmartyInstance() {
        if ($this->smarty !== NULL)
            throw new TemplateException('Smarty was already configured.');
        $this->smarty = new Smarty;
        $this->smarty->compile_dir = TMP.'tpl_c/';
    }
    
    public function __toString() {
        $this->setup();
        ob_start();
        $this->smarty->display($this->file, $this->directory);
        return ob_get_clean();
    }

    public function assignments($assigns) {
        if (!is_array($assigns))
            throw new TemplateException('Invalid template assignments.');

        foreach ($assigns as $key => $value) {
            $this->smarty->assign($key, $value);
        }
    }
    
    public function assign($key, $value) {
        $this->smarty->assign($key, $value);
    }
    
    /**
     * Get the path to the template file directory, based on the template name
     * @param string $template
     * @return string
     * @throws TemplateException
     */
    public static function getTemplateDir($template) {
        if ($template === NULL)
            return ROOT.'tpl/default/';
        if (preg_match('/[a-z0-9\\-_]/i', $template))
            throw new TemplateException('Invalid character in template name.');
        $dir = ROOT.'tpl/'.$template.'/';
        if (file_exists($dir) && is_dir($dir))
            return $dir;
        throw new TemplateException(sprintf('The selected template "%s" does not exist.', htmlspecialchars($template)));
    }
}

class TemplateException extends Exception {
    
}

?>
