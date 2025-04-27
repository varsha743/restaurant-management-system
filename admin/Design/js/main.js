/* ============ TITLE TOOLTIP TOGGLE ============== */
	
$(function () 
{
	$('[data-toggle="tooltip"]').tooltip();
});


/* =========== VALIDATE ADMIN LOGIN FORM ======== */

function validateLoginForm() 
{
	var username_input = document.forms["login-form"]["username"].value;
	var password_input = document.forms["login-form"]["password"].value;
	  
	if (username_input == "" && password_input == "") 
	{
		document.getElementById('username_required').style.display = 'block';
		document.getElementById('password_required').style.display = 'block';
	  return false;
	}
	
	if (username_input == "") 
	{
		document.getElementById('username_required').style.display = 'block';
		return false;
	}
	
	if(password_input == "")
	{
		document.getElementById('password_required').style.display = 'block';
		return false;
	}
}

/* =========== DASHBOARD TOGGLE ORDERS TABS ======== */

function openTab(evt, tabName, tabContent, tabLinks) 
{
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName(tabContent);

    for (i = 0; i < tabcontent.length; i++) 
    {
        tabcontent[i].style.display = "none";
    }
    
    tablinks = document.getElementsByClassName(tabLinks);

    for (i = 0; i < tablinks.length; i++) 
    {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
  
    document.getElementById(tabName).style.display = "table";
    evt.currentTarget.className += " active";
}

/* =========== DOCUMENT READY FUNCTIONS ======== */

$(document).ready(function() {
  // Add mobile menu toggle functionality
  $('<button class="mobile-toggle"><i class="fas fa-bars"></i></button>').insertBefore('.navbar-brand');
  
  $('.mobile-toggle').on('click', function() {
    $('body').toggleClass('show-menu');
  });
  
  // Animate dashboard panels on load
  $('.panel').each(function(index) {
    $(this).css({
      'opacity': 0,
      'transform': 'translateY(20px)'
    });
    
    setTimeout(() => {
      $(this).animate({
        'opacity': 1,
        'transform': 'translateY(0)'
      }, 300);
    }, index * 100);
  });
  
  // Dropdown menu animations
  $('.dropdown-toggle').on('click', function() {
    $(this).next('.dropdown-menu').fadeToggle(200);
  });
  
  $(document).on('click', function(e) {
    if (!$(e.target).closest('.dropdown').length) {
      $('.dropdown-menu').fadeOut(200);
    }
  });
  
  // Form validation highlighting
  $('input, select, textarea').on('focus', function() {
    $(this).parent().addClass('focused');
  }).on('blur', function() {
    if (!$(this).val()) {
      $(this).parent().removeClass('focused');
    }
  });
  
  // Smooth scrolling for anchor links
  $('a[href^="#"]').on('click', function(e) {
    if (this.hash !== '') {
      e.preventDefault();
      
      const target = $(this.hash);
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top - 70
        }, 500);
      }
    }
  });
  
  // Form submission visual feedback
  $('form').on('submit', function() {
    const requiredFields = $(this).find('[required]');
    let isValid = true;
    
    requiredFields.each(function() {
      if (!$(this).val()) {
        isValid = false;
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid');
      }
    });
    
    if (!isValid) {
      // Show toast notification if available, otherwise use alert
      if (typeof swal !== 'undefined') {
        swal("Error", "Please fill out all required fields", "error");
      }
      return false;
    }
  });
  
  // Vertical menu accordion functionality
  $('.a-verMenu').on('click', function(e) {
    if ($(this).next('.sub').length) {
      e.preventDefault();
      $(this).next('.sub').slideToggle(300);
      $(this).find('.angleBottom').toggleClass('rotate-icon');
    }
  });
  
  // Image preview for avatar upload
  $('#imageUpload').on('change', function() {
    readURL(this);
  });
  
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
        $('#imagePreview').hide();
        $('#imagePreview').fadeIn(650);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  
  // Add active menu highlight
  function highlightActiveMenu() {
    // Get current page from URL
    var currentPage = window.location.pathname.split('/').pop();
    
    // Remove any existing active classes
    $('.a-verMenu').removeClass('active_link');
    
    // Set active class based on current page
    if (currentPage === '' || currentPage === 'dashboard.php') {
      $('.dashboard_link').addClass('active_link');
    } else if (currentPage === 'staff.php') {
      $('.inventory_link').addClass('active_link');
    } else if (currentPage === 'manager.php') {
      $('.supplier_link').addClass('active_link');
    } else if (currentPage === 'website-settings.php') {
      $('.clients_link').addClass('active_link');
    }
  }
  
  // Call the function to highlight active menu
  highlightActiveMenu();
  
  // Add hover effects to panels
  $('.panel').hover(
    function() {
      $(this).find('.panel-footer').css('background-color', 'var(--gray-200)');
    }, 
    function() {
      $(this).find('.panel-footer').css('background-color', 'var(--gray-100)');
    }
  );
  
  // Add transition effect to alerts
  $('.alert').css('opacity', '0').animate({opacity: 1}, 500);
  
  // Add click handler for alert close button
  $('.alert .close').on('click', function() {
    $(this).closest('.alert').animate({opacity: 0}, 500, function() {
      $(this).remove();
    });
  });
});