var SONDA = {
ponowiono: false,
okno: function(url, n)
{
	if (window.opener)
	{
		location = url
	}
	else
	{
		return open(url, n, 'scrollbars=yes,width=600,height=540,top=50,left=50')
	}
},
glosuj: function(przycisk)
{
	try
	{
		if(this.wykonaj(przycisk))
		{
			return this.okno('about:blank', 'F3O')
		}
	}
	catch(e)
	{
		alert(e);
		przycisk.disabled = false
	}
	return false
},
wykonaj: function(przycisk)
{
	var form = przycisk.form, dane = '', odp = form.odp||form['odp[]'], i;
	for(i=0; i<odp.length; i++)
	{
		if(odp[i].checked) dane += odp[i].name + '=' + odp[i].value + '&';
	}
	if(!dane) throw 'Zaznacz odpowiedź!';
	dane += 'id='+form.elements['id'].value;
	if(form.elements['csrf'])
	{
		dane += '&csrf='+form.elements['csrf'].value;
	}

	//Obiekt AJAX
	if(window.XMLHttpRequest)
	{
		var http = new XMLHttpRequest;
		if(http.overrideMimeType) http.overrideMimeType('text/html');
	}
	else if(window.ActiveXObject)
	{
		try
		{
			var http = new ActiveXObject("Msxml2.XMLHTTP")
		}
		catch(e)
		{
			try
			{
				var http = new ActiveXObject("Microsoft.XMLHTTP")
			}		
			catch(e)
			{
				return true
			}
		}
	}
	http.onreadystatechange = function()
	{
		if(http.readyState == 4)
		{
			if(http.status == 200 || http.status == 0)
			{
				przycisk.form.innerHTML = http.responseText
			}
			else if(http.status == 419 && !SONDA.ponowiono)
			{
				przycisk.form.elements['csrf'].value = http.responseText;
				SONDA.ponowiono = true;
				SONDA.glosuj(przycisk)
			}
			else
			{
				przycisk.disabled = false;
				alert('Wystąpił błąd. Kod: '+http.status)
			}
		}
	};

	//Kursor
	przycisk.disabled = true;
	przycisk.value = 'CZEKAJ';

	//Otwórz połączenie
	http.open('POST', przycisk.form.action, true);
	http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	http.setRequestHeader('X-Requested-With','XMLHttpRequest');
	http.send(dane)
}
}