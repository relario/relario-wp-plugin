'use strict';


(function ($) {

	/**
	 * Determine the mobile operating system.
	 * This function returns one of 'iOS', 'Android', 'Windows Phone', or 'unknown'.
	 *
	 * @returns {String}
	 */
	function getMobileOperatingSystem() {
		var userAgent = navigator.userAgent || navigator.vendor || window.opera;

		// Windows Phone must come first because its UA also contains "Android"
		if (/windows phone/i.test(userAgent)) {
			return "Windows Phone";
		}

		if (/android/i.test(userAgent)) {
			return "Android";
		}

		// iOS detection from: http://stackoverflow.com/a/9039885/177710
		if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
			return "iOS";
		}


		// iOS detection from: http://stackoverflow.com/a/9039885/177710
		if (/Macintosh|MacIntel|MacPPC|Mac68K/.test(userAgent)) {
			return "macOs";
		}

		return "unknown";
	}

	function getClickToSmsUrl(phoneNumbers, smsBody, smsBodyPrefix) {
		const nbrs = phoneNumbers.map(phoneNumber => `+${phoneNumber}`);

		// const text = encodeURIComponent('Please do not alter this message and press SEND ') + smsBody;
		const text = encodeURIComponent(smsBodyPrefix || '') + (smsBodyPrefix ? ' ' : '') + smsBody;
		// iOS detection from: http://stackoverflow.com/a/9039885/177710
		// @ts-ignore
		if (isIOS() && !window.MSStream) {
			// use the iOS URL
			return generateIosClickToSmsUrl(nbrs, text);
		} else {
			// use the Android URL
			return generateAndroidClickToSmsUrl(nbrs, text);
		}
	}

	// detect client OS
	function isIOS() {
		// eslint-disable-next-line @typescript-eslint/no-explicit-any
		const userAgent = navigator.userAgent || navigator.vendor || window.opera;
		return /iPad|iPhone|iPod|Mac/.test(userAgent);
	}

	function generateIosClickToSmsUrl(phoneNumbers, smsBody) {
		return `sms://open?addresses=${phoneNumbers.join(',')};?&body=${smsBody}`;
	}

	function generateAndroidClickToSmsUrl(phoneNumbers, smsBody) {
		return `sms://${phoneNumbers.join(',')};?&body=${smsBody}`;
	}

	// On fixed button click submit
	$(document.body).on('click', '.relario-support.relario-fixed', function (e) {
		e.preventDefault()

		var wrap = $(this).parents('.relario-support-wrap')
		wrap.submit()

		return false
	})


	
	$(document.body).on('change', '.relario-pay_input', function (e) {
		e.preventDefault()

		var wrap = $(this).parents('.relario-support-wrap')
		var input = wrap.find('.relario-pay_input').first()

		var value = $(this).val();
		if (value > 20) {
			$(this).val(20);
		}
	
		if (value < 1) {
			$(this).val(1);
		}

		wrap.attr('data-smscount', $(this).val())

		return false
	})

	function submitForm(e) {
		e.preventDefault()
		e.stopPropagation();

		var wrap = $(this).parents('.relario-support-wrap')
		wrap.submit();
		return false;
	}
	$(document.body).on('click', '.relario-pay_logo', submitForm)
	// On button click submit
	$(document.body).on('click', '.relario-pay_button-text', submitForm)

	// Support form submitted
	$(document.body).on('submit', '.relario-support-wrap', function (e) {
		e.preventDefault()
		e.stopPropagation()
		var smsTextPrefix = $(this).attr('data-smsTextPrefix');
		debugger;
		var data = {
			action: 'relario_donate_request',
			nonce: relario.nonce,
			smsCount: parseInt($(this).attr('data-smsCount')),
			productId: $(this).attr('data-productId'),
			productName: $(this).attr('data-productName'),
			smsTextPrefix,
		}

		$.post(relario.ajaxurl, data, function (response) {
			// Bail if there's an error
			if (!response.success) {
				console.log(response.error)
				return
			}

			var redirect = getClickToSmsUrl(response.phoneNumbersList, response.smsBody, smsTextPrefix);
			window.open(redirect);
		})
	})

})(jQuery)
