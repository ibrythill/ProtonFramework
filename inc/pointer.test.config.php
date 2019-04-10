<?php
	$pntrs[] = array(
		'id' => 'id2',   // unique id for this pointer
		'screen' => 'post', // this is the page hook we want our pointer to show on
		'target' => '.pr-tab-general', // the css selector for the pointer to be tied to, best to use ID's
		'title' => __( 'Proton Custom Settings', PROTON_SLUG ),
		'order' => '1',
		'tour' => 'proton',
		'content' => 'Dodatkowe META. Możliwość ustawiania zakładek z dodatkowymi zbiorami opcji.',
		'position' => array(
			'edge' => 'bottom', //top, bottom, left, right
			'align' => 'left' //top, bottom, left, right, middle
		),
		'button2'  => __( 'Next', PROTON_SLUG ),
		'button2function' => '$(".protonframework-tabs" ).tabs( "option", "active", 2 ); '
	);
	$pntrs[] = array(
		'id' => 'id3',   // unique id for this pointer
		'screen' => 'post', // this is the page hook we want our pointer to show on
		'target' => '.pr-tab-seo', // the css selector for the pointer to be tied to, best to use ID's
		'title' => 'Moduł SEO',
		'order' => '2',
		'tour' => 'proton',
		'content' => 'Jak widać tutaj. Zakladka dodana przez modul SEO.',
		'position' => array(
			'edge' => 'bottom', //top, bottom, left, right
			'align' => 'left' //top, bottom, left, right, middle
		),
		'button2'  => __( 'Next', PROTON_SLUG ),
		'location' => admin_url( 'post.php?post=189&action=edit' )
	);
	$pntrs[] = array(
	                        'id' => 'calid1',   // unique id for this pointer
	                        'screen' => 'harmonogram', // this is the page hook we want our pointer to show on
	                        'target' => '.pr-tab-calendar', // the css selector for the pointer to be tied to, best to use ID's
	                        'title' => 'Ustawienia Harmonogramu',
		'order' => '2',
		'tour' => 'proton',
	                        'content' => '<p>A tutaj zakładka dodana przez modul harmonogramu. Tutaj zmienić ustawienia dotyczące wydarzenia.<p><p>Ustawisz tutaj m.in. daty oraz godziny  rozpoczęcia i zakończenia wydarzenia.<p>',
	                        'position' => array( 
	                                           'edge' => 'bottom', //top, bottom, left, right
	                                           'align' => 'left' //top, bottom, left, right, middle
	                                           ),
		'button2'  => __( 'Next', PROTON_SLUG ),
		'location' => admin_url( 'post.php?post=4&action=edit' )
	                        );
	$pntrs[] = array(
		'id' => 'id7',   // unique id for this pointer
		'screen' => 'post', // this is the page hook we want our pointer to show on
		'target' => '.pr-tab-galeria', // the css selector for the pointer to be tied to, best to use ID's
		'title' => 'Galeria i obrazki',
		'order' => '4',
		'tour' => 'proton',
		'content' => 'Tutaj sa pola do zabawy z obrazkami.',
		'position' => array(
			'edge' => 'bottom', //top, bottom, left, right
			'align' => 'left' //top, bottom, left, right, middle
		),
		'button2'  => __( 'Next', PROTON_SLUG ),
		'button2function' => '$(".protonframework-tabs" ).tabs( "option", "active", 1 ); proton_pointer_open(1); '
	);
	$pntrs[] = array(
		'id' => 'id4',   // unique id for this pointer
		'screen' => 'post', // this is the page hook we want our pointer to show on
		'target' => '#protonthemes_galeria', // the css selector for the pointer to be tied to, best to use ID's
		'title' => 'Galeria i obrazki',
		'order' => '5',
		'tour' => 'proton',
		'content' => 'Tutaj można pobawić sie w tworzenie galeri. Dodawać, usuwać i zmieniać kolejność',
		'position' => array(
			'edge' => 'bottom', //top, bottom, left, right
			'align' => 'left' //top, bottom, left, right, middle
		)
	);
	$pntrs[] = array(
		'id' => 'id1',   // unique id for this pointer
		'screen' => 'dashboard', // this is the page hook we want our pointer to show on

		'target' => '#toplevel_page_proton-framework', // the css selector for the pointer to be tied to, best to use ID's
		'title' => 'Panel templatki',
		'order' => '1',
		'tour' => 'proton',
		'content' => 'Tutaj znajduje sie panel templatki. Bazuje na gotowym opensourcowym module ale chcialbym cos wlasnego ;)',
		'position' => array(
			'edge' => 'left', //top, bottom, left, right
			'align' => 'top' //top, bottom, left, right, middle
		),
		'button2'  => __( 'Next', PROTON_SLUG ),
		'location' => admin_url( 'admin.php?page=proton-framework' )

	);
	
	$pntrs[] = array(
		'id' => 'id5',   // unique id for this pointer
		'screen' => 'toplevel_page_proton-framework', // this is the page hook we want our pointer to show on

		'target' => '#0_section_group_li', // the css selector for the pointer to be tied to, best to use ID's
		'title' => 'Zakładki z opcjami',
		'order' => '1',
		'tour' => 'proton',
		'content' => 'Można przełączać się pomiędzy różnymi zakładkami',
		'position' => array(
			'edge' => 'left', //top, bottom, left, right
			'align' => 'top' //top, bottom, left, right, middle
		),
		'button2'  => __( 'Next', PROTON_SLUG )

	);
	
	$pntrs[] = array(
		'id' => 'id6',   // unique id for this pointer
		'screen' => 'toplevel_page_proton-framework', // this is the page hook we want our pointer to show on

		'target' => '#1_section_group_li', // the css selector for the pointer to be tied to, best to use ID's
		'title' => 'Opcje seo',
		'order' => '2',
		'tour' => 'proton',
		'content' => 'Wszystko jest ładnie i obiektowo zrobione ;). Ta zakładka została dodana przez moduł SEO, ktory jest opcjonalny.',
		'position' => array(
			'edge' => 'left', //top, bottom, left, right
			'align' => 'top' //top, bottom, left, right, middle
		),
		'button2'  => __( 'Next', PROTON_SLUG ),
		'location' => admin_url( 'post.php?post=4&action=edit' )

	);
?>