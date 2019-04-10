<div>

	<input type="checkbox" class="proton-opt-input-checkbox" id="{{ data.name }}" value="true" {{{ data.attr }}} <# if ( data.value ) { #> checked="checked" <# } #> /><label for="{{ data.name }}"><span class="ui"></span>
	<# if ( data.label ) { #>
		<span class="proton-opt-label">{{ data.label }}</span>
	<# } #>
	</label>
	<# if ( data.description ) { #>
		<span class="proton-opt-description">{{{ data.description }}}</span>
	<# } #>
</div>
