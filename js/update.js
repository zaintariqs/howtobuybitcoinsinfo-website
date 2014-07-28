(function (window) {

	var updateForm  = $('#update');
	var modalWindow = $('#modalBackground');

	$('#sendCorrection').on('click', function () {

		updateForm.show();

	});

	$('#cancelUpdate').on('click', function () {

		updateForm.hide();

	});

	var countries = [];
	$('[data-formtype="country-code"]').on('click', function () {

		var country = this.dataset.country;

		//Check if the country has been clicked already.
		if (countries.indexOf(country) >= 0) {

			countries.splice(countries.indexOf(country), 1);
			$(this).toggleClass('selectedValue');

		} else {

			countries.push(country);
			$(this).toggleClass('selectedValue');

		}

	});

	var coins = ['btc'];
	$('[data-formtype="coin-code"]').on('click', function () {

		var coin = this.dataset.coin;

		//Check if the country has been clicked already.
		if (coins.indexOf(coin) >= 0) {

			$(this).toggleClass('selectedValue');
			coins.splice(coins.indexOf(coin), 1);

		} else {

			coins.push(coin);
			$(this).toggleClass('selectedValue');

		}
		
	});


	var form = $('[data-form="update"]');
	$('#sendUpdate').on('click', function () {

		var l        = form.length;
		var sendForm = {};

		while(l--) {

			var value = form[l].value;
			var name  = form[l].name;

			if (form[l].value === '') {

				return alert(name + ' field can not be empty.');

			}

			sendForm[name] = value;

		}

		//Make sure that at least one country is selected.
		if (countries.length === 0) {

			return alert('There are no countries selected.');

		}
		sendForm.countries = countries;

		//Make sure that at least one coin is selected.
		if (coins.length === 0) {

			return alert('There are no coins selected.');

		}
		sendForm.coins = coins;

		
		modalWindow.show();
		$.post("updates.php", sendForm, function (data) {

			var json;
			try {
				var json = JSON.parse(data);
			} catch (e) {

				alert('Something went wrong. Please try again.');
				modalWindow.hide();

			}
			
			modalWindow.hide();
			updateForm.hide();
			
			alert('Succesfully submitted! We will process your request shortly.');
		


		});	

	});

}(window));