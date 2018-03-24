(function ($, Drupal) {

	'use strict';

	Drupal.behaviors.social_networks = {

		attach: function (context, settings) {

			// Get LinkedIn Token.
			$('#getLinkedinToken').click(function(e){
				e.preventDefault();
				var client_id = $('input[name=linkedin_client_id]').val();
				var client_secret = $('input[name=linkedin_client_secret]').val();
				var redirect_uri = $('input[name=linkedin_redirect_uri]').val();
				if (client_id && client_secret) {
					var url = 'https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=${client_id}&redirect_uri=${redirect_uri}&state=state&scope=rw_company_admin';
					url = url.replace('${client_id}', client_id);
					url = url.replace('${redirect_uri}', redirect_uri);
                    window.open(url);
                } else {
					alert('Veuillez remplir les champs "Client ID" et "Client Secret".');
				}
			});

			// Get Pinterest Token.
			$('#getPinterestToken').click(function(e){
				e.preventDefault();
				var client_id = $('input[name=pinterest_id]').val();
				var client_secret = $('input[name=pinterest_client_secret]').val();
				var redirect_uri = $('input[name=pinterest_redirect_uri]').val();
				if (client_id && client_secret) {
					var url = 'https://api.pinterest.com/oauth/?response_type=code&client_id=${client_id}&redirect_uri=${redirect_uri}&state=state&scope=rw_company_admin';
					url = url.replace('${client_id}', client_id);
					url = url.replace('${redirect_uri}', redirect_uri);
					window.open(url);
				} else {
					alert('Veuillez remplir les champs "Client ID" et "Client Secret".');
				}
			});

			// Get Instagram Token.
			$('#getInstagramToken').click(function(e){
				e.preventDefault();
				var client_id = $('input[name=instagram_id]').val();
				var client_secret = $('input[name=instagram_client_secret]').val();
				var redirect_uri = $('input[name=instagram_redirect_uri]').val();
				if (client_id && client_secret) {
					var url = 'https://api.instagram.com/oauth/?response_type=code&client_id=${client_id}&redirect_uri=${redirect_uri}&state=state&scope=rw_company_admin';
					url = url.replace('${client_id}', client_id);
					url = url.replace('${redirect_uri}', redirect_uri);
					window.open(url);
				} else {
					alert('Veuillez remplir les champs "Client ID" et "Client Secret".');
				}
			});
		}

	}

}(jQuery, Drupal));