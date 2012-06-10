{% extends "elements/base-index.tpl" %}

{% block header %}
<div class="container">
	<h2>{{ 'Users'|trans }}</h2>
</div>
{% endblock %}

{% block table %}
<div class="container">
	<table class="table table-bordered" cellpadding="0" cellspacing="0">
		<tr>
			<th>{{ paginator.sort('id') }}</th>
			<th>{{ paginator.sort('email') }}</th>
			<th>{{ paginator.sort('created') }}</th>
			<th>Actions</th>
	  </tr>
	  {% for row in users %}
	  <tr>
			<td>{{ row.User.id }}</td>
			<td>{{ row.User.email }}</td>
			<td>{{ row.User.created }}</td>
			<td>
			{{ 
				html.link('Edit User', {
					'controller': 'users', 'action': 'edit', 0: row.User.id
				})
			}}
			</td>
	  </tr>
	  {% endfor %}
  </table>
</div>
{% endblock %}  

{% block footer %}
<div class="container">
	Add a paginator, i'd say.
</div>
{% endblock %}