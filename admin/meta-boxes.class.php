<?php
/* this class is used to create custom meta boxes for any post type */
class My_meta_box {

	protected $_meta_box;
	function __construct($meta_box) {	
		if (!is_admin()) return;
		$this->_meta_box = $meta_box;

		add_action('add_meta_boxes', array(&$this, 'add'));
		add_action('save_post', array(&$this, 'save'));				
	}	
	function add() {		
		foreach ($this->_meta_box['pages'] as $page) {		
			add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);			
		}
	}

	function show() {
	
		global $post;
		echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		echo '<table class="form-table">';
		
		foreach ($this->_meta_box['fields'] as $field) {
			if( $this->_meta_box['saveas'] == 'object' ){
				$tmp_meta = get_post_meta($post->ID, THEME_PREFIX . $this->_meta_box['id'], true);	                             
				$tmp_meta = unserialize($tmp_meta);		               
				$meta = stripslashes($tmp_meta[$field['id']]);
			}
			else{
				$meta = get_post_meta($post->ID, $field['id'], true);
			}
			
			if ( ! empty($field) AND is_array($meta) AND $field['type'] != 'message'){
			
				$tmp_meta = array();			
				foreach ($meta as $m)
				{
					$tmp_meta[$m] = $m;
				}
				$meta = $tmp_meta;	
			}
			if( $field['type'] == 'textdateinput' ){
				if( $meta ){
					$meta = date('d/m/Y', $meta);
				}
			}
			echo '<tr>'; 
			if( $field['name'] != '' ){
				if( $this->_meta_box['context'] != 'side' ){
					echo '<th style="width:20%; ', $field['style'], '"><label for="', $field['id'], '">', $field['name'], '</label></th>';
				}
				else{
					echo '<td style="', $field['style'], '"><label for="', $field['id'], '">', $field['name'], '</label><br />';
				}
			}
			else{ //means full width
				echo '<td colspan="2">';
			}
			if( $this->_meta_box['context'] != 'side' && $field['name'] != '' ){
				echo '<td style="width: 80%; padding-right: 0!important; ', $field['style'], '">';
			}			
			switch ($field['type']) {
				case 'text':
					echo '<input type="text" class="', $field['class'] , '" name="', $field['id'], '" id="', $field['id'], '" value="', $meta !='' ? $meta : $field['std'], '" size="30" style="width:100%" />',
						'<br />', $field['desc'];
					break;
				case 'textsmall':
					echo '<input type="text" class="', $field['class'] , '" name="', $field['id'], '" id="', $field['id'], '" value="', $meta!='' ? $meta  : $field['std'], '" size="30" style="width:50%" />',
						'<br />', $field['desc'];
					break;
				case 'textdateinput':
					echo '<input type="text" class="', $field['class'] , '" name="', $field['id'], '" id="', $field['id'], '" value="', $meta!='' ? $meta  : $field['std'], '" size="15" style="width:150px" />',
						'<br />', $field['desc'];
					break;			
				case 'textarea':
					echo '<textarea  class="', $field['class'] , '" name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:100%">', $meta ? $meta : $field['std'], '</textarea>',
						'<br />', $field['desc'];
					break;
				case 'textareasmall':
					echo '<textarea  class="', $field['class'] , '" name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="3" style="width:50%">', $meta ? $meta : $field['std'], '</textarea>',
						'<br />', $field['desc'];
					break;
				case 'select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					foreach ($field['options'] as $option) {
						echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
					}
					echo '</select>';
					break;
				case 'multiselect':
					echo '<select style="width:98%;max-height:200px" multiple="multiple" name="', $field['id'], '[]" id="', $field['id'], '">';
					foreach ($field['options'] as $key => $option) {
						echo '<option value="', $key , '"', $meta[$key] == $key ? ' selected="selected"' : '', '>', $option, '</option>';
					}
					echo '</select>';
					break;
				case 'selectbykey':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				
					foreach ($field['options'] as $key => $option) {
						echo '<option value="', $key , '"', $meta == $key ? ' selected="selected"' : '', '>', $option, '</option>';
					}
					echo '</select>';
					break;
				case 'radio':
					foreach ($field['options'] as $option) {
						echo '<input type="radio" style="margin-right: 10px;" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' /><span style="margin-right: 20px;">', $option['name'].'</span>';
					}
					break;
				case 'checkbox':
						echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '" value="', $field['value'], '"', $meta ? ' checked="checked"' : '', ' />', '<label for="', $field['id'], '" style="padding-left: 10px;">'.$field['desc'].'</label>';
					break;
				case 'file_attachment': 
					echo '<input class="meta_upload_file" name="' , $field['id'], '" id="', $field['id'], '" type="text" value="', $meta!='' ? $meta  : $field['std'], '" size="30" style="width:50%" />';
					echo '<input class="meta_upload_file_button button-secondary" type="button" value="Upload" />';
					break;
				case 'file':
					echo $meta ? "$meta<br />" : '', '<input type="file" name="', $field['id'], '" id="', $field['id'], '" />',
						'<br />', $field['desc'];
					break;
				case 'message': 
					echo $field['message'];
					break;
				case 'googlemap' : 
					echo '<div id="' , $field['id'] , '" style="', $field['style'] , '"></div>';
					
					break;
				case 'image':
					$img = wp_get_attachment_image_src($meta);
					echo $meta ? "<img style=\"float: left; margin: 10px 20px 10px 0; \" src=\"$img[0]\" width=\"48\" height=\"48\" /><br />" : '', '<input type="file" name="', $field['id'], '" id="', $field['id'], '" />',
						'<br />', $field['desc'];
					break;
				}
			echo 	'<td>',
				'</tr>';
			}

		echo '</table>';
		}

	function save($post_id) {
		global $wpdb;
		if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
			return $post_id;
			}
	
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
			}

		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
				}
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
			}
		
		
		if( $this->_meta_box['saveas'] == 'object' ){
			$new = array();
			foreach ($this->_meta_box['fields'] as $field) {
				if( $field['type'] == 'textdateinput' ){						
					$d = convert_to_sql_date_string($_POST[$field['id']], 'Y-m-d');			
					$new[$field['id']] = strtotime($d);					
				}
				else{
					$new[$field['id']] = $_POST[$field['id']];
				}
			}		
         
			update_post_meta($post_id, THEME_PREFIX . $this->_meta_box['id'], addslashes(serialize($new)));			
		}
		else{
			foreach ($this->_meta_box['fields'] as $field) {
	
				$name = $field['id'];			
				$old = get_post_meta($post_id, $name, true);
				$new = $_POST[$field['id']];
				
				if( $name == THEME_PREFIX . 'event_ticket_price' && $new == '' ){
					$new = "0";	
				}
				
				if( $field['type'] == 'textdateinput' ){						
					$new = convert_to_sql_date_string($new, 'Y-m-d');			
					$new = strtotime($new);					
				}
				
				if ($field['type'] == 'file' || $field['type'] == 'image') {
					$_FILES['image'] = $_FILES[$field['id']];
					require_once('image-upload.php');
					upload_image(get_post($post_id)->post_parent, $name, get_post($post_id)->post_title);
				//	$file = wp_handle_upload($_FILES[$name], array('test_form' => false));
				//	$new = $file['url'];
				}
                if( $name == 'd_offer_start_date' || $name == 'd_offer_end_date'){
                    update_post_meta($post_id, $name, $new);
                }
                else{
		
                    if ( $new != '' && $new != $old && $field['type'] != 'file' && $field['type'] != 'image') {

                        update_post_meta($post_id, $name, $new);
                    } elseif ('' == $new && $old && $field['type'] != 'file' && $field['type'] != 'image') {

                        delete_post_meta($post_id, $name, $old);
                        }
                    }
                }
                    
			}
		}
	}
