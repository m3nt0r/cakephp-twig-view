{#
This is the example data from "default.po" and "users.po"

msgid "Words"
msgstr "Wörter"

msgid "Word"
msgid_plural "Words"
msgstr[0] "Wort"
msgstr[1] "Wörter"

#}


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