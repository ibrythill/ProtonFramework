<?php
if ( ! function_exists( 'proton_TinyMCE' ) ) :

function proton_TinyMCE( $in ) {
	$in['remove_linebreaks'] = false;
	$in['gecko_spellcheck'] = false;
	$in['keep_styles'] = true;
	$in['accessibility_focus'] = true;
	$in['tabfocus_elements'] = 'major-publishing-actions';
	$in['media_strict'] = false;

	$in['paste_remove_styles'] = false;
	$in['paste_remove_spans'] = false;
	$in['paste_strip_class_attributes'] = 'none';
	$in['paste_text_use_dialog'] = true;

	//clean ms word
	$in['paste_auto_cleanup_on_paste'] = true;
	$in['paste_convert_headers_to_strong'] = true;

	$in['wpeditimage_disable_captions'] = true;
	$in['spellchecker_languages'] = '+Polish=pl, English=en';
	$in['plugins'] = 'tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs';
	//$in['content_css'] = get_template_directory_uri() . "/editor-style.css";
	$in['wpautop'] = true;
	$in['apply_source_formatting'] = false;
        $in['block_formats'] = "Paragraph=p; Heading 3=h3; Heading 4=h4";
	$in['toolbar1'] = 'formatselect, styleselect,forecolor,backcolor,|,bold,italic,underline,strikethrough,|, sub, sup,|,bullist,numlist,blockquote,|,alignleft,aligncenter,alignright,alignjustify,|,link,unlink,|,wp_fullscreen,wp_more,|,wp_adv';
	$in['toolbar2'] = 'fontselect,fontsizeselect,spellchecker,|,copy,paste,selectall,|,pastetext,pasteword,removeformat,|,code,charmap,|, anchor, hr,|,outdent,indent,|,visualaid,media,charmap,|,undo,redo,';
	$in['toolbar3'] = '';
	$in['toolbar4'] = '';

	$in['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
	$in['font_formats'] = 'Lato=Lato;Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;';

	// Create array of new styles
	$new_styles = array(
		array(
			'title'	=> __( 'Custom Styles', PROTON_SLUG ),
			'items'	=> array(
				array(
					'title'		=> __('Theme Button','proton_framework'),
					'selector'	=> 'a',
					'classes'	=> 'theme-button'
				),
				array(
					'title'		=> __('Highlight','proton_framework'),
					'inline'	=> 'span',
					'classes'	=> 'text-highlight',
				),
			),
		),
	);

	// Merge old & new styles
	$in['style_formats_merge'] = true;

	// Add new styles
	$in['style_formats'] = json_encode( $new_styles );
	return $in;
}
add_filter( 'tiny_mce_before_init', 'proton_TinyMCE' );

endif;





?>