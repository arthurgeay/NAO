if(compte > 0)
{
	$.notify({
		message: 'Il y a ' + compte + ' compte(s) en attente de modération.'
	},{
		type: 'warning'
	});
}

if(obs > 0)
{
	$.notify({
		message: 'Il y a ' + obs + ' observation(s) en attente de modération.'
	},{
		type: 'info'
	});
}