@push('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<link rel="stylesheet" href="{{ asset('/css/chosenIcon.css') }}">
<style>	
	.radio-inline+.radio-inline, .checkbox-inline+.checkbox-inline {
	margin-top: 0;
	margin-left: 0;
	margin-right: 10px;
	}
	.radio-inline:not(first-child), .checkbox-inline:not(first-child){
	margin-right: 10px;
	}
	.chosen-container{
	font-size: inherit;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
    border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
    border-color: #d2d6de !important;	
	box-shadow: none;
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('/js/chosenIcon.jquery.js') }}"></script>
<script>
	$(function(){
		$(".icon-select").chosenIcon({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});
</script>
@endpush

<div class="form-group">
	{!! Form::label('group', 'Group:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.route_group_middleware') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="group" ';
				if(isset($route) and $k == $route -> group) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. implode(', ', $v) .'</label>';
			}
		?>
	</div>
</div>

<div class="form-group">
	{!! Form::label('method', 'Method:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(['get', 'post', 'put', 'patch', 'delete', 'options'] as $k) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="method" ';
				if(isset($route) and $k == $route -> method) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. strtoupper($k) .'</label>';
			}
		?>
	</div>
</div>

<div class="form-group">
	{!! Form::label('uri', 'URI:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-7">
		<div class="input-group">
			<span class="input-group-addon">{{ url('/') }}</span>
			{!! Form::text('uri', null, array('class' => 'form-control')) !!}
		</div>
	</div>
</div>

<div class="form-group">
	{!! Form::label('as', 'Name', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('as', null, array('class' => 'form-control')) !!}
		<?php
/* 			foreach($crud as $k) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="as_crud_type" ';
				echo 'value="'. $k .'"> '. $k .'</label>';
			} */
		?>
	</div>
</div>

<div class="form-group">
	<label for="roles" class="col-sm-2 control-label">Roles:</label>
	<div class="col-sm-10">
		<?php
			foreach($roles as $k => $v) 
			{
				echo '<label class="checkbox-inline">';
				echo '<input type="checkbox" name="roles[]" ';
				if(isset($roles_a) and in_array($k, $roles_a)) echo ' checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>

<div class="form-group">
	{!! Form::label('uses', 'Uses:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('uses', null, array('class' => 'form-control')) !!}
		<?php
/* 			foreach($crud as $k) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="uses_crud_type" ';
				echo 'value="'. $k .'"> '. $k .'</label>';
			} */
		?>
	</div>
</div>

<hr/>

<div class="form-group">
	{!! Form::label('icon', 'Icon:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<select data-placeholder="Pilih Icon..." class="icon-select" name="icon" style="width:350px;"
		tabindex="2">
			<?php
				foreach($icons as $k => $v) 
				{
					echo '<option data-icon="'. $k .'" ';
					if(isset($route) and $route -> icon == $v) echo ' selected="selected" ';
					echo '>' . $v . '</option>'; 
				}
			?>
		</select>
	</div>
</div>

<div class="form-group">
	{!! Form::label('label', 'Label:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('label', null, array('class' => 'form-control')) !!}
	</div>
</div>
<?php
	$parent_id = (null !== Request::get('parent')) ? Request::get('parent') : null;
?>
<div class="form-group">
	{!! Form::label('parent', 'Parent:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('parent_id', $parents, $parent_id, array('class' => 'form-control', 'placeholder' => '-- Parent Menu --')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('position', 'Position:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('position', array_combine($r = range(1,50), $r), null, array('class' => 'form-control', 'placeholder' => '-- Position --')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('description', 'Description:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-7">
		{!! Form::textarea('description', null, array('class' => 'form-control', 'rows' => "3")) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('hidden', 'Hidden:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(['y' => 'Yes', 'n' => 'No'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="hidden" ';
				if(isset($route) and $k == $route -> hidden) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>

<div class="form-group">
	{!! Form::label('attr', 'Attribute:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-7">
		{!! Form::textarea('attr', null, array('class' => 'form-control', 'rows' => "3", 'placeholder' => 'Misc. attributes')) !!}
	</div>
</div>																			