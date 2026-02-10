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
        form.classList.add('was-validated')
      }, false)
    }) 
  })()

/**
 * Update character count and limit input for phone numbers
 * @param {HTMLInputElement} input The input element
 * @param {string} counterId The ID of the counter element
 */
function countPhoneDigits(input, counterId) {
    // Remove non-numeric characters for counting logic
    const val = input.value;
    const digits = val.replace(/[^0-9]/g, '');
    const count = digits.length;
    
    // Update counter text
    const counter = document.getElementById(counterId);
    if(counter) {
        counter.textContent = count + '/11 digits';
        
        // Visual feedback
        if (count > 11) {
            counter.className = 'text-danger small'; // Too long
        } else if (count === 11) {
            counter.className = 'text-success small'; // Perfect
        } else {
            counter.className = 'text-muted small'; // Typing...
        }
    }
}