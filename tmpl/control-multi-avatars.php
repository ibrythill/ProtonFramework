<# if ( data.label ) { #>
	<span class="proton-opt-label">{{ data.label }}</span>
<# } #>

<# if ( data.description ) { #>
	<span class="proton-opt-description">{{{ data.description }}}</span>
<# } #>

<div class="proton-opt-multi-avatars-wrap">

	<# _.each( data.choices, function( user ) { #>

		<label>
			<input type="checkbox" value="{{ user.id }}" name="{{ data.field_name }}[]" <# if ( -1 !== _.indexOf( data.value, user.id ) ) { #> checked="checked" <# } #> />

			<span class="screen-reader-text">{{ user.name }}</span>

			{{{ user.avatar }}}
		</label>

	<# } ) #>

</div><!-- .proton-opt-multi-avatars-wrap -->
