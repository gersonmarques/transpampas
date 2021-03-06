<?php
namespace ACFFrontendForm\Module\Widgets;

use ACFFrontendForm\Module\Widgets\ACF_Elementor_Form_Base;


	
/**
 * Elementor ACF Frontend Form Widget.
 *
 * Elementor widget that inserts an ACF frontend form into the page.
 *
 * @since 1.0.0
 */
class Edit_User_Widget extends ACF_Elementor_Form_Widget {
	
	/**
	 * Get widget name.
	 *
	 * Retrieve acf ele form widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'edit_user';
	}

	/**
	* Get widget action.
	*
	* Retrieve acf ele form widget action.
	*
	* @since 1.0.0
	* @access public
	*
	* @return string Widget action.
	*/
	public function get_form_defaults() {
		return [ 
				'main_action' => 'edit_user',
				'form_title' => __( 'Edit Profile', 'acf-frontend-form-element' ),
				'submit' => __( 'Update', 'acf-frontend-form-element' ),
				'success_message' => __( 'Your profile has been updated successfully.', 'acf-frontend-form-element' ),
				'field_type' => 'username',
				'fields' => [
					[
						'field_type' => 'username',
						'field_label_on' => 'true',
						'field_required' => 'true',
					],						
					[
						'field_type' => 'password',
						'field_label_on' => 'true',
						'field_required' => 'true',
					],						
					[
						'field_type' => 'email',
						'field_label_on' => 'true',
						'field_required' => 'true',
					],		
				],
			];
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve acf ele form widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Edit User Form', 'acf-frontend-form-element' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve acf ele form widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-user-edit frontend-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the acf ele form widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'acfef-forms' ];
	}

}
