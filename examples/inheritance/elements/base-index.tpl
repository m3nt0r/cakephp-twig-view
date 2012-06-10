<div id="base-header">
	{% block header %}
      <h1>Some Index</h1>
  {% endblock %}	
</div>
<div id="base-table">
	{% block table %}
      <table border="0" cellspacing="0" cellpadding="5">
      	<tr><th>Some</th></tr>
      	<tr><td>Table</td></tr>
      </table>
  {% endblock %}
</div>
<div id="base-footer">
	{% block footer %}
      &copy; Copyright 2012 by <a href="http://domain.invalid/">you</a>.
  {% endblock %} 
</div>
