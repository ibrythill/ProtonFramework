<?php
/**
 * Backbone Templates
 * This file contains all of the HTML used in our modal and the workflow itself.
 *
 * Each template is wrapped in a script block ( note the type is set to "text/html" ) and given an ID prefixed with
 * 'tmpl'. The wp.template method retrieves the contents of the script block and converts these blocks into compiled
 * templates to be used and reused in your application.
 */


/**
 * The Modal Window, including sidebar and content area.
 * Add menu items to ".navigation-bar nav ul"
 * Add content to ".proton_modal-main article"
 */
?>
<script type="text/html" id='tmpl-proton-modal-window'>
	<div class="proton_modal protonthemes-opts {{{data.theme}}}">
		<a class="proton_modal-close dashicons dashicons-no" href="#"
		   title="<?php echo __( 'Close', PROTON_SLUG ); ?>"><span
				class="screen-reader-text"><?php echo __( 'Close', PROTON_SLUG ); ?></span></a>

		<div class="proton_modal-content">
			<section class="proton_modal-main" role="main">
				<header>
					<# if(data.title){ #><h1>{{{data.title}}}</h1><# } #>
					<# if(!data.content){ #>
					<section id="proton-modal-tabs">
					    <# if(data.tabs){ #><ul id="proton-modal-tabs-labels">{{{data.tabs}}}</ul><# } #>
					</section>
					<# }else{ #>
					<# if(data.desc){ #><div class="desc">{{{data.desc}}}</div><# } #>
					<# } #>
				</header>
				<article id="proton-modal-tabs-content">
					<# if(data.content){ #>{{{data.content}}}<# }else{ #><form>{{{data.tabscontent}}}</form><# } #>
				</article>

				<footer>
					<div class="inner text-right">
						<button id="btn-cancel"
						        class="metabutton"><?php echo __( 'Cancel', PROTON_SLUG ); ?></button>
						<button id="btn-ok"
						        class="metabutton"><?php echo __( 'Save &amp; Continue', PROTON_SLUG ); ?></button>
					</div>
				</footer>
			</section>
		</div>
	</div>
</script>

<?php
/**
 * The Modal Backdrop
 */
?>
<script type="text/html" id='tmpl-proton-modal-backdrop'>
	<div class="proton_modal-backdrop">&nbsp;</div>
</script>

<?php
/**
 * The Modal Tabs
 */
?>
<script type="text/html" id="tmpl-proton-modal-label">
    <li<# if(data.active){ #>  class="active"<# } #> data-label="{{ data.label }}"><span>{{ data.label }}</span></li>
</script>
<script type="text/html" id="tmpl-proton-modal-content">
    <span<# if(!data.active){ #> class="deactive"<# } #> id="content-{{ data.label }}"><div class="desc">{{{data.desc}}}<div data-tip="Clever hint" class="data-tip-left">?<div class="tip-content">Clever hint</div></div></div>{{{ data.content }}}</span>
</script>
