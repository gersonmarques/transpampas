<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( function_exists('acf_add_local_field') ):

acf_add_local_field(
	array(
		'key' => 'acfef_row_author',
		'label' => __( 'Row Author', 'acf-frontend-form-element' ),
		'name' => 'acfef_row_author',
		'type' => 'text',
	)
);	
acf_add_local_field(
		array(
			'key' => 'acfef_payments_active',
			'label' => __( 'Activate Payments', 'acf-frontend-form-element' ),
			'name' => 'acfef_payments_active',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'only_front' => 0,
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
        )
    );
acf_add_local_field(
		array(
			'key' => 'acfef_gateways',
			'label' => __( 'Payment Gateways', 'acf-frontend-form-element' ),
			'name' => 'acfef_gateways',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'acfef_payments_active',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'block',
			'sub_fields' => array(
				array(
					'key' => 'acfef_stripe_tab',
					'label' => __( 'Stripe', 'acf-frontend-form-element' ),
					'type' => 'tab',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'placement' => 'top',
					'endpoint' => 0,
				),
				array(
					'key' => 'acfef_stripe_active',
					'label' => __( 'Activate Stripe', 'acf-frontend-form-element' ),
					'name' => 'stripe_active',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 1,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'acfef_stripe',
					'name' => 'stripe',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'acfef_stripe_active',
								'operator' => '==',
								'value' => '1',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'acfef_stripe_live_mode',
							'label' => __( 'Use Live Keys', 'acf-frontend-form-element' ),
							'name' => 'live_mode',
							'type' => 'true_false',
							'instructions' => __( 'We reccomend testing out the test keys before using the live keys', 'acf-frontend-form-element' ),
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'only_front' => 0,
							'message' => '',
							'default_value' => 0,
							'ui' => 1,
							'ui_on_text' => '',
							'ui_off_text' => '',
						),
						array(
							'key' => 'acfef_stripe_live_publish_key',
							'label' => __( 'Live Publishable Key', 'acf-frontend-form-element' ),
							'name' => 'live_publishable_key',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array(
								array(
									array(
										'field' => 'acfef_stripe_live_mode',
										'operator' => '==',
										'value' => '1',
									),
								),
							),
							'wrapper' => array(
								'width' => '50.1',
								'class' => '',
								'id' => '',
							),
						),
						array(
							'key' => 'acfef_stripe_live_secret_key',
							'label' => __( 'Live Secret Key', 'acf-frontend-form-element' ),
							'name' => 'live_secret_key',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array(
								array(
									array(
										'field' => 'acfef_stripe_live_mode',
										'operator' => '==',
										'value' => '1',
									),
								),
							),
							'wrapper' => array(
								'width' => '50.1',
								'class' => '',
								'id' => '',
							),
						),
						array(
							'key' => 'acfef_stripe_test_publish_key',
							'label' => __( 'Test Publishable Key', 'acf-frontend-form-element' ),
							'name' => 'test_publishable_key',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array(
								array(
									array(
										'field' => 'acfef_stripe_live_mode',
										'operator' => '!=',
										'value' => '1',
									),
								),
							),
							'wrapper' => array(
								'width' => '50.1',
								'class' => '',
								'id' => '',
							),
						),
						array(
							'key' => 'acfef_stripe_test_secret_key',
							'label' => __( 'Test Secret Key', 'acf-frontend-form-element' ),
							'name' => 'test_secret_key_copy',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array(
								array(
									array(
										'field' => 'acfef_stripe_live_mode',
										'operator' => '!=',
										'value' => '1',
									),
								),
							),
							'wrapper' => array(
								'width' => '50.1',
								'class' => '',
								'id' => '',
							),
						),
					),
				),
				array(
					'key' => 'acfef_paypal_tab',
					'label' => __( 'Paypal', 'acf-frontend-form-element' ),
					'type' => 'tab',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'placement' => 'top',
					'endpoint' => 0,
				),
				array(
					'key' => 'acfef_paypal_active',
					'label' => __( 'Activate Paypal', 'acf-frontend-form-element' ),
					'name' => 'paypal_active',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 1,
					'ui_on_text' => '',
					'ui_off_text' => '',
					'custom_sold_ind' => 0,
				),
				array(
					'key' => 'acfef_paypal_message',
					'type' => 'message',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'acfef_paypal_active',
								'operator' => '==',
								'value' => '1',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => __( '<h2>Set Up</h2>

Click <a target="_blank" href="https://developer.paypal.com/developer/applications/create">here</a> to create a PayPal App. Once you do that you will recieve your "Client ID" .', 'acf-frontend-form-element' ),
					'new_lines' => 'wpautop',
					'esc_html' => 0,
				),
				array(
					'key' => 'acfef_paypal',
					'name' => 'paypal',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'acfef_paypal_active',
								'operator' => '==',
								'value' => '1',
							),
						),
					),
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'acfef_paypal_live_mode',
							'label' => __( 'Use Live Key', 'acf-frontend-form-element' ),
							'name' => 'live_mode',
							'type' => 'true_false',
							'instructions' => __( 'We reccomend testing out the test keys before using the live keys', 'acf-frontend-form-element' ),
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'ui' => 1,
						),
						array(
							'key' => 'acfef_paypal_live_client_id',
							'label' => __( 'Live Client ID', 'acf-frontend-form-element' ),
							'name' => 'live_client_id',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array(
								array(
									array(
										'field' => 'acfef_paypal_live_mode',
										'operator' => '==',
										'value' => '1',
									),
								),
							),
							'wrapper' => array(
								'width' => '50.1',
								'class' => '',
								'id' => '',
							),
						),
						array(
							'key' => 'acfef_paypal_test_client_id',
							'label' => __( 'Test Client ID', 'acf-frontend-form-element' ),
							'name' => 'test_client_id',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array(
								array(
									array(
										'field' => 'acfef_paypal_live_mode',
										'operator' => '!=',
										'value' => '1',
									),
								),
							),
							'wrapper' => array(
								'width' => '50.1',
								'class' => '',
								'id' => '',
							),
						),
					),
				),
			),
		)
	);

