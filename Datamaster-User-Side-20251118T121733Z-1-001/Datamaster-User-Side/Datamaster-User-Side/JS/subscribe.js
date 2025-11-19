  //Validate code for inputs
  var email =  document.forms['form']['email'];

  var email_error = document.getElementById('email_error');

  email.addEventListener('textInput', email_Verify);

  function validated() {
    if(email.value.length < 9) {
      email_error.style.display = "block";
      email.focus();
      return false;
    }
  }

  function email_Verify() {
    if(email.value.length >= 10) {
      email_error.style.display = "none";
      return true;
    }
  }
