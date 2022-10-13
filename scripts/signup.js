// JavaScript Document

var signupElementsValidators = {
	//Retrieving all form input elements through the DOM
	signupFormNameInput: document.getElementById("id-name"),
	signupFormLastNameInput: document.getElementById("id-last-name"),
	signupFormEmailInput: document.getElementById("id-email"),
	signupFormTelephoneInput: document.getElementById("id-telephone"),
	signupFormPasswordInput: document.getElementById("id-password"),
	signupFormConfirmPasswordInput: document.getElementById("id-confirm-password"),

	sanitizeNameInputValue: () => {
		signupElementsValidators.signupFormNameInput.value = signupElementsValidators.signupFormNameInput.value.replace(/[0-9~!@#$%^&*())_{}|:\"<>?`=+\-[\];',.\/\s]/g, "");
	},
	sanitizeLastNameInputValue: () => {
		signupElementsValidators.signupFormLastNameInput.value = signupElementsValidators.signupFormLastNameInput.value.replace(/[0-9~!@#$%^&*())_{}|:\"<>?`=+\-[\];',.\/\s]/g, "");
	},
	validateSignupFormNameInput: () => {
		if (signupElementsValidators.signupFormNameInput.value.length < 1) {
			signupElementsValidators.signupFormDetailsOnErrorAlert("Oops! Sorry, you forgot to enter your name", "name");
			return false;
		}
		else if (signupElementsValidators.signupFormNameInput.value.match(/[0-9~!@#$%^&*())_{}|:\"<>?`=+\-[\];',.\/\s]/g)) {
			signupElementsValidators.signupFormDetailsOnErrorAlert("Oops! Sorry, but the name seems invalid", "name");
			return false;
		}
		return true;
	},
	validateSignupFormLastNameInput: () => {
		if (signupElementsValidators.signupFormLastNameInput.value.length < 1) {
			signupElementsValidators.signupFormDetailsOnErrorAlert("Oops! Sorry, you forgot to enter your name", "last-name");
			return false;
		}
		else if (signupElementsValidators.signupFormLastNameInput.value.match(/[0-9~!@#$%^&*())_{}|:\"<>?`=+\-[\];',.\/\s]/g)) {
			signupElementsValidators.signupFormDetailsOnErrorAlert("Oops! Sorry, but the name seems invalid", "last-name");
			return false;
		}
		return true;
	},
	validateSignupFormEmail: () => {
		if (signupElementsValidators.signupFormEmailInput.value.match(/^[^\s@]+@\w[\w\-.]+$/)) {
			return true;
		}
		return false;
	},
	validateSignupFormPassword: () => {
		if (signupElementsValidators.signupFormPasswordInput.value.length < 6) {
			signupElementsValidators.signupFormDetailsOnErrorAlert("Oops! Sorry, your password cannot be less than six(6) characters", "password");
			return false;
		} else if (signupElementsValidators.signupFormPasswordInput.value != signupElementsValidators.signupFormConfirmPasswordInput.value) {
			signupElementsValidators.signupFormDetailsOnErrorAlert("Oops! Sorry, but your password confirmation doesn't match", "confirm-password");
			return false;
		}
		return true;
	},
	signupFormDetailsOnErrorAlert: (errorText, type = "generic") => {

		let errorParagraphElement = document.createElement("p");
		errorParagraphElement.setAttribute("class", "signup-errors signup-form-details-errors");
		errorParagraphElement.setAttribute("id", ("signup-form-details-error-" + type));
		errorParagraphElement.appendChild(document.createTextNode(errorText));
		if (type == "password") {
			let signupFormPasswordParent = signupElementsValidators.signupFormPasswordInput.parentElement;
			if (document.getElementById(("signup-form-details-error-" + type))) {
				signupFormPasswordParent.removeChild(document.getElementById(("signup-form-details-error-" + type)));
			}
			signupFormPasswordParent.appendChild(errorParagraphElement);
		} else if (type == "confirm-password") {
			let signupFormConfirmPasswordParent = signupElementsValidators.signupFormConfirmPasswordInput.parentElement;
			if (document.getElementById(("signup-form-details-error-" + type))) {
				signupFormConfirmPasswordParent.removeChild(document.getElementById(("signup-form-details-error-" + type)));
			}
			signupFormConfirmPasswordParent.appendChild(errorParagraphElement);
		} else if (type == "email") {
			let signupFormEmailParent = signupElementsValidators.signupFormEmailInput.parentElement;
			if (document.getElementById(("signup-form-details-error-" + type))) {
				signupFormEmailParent.removeChild(document.getElementById(("signup-form-details-error-" + type)));
			}
			signupFormEmailParent.appendChild(errorParagraphElement);
		} else if (type == "last-name") {
			let signupFormLastNameParent = signupElementsValidators.signupFormLastNameInput.parentElement;
			if (document.getElementById(("signup-form-details-error-" + type))) {
				signupFormLastNameParent.removeChild(document.getElementById(("signup-form-details-error-" + type)));
			}
			signupFormLastNameParent.appendChild(errorParagraphElement);
		} else if (type == "name") {
			let signupFormNameParent = signupElementsValidators.signupFormNameInput.parentElement;
			if (document.getElementById(("signup-form-details-error-" + type))) {
				signupFormNameParent.removeChild(document.getElementById(("signup-form-details-error-" + type)));
			}
			signupFormNameParent.appendChild(errorParagraphElement);
		} else if (type == "generic") {
			if (document.getElementById(("signup-head-error"))) {
				document.forms[0].removeChild(document.getElementById(("signup-head-error")));
			}
			let errorDivElement = document.createElement("div");
			errorDivElement.setAttribute("class", "signup-errors");
			errorDivElement.setAttribute("id", ("signup-head-error"));
			errorParagraphElement.removeAttribute("class");
			errorParagraphElement.removeAttribute("id");
			errorDivElement.appendChild(errorParagraphElement);
			document.forms[0].insertBefore(errorDivElement, document.forms[0].firstChild);
		} else {
			alert(errorText);
		}
	},
	validateAllFormInput: () => {
		if (signupElementsValidators.validateSignupFormNameInput() &&
			signupElementsValidators.validateSignupFormLastNameInput() &&
			signupElementsValidators.validateSignupFormPassword()) {
			return true;
		}
		return false;
	},
	sanitizeAllInput: () => {
		signupElementsValidators.sanitizeNameInputValue();
		signupElementsValidators.sanitizeLastNameInputValue();
	}
}

function main() {
	setInterval(signupElementsValidators.sanitizeAllInput, 1000);
}
main();
