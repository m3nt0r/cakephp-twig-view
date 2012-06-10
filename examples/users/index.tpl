
<div class="container">
	<h2>{{ 'Users' }}</h2>
	<table class="table table-bordered">
		<thead>
			<tr>
					<th>{{ paginator.sort('id') }}</th>
					<th>{{ paginator.sort('email') }}</th>
					<th>{{ paginator.sort('password') }}</th>
					<th>{{ paginator.sort('created') }}</th>
					<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			{% for row in users %}
			<tr>
				{# this is how we access results #}
				<td>{{ row.User.id }}</td>
				<td>{{ row.User.email }}</td>
				<td>{{ row.User.password }}</td>
		
				{# this is how we can use helpers #}
				<td>{{ time.niceShort(row.User.created) }}</td>
		
				{# this is how we have to write our url arrays #}
				<td>{{ html.link('Edit User', {'controller': 'users', 'action': 'edit', 0: row.User.id}) }}</td>
			</tr>
			{% endfor %}
		</tbody>
	</table>
</div>

{# this is how we import elements. #}
<div class="container">
	{% include 'test.ctp' %} 
</div>