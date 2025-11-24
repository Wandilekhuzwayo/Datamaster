(function () {
  'use strict'
  const forms = document.querySelectorAll('.requires-validation')
  Array.from(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
  
        if (type == 'email' && validateEmail(inputs) == false){
          console.log('Sorry');
          event.preventDefault()
          event.stopPropagation()
          return;
        }
        else if(type == 'phone' && validateNumber(inputs) == false){
          console.log('Sorry');
          event.preventDefault()
          event.stopPropagation()
          return;
        }
        else if(type == null || inputs == null){
          console.log('Sorry');
          event.preventDefault()
          event.stopPropagation()
          return;
        }
        form.classList.add('was-validated')
      }, false)
    }) 
  })()

  var type = null;
  function selectChange(val) {
    console.log(val);
    type = val;
    document.getElementById(
      'input').value = ''
  } 

  var inputs = null;
  function inputChange(val) {
    console.log(val);
    inputs = val;
  }

  function validateEmail(inputText) {
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    return inputText.match(mailformat) ? true : false;
  }

  function validateNumber(inputText) {
    var phoneno = /^\d{10}$/;
    return inputText.match(phoneno) ? true : false;
  }