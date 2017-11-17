if(typeof compte !== 'undefined' && compte > 0)
{
	$.notify({
		message: 'Il y a ' + compte + ' compte(s) en attente de modération.'
	},{
		type: 'warning'
	});
}

if(typeof obs !== 'undefined' && obs > 0)
{
	$.notify({
		message: 'Il y a ' + obs + ' observation(s) en attente de modération.'
	},{
		type: 'info'
	});
}

if(typeof message !== 'undefined' && message != null) 
{
	$.notify({
		message: message
	},{
		type: 'success'
	});
}

if(typeof comptes !== 'undefined' && comptes == null)
{
	$.notify({
		message: "Il n'y a pas de compte à modérer pour le moment. Revenez plus tard."
	},{
		type: 'info'
	});
}

if(typeof obs !== 'undefined' && obs == null)
{
	$.notify({
		message: "Il n'y a pas d'observation à modérer pour le moment. Revenez plus tard."
	},{
		type: 'info'
	});
}


