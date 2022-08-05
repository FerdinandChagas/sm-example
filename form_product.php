<?php

defined('MOODLE_INTERNAL') || die('');
require_once($CFG->libdir."/formslib.php");

class mod_problem_product_form extends moodleform {
	function definition() {

        $attachmentoptions = $this->_customdata['attachmentoptions'];

		$this->_form->addElement('header', 'general', 'Gerenciador de arquivos');
		$this->_form->addElement('filemanager', 'attachment_filemanager', '', null, $attachmentoptions);

		$buttonarray=array();
		$buttonarray[] = &$this->_form->createElement('submit', 'submitbutton', get_string('savechanges'));
		$this->_form->addGroup($buttonarray, 'buttonar', '', array(' '), false);

	}
}