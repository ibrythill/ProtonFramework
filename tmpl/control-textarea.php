<label>
	<# if ( data.label ) { #>
		<span class="proton-opt-label">{{ data.label }}</span>
	<# } #>

	<textarea {{{ data.attr }}}>{{{ data.value }}}</textarea>

	<# if ( data.description ) { #>
		<span class="proton-opt-description">{{{ data.description }}}</span>
	<# } #>
</label>