endif;

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_acfef_payments',
		'title' => __( 'Payments', 'acf-frontend-form-element' ),
		'acfef_group' => 1,
        'fields' => array(
            array(
                'key' => 'acfef_payment_external_id',
                'label' => __( 'External Id', 'acf-frontend-form-element' ),
                'name' => 'acfef_payment_external_id',
                'type' => 'text',
                'disabled' => '1',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'acfef_payment_acfef',
                'label' => __( 'User', 'acf-frontend-form-element' ),
                'name' => 'acfef_payment_user',
                'type' => 'user',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'role' => '',
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'id',
            ),
            array(
                'key' => 'acfef_payment_currency',
                'label' => __( 'Currency', 'acf-frontend-form-element' ),
                'name' => 'acfef_payment_currency',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '20',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => acfef_get_stripe_currencies(),
                'default_value' => 'USD',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 1,
                'ajax' => 1,
                'return_format' => 'value',
                'placeholder' => '',
            ),
            array(
                'key' => 'acfef_payment_amount',
                'label' => __( 'Amount', 'acf-frontend-form-element' ),
                'name' => 'acfef_payment_amount',
                'type' => 'number',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '20',
                    'class' => '',
                    'id' => '',
                ),
                'only_front' => 0,
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'min' => '',
                'max' => '',
                'step' => '',
            ),
            array(
                'key' => 'acfef_payment_method',
                'label' => __( 'Method', 'acf-frontend-form-element' ),
                'name' => 'acfef_payment_method',
                'type' => 'radio',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'only_front' => 0,
                'choices' => array(
                    'stripe' => 'Stripe',
                    'paypal' => 'PayPal',
                ),
                'allow_null' => 0,
                'other_choice' => 1,
                'save_other_choice' => 0,
                'default_value' => '',
                'layout' => 'horizontal',
                'return_format' => 'value',
            ),
        ),
		'location' => array(
			array(
				array(
					'param' => 'current_user',
					'operator' => '==',
					'value' => 'viewing_back',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'left',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => false,
		'description' => '',
	));
	

    
    endif;