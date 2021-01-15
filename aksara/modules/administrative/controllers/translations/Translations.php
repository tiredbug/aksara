<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Administrative > Translations
 *
 * @version			2.1.1
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Translations extends Aksara
{
	private $_table									= 'app__languages';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->restrict_on_demo();
		
		$this->set_permission(1);
		$this->set_theme('backend');
		
		$this->unset_update('id', array(1));
		$this->unset_delete('id', array(1));
	}
	
	public function index()
	{
		$this->set_title(phrase('translations'))
		->set_icon('mdi mdi-translate')
		->set_description
		('
			<div class="alert-info pr-3 pl-3" style="margin-left:-15px; margin-right:-15px">
				' . phrase('click_the_synchronize_button_to_equate_the_phrases_for_each_translations') . '
			</div>
		')
		->unset_column('id')
		->unset_field('id')
		->unset_view('id')
		->set_field
		(
			array
			(
				'description'						=> 'textarea',
				'status'							=> 'boolean'
			)
		)
		->add_action('toolbar', 'synchronize', phrase('synchronize'), 'btn btn-info --xhr show-progress', 'mdi mdi-reload')
		->add_action('option', 'translate', phrase('translate'), 'btn btn-success --xhr', 'mdi mdi-comment-processing-outline', array('id' => 'id', 'code' => 'code', 'keyword' => null))
		->set_validation
		(
			array
			(
				'language'							=> 'required|xss_clean|max_length[32]',
				'description'						=> 'required|xss_clean',
				'code'								=> 'required|alpha_dash|max_length[32]|is_unique[app__languages.code.id.' . $this->input->get('id') . ']',
				'locale'							=> 'required|xss_clean|max_length[64]',
				'status'							=> 'is_boolean'
			)
		)
		->set_alias
		(
			array
			(
				'language'							=> phrase('language'),
				'description'						=> phrase('description'),
				'code'								=> phrase('code'),
				'locale'							=> phrase('locale'),
				'status'							=> phrase('status')
			)
		)
		->render($this->_table);
	}
	
	public function after_insert()
	{
		/* try to add language file */
		try
		{
			/* check if language directory is exists */
			if(!is_dir(TRANSLATION_PATH) && mkdir(TRANSLATION_PATH, 0755, true))
			{
				/* put content into file */
				file_put_contents(TRANSLATION_PATH . DIRECTORY_SEPARATOR . $this->input->post('code') . '.json', json_encode(array()));
			}
			else
			{
				/* put content into file */
				file_put_contents(TRANSLATION_PATH . DIRECTORY_SEPARATOR . $this->input->post('code') . '.json', json_encode(array()));
			}
		}
		catch(Exception $e)
		{
			/* failed to write file */
		}
	}
	
	public function after_update()
	{
		/* try to update language file */
		try
		{
			/* check if language directory is exists */
			if(file_exists(TRANSLATION_PATH . DIRECTORY_SEPARATOR . $this->input->get('code') . '.json'))
			{
				/* rename old file */
				rename(TRANSLATION_PATH . DIRECTORY_SEPARATOR . $this->input->get('code') . '.json', TRANSLATION_PATH . DIRECTORY_SEPARATOR . $this->input->post('code') . '.json');
			}
		}
		catch(Exception $e)
		{
			/* failed to write file */
		}
	}
}
