document.addEventListener('DOMContentLoaded', function() {
    var closeButton = document.getElementById('closeb');
    if (closeButton) {
      closeButton.addEventListener('click', function() {
        elementorProFrontend.modules.popup.closePopup();  // Закриває попап
      });
    }
  });