
{# this is how we translate using cakes i18n. #}
<div class="container">
	<h2>Translation</h2>
	
	{{ 'Word'|trans }}<br>
	{{ 'Words'|trans }}
	
	<h4>With plural</h4>
	
	Count: 1 = {{ 'Word'|trans('Words', 1) }}<br>
	Count: 4 = {{ 'Word'|trans('Words', 4) }}
	
	<h4>Translation + domain</h4>
	
	{{ 'Word'|trans('users') }}<br>
	{{ 'Words'|trans('users') }}
	
	<h4>With plural + domain</h4>
	
	Count: 1 = {{ 'Word'|trans('Words', 'users', 1) }}<br>
	Count: 4 = {{ 'Word'|trans('Words', 'users', 4) }}
	
</div>